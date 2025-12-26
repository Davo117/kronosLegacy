<?php
@session_start();

include 'db_Producto.php';
include  'functionsEmpaque.php';
//error_reporting(0);

$arrTemporal=array();


/* Inicio Código buscar */
//Si se dio clic en guardar:

if(!empty($_GET['comboEmpaque']))
{
	$q=$_GET['comboEmpaque'];
	//$MySQLiconn->query("UPDATE cache SET dato='$q' WHERE id=8");
	if($q!=$_SESSION['empaqueActual'])
	{
		$_SESSION['array']=array();
		$_SESSION['producto']="";
	$_SESSION['estatus']="";
	}
	
}
if(isset($_GET['del']))
{
		$_SESSION['array']=array();
		$_SESSION['estatus']="";
		$_SESSION['producto']="";
		//echo "<script>window.open('../Empaque_Ajuste.php)</script>";
		echo("<script>window.location = '../Empaque_Ensamble.php';</script>");
}

if(isset($_POST['find']))
{
	//Pasamos los parametros por medio de post
	//$_SESSION['empaqueActual'] = $MySQLiconn->real_escape_string($_POST['comboEmpaque']);

}
if(isset($_GET['out']))
{
	//array_push($arrTemporal,$_SESSION['array'][$i]);
	//$_SESSION['array'] = array_diff($_SESSION['array'], array($_GET['out']));

	foreach (array_keys($_SESSION['array'], $_GET['out']) as $key) 
    {
        unset($_SESSION['array'][$key]);
        sort($_SESSION['array']);
    }
}
/* Fin Código Insertar */
//////////////////////////////////////////////////////////////////////////////////////////////

/* Inicio Código Eliminación Logíca */
//Si se dio clic en Eliminar de forma logica:
if(isset($_GET['envio']))//Se registran los paquetes agregados en la base de datos
{
	$empaque=$_GET['envio'];
	$referencia="";
	$producto="";
	$noElementos="";
	$piezas="";
	$longitudTotal=0;
	$piezasTotales=0;
	$referencia="";
	$idProceso="";
	$idProducto="";
	$idEmpaque="";
	$tipo="";
	$fallas=0;
	$longitud=0;
	$codigoBarras="";
	$MySQLiconn->begin_transaction();
	mysqli_autocommit($MySQLiconn,FALSE);
	if(count($_SESSION['array'])>0)// Verifico que el arreglo no esté vacío
	{
		$cod=$_SESSION['array'][0];
		$elementos=count($_SESSION['array']);
		$arrayDatos=parsearCodigo($cod,$MySQLiconn);
		$arrayDatos=explode('|',$arrayDatos);
		$producto=$arrayDatos[0];
		$idProducto=$arrayDatos[8];
$Pa=$MySQLiconn->query("SELECT descripcionProceso from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id=(SELECT tipo from producto where ID=(SELECT descripcionDisenio from impresiones where id='$idProducto'))) and baja=1 order by numeroProceso desc limit 1");
	$Pe=$Pa->fetch_array();
	$ultimoProceso=$Pe['descripcionProceso'];
	//$referencial=$Pe['referencial'];
		if($empaque=="caja")
		{
			$Mod1=$MySQLiconn->query("INSERT into caja(producto)
		values('$idProducto')");
			//$ultimo_id =$MySQLiconn->insert_id;
		}
		else if($empaque=="rollo")
		{
			$Mod2=$MySQLiconn->query("INSERT into rollo(producto)
		values('$idProducto')");
		}

	for($i=0;$i<count($_SESSION['array']);$i++)
	{
		$cod=$_SESSION['array'][$i];
		$arrayDatos=parsearCodigo($cod,$MySQLiconn);
		$arrayDatos=explode('|',$arrayDatos);
		$noop=$arrayDatos[4];
		$nuevoProducto=$arrayDatos[0];
		$proceso=$arrayDatos[1];
		$tipo=$arrayDatos[6];
		$idtipo=$arrayDatos[9];
		$row="";
		if($producto==$nuevoProducto)
		{
			if($empaque=="rollo")
		{
	$SQL=$MySQLiconn->query("SELECT MAX(id) as id,count(id) as numero,(select MAX(refEnsamble)+1 from ensambleempaques where producto='".$idProducto."' and tipoEmpaque='rollo') as refEnsamble,(SELECT id from juegoprocesos where descripcionProceso='rollo' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id=(SELECT tipo from producto where ID=(SELECT descripcionDisenio from impresiones where id='$idProducto'))) and baja=1) as proceso,(SELECT millaresPorPaquete from impresiones where id='$idProducto') as piezas,(SELECT unidades from `tbpro".$proceso."` where noop='$noop' and `tbpro".$proceso."`.tipo='".$idtipo."') as unidades,(SELECT longitud from `tbpro".$proceso."` where noop='$noop' and `tbpro".$proceso."`.tipo='".$idtipo."') as longitud from rollo where producto='".$idProducto."'");

	$row=$SQL->fetch_array();
	$idProceso=$row['proceso'];
	$idEmpaque=$row['id'];
	$unidades=$row['unidades'];
	$longitud=$row['longitud'];
	$referencia=$row['numero'];
	$refEnsamble=$row['refEnsamble'];
	$comprobar=1;
				while($comprobar==1)
				{
					$refnew='Q'.$referencia;
				$sel=$MySQLiconn->query("SELECT referencia from rollo where referencia='".$refnew."' and producto='".$idProducto."'");
				$rec=$sel->fetch_assoc();
				if(empty($rec['referencia']))
				{
					$comprobar=0;
					//$referencia=$refnew;
				}
				else
				{
					$referencia++;

				}
				}
		}
		else if($empaque=="caja")
		{
				$SQL=$MySQLiconn->query("SELECT MAX(id) as id,count(id) as numero,(select MAX(refEnsamble)+1 from ensambleempaques where producto='".$idProducto."' and tipoEmpaque='caja') as refEnsamble,(SELECT id from juegoprocesos where descripcionProceso='$empaque' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id=(SELECT tipo from producto where ID=(SELECT descripcionDisenio from impresiones where id='$idProducto'))) and baja=1) as proceso,(SELECT millaresPorPaquete from impresiones where id='".$idProducto."') as piezas from caja where producto='".$idProducto."'");
				$row=$SQL->fetch_array();
				$idProceso=$row['proceso'];
				$idEmpaque=$row['id'];
				$piezas=$row['piezas']*1000;
				$referencia=$row['numero'];
				$refEnsamble=$row['refEnsamble'];
				//$idRef=$referencia;
				//$idRef=$idRef[0];
				$comprobar=1;
				while($comprobar==1)
				{
					$refnew='C'.$referencia;
				$sel=$MySQLiconn->query("SELECT referencia from caja where referencia='".$refnew."' and producto='".$idProducto."'");
				$rec=$sel->fetch_assoc();
				if(empty($rec['referencia']))
				{
					$comprobar=0;
					//$referencia=$refnew;
				}
				else
				{
					$referencia++;

				}
				}

		}
		
		$codigoBarras=str_repeat("0",3 - strlen($idProducto)).$idProducto;
      	$codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
      	$codigoBarras=$codigoBarras.str_repeat("0",4 - strlen($referencia)).$referencia;
 	  	$divisiones = 0;
		if($empaque=="caja")
		{

			$comprob=1;
			$refi=$referencia;
			while ($comprob==1) 
			{
				$rol=$MySQLiconn->query("SELECT id FROM caja where codigo='".$codigoBarras."'");
				$rom=$rol->fetch_array();
				if(empty($rom['id']))
				{
					$comprob=0;
				}
				else
				{
					$codigoBarras="";
					$refi++;
					$codigoBarras=str_repeat("0",3 - strlen($idProducto)).$idProducto;
      				$codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
      				$codigoBarras=$codigoBarras.str_repeat("0",4 - strlen($refi)).$refi;
 	  				
				}
			}
			$piezasTotales=($elementos*$piezas)/1000;
			$Mod3=$MySQLiconn->query("INSERT into ensambleempaques(referencia,codEmpaque,producto,piezas,codigo,tipoEmpaque,refEnsamble)
		values('C$referencia','$codigoBarras','$idProducto','$piezas','$cod','$empaque','$refEnsamble')");

			$Mod4=$MySQLiconn->query("UPDATE `tbprocorte` set total=0 where rollo='".$cod."'");
			if ($MySQLiconn->query($Mod4) === TRUE) {
				//$last_id = $MySQLiconn->insert_id;
				//echo "Nuevo registro insertado. El ID es: " . $last_id;
				$Mod5=$MySQLiconn->query("UPDATE tbcodigosbarras set baja=0 where codigo='$cod'");
			}
			
		}
		else if($empaque=="rollo")
		{
			$longitudTotal=$longitud+$longitudTotal;
			$piezasTotales=$piezasTotales+$unidades;
			$comprob=1;
			$refi=$referencia;
			while ($comprob==1) 
			{
				$rol=$MySQLiconn->query("SELECT id FROM rollo where codigo='".$codigoBarras."'");
				$rom=$rol->fetch_array();
				if(empty($rom['id']))
				{
					$comprob=0;
				}
				else
				{
					$codigoBarras="";
					$refi++;
					$codigoBarras=str_repeat("0",3 - strlen($idProducto)).$idProducto;
      				$codigoBarras=$codigoBarras.str_repeat("0",3 - strlen($idProceso)).$idProceso;
      				$codigoBarras=$codigoBarras.str_repeat("0",4 - strlen($refi)).$refi;
 	  				
				}
			}
			$Mod6=$MySQLiconn->query("INSERT into ensambleempaques(referencia,codEmpaque,producto,longitud,piezas,codigo,tipoEmpaque,refEnsamble)
		values('Q$referencia','$codigoBarras','$idProducto','".$longitud."','$unidades','$cod','$empaque','$refEnsamble')");
			
			$Mod7="UPDATE `tbpro$proceso` set total=0 where noop='$noop' and tipo='".$idtipo."'";
			if ($MySQLiconn->query($Mod7) === TRUE) {
				$Mod8=$MySQLiconn->query("UPDATE tbcodigosbarras set baja=0 where codigo='$cod'");
			}
			
		}

		}
		else
		{
			$fallas++;
		}
	}
	if($fallas<1)
	{
	  

	if($empaque=="caja")
		{
			$Mod9=$MySQLiconn->query("UPDATE caja set referencia='C$referencia',producto='$idProducto',noElementos='$elementos',piezas='$piezasTotales',codigo='$codigoBarras'  where id=(select id from (select caja.id from caja order by caja.id desc limit 1) as consulta)");
			$_SESSION['etiquetasInd']=$codigoBarras;
			$_SESSION['estatus']='*'.$empaque." '"."C".$referencia."' Agregado corrrectamente" ;

		}
		else if($empaque=="rollo")
		{
			$piezasTotales=number_format($piezasTotales,3);
			$Mod10=$MySQLiconn->query("UPDATE rollo set referencia='Q$referencia',producto='$idProducto',noElementos='$elementos',piezas='$piezasTotales',longitud='$longitudTotal',codigo='$codigoBarras' where id=(select id from (select rollo.id from rollo order by rollo.id desc limit 1) as consulta)");//antes era Q por queso,pero ahora se maneja por rollo 'R'
			$_SESSION['etiquetasInd']=$codigoBarras;
			$_SESSION['estatus']='*'.$empaque." '"."Q".$referencia."' Agregado corrrectamente" ;
		}
		//echo $codigoBarras."**";
		 registrarCodigoEmpaque($codigoBarras,$empaque,$MySQLiconn);
		$_SESSION['array']=array();
		
		echo"<script>alert('Registrado correctamente')</script>";
$documento="etiq".$empaque."In";
$name_document = '../../Produccion/etiquetas/'.$documento.'.php';


if (file_exists($name_document)) {
	//echo "<script>Location.reload(true);</script>";
	  if(isset($Mod1) and !$Mod1 || isset($Mod2) and !$Mod2 || isset($Mod3) and !$Mod3 || isset($Mod4) and !$Mod4 || isset($Mod5) and !$Mod5 || isset($Mod6) and !$Mod6 || isset($Mod7) and !$Mod7 || isset($Mod8) and !$Mod8 || isset($Mod9) and !$Mod9 || isset($Mod10) and !$Mod10)
{
  $MySQLiconn->ROLLBACK();
  echo"<script>alert('Algo salió mal durante la transacción,consulte a TI')</script>";
}
else
{
  $MySQLiconn->COMMIT();
}
	$_SESSION['banderin']="";
	echo "<script>window.open('../pdf/empaques_exterior.php?etiquetasInd=$codigoBarras&empaque=$empaque', '_blank')</script>";
} else {
	echo "<script>window.open('../../Produccion/generarEtiquetasDinamicas.php', '_blank');</script>";
}


echo("<script>window.location = '../Empaque_Ensamble.php';</script>");

	}
	else
	{
		$_SESSION['estatus']=$fallas." "."Registros no coincidieron con el empaque,operación no exitosa";
		$MySQLiconn->query("DELETE from $empaque where id='$idEmpaque'");
		$_SESSION['array']=array();
		echo("<script>window.location = '../Empaque_Ensamble.php';</script>");
	}

	}
	else
	{
		echo("<script>alert('¡El empaque no puede estar vacío!')';</script>");
		echo("<script>window.location = '../Empaque_Ensamble.php';</script>");
	}
	
}

if(!empty($_GET['e']))
{
	$e=$_GET['e'];
	$_SESSION['cod']="";
	$empaque=$_SESSION['empaqueActual'];
	$_SESSION['estatus']=$empaque;
	$e=get_last_code($e,$MySQLiconn);
	$a=parsearCodigo($e,$MySQLiconn);
	if($a!="")
	{

		$producto=explode('|',$a);
$array=$a;
		$proceso=$producto[1];
		$noProces=$producto[3];
		$producto=$producto[0];
		$idProducto=$producto[8];
		

		$already=0;
		$valid=0;
		$SQL=$MySQLiconn->query("SELECT nombreParametro,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
                    id=(select tipo from producto where 
                          ID=(select descripcionDisenio from impresiones where id='".$idProducto."'))) and numeroProceso='".$noProces."'+1 and baja=1) as procesoSiguiente from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where id=(select tipo from producto where 
                          ID=(select descripcionDisenio from impresiones where id='".$idProducto."'))) and numeroProceso='".$noProces."'+1 and baja=1))");


		$He=$SQL->fetch_array();
		$referencial=$He['nombreParametro'];
		$sig=$He['procesoSiguiente'];
		if(!empty($sig))//si no hay proceso siguiente,significa que se tiene que evaluar que no haya sido registrado ese codigo ya.

		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from ensambleempaques where codigo='$e') as existente,(SELECT producto from `tbpro".$sig."` where $referencial='$e' limit 1) as disponible from tbcodigosbarras where codigo='$e' and baja=1");
		}
		else
		{
			$SQL=$MySQLiconn->query("SELECT codigo,(select codigo from ensambleempaques where codigo='$e') as existente from tbcodigosbarras where codigo='$e' and baja=1");
		}
		$row=$SQL->fetch_array();
		if(empty($row['disponible']))
		{
			if(empty($row['existente']))
		{
			
				if(!empty($row['codigo']))

				{
					if($proceso=="corte" && $empaque=="caja")
					{
						$valid=1;
					}
					else if($proceso!="corte" && $empaque=="rollo")
					{
						$valid=1;
					}
				
						for($i=0;$i<count($_SESSION['array']);$i++)
					{
			//echo '<p>'.$_SESSION['array'][$i].'</p><br>';

						if($_SESSION['array'][$i]==$e)
						{
							$already=1;
						}
							array_push($arrTemporal,$_SESSION['array'][$i]);
						
					}
					if($already==0)
					{
							if($valid==1)
					{
						$esc=$MySQLiconn->query("SELECT noProceso,tipo FROM tbcodigosbarras WHERE codigo='".$e."'");
						$pro=$esc->fetch_array();
						$proceso=$pro['noProceso'];
						$tipo=$pro['tipo'];
						if($proceso>0)
						{
							if($tipo!="1")
							{
								if(empty($_SESSION['array']))
								{
									$_SESSION['producto']=$producto;
								}
								if($_SESSION['producto']==$producto)
								{
									array_push($arrTemporal,$e);
									$_SESSION['estatus']="";
								}
								else
						{
							$_SESSION['estatus']="No se pueden empacar dos productos distintos";
						}
							
								
							}
							else if($tipo==1)
							{
								$_SESSION['estatus']="La banda de seguridad no se puede empacar"."SELECT noProceso,tipo FROM tbcodigosbarras WHERE codigo='".$e."'";
							}
							
						}
						else
						{
							$_SESSION['estatus']="Código inválido";
						}
						
					}
					else
					{
						$_SESSION['estatus']="Tipo de empaque incorrecto";
					}
					
					}
					else
					{
						$_SESSION['estatus']="El código '".$e ."' ya esta agregado";
					}
					
					$_SESSION['array']=$arrTemporal;
					
				}
				else
				{
					$_SESSION['estatus']="El código '".$e ."' no puede empacarse en este momento";
				}
			
		}
		else
		{
			$_SESSION['estatus']="El código '".$e ."' ya fue empacado";
		}
	}
else
{
	$_SESSION['estatus']="El código '".$e ."' ya no puede ser empacado porque cambió de proceso";
		
}
		
	
}
		
	else
	{
		$_SESSION['estatus']="El código '".$e ."' no esta disponible";
	}
	

	
	

}


/* Inicio Código Atualizar  */

/* Fin Código Atualizar */
