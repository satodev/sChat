<?php
class Database
{
	private $pdo_object;
	private $table_name;
	private $current_id_user_connected = Array();
	public function __construct()
	{
		$this->pdo_object = $this->connect();
		$this->table_name = array('chats','groups', 'messages', 'rooms', 'security_systems', 'users', 'friend_list');
		//testing function (they will be called in sChat class after that)
		$this->callCreateAllStructure();
		$this->querySelectAllDataTable($this->pdo_object, 'users');	
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

		$this->callLoginProcess('hemmi.satoru@gmail.con', 'warleague@4591');
		$this->callLoginProcess('satoru.hemmi@gmail.con', 'meinpassword123');
		$this->queryAddGroupFriendList($this->pdo_object, $this->current_id_user_connected, 1);
		$this->callAddFriend(1, 2);
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
	/**
	* call to login process 
	* verif if the query has correctly been done
	* also verif if the id_user has been implemented into the authentification array
	*/
	public function callLoginProcess($login, $mdp)
	{
		$pdo_object = $this->pdo_object;
		$verif_current_id_user =  $this->queryLoginProcess($pdo_object, $login, $mdp);
		if($pdo_object && $login && $mdp && $verif_current_id_user && $this->current_id_user_connected[$verif_current_id_user]){
			return true;
		}else{
			return false;
		}
	}
	/**
	*	@args id_user
	*/
	public function callQueryLogoutProcess($id_user)
	{
		$pdo_object = $this->pdo_object;
		$this->queryLogoutProcess($pdo_object, $id_user);
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
	/**
	* login process
	* @args login mdp 
	* @return connected?true:false
	*/
	public function queryLoginProcess($pdo_object, $login, $mdp)
	{
		//detect if it's a email form or a nickname form
		//then check the credentials into DB
		if($pdo_object){
			$mail_type = $this->verifEmailAddress($login);
			if(!$mail_type){
				//it's the nickname
				$sql = "SELECT id_user FROM `users` WHERE nickname = \"$login\" AND password = \"$mdp\"";
				$q = $pdo_object->prepare($sql);
				$q->execute();
				$result = $q->fetchAll(PDO::FETCH_ASSOC);
				if($result){
					// echo 'nickname login : id is : '.$result[0]['id_user'];
					$this->current_id_user_connected[$result[0]['id_user']] = $result[0]['id_user'];
					return $result[0]['id_user'];
				}
			}
			if($mail_type){
				//it's the email
				$sql = "SELECT id_user FROM `users` WHERE email = \"$login\" AND password = \"$mdp\"";
				$q = $pdo_object->prepare($sql);
				$q->execute();
				$result = $q->fetchAll(PDO::FETCH_ASSOC);
				if($result){
					// echo 'email login : id is : ' .$result[0]['id_user'];
					$this->current_id_user_connected[$result[0]['id_user']] = $result[0]['id_user'];
					return $result[0]['id_user'];
				}	
			}
		}
	}
	/**
	* @args pdo_object
	* @args id_user to disconnect
	* @return ? true : false
	*/
	public function queryLogoutProcess($pdo_object, $id_user){
		if($id_user && $pdo_object && $this->current_id_user_connected[$id_user]){
			unset($this->current_id_user_connected[$id_user]);
		}else{
			Tools::throwWarningMessage('unable to find this user to logout');
		}
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
	/**
	* @return id_user ? true: false
	*/
	public function queryVerifUserExistsById($pdo_object, $id_user)
	{
		if($pdo_object && $id_user)
		{
			$sql = "SELECT * FROM users WHERE id_user = ".$id_user;
			$q = $pdo_object->prepare($sql);
			$q->execute();
			$results = $q->fetchAll(PDO::FETCH_ASSOC);
			return $results;
		}
	}
	/**
	* @args pdo_object, nickname, name, password, email, ip_address
	* Verif if user exists in DB
	* @return true:false
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
			if($result['email'] && $result['ip_address']){
				return true;
			}else{
				return false;
			}
		}
	}
	/**
	* @args nickname, name, password, email, ip_address
	* verif all data before user insert to db
	* allinfocorrect? return true: echo 'noncorrectData'
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
	/**
	* @args nickname
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
	/**
	* @args name
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
	/**
	* @args password
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
	/**
	* @args email
	* @return true|false
	* Basic email control
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
	/**
	* verification id_user <=> id_group
	* return correspondence
	*/
	public function verifUserToGroupCorrespondence($pdo_object, $id_user)
	{
		if($id_user){
			$sql = "SELECT id_user FROM `groups` WHERE id_user = \"$id_user\" AND id_group = \"$id_user\"";
			$q = $pdo_object->prepare($sql);
			$q->execute();
			$result = $q->fetchAll(PDO::FETCH_ASSOC);
			return $result[0]['id_user'];
		}
	}
	/**
	* verif by id if user is logged
	*/
	public function verifIsLoggedUser($pdo_object, $id_user)
	{
		if($pdo_object && $id_user){
			if($this->current_id_user_connected[$id_user]){
				return true;
			}else{
				return false;
			}
		}
	}
	/**
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
	* @args pdo_object
	* @user_host
	* @id_user
	* @return true:false
	* 
	*/
	public function queryAddGroupFriendList($pdo_object, $user_host, $id_user)
	{
		//DB column utilities : friend_list_length; nearby_user_length; seen_user_length 
		$result = $this->queryVerifUserExistsById($pdo_object, $id_user);
		if($pdo_object && $result)
		{
			$sql = 'UPDATE groups SET friend_list = concat(friend_list, 2) WHERE id_group = 1';
			$q = $pdo_object->prepare($sql);
			$q->execute();
			return true;
		}else{
			Tools::throwErrorMessage('queryAddGroupFriendList : id_User doesn\'t exists');
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