<?php
class Database
{
	private $pdo_object;
	private $table_name;
	public function __construct()
	{
		$this->pdo_object = $this->connect();
		$this->table_name = array('chats','groups', 'messages', 'rooms', 'security_systems', 'users');
		//testing function (they will be called in sChat class after that)
		$this->callCreateAllStructure();
		// $this->querySelectAllDataTable($pdo_object, 'user');	
		$this->callQueryInsertUser('Sat','satoru','warleague@4591','hemmi.satoru@gmail.con','::1');
		$this->callQueryInsertUser('Sato1','sato1','meinpassword123','satoru.hemmi@gmail.con','::1');
		$this->callQueryInsertUser('Sato2','satoru','warleague@4591','s.hemmi@gmail.con','::1');
		$this->callQueryInsertUser('Sato3','satoru','warleague@4591','sa.hemmi@gmail.con','::1');
		$this->callQueryInsertUser('Sato4','satoru','warleague@4591','sato.hemmi@gmail.con','::1');
		$this->callQueryInsertUser('Sato5','satoru','warleague@4591','sator.hemmi@gmail.con','::1');
		$this->callQueryInsertUser('Sato6','satoru','warleague@4591','satoru.h@gmail.con','::1');
		$this->callQueryInsertUser('Sato7','satoru','warleague@4591','satoru.he@gmail.con','::1');
		$this->callQueryInsertUser('Sato8','satoru','warleague@4591','satoru.hem@gmail.con','::1');
		$this->callQueryInsertUser('Sato9','satoru','warleague@4591','satoru.hemm@gmail.con','::1');


		//clean DB
		// $this->callCleanDb();

		//destoy DB Structures
		// $this->callDestoyDbStructures();
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
	/*
	* pdo_dependant function
	* args dynInfo related to users insertion
	* call to queryInsertUser
	*/
	public function callQueryInsertUser($nickname, $name, $password, $email, $ip_address)
	{
		$pdo_object = $this->pdo_object;
		$user_data_correct = $this->verifUserDataCorrect($nickname, $name, $password, $email, $ip_address);
		if($user_data_correct){
			$this->queryInsertUser($pdo_object, $nickname, $name, $password, $email, $ip_address);
			$this->createGroup($pdo_object);
		}else{
			Tools::throwErrorMessage('user_data_incorrect');
		}
	}
	/*
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
	/*
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
		}
		return array($query_Create_Users_structure, $query_Create_Chat_structure, $query_Create_Group_structure, $query_Create_Rooms_structure, $query_Create_Message_structure, $query_Create_SecuritySystem_structure);
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
		}
	}
	/*
	*	User Insert Db
	*/
	public function queryInsertUser($pdo_object, $nickname, $name, $password, $email, $ip_address)
	{
		if($pdo_object && $nickname && $name && $password && $email && $ip_address){
			$user_exists = $this->queryVerifUserExists($pdo_object, $nickname, $name, $password, $email, $ip_address);
			if($user_exists == false){
				$q = $pdo_object->prepare('INSERT INTO users (nickname, name, password, email, ip_address)
					VALUES (
						:nickname,
						:name,
						:password,
						:email,
						:ip_address
						)');
				$q->bindParam(':nickname', $nickname,PDO::PARAM_STR);
				$q->bindParam(':name', $name, PDO::PARAM_STR);
				$q->bindParam(':password', $password, PDO::PARAM_STR);
				$q->bindParam(':email', $email, PDO::PARAM_STR);
				$q->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
				$q->execute();
			}else if($user_exists){
				Tools::throwWarningMessage('user exists');
			}
		}
	}
	/*
	*	Verif if user exists in DB
	*/
	public function queryVerifUserExists($pdo_object, $nickname, $name, $password, $email, $ip_address)
	{
		if($pdo_object && $nickname && $name && $password && $email && $ip_address)
		{
			$q = $pdo_object->prepare('SELECT * FROM users WHERE ip_address = :ip_address AND email = :email');
			$q->bindParam(':email', $email, PDO::PARAM_STR);
			$q->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
			$q->execute();
			$result = $q->fetch(PDO::FETCH_ASSOC);
			if($result['email']&& $result['ip_address']){
				return true;
			}else{
				return false;
			}
		}
	}
	/*
	*	verif all data before user insert to db
	*   allinfocorrect? return true: echo 'noncorrectData'
	*/
	public function verifUserDataCorrect($nickname, $name, $password, $email, $ip_address)
	{
		if($this->verifNickname($nickname) 
			&& $this->verifName($name) 
			&& $this->verifPassword($password) 
			&& $this->verifEmailAddress($email) 
			&& $this->verifIPAddress($ip_address))
		{
			return true;	
		}else{
			return false;
		}
	}
	/*
	* verif function verifNickname
	* return ? true: false 
	*/
	public function verifNickname($nickname)
	{
		//nickname must be string && must be longer than 100 char && must start with a letter 
		$pattern = '#^[a-zA-Z]+#';
		$nickname_match = Tools::preg_m($pattern, $nickname);
		if(strlen($nickname) > 100 && $nickname_match == null || $nickname_match == ""){
			echo '<br />notpassNickname<br />';
			Tools::throwWarningMessage('nickname is not good');
			return false;
		}
		return true;
	}
	/*
	* verif function verifName
	* return ? true: false 
	*/
	public function verifName($name)
	{
		//name follow same rules as nickname 
		$pattern = '#^[a-zA-Z]+#';
		$name_match = Tools::preg_m($pattern, $name);
		if(!is_string($name) && strlen($name) > 100 && $name_match[0][0] == null || $name_match[0][0] == ""){
			Tools::throwWarningMessage('name is not good');
			return false;
		}
		return true;
	}
	/*
	* verif function verifPassword
	* return ? true: false 
	*/
	public function verifPassword($password)
	{
		//password must be max 100 char, min 8 char, have letters uppercase and lowercase, have at least one number and one special char, starting with a letter(up||low)
		$p_first_letter = '#(?=^[a-zA-Z]+)(?=(^.*[a-zA-Z0-9]*.*$))(?=^.{8,100})#';
		$password_match1 = Tools::preg_m($p_first_letter, $password);
		if(strlen($password) > 100 && strlen($password) < 8 && $password_match1 == "" || $password_match1 == null){
			Tools::throwWarningMessage('password is not good - start with a letter, 8 to 100 long');
			return false;
		}
		return true;
	}
	/*
	* @args email
	* @return true|false
	** Basic email control
	*/
	public function verifEmailAddress($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		// invalid emailaddress
			Tools::throwWarningMessage('email is not valid');
			return false;
		}
		return true;
	}
	/*
	* Basic ip verification
	*/
	public function verifIPAddress($ip_address)
	{
		$control = Tools::getUserIP();
		
		if($ip_address == $control){
			return true;
		}else{
			Tools::throwWarningMessage('Something wrong with your ip_address');
			return false;
		}
	}
	/*
	*	createGroup to init id and synchronize it with the user table ones
	*/
	public function createGroup($pdo_object)
	{
		$user_indexes = $this->querySelectUserId($pdo_object);
		foreach($user_indexes as $id_user){
			$sql = 'SELECT id_user from groups WHERE id_user ='.$id_user;
			$q = $pdo_object->prepare($sql);
			$q->execute();
			$result = $q->fetchAll(PDO::FETCH_ASSOC);
		}
		if(!$result){
			$sql = 'INSERT INTO groups (id_user) VALUES (:id_user)';
			$q = $pdo_object->prepare($sql);
			$q->bindParam(':id_user', $id_user,PDO::PARAM_STR);	
			$q->execute();
		}else{	
			Tools::throwWarningMessage('id_user already exists');
		}
	}
	/**
	* @return true:false
	* 
	*/
	public function queryAddGroupFriendList($pdo_object, $id_user)
	{
		if($id_user)
		{
			
		}
	}
	/*
	* arg pdo_object
	* return array id_user
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
}