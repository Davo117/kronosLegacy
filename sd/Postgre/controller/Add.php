<?php
class Add extends Controllers
{
	function __construct()
	{
		parent::__construct();
		$this->view->render("add/Index");
	}
	function load()
	{
		echo "Entró a cargar";
	}
}
?>