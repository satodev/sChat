<?php
class SChat{
	function __construct(){
		// echo "hello<br/>";
	}
	function sendMessage()
	{
		// echo "hello2<br/>";
	}
	function dbconnect(){
		try{
			$a1 = new mysqli("localhost:3307", "root", "usbw", "schat");
			echo "<pre>";
			// var_dump($a1);
			echo "</pre>";
			// $result = $a1->query("CREATE TABLE IF NOT EXISTS contact(
			// 	name VARCHAR( 255 ) ,
			// 	mail VARCHAR( 255 )
			// 	)");
			// $row = $result->ping();
			// echo "it works";
		}catch(Exception $e){
			echo $e->getMessage();
		}
		try{
			$a2 = new PDO('mysql:host=127.0.0.1;port=3307;dbname=schat;charset=UTF8;','root','usbw', array(PDO::ATTR_PERSISTENT=>true));
		}catch(Exception $e){
			echo $e->getMessage();
		}

		// $a3 = mysql_connect();
	}
}
