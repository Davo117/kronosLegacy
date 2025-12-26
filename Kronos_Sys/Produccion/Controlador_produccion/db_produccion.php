<?php //Datos de conexión a la base de datos
$hostname = 'localhost';
$database = 'saturno';
$username = 'kronos';
$password = 'gl123';
$tablaus ='usuario';

$MySQLiconn = new mysqli($hostname, $username,$password, $database);
if ($MySQLiconn -> connect_errno) {
die( "Fallo la conexión a MySQL: (" .$MySQLiconn -> mysqli_connect_errno() 
. ") " . $MySQLiconn -> mysqli_connect_error());
}

$MySQLic= new mysqli($hostname, $username,$password, $database);
if ($MySQLiconn -> connect_errno) {
die( "Fallo la conexión a MySQL: (" .$MySQLiconn -> mysqli_connect_errno() 
. ") " . $MySQLiconn -> mysqli_connect_error());
}

?>
