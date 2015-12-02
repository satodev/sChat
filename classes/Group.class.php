<?php
class Group extends Database
{
	private $pdo_object;

	public function __construct()
	{
		$this->pdo_object = $this->connect();
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
	*	createGroup to init id and synchronize it with the user table ones
	*/
	public function createGroup($pdo_object)
	{
		$user_indexes = $this->querySelectUserId($pdo_object);
		foreach($user_indexes as $id_user){
			$sql = 'SELECT id_user FROM groups WHERE id_user ='.$id_user;
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
}