<?php
class Tools{
	private static $test_login;
	public static function preg_m($pattern, $subject) 
	{
		preg_match($pattern, $subject, $match, PREG_OFFSET_CAPTURE);
		return $match;
	}
	public static function throwWarningMessage($message)
	{
		echo '<div class="container-fluid"><p class="col-xs-2 col-sm-2 col-md-2 col-lg-2 bg-warning center-block" style="text-align:center; padding:5px;">'.strtoupper($message).'</p></div>';
	}
	public static function throwErrorMessage($message)
	{
		echo '<div class="container-fluid"><p class="col-xs-2 col-sm-2 col-md-2 col-lg-2 bg-danger center-block" style="text-align:center; padding:5px;">'.strtoupper($message).'</p></div>';
	}
	public static function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		return $ip;
	}
	public static function callSetTestLogin($arg)
	{
		self::$test_login = $arg;
	}
	public static function showTestLogin()
	{
		echo self::$test_login;
	}
	public static function setCookie($name, $value, $time=NULL)
	{
		setcookie($name, $value, time()+$time);
	}
	public static function mkdir($folder_name)
	{
		if($folder_name && !file_exists($folder_name)){
			mkdir($folder_name);
			return true;
		}else{
			return false;
		}
	}
	public static function recursiveEchoParseArray($array)
	{
		foreach($array as $key=>$a){
			if(gettype($a) == 'string'){
				echo $key .'=>'.$a.'<br />';
			}else{
				echo $key.'<br />';
				Tools::recursiveEchoParseArray($a);
			}
		}
	}
	public static function arraySearch($search, $array)
	{
		$key = array_search($search, $array);
		echo 'pass'.$key;
	}
}