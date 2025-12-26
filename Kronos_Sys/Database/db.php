<?php

//Recursos Humanos
     $depa='departamento';
     

//Modulos de Logistica:
     $tablaem ='empleado';              $tablacli ='tablacliente';

     $tablaconcli='tablacontcli';       $tablasucursal='tablasuc';

     $tablaconsuc='tablaconsuc';        $tablaOrden= 'ordencompra';
     
     $reqProd='requerimientoprod';      $confirProd='confirmarprod';

     $embarq= 'embarque';               $devolucion= 'devoluciones';


//Cache 
     $cache='cache';


//Modulos de Productos
     $impresion='impresiones';          $disenio='producto';

     $cilindro= 'juegoscilindros';      $bandaS='bandaseguridad';

     $prodcli='productoscliente';       $bandaspp='bandaspp';
     

//Modulos de Materia Prima:
     $bloqueM='bloquesmateriaprima';


//Modulos de Produccion:
     $maquina='maquinas';               $sub='procesos';
     
     $produccion='produccion';
     
//Modulos de Sistema:
     $user='usuarios';                  $reporte= 'reporte';


/*define('_HOST_NAME','localhost');
     define('_DATABASE_NAME','saturno');
     define('_DATABASE_USER_NAME','root');
     define('_DATABASE_PASSWORD','gl123');

*/
$hostname='localhost';
$basededatos='saturno';
$usuario='kronos';
$clave='gl123';
     
     $MySQLiconn = new MySQLi($hostname,$usuario,$clave,$basededatos);
     if($MySQLiconn->connect_errno){
          die("ERROR : -> ".$MySQLiconn->connect_error);
     }
