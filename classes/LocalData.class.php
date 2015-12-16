<?php
class LocalData
{
	public static $file_name = 'user';
	public static $folder_name = 'data';

	public static function writeJsonFile($content)
	{
		if(LocalData::$file_name && $content && LocalData::verifFolderExists()){
			$fp = fopen(LocalData::$folder_name.'/'.LocalData::$file_name.'.json', 'w');
			fwrite($fp, json_encode($content));
			fclose($fp);
		}else{
			if(Tools::mkdir(LocalData::$folder_name)){
				$this->writeJsonFile($content);
			}else{
				Tools::throwErrorMessage('couldn`t write in file');
			}
		}
	}
	public static function readJsonFile($id_user = NULL)
	{
		if(LocalData::$file_name && LocalData::verifFileExists()){
			$string = file_get_contents(LocalData::$folder_name.'/'.LocalData::$file_name.'.json');
			$json_a = json_decode($string, true);
			if($id_user){
				Tools::recursiveEchoParseArray($json_a[$id_user]);
			}else{
				Tools::recursiveEchoParseArray($json_a);
			}
		}else{
			if(Tools::mkdir(LocalData::$folder_name)){
				$this->readJsonfile(LocalData::$file_name);
			}else{
				Tools::throwErrorMessage('couldn`t read in file');
			}
		}
	}
	public static function deleteJsonFile()
	{
		if(LocalData::$file_name){
			unlink(LocalData::$folder_name.'/'.LocalData::$file_name.'.json');
			return true;
		}else if(file_exists(LocalData::verifFolderExists())){
			$dir = scandir(LocalData::$folder_name);
			foreach($dir as $elem){
				if($elem != "." && $elem != ".."){
					unlink(LocalData::$folder_name.'/'.$elem);
				}
			}
		}
	}
	public static function verifFileExists()
	{
		if(LocalData::verifFolderExists()){
			return (LocalData::$file_name && file_exists(LocalData::$folder_name.'/'.LocalData::$file_name.'.json')) ? true : false;
		}
	}
	public static function verifFolderExists()
	{
		return (LocalData::$folder_name && file_exists(LocalData::$folder_name)) ? true : false;
	}
}