<?php
class Database
{
	private $pdo_object;
	public function __construct()
	{
		$this->pdo_object = $this->connect();

		$this->callCreateAllStructure();
		// $this->querySelectAllDataTable($pdo_object, 'user');	
		$this->callQueryInsertUser('SynToX','Sato','satoruHemmi','s.hemmi@gmail.com','192.168.1.3');
		//clean DB
		$this->callCleanDb();
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
		echo 'user data '.$user_data_correct;
		if($user_data_correct){
			$this->queryInsertUser($pdo_object, $nickname, $name, $password, $email, $ip_address);
		}else{
			Tools::throwErrorMessage('user_data_incorrect');
		}
	}
	public function callCleanDb()
	{
		$pdo_object = $this->pdo_object;
		$table_name = array('chats','groups', 'messages', 'rooms', 'security_systems', 'users');
		$this->cleanDb($pdo_object, $table_name);
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
		//get all db
		//foreach db 
		//truncate it		
		if($table_name){
			if($pdo_object){
				foreach($table_name as $db_name){
					$sql = 'TRUNCATE '.$db_name;
					$pdo_object->exec($sql);
				}
			}
		}
	}
	/*
	* 	Select all data in specific table
	*	@args pdo_object && table name
	*	echo table with data selected
	*/
	public function querySelectAllDataTable($pdo_object,$table)
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
		if($this->verifNickname($nickname) && $this->verifName($name) && $this->verifPassword($password) && $this->verifEmailAddress($email))
		{
			return true;	
		}else{
			return false;
		}
		//email must be a valid email address

		//ip_address is automatically retreived from user machine
		
	}
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
	public function verifName($name)
	{
		echo 'pass Name';
		//name follow same rules as nickname 
		$pattern = '#^[A-Z]?||^[a-z]?#';
		$name_match = Tools::preg_m($pattern, $name);
		if(!is_string($name) && strlen($name) > 100 && $name_match[0][0] == null || $name_match[0][0] == ""){
			Tools::throwWarningMessage('name is not good');
			return false;
		}
		return true;
	}
	public function verifPassword($password)
	{
		echo 'pass Password';
		//password must be max 100 char, min 8 char, have letters uppercase and lowercase, have at least one number and one special char, starting with a letter(up||low)
		$p_first_letter = '#(?=^[a-zA-Z]+)(?=(^.*[a-zA-Z0-9]*.*$))(?=^.{8,100})#';
		$password_match1 = Tools::preg_m($p_first_letter, $password);
		if(strlen($password) > 100 && strlen($password) < 8 && $password_match1 == "" || $password_match1 == null){
			Tools::throwWarningMessage('password is not good - start with a letter, 8 to 100 long');
			return false;
		}
		return true;
	}
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