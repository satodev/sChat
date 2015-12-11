<?php
class Ajax
{
	public function __construct()
	{
		if(isset($_POST)){
			if(isset($_POST['logout'])){
				$this->queryLogout();
			}
		}
	}
	public function queryLogout()
	{
		echo "logouted";
	}
}
$a = new Ajax();