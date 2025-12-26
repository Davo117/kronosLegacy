<?php 

$MySQLi= new mysqli('localhost', 'kronos','gl123','saturno');
/*$SQL=$MySQLi->query("SELECT j.identificadorJuego,(SELECT numParametro FROM juegoparametros WHERE identificadorJuego=j.identificadorJuego order by id desc limit 1) as nu  from juegoparametros j GROUP by j.identificadorJuego");
$contador=1;
while ($row=$SQL->fetch_array()) {
  if(!empty($row['nu']))
  {
  $numero=explode('PAM',$row['nu']);
  if(!empty($numero[1]))
  {
    $numero=$numero[1]+1;
  echo $contador."\n";
    $no="PAM".$numero;
    //$MySQLi->query("INSERT into juegoparametros(identificadorJuego,nombreparametro,numParametro,baja,requerido,leyenda,placeholder) values ('".$row['identificadorJuego']."','logproveedor','".$no."','1','0','Cod. proveedor','Identificador proveedor')");
    $contador++;
  }
  
  }
  
 // 
}
/*
set_time_limit(500);
for($i=0;$i<100000;$i++)
{
	$MySQLi->query("INSERT into lotes(bloque,referenciaLote,longitud,peso,tarima,estado,ancho,espesor)values('O.C. 5409 450 mm','LoteVirus".$i."','10000','100','TAMPICO-01','0','400','20')");


	$MySQLi->query("INSERT into codigosbarras(codigo,proceso,)values('O.C. 5409 450 mm','LoteVirus".$i."','10000','100','TAMPICO-01','0','400','20')");
}*/
/*echo "<p>hahaha</p>";
$SQL=$MySQLi->query("SELECT*from lotes");
while($row=$SQL->fetch_array())
{
	echo $row['referenciaLote'];
}*/
/*$valNum=0;
$numero=9;
$tarima="7001674-005.320-2";
while($valNum<1)
{
$SK=$MySQLi->query("SELECT numeroLote from lotes where numeroLote='$numero' and tarima='$tarima'");
if($SK->num_rows>0)
{
	$numero++;
}
else
{
	$valNum=1;
}
}
echo $numero."|".$valNum;*/

/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title>Grupo Labro</title>
 <link rel="apple-touch-icon" sizes="76x76" href="pictures/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="pictures/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="pictures/favicon-16x16.png">
	<link rel="manifest" href="pictures/manifest.json">
	<link rel="mask-icon" href="pictures/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#000">
 <style type="text/css"> 

html, body, div, iframe { 
margin:0; 
padding:0; 
height:100%; 
} 
iframe { 
display:block; 
width:100%; 
border:none; } 
</style>
 </head>
 <body>
 	<?php
   
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
$MySQLic = new mysqli($hostname, $username,$password, $database);
if ($MySQLiconn -> connect_errno) {
die( "Fallo la conexión a MySQL: (" .$MySQLiconn -> mysqli_connect_errno() 
. ") " . $MySQLiconn -> mysqli_connect_error());
}

$var="";
//$MySQLiconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$MySQLiconn->begin_transaction();
mysqli_autocommit($MySQLiconn,FALSE);
//$MySQLiconn->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

if(1==1)
{
	$SWOL=$MySQLiconn->query("
	INSERT into pruebas(dato,entero) values('jeje',12)");
}
$SQiL =$MySQLic->query("call getnoop('Santorini')");
						$ro=$SQiL->fetch_array();
						$noop=$ro['noop'];	
						$MySQLic->next_result();


$var=funcionloka($MySQLiconn);


$SIK=$MySQLiconn->query("
	INSERT into pruebas(dato,entero) values('segunda',13)");

$SQL=$MySQLiconn->query("
	INSERT into pruebas(dato,entero) values('tercera',14)");

if(!$SQL or !$SWOL or !$SIK)
{
	$MySQLiconn->rollback();
	echo $var;
}	
else
{
	$MySQLiconn->commit();
	echo $var;
	echo "Revisa tu bandeja prro";
	$fecha = date('d-m-Y H:i:s');
	$para='castillo.araiza.erik@gmail.com';
	$asunto='Documento no encontrado.';
	$mensaje='Saca las panochas perro,que sea el dia de '.$fecha;
	mail($para,$asunto,$mensaje);
}





   //<iframe src="Producto/Producto_Consumos.php"></iframe>

function funcionLoka($con)
{
	$MAI=$con->query("SELECT*FROM pruebas WHERE dato='jeje'");
$ted=$MAI->fetch_array();
$var=$ted['dato'];
return $var;
}
 ?>


  </body>
 </html>*/


 /*

 Codigo para los mapas
 ?>
 <!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Places Searchbox</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
     //   height: 100%;
      //}
      /* Optional: Makes the sample page fill the window. 
      //html, body {
        //height: 100%;
        //margin: 0;
        //padding: 0;
      //}
      //.controls {
        //margin-top: 10px;
        //border: 1px solid transparent;
        //border-radius: 2px 0 0 2px;
        //box-sizing: border-box;
    //    -moz-box-sizing: border-box;
     //   height: 32px;
      //  outline: none;
     //   box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
     // }

      #pac-input {
       // background-color: #fff;
       // font-family: Roboto;
       // font-size: 15px;
       // font-weight: 300;
     //   margin-left: 12px;
      //  padding: 0 11px 0 13px;
        //text-overflow: ellipsis;
        //width: 300px;
      //}

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      #target {
        width: 345px;
      }
    </style>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
    <script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
         center: {lat: 21.137534, lng: -101.689667},
          zoom: 6
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

    </script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPBbPUfZWXNXU-J-2iudOSsnPuN5dzjOs&callback=initMap"
    async defer></script>
  </body>
</html>
*/
/*include("Database/SQLConnection.php");
$sttm=sqlsrv_query($SQLconn,"SELECT a.CIDPRODUCTO,a.CIDPROVEEDOR,a.CPRECIOCOMPRA,p.CNOMBREPRODUCTO,c.CRAZONSOCIAL,p.CIDVALORCLASIFICACION1 FROM admPreciosCompra a
INNER JOIN admProductos p on p.CIDPRODUCTO=a.CIDPRODUCTO
inner join admClientes c on c.CIDCLIENTEPROVEEDOR=a.CIDPROVEEDOR
where p.CIDVALORCLASIFICACION1=11 or p.CIDVALORCLASIFICACION2=11 or p.CIDVALORCLASIFICACION3=11 or p.CIDVALORCLASIFICACION4=11");
while($row= sqlsrv_fetch_array($sttm, SQLSRV_FETCH_ASSOC))
{
  ?>
  <p><?php echo $row['CRAZONSOCIAL']."---".$row['CNOMBREPRODUCTO'];?></p>
    <?php
}*/

require_once('Excel/Classes/PHPExcel/IOFactory.php'); 
require_once('Excel/Classes/PHPExcel.php');
require_once('Excel/Classes/PHPExcel/Reader/Excel2007.php');

$hostname = 'localhost';
$database = 'saturno';
$username = 'kronos';
$password = 'gl123';
$tablaus ='lotes';

$mysqli = new mysqli($hostname, $username,$password, $database);
if ($mysqli -> connect_errno) {
die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
. ") " . $mysqli -> mysqli_connect_error());
}
//cargamos el archivo excel(extension *.xls) 
$objPHPExcel = PHPExcel_IOFactory::load('Documentos/seguro.xls'); 
$bloque=$_SESSION['desBloque'];
// Asignamos la hoja excel activa 
$objPHPExcel->setActiveSheetIndex(0); 
$i=2;
 $msj="";
 if(empty($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
''))
 {
  $msj=2;
 }
 
while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() !=
'') 
{   
/*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS*/
/** Invocacion de Clases necesarias */

//DATOS DE CONEXION A LA BASE DE DATOS

//$db = mysqli_select_db ("escuela",$mysqli) or die ("ERROR AL CONECTAR A LA BD");
 
$referenciaLote=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
//$arrayNumero = explode("-", $referenciaLote, 2);

$longitud=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$ancho=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$espesor=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$encogimiento=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$peso=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
$tarima=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();
$loteGral=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getValue();
$valNum=0;
$Sq=$mysqli->query("SELECT numeroLote from tblotes where bloque='".$bloque."' and loteGral='".$loteGral."' order by numeroLote desc");
$rum=$Sq->fetch_array();
$numero=$rum['numeroLote']+1;
while($valNum<1)
{
$SK=$mysqli->query("SELECT numeroLote from tblotes where numeroLote='".$numero."' and loteGral='".$loteGral."'");
if($SK->num_rows>0)
{
  $numero++;
}
else
{
  $valNum=1;
}
}

if($valNum==1)
{
  $result=$mysqli->query("SELECT referenciaLote from tblotes where referenciaLote='$referenciaLote'");
 $rau = $result->fetch_array();
 $SQL="";
 if(empty($rau['referenciaLote']))
 {
  $SQL =$mysqli->query("INSERT INTO tblotes (bloque, loteGral,referenciaLote, longitud, peso, tarima, estado, shower, noop, ancho, espesor, encogimiento,numeroLote,baja) VALUES ('$bloque','$loteGral','$referenciaLote', '$longitud', '$peso', '$tarima', 0, 1, 0, '$ancho', '$espesor', '$encogimiento','$numero', 1)");

 }
 else
 {
  $msj=1;
 }
$i++;
}
 
}
//echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=Materia_prima/MateriaPrima_Bloques.php'>";
if($msj==1)
{
  $mensaje="warning";
}
else if($msj==2)
{
  $mensaje="danger";
}
else
{
  $mensaje="success";
}
echo "<script>window.location='Materia_prima/MateriaPrima_Bloques.php?msj=".$mensaje."';</script>";
?>
?>