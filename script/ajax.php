<?php
class Ajax
{
	public function __construct()
	{
		if(isset($_POST)){
			if(isset($_POST['logout'])){
				$this->queryLogout();
			}
			if(isset($_POST['subscribe']) && isset($_POST['pseudo'])&& isset($_POST['email'])&& isset($_POST['name'])&&	isset($_POST['password']))
			{
				$this->subscribeAjaxProcess();
			}
			if(isset($_POST['login']) && isset($_POST['user']) && isset($_POST['pwd'])){
				$this->loginAjaxProcess();
			}
		}
	}
	public function queryLogout()
	{
		echo "logouted";
	}
	public function loginAjaxProcess()
	{

		echo base64_decode($_POST['user']).'<br />';
		echo base64_decode($_POST['pwd']).'<br />';
		echo "login";
	}
	public function subscribeAjaxProcess()
	{
		echo base64_decode($_POST['pseudo']).'<br />';
		echo base64_decode($_POST['email']).'<br />';
		echo base64_decode($_POST['name']).'<br />';
		echo base64_decode($_POST['password']).'<br />';
		echo "subscribe";
	}
}
$a = new Ajax();