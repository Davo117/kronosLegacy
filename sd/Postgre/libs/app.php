<?php
require_once 'controller/Error.php';
class App
{
	function __construct()
	{
		$url=$_GET['url'];
		$url=rtrim($url,"/");
		$url=explode("/", $url);
		$file="controller/".$url[0].".php";
		if(file_exists($file))
		{
			include_once $file;
			$view=new $url[0];
			if(isset($url[1]))
			{
				$view->{$url[1]}();
			}
		}
		else
		{
			$view=new Fail();
		}
	}
}
?>