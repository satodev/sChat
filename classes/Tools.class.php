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
	public static function writeJsonFile($file_name, $content)
	{
		if($file_name && $content && file_exists('data')){
			$fp = fopen('data/'.$file_name.'.json', 'w');
			fwrite($fp, json_encode($content));
			fclose($fp);
		}else{
			if(Tools::mkdir('data')){
				Tools::writeJsonFile($file_name,$content);
			}else{
				Tools::throwErrorMessage('couldn`t write in file');
			}
		}
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
	public static function readJsonFile($file_name)
	{
		if($file_name && file_exists('data')){
			$string = file_get_contents('data/'.$file_name.'.json');
			$json_a = json_decode($string, true);
			Tools::recursiveParseArray($json_a);
		}else{
			if(Tools::mkdir('data')){
				Tools::readJsonfile($file_name);
			}else{
				Tools::throwErrorMessage('couldn`t read in file');
			}
		}
	}	
	public static function deleteJsonFile($file_name=NULL)
	{
		if($file_name){
			unlink('data/'.$file_name.'.json');
			return true;
		}else{
			$dir = scandir('data');
			foreach($dir as $elem){
				echo $elem.'<br />';
				if($elem != "." && $elem != ".."){
					unlink('data/'.$elem);
				}
			}
		}
	}
	public static function recursiveParseArray($array)
	{
		foreach($array as $key=>$a){
			if(gettype($a) == 'string'){
				echo $key .'=>'.$a.'<br />';
			}else{
				echo $key.'<br />';
				Tools::recursiveParseArray($a);
			}
		}
	}
}