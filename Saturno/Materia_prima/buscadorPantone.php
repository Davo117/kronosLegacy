<?php
$pantone=$_POST['descripcionPantone'];

$arrayPantone = explode(" ", $pantone, 3);
print_r($arrayPantone[1]);
print_r($arrayPantone[2]);
//	echo "<META tarjet="_blank" HTTP-EQUIV='REFRESH' CONTENT='0; URL=https://www.pantone.com/color-finder/$arrayPantone[1]-$arrayPantone[2]' >";
header('Location: https://www.pantone.com/color-finder/'.$arrayPantone[1].'-'.$arrayPantone[2].'');
?>