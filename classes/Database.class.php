<?php
class Database
{
	public function __construct()
	{
		$pdo_object = $this->connect();
		$create_all_structure = $this->createAllStructure($pdo_object);
		echo '<pre style="font-family: sans-serif; font-size: 1rem;display:block; width: 100%; word-wrap: break-word;">';
		var_dump($create_all_structure);
		echo '</pre>';
	}
	public function connect()
	{
		$dsn = 'mysql:host=localhost:3307;dbname=schat';
		$user = 'root';
		$passwd = 'usbw';
		try{
			$pdo = new PDO($dsn,$user,$passwd);
		}catch(Exception $e){
			echo $e->getMessage();;
		}
		return $pdo;
	}
	public function createAllStructure($pdo_object)
	{
		if($pdo_object){
			if($this->queryCreateUsers($pdo_object)){
				$query_Create_Users_structure = (boolean)$this->queryCreateUsers($pdo_object);
			}
			if($this->queryCreateChat($pdo_object)){
				$query_Create_Chat_structure = (boolean)$this->queryCreateChat($pdo_object);
			}
			if($this->queryCreateGroup($pdo_object)){
				$query_Create_Group_structure = (boolean)$this->queryCreateGroup($pdo_object);
			}
			if($this->queryCreateRooms($pdo_object)){
				$query_Create_Rooms_structure = (boolean)$this->queryCreateRooms($pdo_object);
			}
			if($this->queryCreateMessage($pdo_object)){
				$query_Create_Message_structure = (boolean)$this->queryCreateMessage($pdo_object);
			}
			if($this->queryCreateSecuritySystem($pdo_object)){
				$query_Create_SecuritySystem_structure = (boolean)$this->queryCreateSecuritySystem($pdo_object);
			}
		}
		return array($query_Create_Users_structure, $query_Create_Chat_structure, $query_Create_Group_structure, $query_Create_Rooms_structure, $query_Create_Message_structure, $query_Create_SecuritySystem_structure);
	}
	public function queryCreateUsers($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS user(
			id_user INT PRIMARY KEY NOT NULL,
			nickname VARCHAR(100),
			name VARCHAR(100),
			password VARCHAR(100),
			email VARCHAR(100),
			ip_address VARCHAR(100)
			)')  or die('false');
		return $pdo_object;
		
	}
	public function queryCreateChat($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS chat(
			id_chat INT PRIMARY KEY NOT NULL,
			id_leader INT NOT NULL,
			id_invite INT NOT NULL,
			id_group INT NOT NULL
			)') or die('false');
		return $pdo_object;
		
	}
	public function queryCreateGroup($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS groups(
			id_group INT PRIMARY KEY NOT NULL,
			friend_list VARCHAR(255),
			nearby_user VARCHAR(255),
			seen_user VARCHAR(255)
			)') or die('false');
		return $pdo_object;
		
	}
	public function queryCreateRooms($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS rooms(
			id_room INT PRIMARY KEY NOT NULL,
			id_chat INT NOT NULL, 
			conf_room INT NOT NULL,
			conf_chats INT NOT NULL,
			conf_users BOOLEAN NOT NULL
			)') or die('false');
		return $pdo_object;
		
	}
	public function queryCreateMessage($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS messages(
			id_msg INT PRIMARY KEY NOT NULL,
			from_user INT NOT NULL,
			to_user INT NOT NULL,
			msg_content VARCHAR(255) NOT NULL,
			date_sent DATE NOT NULL,
			time_sent TIME NOT NULL
			)') or die('false');
		return $pdo_object;
		
	}
	public function queryCreateSecuritySystem($pdo_object)
	{
		$pdo_object->exec('CREATE TABLE IF NOT EXISTS security_systems(
			id_skey INT PRIMARY KEY NOT NULL ,
			id_user INT NOT NULL ,
			token_key INT NOT NULL
			)') or die('false');
		return $pdo_object;
		
	}
}