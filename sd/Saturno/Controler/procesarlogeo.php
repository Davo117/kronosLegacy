<?php
session_start();
?>
<?php
 
include("../Database/conexionphp.php");
 
//$link=Conectarse();
 
$login = $_POST['user'];
 
$pass = $_POST['pass'];
 
//$pass=sha1(md5($pass));
 
$query ="SELECT id,
usuario,nombre,rol FROM usuarios WHERE usuario= '$login' && contrasenia = '$pass'";

$result = $mysqli->query($query);
//printf($result);
if ($result->num_rows==0) {     
	 
	 $row = $result->fetch_array(MYSQLI_ASSOC); 
	  
	  	$_SESSION['logan']=true;//Sirve para validar la sesion
	    $_SESSION['usuario'] = $row['usuario'];
	    $_SESSION['nombre']=$row['nombre'];
	    $_SESSION['rol']=$row['rol'];
	    $_SESSION['start'] = time();

	    $_SESSION['expire'] = $_SESSION['start'] + (36000);

	echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=../Menu.php'>";
	 	
	 
	 } else { 
	  echo '<script language="javascript">alert("Tus datos son incorrectos o no estás registrado");</script>'; 
	  echo "<META HTTP-EQUIV='REFRESH' CONTENT='0; URL=../ingresar.php'>";
	 }
	 include("../Database/cerrarconexion.php");
	

?>
