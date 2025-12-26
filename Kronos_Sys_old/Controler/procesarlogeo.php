<?php
session_start();
?>
<?php
 
include("../Database/conexionphp.php");
 
//$link=Conectarse();
 
$login = $_POST['user'];
 
$pass = $_POST['pass'];
if($_POST['txtRuta']=="/ingresar.php")
{
	$ruta="Menu.php";
}
else
{
	$ruta=$_POST['txtRuta'];
}
if(!empty($ruta))
{
	$ruta="../".$ruta;
}
else
{
	$ruta='../Menu.php';
}

 
//$pass=sha1(md5($pass));
 
$query ="SELECT id, usuario, nombre, rol, perfil FROM usuarios WHERE usuario= '$login' && AES_DECRYPT(contrasenia,667) = '$pass'";

$result = $mysqli->query($query);
//printf($result);
if ($result->num_rows > 0) {   

	$row = $result->fetch_array(MYSQLI_ASSOC);
	$_SESSION['nombrerol']=$row['rol'];  
	$datos=explode('PF', $row['perfil']);

	$depa=$mysqli->query("SELECT departamento from empleado where ID='".$datos[1]."'");
		$rowRol = $depa->fetch_array(MYSQLI_ASSOC);


	  	$_SESSION['logan']=true;//Sirve para validar la sesion
	    $_SESSION['usuario'] = $row['usuario'];
	    $_SESSION['nombre']=$row['nombre'];
	    $_SESSION['rol']=$rowRol['departamento'];
	    $_SESSION['perfil']=$row['perfil'];
	    $_SESSION['start'] = time();

	    $_SESSION['expire'] = $_SESSION['start'] + (36000);
/*if($_SESSION['rol']=='Produccion')
{
	echo "<script>window.location='../Produccion/Produccion_control.php';</script>";
}
	else if($_SESSION['rol']=='Compras')
	{
		echo "<script>window.location='../Materia_prima/MateriaPrima_Bloques.php';</script>";
	}
	else if(strncmp($_SESSION['rol'],'Logística', 3)===0)
	{
		echo "<script>window.location='../Logistica/Logistica_Clientes.php';</script>";
	}
	else if(strncmp($_SESSION['rol'],'Tecnologías de la información', 3)===0)
	{
		echo "<script>window.location='../Sistema/bitacora.php';</script>";
	}
	else if(strncmp($_SESSION['rol'],'Calidad', 3)===0)
	{
		echo "<script>window.location='../Sistema/bitacora.php';</script>";
	}
	else
	{
		echo "<script>window.location='../Menu.php';</script>";
	}*/
 
  	echo "<script>window.location='$ruta';</script>";
 
	
	 	
	 
	} else { 

	  echo '<script language="javascript">alert("Tus datos son incorrectos o no estás registrado");</script>'; 
	  	echo "<script>window.location='".$ruta."';</script>";
	 }
	 include("../Database/cerrarconexion.php");
	

?>
