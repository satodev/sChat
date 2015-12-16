<?php
class Authentification
{
	private static $form;
	private static $user;
	private static $login_user;
	private static $login_pwd;

	public function __construct()
	{	
		Authentification::$form = new Form();
		Authentification::$user = new User();

		// $db = new User();
		// $db->callLoginProcess('hemmi.satoru@gmail.con', 'warleague@4591');
		// $db->showCurrentUserLogged();
		// $db->callLoginProcess('satoru.hemmi@gmail.con', 'meinpassword123');
		// $db->showCurrentUserLogged();
	}
	public static function login()
	{
		$form = Authentification::$form;
		
		$id = $user->callLoginProcess(Authentification::$login_user, Authentification::$login_pwd);
		echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
		var_dump($id);
		echo '</pre>';
		if($id){

		}else{
			Tools::throwWarningMessage('wrong identification');
		}
		Authentification::isMemberConnected();
	}
	public static function logout()
	{

	}
	public static function isMemberConnected()
	{

		echo Authentification::$user->showCurrentUserLogged();die;
		Authentification::$user->callVerifUserExistsById();
	}
	public static function signin()
	{

	}
}