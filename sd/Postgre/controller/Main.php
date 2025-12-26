<?php	

class Main extends Controllers
{
	function __construct()
	{
		parent::__construct();
		$this->view->render("main/Index");
	}
	function saludo()
	{
		
	}
}
?>