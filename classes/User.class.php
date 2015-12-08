<?php
class User extends Database
{
	private $pdo_object;
	private $current_id_user_connected = Array();

	public function __construct()
	{
		$this->pdo_object = $this->connect();

		//testing user here

		// $this->callQueryInsertUser('Syntox','sat','warleague@4591','hemmi.satoru@gmail.com','::1');
		// // $this->callQueryInsertUser('Sato1','sato1','meinpassword123','satoru.hemmi@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato2','satoru','warleague@4591','s.hemmi@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato3','satoru','warleague@4591','sa.hemmi@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato4','satoru','warleague@4591','sato.hemmi@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato5','satoru','warleague@4591','sator.hemmi@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato6','satoru','warleague@4591','satoru.h@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato7','satoru','warleague@4591','satoru.he@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato8','satoru','warleague@4591','satoru.hem@gmail.con','::1');
		// // $this->callQueryInsertUser('Sato9','satoru','warleague@4591','satoru.hemm@gmail.con','::1');
		
		
		// $this->queryAddUserToFriendList($this->pdo_object, $this->current_id_user_connected, 1);	
	}
	public function showCurrentUserLogged()
	{
		echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
		var_dump($this->current_id_user_connected);
		echo '</pre>';
	}
	/**
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
			$group = new Group();
			$group->createGroup($pdo_object);
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
			return $verif_current_id_user;
		}else{
			return false;
		}
	}
	/**
	*	@param id_user
	*   call to queryLogoutProcess
	*/
	public function callQueryLogoutProcess($id_user)
	{
		$pdo_object = $this->pdo_object;
		$this->queryLogoutProcess($pdo_object, $id_user);
	}
	/**
	*
	*/
	public function callVerifUserExistsById($id_user)
	{
		$pdo_object = $this->pdo_object;
		$this->queryVerifUserExistsById($pdo_object, $id_user);
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
	*	User Insert Db
	*/
	public function queryInsertUser($pdo_object, $nickname, $name, $password, $email, $ip_address)
	{
		if($pdo_object && $nickname && $name && $password && $email && $ip_address){
			$user_exists = $this->queryVerifUserExists($pdo_object, $nickname, $name, $password, $email, $ip_address);
			if($user_exists == false){
				$sql = 'INSERT INTO users (nickname, name, password, email, ip_address)
					VALUES (
						:nickname,
						:name,
						MD5(:password),
						:email,
						:ip_address
						)';
				$q = $pdo_object->prepare($sql);
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
	* @param pdo_object, id_user
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
			// Tools::throwWarningMessage('email is not valid');
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
	* verif by id if user is logged
	*/
	public function verifIsLoggedUser($pdo_object, $id_user)
	{
		if($pdo_object && $id_user && $this->current_id_user_connected){
			if($this->current_id_user_connected[$id_user]){
				return true;
			}else{
				return false;
			}
		}
	}
}