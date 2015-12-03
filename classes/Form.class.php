<?php
class Form
{
	private $post_name;
	private $post_value;
	public function __construct()
	{
		$this->detectPostName();
		$this->showPostSet();
		// echo $this->retreiveFormValueByName('type_of_form');
	}
	public function detectPostName()
	{
		$array_key = Array();
		$array_value = Array();
		if(isset($_POST)){
			foreach($_POST as $key => $value){
				array_push($array_key, $key);
				array_push($array_value, $value);
			}
			$this->post_name = $array_key;
			$this->post_value = $array_value;
		}
	}
	public function showPostSet()
	{
		if(isset($_POST)){
			foreach($_POST as $key => $value){
				echo $key .' : '. $value. '<br />';
			}
		}
	}
	public function retreiveFormValueByName($form_input)
	{
		if(isset($this->post_name) && $form_input){
			foreach($this->post_name as $data){
				if($data == $form_input){
					return $_POST[$data];
				}
			}
		}
	}
}
