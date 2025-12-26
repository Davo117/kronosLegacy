<?php
     define('_HOST_NAME','localhost');
     define('_DATABASE_NAME','saturno');
     define('_DATABASE_USER_NAME','kronos');
     define('_DATABASE_PASSWORD','gl123');


     $tablaem ='empleado';              $tablacli ='tablacliente';

     $tablaconcli='tablacontcli';       $tablasucursal='tablasuc';

     $tablaconsuc='tablaconsuc';        $tablaOrden= 'ordencompra';
     
     $reqProd='requerimientoProd';      $confirProd='confirmarprod';

     $embarq= 'embarque';               $devolucion= 'devolucion';

     $prodcli='productoscliente';       $impresion='impresiones';





     
     $MySQLiconn = new MySQLi(_HOST_NAME,_DATABASE_USER_NAME,_DATABASE_PASSWORD,_DATABASE_NAME);
     if($MySQLiconn->connect_errno){
     	die("ERROR : -> ".$MySQLiconn->connect_error);
     	}