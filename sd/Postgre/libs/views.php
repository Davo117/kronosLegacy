<?php
class Views
{
	
	function __construct()
	{
	}
	function render($vista)
	{
		require 'view/'.$vista.".php";
	}
}
?>