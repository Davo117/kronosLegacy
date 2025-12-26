<!DOCTYPE html>
<html>
<head>
	<title>Página principal</title>
</head>
<body>
	<?php require "view/header.php";
		  require "config/conection.php";?>
<h2>Esta es la página principal</h2>
<?php
$query="SELECT*FROM perrito";
$perritos = pg_query($query) or die('Error: ' . pg_last_error());
$resutado = array();
while ($row = pg_fetch_assoc($perritos)) {
   echo "<p>".$row['nombre']."</p>";
}
 
?>
</body>
</html>