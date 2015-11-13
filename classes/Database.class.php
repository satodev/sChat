<?php
class Database
{
	public function __construct()
	{
		$pdo_object = $this->connect();
		$create_all_structure = $this->createAllStructure($pdo_object);
		$this->querySelectAllDataTable($pdo_object, 'user');	
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
	public function destroyAllStructure($pdo_object)
	{

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
	public function queryInsertUser($pdo_object)
	{
		$pdo_object->exec('Insert INTO TABLE IF NOT EXISTS ')
	}
	/*
	*	Create Table Users
	*	@args $pdo_object
	*	return query_status? true:false
	*/
	public function queryCreateUsers($pdo_object)
	{
		if($pdo_object->exec('CREATE TABLE IF NOT EXISTS users(
			id_user INT PRIMARY KEY NOT NULL,
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
			id_chat INT PRIMARY KEY NOT NULL,
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
			id_group INT PRIMARY KEY NOT NULL,
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
			id_room INT PRIMARY KEY NOT NULL,
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
			id_msg INT PRIMARY KEY NOT NULL,
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
			id_skey INT PRIMARY KEY NOT NULL ,
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