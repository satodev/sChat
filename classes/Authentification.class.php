<?php
class Authentification
{
	public function __construct()
	{
		// $this->createPDOObject();
	}
	public function isDBinit()
	{

	}
	public function startDB()
	{
		$this->createPDOObject();
		$this->createDBName();
		$this->createTable();
	}
	public function createPDOObject()
	{
		$host  = 'mysql:host=localhost:8080;';
		$user = 'root';
		$passwd = 'passwd';
		$pdo_object = new PDO($host, $user, $passwd);
	}
	public function createDBName()
	{

	}
	public function createTable()
	{

	}
	public function login()
	{

	}
	public function isMemberConnected()
	{

	}
	public function signin()
	{
		
	}
}