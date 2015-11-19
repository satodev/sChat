<?php
class Database
{

	public function __construct()
	{
		$pdo_object = $this->connect();
		$create_all_structure = $this->createAllStructure($pdo_object);
		// $this->querySelectAllDataTable($pdo_object, 'user');	
		$this->queryInsertUser($pdo_object,'SynToX','Sato','sat','s.hemmi@gmail.com','192.168.1.3');
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
	*	Destoy all DB structures
	*	@args pdo_object
	*/
	public function destroyAllStructure()
	{

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
				echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
				var_dump($q);
				echo '</pre>';
				$q->bindParam(':nickname', $nickname,PDO::PARAM_STR);
				$q->bindParam(':name', $name, PDO::PARAM_STR);
				$q->bindParam(':password', $password, PDO::PARAM_STR);
				$q->bindParam(':email', $email, PDO::PARAM_STR);
				$q->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
				$q->execute();
			}else if($user_exists){
				echo 'user exists';
			}
		}
	}
	public function queryVerifUserExists($nickname, $name, $password, $email, $ip_address)
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
	*	Create Table Users
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateUsers()
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
	public function queryCreateChats()
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
	public function queryCreateGroups()
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
	public function queryCreateRooms()
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
	public function queryCreateMessages()
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
	public function queryCreateSecuritySystems()
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