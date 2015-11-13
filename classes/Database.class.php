<?php
class Database
{
	public function __construct()
	{
		$pdo_object = $this->connect();
		$create_all_structure = $this->createAllStructure($pdo_object);
		$this->querySelectAllDataTable($pdo_object, 'user');	
	}
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
	public function createAllStructure($pdo_object)
	{
		if($pdo_object){
				$query_Create_Users_structure = $this->queryCreateUsers($pdo_object);
				$query_Create_Chat_structure = $this->queryCreateChat($pdo_object);
				$query_Create_Group_structure = $this->queryCreateGroup($pdo_object);
				$query_Create_Rooms_structure = $this->queryCreateRooms($pdo_object);
				$query_Create_Message_structure = $this->queryCreateMessage($pdo_object);
				$query_Create_SecuritySystem_structure = $this->queryCreateSecuritySystem($pdo_object);
		}
		return array($query_Create_Users_structure, $query_Create_Chat_structure, $query_Create_Group_structure, $query_Create_Rooms_structure, $query_Create_Message_structure, $query_Create_SecuritySystem_structure);
	}
	public function querySelectAllDataTable($pdo_object, $table)
	{
		if($pdo_object){
			$sql = 'SELECT * FROM '.$table;
			foreach($pdo_object->query($sql) as $row){
				echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
				var_dump($row);
				echo '</pre>';
			}
		}
	}
	public function queryCreateUsers($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS user(
			id_user INT PRIMARY KEY NOT NULL,
			nickname VARCHAR(100),
			name VARCHAR(100),
			password VARCHAR(100),
			email VARCHAR(100),
			ip_address VARCHAR(100)
			)')){
			return true;
		}else{
			return false;
		}
	}
	public function queryCreateChat($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS chat(
			id_chat INT PRIMARY KEY NOT NULL,
			id_leader INT NOT NULL,
			id_invite INT NOT NULL,
			id_group INT NOT NULL
			)')){
			return true;
		}else{
			return false;
		}
	}
	public function queryCreateGroup($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS groups(
			id_group INT PRIMARY KEY NOT NULL,
			friend_list VARCHAR(255),
			nearby_user VARCHAR(255),
			seen_user VARCHAR(255)
			)')){
			return true;
		}else{
			return false;
		}
	}
	public function queryCreateRooms($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS rooms(
			id_room INT PRIMARY KEY NOT NULL,
			id_chat INT NOT NULL, 
			conf_room INT NOT NULL,
			conf_chats INT NOT NULL,
			conf_users BOOLEAN NOT NULL
			)')){
			return true;
		}else{
			return false;
		}
	}
	public function queryCreateMessage($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS messages(
			id_msg INT PRIMARY KEY NOT NULL,
			from_user INT NOT NULL,
			to_user INT NOT NULL,
			msg_content VARCHAR(255) NOT NULL,
			date_sent DATE NOT NULL,
			time_sent TIME NOT NULL
			)')){
			return true;
		}else{
			return false;
		}
	}
	public function queryCreateSecuritySystem($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS security_systems(
			id_skey INT PRIMARY KEY NOT NULL ,
			id_user INT NOT NULL ,
			token_key INT NOT NULL
			)')){
			return true;
		}else{
			return false;
		}
	}
}