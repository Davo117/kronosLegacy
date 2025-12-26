<?php

include 'db_Producto.php';

if(isset($_POST['save']))
{
	//Pasamos los parametros por medio de post
	
$codigo = trim($MySQLiconn->real_escape_string($_POST['codigo']));
	$descripcion =trim($MySQLiconn->real_escape_string($_POST['descripcion']));   
	$predeterminados=0;
	$hologram=0;
	if(isset($_POST['holog']))
	{
		$hologram=1;
	}
	
		$tipo = $MySQLiconn->real_escape_string($_POST['tipocombo']);
		echo $tipo;
	
	$SQL;
	$result=$MySQLiconn->query("SELECT descripcion from producto where descripcion='".$descripcion."'");
	$rau = $result->fetch_array();
	if(empty($rau['descripcion'])){
		
		$result=$MySQLiconn->query("SELECT codigo from producto where codigo='$codigo'");
	$rau = $result->fetch_array();

		if(empty($rau['codigo']))
		{
			if(isset($_POST['pretederminados']))
	$predeterminados;
				if(isset($_POST['predeterminados'])){
		$predeterminados=1;
		$consulta =$MySQLiconn->query("SELECT subProceso,elemento,consumo from consumos where producto='0' and baja=1;");

		while ($rowC = $consulta->fetch_array()) {
			$consumofiltro=$MySQLiconn->query("INSERT INTO consumos(subProceso,elemento,consumo,producto) 
			VALUES('".$rowC['subProceso']."','".$rowC['elemento']."','".$rowC['consumo']."','$descripcion')");
		}	 
	}
	$cilindros=0;
	$cireles=0;
	$suaje=0;
	$refil=0;
	$fus=0;
	$MySQLiconn->query("SET @p0='".$tipo."'");
	$TI=$MySQLiconn->query("CALL getJuegoProcesosByType(@p0)");
	//echo "call getJuegoProcesosByType('".$tipo."')";
	while($rew=$TI->fetch_array())
	{
		
		if($rew['descripcionproceso']=="impresion")
		{
			$cilindros=1;
		}
		if($rew['descripcionproceso']=="impresion-flexografica")
		{
			$cireles=1;
			$suaje=1;
		}
		if($rew['descripcionproceso']=="suajado")
		{
			$suaje=1;
		}   
		if($rew['descripcionproceso']=="troquelado")
		{
			$suaje=1;
		}
		if($rew['descripcionproceso']=="refilado")
		{
			$refil=1;
		}
		if($rew['descripcionproceso']=="fusion")
		{
			$fus=1;
		}
	}
$MySQLiconn->next_result();
	//Realizamos la consulta
	 $SQL =$MySQLiconn->query("INSERT INTO producto(codigo,descripcion, tipo,consPre,cilindros,cireles,suaje,refil,fus,holograma) VALUES('$codigo','$descripcion','$tipo','$predeterminados','$cilindros','$cireles','$suaje','$refil','$fus','$hologram')");
	 
	 //En caso de ser diferente la consulta:
	 if(!$SQL)
	 {
	 	//Mandar el mensaje de error
		 echo $MySQLiconn->error;
	 } else{
	 //Mandamos un mensaje de exito:
	 echo"<script>alert('Se ha Agregado un nuevo producto')</script>";
		echo "<script>window.location= 'Producto_Disenio.php';</script>";
	 }
		}
		else
		{
			 echo "<div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Registro duplicado</strong> ,El producto que esta intentado ingresar ya esta registrado.
			</div>";
		echo "<script>window.location= 'Producto_Disenio.php';</script>";
		}

	
	}
	 else
	 {
	 echo "<div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Registro duplicado</strong> ,El producto que esta intentado ingresar ya esta registrado.
			</div>";
		echo "<script>window.location= 'Producto_Disenio.php';</script>";
	 }
 echo"<script>alert('Se ha registrado de Baja Exitosamente')</script>";
		echo "<script>window.location= 'Producto_Disenio.php';</script>";
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['del']))
{	//Lanzamos la consulta actualizando la baja a 0
	$SQL = $MySQLiconn->query("UPDATE producto	SET baja=0 WHERE ID=".$_GET['del']);
	$SQL = $MySQLiconn->query("UPDATE impresiones SET baja=0 WHERE descripcionDisenio=(SELECT descripcion from producto where ID=".$_GET['del'].")");
	
	if(!$SQL)
	{
	 echo "<div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Registro erróneo</strong> ,Error en la base de datos,inténtelo mas tarde.
			</div>";
	}
	else
	{
	  echo "<div class='alert alert-info alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Realizado correctamente</strong> ,Se ha dado de baja exitosamente
			</div>";
	}

	
}


/* Inicio Código Actualizar  */
if(isset($_GET['edit']))
{
	$SQL = $MySQLiconn->query("SELECT DISTINCT p.descripcion,p.ID,p.codigo,p.tipo,p.holograma,pr.disenio as prod FROM producto p left join produccion pr ON pr.disenio=p.descripcion WHERE p.ID='".$_GET['edit']."'");

	$getROW = $SQL->fetch_array();
}

if(isset($_POST['update']))
{
	$hologram=0;
	if(isset($_POST['holog']))
	{
		$hologram=1;
	}
	$SQL = $MySQLiconn->query("UPDATE producto SET codigo='".$_POST['codigo']."', descripcion='".$_POST['descripcion']."', tipo='".$_POST['tipocombo']."',holograma='".$hologram."' WHERE ID=".$_GET['edit']);
	if(!$SQL)
	{
		//echo "<script>window.location= 'Producto_Disenio.php';</script>";
		echo "<div class='alert alert-warning alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Registro erróneo</strong> ,Error en la base de datos,inténtelo mas tarde.
			</div>";
	}
	else
	{
		//echo "<script>window.location= 'Producto_Disenio.php';</script>";
		echo "<div class='alert alert-success alert-dismissible fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<strong>Operación exitosa</strong> ,Modificado exitosamente.
			</div>";
		
	}
	
	
	

	 
}

if(isset($_GET['imp']))
{
	$SQL=$MySQLiconn->query("UPDATE cache set dato='".$_GET['imp']."' where ID=1");
		echo "<script>window.location= 'Producto_Impresiones.php'</script>";

}

if(isset($_GET['acti']))
{
	$MySQLiconn->query("UPDATE producto set baja=1 where ID='".$_GET['acti']."'");
		echo "<script>window.location= 'Producto_Disenio.php';</script>";

}
if(isset($_GET['delfin']))
{
	$SQL=$MySQLiconn->query("DELETE from producto where ID='".$_GET['delfin']."'");
		echo"<script>alert('Eliminado Exitosamente')</script>";
		echo "<script>window.location= 'Producto_Disenio.php';</script>";
		if(!$SQL)
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Error</strong> , Hubo un problema eliminar
		</div>
		";
	}
	else
	{
		echo "<div class='alert alert-success alert-dismissible fade in'>
		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Operación exitosa</strong> , Eliminado correctamente
		</div>
		";
	}
}
/* Fin Código Atualizar */
if(isset($_POST['b']))
{
	$user = $_POST['b'];
       
      if(!empty($user)) {
            comprobar($user);
      }
       
      
}
function comprobar($b) {
           include 'db_Producto.php';
       
            $sql = $MySQLiconn->query("SELECT descripcion FROM producto WHERE descripcion = '".$b."'");
             
            $contar = $sql->num_rows;
             
            if($contar == 0){
                  echo "<span style='font-weight:bold;color:green;'>Disponible.</span>";
            }else{
                  echo "<span style='font-weight:bold;color:red;'>Ese nombre ya existe.</span>";
            }
      }   
?>