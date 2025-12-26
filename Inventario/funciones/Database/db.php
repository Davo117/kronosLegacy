<?php
/*define('_HOST_NAME','localhost');
     define('_DATABASE_NAME','saturno');
     define('_DATABASE_USER_NAME','root');
     define('_DATABASE_PASSWORD','gl123');
*/
$hostname='localhost';
$basededatos='wally';
$usuario='kronos';
$clave='gl123';
     
$MySQLiconn = new MySQLi($hostname,$usuario,$clave,$basededatos);
if($MySQLiconn->connect_errno){
     die("ERROR : -> ".$MySQLiconn->connect_error);
}
