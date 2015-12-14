<?php
class Controller
{
	public function __construct()
	{
		//load classes 
		$this->loadAllClasses();
		//routes init
		$this->router();
	}
	/*
	* 	if folder_name && class_name set -> include the specific file [TODO]
	* 	if folder_name || class_name set -> all folder || the specific class if founded [TODO]
	*	load all classes in folder_name defined folder
	*/
	public function loadAllClasses($folder_name = null, $class_name = null)
	{
		$folder_name = 'classes';
		$result = array();
		$pattern = '#.*.class.php$#';

		if(is_dir($folder_name))
		{
			$folder = scandir($folder_name);
			foreach($folder as $key  => $value)
			{
				preg_match($pattern, $value, $match);
				if(isset($match[0]))
				{
					array_push($result, $match[0]);
				}

			}
			$this->importFoundedClasses($folder_name,$result);
		}
		else{
			return false;
		}
	}
	/*
	*	$result = array();
	*/
	public function importFoundedClasses($folder_name, $result)
	{
		foreach($result as $v){
			include_once($folder_name.'/'.$v);
		}
	}
	/*
	* init router functions
	*/
	public function router()
	{
		$this->getDomainName();
		$current_page = $this->getCurentPage();	
		if($current_page){
			$this->getContent($current_page);
		}
		$this->initPageController($current_page);
	}
	/**
	*	@return http://serverHost/
	*/
	public function getDomainName(){
		return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/';
	}
	/*
	*	return alias current page depending on URI 
	*/
	public function getCurentPage()
	{
		$pattern = '#[a-z]*.php$#';
		preg_match($pattern, $_SERVER['REQUEST_URI'], $current_page);
		if(isset($current_page) && $current_page){
			foreach($current_page as $cp){
				switch($cp){
					case 'index.php': 
					return 'index';
					break;
					case  'rooms.php':
					return 'rooms';
					break;
					case 'chat.php':
					return 'chat';
					break;
					case 'contact.php': 
					return 'contact';
					break;
					default: 	
					return 'other';
				}
			}
		}else{
			$this->goToIndex();
		}
	}
	/*
	* redirect to -> page_name = string
	*/
	public function getContent($page_name = null)
	{
		$this->initPageHeaders();
		if(isset($page_name) && $page_name != 'index' && $page_name != 'other'){
			require('/view/'.$page_name.'.php');
		}
		if($page_name == 'index'){
			$this->initPageHeaders();
			require('/view/home.php');
		}
		if($page_name == 'other'){
			//if no argument in the url -> go to Index -> otherwise -> check url values and treat it
			$this->goToIndex();
		}
		$this->initPageFooter();
	}
	/*
	*	return project index
	*/
	public function goToIndex()
	{
		header('Location:'.$this->getDomainName().'sChat/index.php');
	}
	/*
	*	init page headers (style, js, other description and data-type for example) 
	*/
	public function initPageHeaders()
	{
		include_once('view/main_page_header.php');
	}
	/*
	*  init page footers (close the html tags for example)
	*/
	public function initPageFooter()
	{
		include_once('view/main_page_footer.php');
	}
	/*
	* args current page 
	* init controller for each current_page
	*/
	public function initPageController($current_page)
	{
		if($current_page){
			switch($current_page)
			{
				case 'index':
				$this->indexController();
				break;
				case 'rooms' : 
				$this->roomsController();
				break;
				case 'chat':
				$this->chatController();
				break;
				case 'contact':
				$this->contactController();
				break;
			}
		}
	}
	public function indexController()
	{
		//authentification controller && security controller
		$auth = new Authentification();
		
		// session_start();
		// $_SESSION['name'] = "MyName";
		// echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
		// var_dump($_SESSION['name']);
		// echo '</pre>';
		// for($a = 0; $a < 10; $a++){
		// 	$ar = array($a => $a);
		// 	Tools::writeJsonFile('test'.$a, $ar);	
		// }
		// Tools::readJsonFile('test');
		$a = array("1" => array("1" =>"data1"), "2" => "data2", "3" => "data3");
		Tools::recursiveArray($a);
	}
	public function roomsController()
	{
		//rooms listing
		echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
		var_dump($_COOKIE);
		echo '</pre>';
	}
	public function chatController()
	{
		//chatting interface
		echo '<pre style="font-family: sans-serif; font-size: 1.5rem;display:block; width: 100%; word-wrap: break-word;">';
		var_dump($_COOKIE);
		echo '</pre>';
	}
	public function contactController()
	{
		//contact support or enhancement service (me) and other information (git & todo list & release) about this app
	}
}