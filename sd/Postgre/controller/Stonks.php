<?php
class Stonks extends Controllers
{
	function __construct()
	{
		parent::__construct();
		$this->view->render("stonks/Index");
	}
}
?>