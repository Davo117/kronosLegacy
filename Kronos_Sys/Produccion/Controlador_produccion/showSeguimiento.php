<?php
ob_start();
include_once 'db_produccion.php';
//print("<script>alert('Esta enviando el parametro');</script>");
if(!empty($_GET['d']))
{
	$result=$MySQLiconn->query("UPDATE cache set dato='".$_GET['d']."' where id=7");
}
if(isset($_GET['generar']))
	{
		$_SESSION['etiquetas']=$_GET['generar'];
		$proceso=$_SESSION['etiquetas'];
		$proceso=explode("|", $proceso,3);
		$producto=$proceso[1];
		$proceso=$proceso[0];
		$documento="etiq".$proceso;
		$name_document = 'etiquetas/'.$documento.'.php';
if (file_exists($name_document)) {

    echo "<script>window.location='$name_document?etiquetas=".$_GET['generar']."';</script>";
} else {
    echo "<script>window.location='generarEtiquetasDinamicas.php?etiquetas=".$_GET['generar']."';</script>";
}
		
	}
	if(isset($_GET['generarAll']))
	{
		$_SESSION['etiquetas']=$_GET['generarAll'];
		$proceso=$_SESSION['etiquetas'];
		$proceso=explode("|", $proceso,3);
		$producto=$proceso[1];
		$proceso=$proceso[0];
		$documento="etiq".$proceso;
		$name_document = 'etiquetas/'.$documento.'.php';

if (file_exists($name_document)) {
    echo "<script>window.location='$name_document?etiquetas=".$_GET['generarAll']."&tipo=".$_GET['tipo']."';</script>";
} else {
    echo "<script>window.location='etiquetas/generarEtiquetasAll.php?etiquetas=".$_GET['generarAll']."&tipo=".$_GET['tipo']."';</script>";
}
		
	}

	
?>
<?php
ob_end_flush();
?>


