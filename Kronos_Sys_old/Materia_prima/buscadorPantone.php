<?php
$pantone=$_POST['descripcionPantone'];
$pantone=trim($pantone);
$pantone=strtolower($pantone);
$newPantone=str_ireplace('pantone','',$pantone);
$pantone=trim($newPantone);
$arrayPantone = explode(" ", $pantone);
//	echo "<META tarjet="_blank" HTTP-EQUIV='REFRESH' CONTENT='0; URL=https://www.pantone.com/color-finder/$arrayPantone[1]-$arrayPantone[2]' >";
if(count($arrayPantone)==2)
{
	header('Location: https://www.pantone.com/color-finder/'.$arrayPantone[0].'-'.$arrayPantone[1].'');
}
else if(count($arrayPantone)==3)
{
	header('Location: https://www.pantone.com/color-finder/'.$arrayPantone[0].'-'.$arrayPantone[1].'-'.$arrayPantone[2].'');
}
else if(count($arrayPantone)==4)
{
	header('Location: https://www.pantone.com/color-finder/'.$arrayPantone[0].'-'.$arrayPantone[1].'-'.$arrayPantone[2].'-'.$arrayPantone[3].'');
}
else if(count($arrayPantone)==5)
{
	header('Location: https://www.pantone.com/color-finder/'.$arrayPantone[0].'-'.$arrayPantone[1].'-'.$arrayPantone[2].'-'.$arrayPantone[3].'-'.$arrayPantone[4].'');
}

?>