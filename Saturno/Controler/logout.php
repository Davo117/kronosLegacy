<?php
	session_start();
		unset ($_SESSION['usuario']);
			session_destroy();
	 
	/*include("../ingresar.php");*/
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=../Menu.php'>";
	?>