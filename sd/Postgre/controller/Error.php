<?php
class Fail extends Controllers
{
	function __construct()
	{
		parent::__construct();
		$this->view->render("error/Index");
	}
}
?>