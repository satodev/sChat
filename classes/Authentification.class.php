<?php
class Authentification
{
	private static $form;
	private static $user;
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
		$user = Authentification::$user;
		if($form->returnTypeOfForm() == 'login'){
			$name = $form->retreiveFormValueByName('name');
			$pwd = $form->retreiveFormValueByName('password');
			$id = $user->callLoginProcess($name, $pwd);
			echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
			var_dump($id);
			echo '</pre>';
			if($id){
				Tools::setCookie($id, $name,100000);
				echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
				var_dump($_COOKIE);
				echo '</pre>';
			}else{
				Tools::throwWarningMessage('wrong identification');
			}
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