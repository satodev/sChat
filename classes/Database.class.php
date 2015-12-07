<?php
class Database
{
	private $pdo_object;
	private $table_name;
	
	public function __construct()
	{
		$this->pdo_object = $this->connect();
		$this->table_name = array('chats','groups', 'messages', 'rooms', 'security_systems', 'users', 'friend_list');
		$this->callAddFriend(1, 2);
		//testing function (they will be called in sChat class after that)
		// $this->callCreateAllStructure();
		// // $this->querySelectAllDataTable($this->pdo_object, 'users');	
		
		// $this->callQueryLogoutProcess(3);

		//clean DB
		// $this->callCleanDb();

		//destoy DB Structures
		// $this->callDestoyDbStructures();
	}
	/** 
	*	connect to sgbd
	*	return pdo_object
	*/
	public function connect()
	{
		$dsn = 'mysql:host=localhost:3307;dbname=schat';
		$user = 'root';
		$passwd = 'usbw';
		try{
			$pdo = new PDO($dsn,$user,$passwd);
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return $pdo;
	}
	/*
	* pdo_dependant functions
	* call to CreateAllStructure 
	* create variable of controll
	*/
	public function callCreateAllStructure()
	{
		$pdo_object = $this->pdo_object;
		$create_all_structure = $this->createAllStructure($pdo_object);
	}
	/**
	* create custom array with db name (nothing dynamic)
	*/
	public function callCleanDb()
	{
		$pdo_object = $this->pdo_object;
		$table_name = $this->table_name;
		$this->cleanDb($pdo_object, $table_name);
	}
	/*
	* call to destoy all structure, for dev purposes
	*/
	public function callDestoyDbStructures()
	{
		$pdo_object = $this->pdo_object;
		$table_name = $this->table_name;
		$this->destoyDbStructures($pdo_object, $table_name);
	}
	/**
	*
	*/
	public function callAddFriend($logged_user, $user_friend)
	{
		$pdo_object = $this->pdo_object;
		$this->queryAddFriend($pdo_object, $logged_user, $user_friend);
	}
	/*
	*	create All structure
	*	@pdo_object
	* 	return array query_status boolean
	*/
	public function createAllStructure($pdo_object)
	{
		if($pdo_object){
			$query_Create_Users_structure = $this->queryCreateUsers($pdo_object);
			$query_Create_Chat_structure = $this->queryCreateChats($pdo_object);
			$query_Create_Group_structure = $this->queryCreateGroups($pdo_object);
			$query_Create_Rooms_structure = $this->queryCreateRooms($pdo_object);
			$query_Create_Message_structure = $this->queryCreateMessages($pdo_object);
			$query_Create_SecuritySystem_structure = $this->queryCreateSecuritySystems($pdo_object);
			$query_Create_Friend_List = $this->queryCreateFriendList($pdo_object);
		}
		return array($query_Create_Users_structure, $query_Create_Chat_structure, $query_Create_Group_structure, $query_Create_Rooms_structure, $query_Create_Message_structure, $query_Create_SecuritySystem_structure, $query_Create_Friend_List);
	}
	/*
	*	Destoy the db
	* 	in case DB is corrupted
	*	@args pdo_object
	*/
	public function cleanDb($pdo_object, $table_name)
	{	
		if($table_name && $pdo_object){
			foreach($table_name as $db_name){
				$sql = 'TRUNCATE '.$db_name;
				$pdo_object->exec($sql);
			}
		}
	}
	/*
	*  Dev purpose
	*  Destoy all DB structures 
	*/
	public function destoyDbStructures($pdo_object, $table_name)
	{
		if($pdo_object && $table_name){
			foreach($table_name as $db_name){
				$sql  = 'DROP TABLE '. $db_name;
				$pdo_object->exec($sql);
			}
		}
	}
	/*
	* 	Select all data in specific table
	*	@args pdo_object && table name
	*	echo table with data selected
	*/
	public function querySelectAllDataTable($pdo_object, $table)
	{
		if($pdo_object){
			$sql = 'SELECT * FROM '.$table;
			echo '<table class="table table-bordered table-hover" style="width:100%;">';
			if($pdo_object->query($sql))
			{
				foreach($pdo_object->query($sql) as $row){
					echo '<tr>';
					foreach($row as $key=>$value){
						if(is_string($key)){
							echo '<td>';
							echo $key. " : ";
							echo $value;
							echo '</td>';
						}
					}
					echo '</tr>';
				}
				echo '</table>';
			}else{
				echo 'Table doesn\'t exists';
				die();
			}
		}
	}
	/**
	* @args pdo_object
	* @user_host
	* @id_user
	* @return true:false
	* 
	*/
	public function queryAddUserToFriendList($pdo_object, $user_host, $id_user)
	{
		//DB column utilities : friend_list_length; nearby_user_length; seen_user_length 
		$result = $this->queryVerifUserExistsById($pdo_object, $id_user);
		if($pdo_object && $result)
		{
			$sql = "INSERT INTO friend_list (id_friend_list, id_group, id_friend, date_add_friend) VALUES (\"$user_host\",\"$user_host\",\"$id_user\", CURRENT_DATE())";
			echo $sql; die;
			$q = $pdo_object->prepare($sql);
			$q->execute();
			return true;
		}else{
			Tools::throwErrorMessage('queryAddUserToFriendList : id_User doesn\'t exists');
		}
	}
	/**
	* @args $pdo_object, $logged_user, $user_friend
	* @return friend_added ? true : false;
	*/
	public function queryAddFriend($pdo_object, $logged_user, $user_friend)
	{		
		if($pdo_object && $logged_user && $user_friend 
			&& $this->verifIsLoggedUser($pdo_object, $logged_user) 
			&& $this->queryVerifUserExistsById($pdo_object, $user_friend)
			&& $this->verifUserToGroupCorrespondence($pdo_object, $logged_user))
		{
			$sql = "INSERT INTO friend_list (`id_friend_list`,`id_group`,`id_friend`,`date_add_friend`) 
			VALUES (\"$logged_user\",\"$logged_user\",\"$user_friend\", CURDATE() )";
			$q = $pdo_object->prepare($sql);
			$q->execute();
			return true;
		}else{
			Tools::throwWarningMessage('Couldn\'t add friend');
		}
	}
	/**
	* @arg pdo_object
	* @return array id_user
	*/
	public function querySelectUserId($pdo_object)
	{
		if($pdo_object){
			$sql = "SELECT * FROM users WHERE 1 LIMIT 0,30";
			$q = $pdo_object->prepare($sql);
			$q->execute();
			$result = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $user_row){
				foreach($user_row as $key => $row){
					if($key == 'id_user'){
							// echo $key.' = '.$row.'<br />';
						$return_array[] = $row;
					}
				}
			}
			return $return_array;
		}
	}
	/*
	*	Create Table Users
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateUsers($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS users(
			id_user INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			nickname VARCHAR(100),
			name VARCHAR(100),
			password VARCHAR(100),
			email VARCHAR(100),
			ip_address VARCHAR(100)
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	*	Create Table chats
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateChats($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS chats(
			id_chat INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			id_leader INT NOT NULL,
			id_invite INT NOT NULL,
			id_group INT NOT NULL
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	*	Create Table Groups
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateGroups($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS groups(
			id_group INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			id_user INT NOT NULL,
			friend_list VARCHAR(255),
			nearby_user VARCHAR(255),
			seen_user VARCHAR(255)
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	*	Create Table User
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateRooms($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS rooms(
			id_room INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			id_chat INT NOT NULL, 
			conf_room INT NOT NULL,
			conf_chats INT NOT NULL,
			conf_users BOOLEAN NOT NULL
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	*	Create Table Messages
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateMessages($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS messages(
			id_msg INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
			from_user INT NOT NULL,
			to_user INT NOT NULL,
			msg_content VARCHAR(255) NOT NULL,
			date_sent DATE NOT NULL,
			time_sent TIME NOT NULL
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/*
	*	Create Table Security_systems
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateSecuritySystems($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS security_systems(
			id_skey INT PRIMARY KEY NOT NULL  AUTO_INCREMENT,
			id_user INT NOT NULL ,
			token_key INT NOT NULL
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
	/**
	* Create friend list
	* @args pdo_object
	* @return query_status ? true : false
	*/
	public function queryCreateFriendList($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS friend_list(
			id_friend_list INT NOT NULL,
			id_group INT NOT NULL,
			id_friend INT NOT NULL,
			date_add_friend DATE
			)'))
		{
			return true;
		}else{
			return false;
		}
	}
}