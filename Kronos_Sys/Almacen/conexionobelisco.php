<?php //Datos de conexión a la base de datos
$hostname = 'localhost';
$database = 'obelisco';
$username = 'kronos';
$password = 'gl123';
$tablaus ='pzcodes';

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
?>

