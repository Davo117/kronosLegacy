	<?php
	//session_start();
	include_once 'functions.php';
	include_once'db_produccion.php';
	@session_start();
	error_reporting(0);
	if(!empty($_GET['c']))
	{
			$MySQLiconn->query("
				UPDATE cache 
				set dato='".$_GET['c']."' 
				WHERE id=6");

	}
	if(isset($_POST['save2']))
	{
		//echo "<script language='JavaScript'>showLoad();</script>";
		$newCode=explode("|",$_GET['inputs']);
		$proceso=$newCode[2];
		$nameProcess=$newCode[7];
		$rest = $MySQLiconn->query("
			SELECT nombreparametro
			from juegoparametros 
			where identificadorJuego=(
									select packParametros 
									from procesos 
									where descripcionProceso='".$nameProcess."') and baja=1 and numParametro='G'");

		
		$contador=0;
		
		$divisiones=($newCode[0]);//Recibe las divisiones que tendra el lote
			$tipo=$newCode[6];
		$campo=$newCode[3];
		$ProsAnterior=$newCode[4];
		$noop=$newCode[5];
		$newCode=$newCode[1];
		$unidades=0;
		$banderas=0;
		$longTotal=0;
		$unidadesTotales=0;
		$lonTot=0;
		$counter=0;
		$contador=0;
		$banUnidades=0;
		$idtipo=$newCode[9];
		//$MySQLiconn->begin_transaction();
		//mysqli_autocommit($MySQLiconn,FALSE);
		$rex=array();
		$cont=0;
		while($rel = $rest->fetch_assoc())
		{
			$rex[$cont]['nombreparametro']=$rel['nombreparametro'];
			$cont++;
 		}
 		$giros=count($rex);
 		for($w=0;$w<$giros;$w++)
 		{
 			for($i=0;$i<$divisiones;$i++){


				$parametro=$rex[$w]['nombreparametro'].$i;
				$valor=$_POST[$parametro];
				$unidades=calcularUnidades($nameProcess,$noop,$tipo,$MySQLiconn);
				
   //$contador++;


				if($divisiones==1)
				{
					$Mod1=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set ".$rex[$w]['nombreparametro']."='$valor' where ".$campo."='$newCode'");

				     if($rex[$w]['nombreparametro']=="longitud")//Aqui se insertan los metros out del producto
					{
							$Mod2=$MySQLiconn->query("UPDATE tbmerma set longOut='".$valor."' where codigo='".$newCode."'");
					
						
					}

					else if($rex[$w]['nombreparametro']=="bandera")
					{
						$Mod3=$MySQLiconn->query("UPDATE tbmerma set banderas='".$valor."' where codigo='".$newCode."'");
					}
					else if($rex[$w]['nombreparametro']=="unidades")
					{
						$banUnidades=1;
						$Mod4=$MySQLiconn->query("UPDATE tbmerma set unidadesOut='".$valor."' where codigo='".$newCode."'");
					}
					
				}
				
				else
		{//Si existen divisiones en el proceso entonces no va a modificar el codigo padre,sino mas bien los codigos hijos

			$noAc=$i+1;
			$newop=$noop.'-'.$noAc;
			//$unidades=calcularUnidades($proceso,$newop);

			$Mod5=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set ".$rex[$w]['nombreparametro']."='$valor' where noop like '".$noop.'-'.$noAc."'");//Aqui se inserta el noop nuevo
			
				if($rex[$w]['nombreparametro']=="longitud")
			{

				if($valor!=${"anValue".$noAc})
			{
				
				${"anValue".$noAc}=$valor;
				$longTotal=$longTotal+$valor;

			}
				if($nameProcess=="refilado")//Si el proceso es refilado,la longitud de merma se calcula de una manera distinta,ya que el rollo se corta lateralmente,es decir,cada rollo sigue teniendo la misma longitud pero menos amplitud
				{
					$Mod6=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longTotal/$divisiones."' where codigo='".$newCode."'");
				}
				else if($nameProcess=="sliteo")//Si el proceso es sliteo,la longitud de merma se calcula de una manera distinta,ya que el rollo se corta lateralmente,es decir,cada rollo sigue teniendo la misma longitud pero menos amplitud
				{
					//En banda de seguridad "sliteo", funciona de la misma manera que en manga, pero en los demás productos, sliteo corta transversal y lateralmente, como en fusión, por lo tanto se hizo esta corrección el día 06/02/2020 para que mande la longitud correcta.
					if($idtipo==1)
					{
						$Mod7=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longTotal/$divisiones."' where codigo='".$newCode."'");
					}
					else
					{
						$Mod7=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longTotal."' where codigo='".$newCode."'");
					}
					
				}
				else
					{
						$Mod8=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longTotal."' where codigo='".$newCode."'");
					}
				
			}
			////Para los productos que en lugar de requerir longitud,se les inserte piezas
				if($rex[$w]['nombreparametro']=="unidades")
			{
				$banUnidades=1;
				if($valor!=${"anValue".$noAc})
			{
				
				${"anValue".$noAc}=$valor;
				$pzTotal=$pzTotal+$valor;

			}
				
						$Mod9=$MySQLiconn->query("UPDATE tbmerma set unidadesOut='".$pzTotal."' where codigo='".$newCode."'");
				
			}
			if($rex[$w]['nombreparametro']=="bandera")
			{
				if($valor!=${"bValue".$noAc})
			{
				
				${"bValue".$noAc}=$valor;
				$banderas=$banderas+$valor;

			}
				$Mod10=$MySQLiconn->query("UPDATE tbmerma set banderas='".$banderas."' where codigo='".$newCode."'");
			}
					
			

			
			
		}
		
		if($divisiones==1)
		{
			if($banUnidades==0)
			{
			$unidades=calcularUnidades($nameProcess,$noop,$tipo,$MySQLiconn);
			$Mod11=$Mod=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set unidades='$unidades' where ".$campo."='$newCode'");
			$Mod12=$MySQLiconn->query("UPDATE tbmerma set unidadesOut='".$unidades."' where codigo='".$newCode."'");
			}
			else//Si se marco la bandera de unidades,entonces en lugar de registrar piezas,registrará longitud
			{
			$longs=calcularLongitud($nameProcess,$noop,$tipo,$MySQLiconn);
			$Mod13=$Mod=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set longitud='$longs' where ".$campo."='$newCode'");
			$Mod14=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longs."' where codigo='".$newCode."'");
			}
		}
/**************************************************************************

LimitRequestLine 10000
*/
		else
		{//Si existen divisiones en el proceso entonces no va a modificar el codigo padre,sino mas bien los codigos hijos
			if($banUnidades==0)
			{
				$noAc=$i+1;
			$newop=$noop.'-'.$noAc;
			 $uni=calcularUnidades($nameProcess,$newop,$tipo,$MySQLiconn);

				if($uni!=${"unValue".$noAc})
				{

					${"unValue".$noAc}=$uni;
					$unidadesTotales=$unidadesTotales+$uni;

				}

			$Mod15=$Mod=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set unidades='$uni' where noop like '".$noop.'-'.$noAc."' and tipo='$tipo'");//Aqui se inserta el opp nuevo
			$Mod16=$MySQLiconn->query("UPDATE tbmerma set unidadesOut='".$unidadesTotales."' where codigo='".$newCode."'");
			}
			else
			{
				$noAc=$i+1;
			$newop=$noop.'-'.$noAc;
			 $lon=calcularLongitud($nameProcess,$newop,$tipo,$MySQLiconn);

				if($lon!=${"unValue".$noAc})
				{
					${"unValue".$noAc}=$lon;
					$lonTot=$lonTot+$lon;

				}

			$Mod17=$Mod=$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` set longitud='$lon' where noop like '".$noop.'-'.$noAc."' and tipo='$tipo'");//Aqui se inserta el opp nuevo
			$Mod18=$MySQLiconn->query("UPDATE tbmerma set longOut='".$lonTot."' where codigo='".$newCode."'");
			}
			
		}
/************************************************************************/

		
	}
 		}
 
if($tipo!="1")
{
	//Este código se encarga de verificar si las piezas resultantes están dentro de lo permitido, por el momento no se utiliza
	/*if($nameProcess=="revision" || $nameProcess=="revision 2" || $nameProcess=="corte" || $nameProcess=="sliteo" || $nameProcess=="foliado" || $nameProcess=="fusion")
	{
		$val=calcularTolerancia($newCode,$nameProcess,$noop,$divisiones,$MySQLiconn);
		$val=1;

	}
	else
	{
		$val=1;
	}*/
	if(!isset($_SESSION['logan']))
	{
		$val=calcularTolerancia($newCode,$nameProcess,$noop,$divisiones,$MySQLiconn);
	}
	else if($_SESSION['perfil']=='PF47' || $_SESSION['perfil']=='PF0')
	{
		$val=1;
	}
	else
	{
		$val=calcularTolerancia($newCode,$nameProcess,$noop,$divisiones,$MySQLiconn);
	}

	//$val=1;
}
else if($tipo=="1")
{
	$val=1;
}
 
	if($val==1)
{
 	if($ProsAnterior=="programado")//Si el proceso anterior es programado entonces  modifica el estatus del lote
	{
		$Mod19=$MySQLiconn->query("UPDATE tblotes set estado=5 where noop='$noop' and tipo='$tipo'");//Si no es programado modifica otros procesos
	}
	else
	{
		$Mod20=$MySQLiconn->query("UPDATE `tbpro".$ProsAnterior."` set total=0 where noop like '$noop' and tipo='$tipo'");
	}
	
}

$errores=0;
/*for($i=1;$i<=20;$i++)
{
	//echo  ${"Mod".$i};
	if(isset(${"Mod".$i}) and !${"Mod".$i})
	{
		$errores++;
	}
}*/
if($errores>0)
{
	//$MySQLiconn->ROLLBACK();
	echo"<script>alert('Algo salió mal durante la transacción,consulte al encargado de TI')</script>";
	echo("<script>window.location = '../Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
}
else if($val==1)
{
	//$MySQLiconn->COMMIT();
	//echo "<script language='JavaScript'>hideLoad();</script>";
	if($val==1)
{
	echo"<script>alert('Registrado correctamente')</script>";
$documento="etiq".$nameProcess."In";
$name_document = '../etiquetas/'.$documento.'.php';
$sendGet=$newCode;
if (file_exists($name_document)) {

	//echo "<script>Location.reload(true);</script>";
	//$cadena="<script>window.open('$name_document?etiquetasInd=$sendGet', '_blank')</script>";
	$label=register_cdg($name_document,$sendGet,$nameProcess,$MySQLiconn);
	
	echo $label;

} else {
	//$cadena="<script>window.open('../generarEtiquetasDinamicas.php?etiquetasInd=$sendGet&proceso=".$nameProcess."', '_blank');</script>";
	$label=register_cdg($name_document,$sendGet,$nameProcess,$MySQLiconn);
	
	echo $label;
}


echo("<script>window.location = '../Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
}
else
{
	echo"<script>alert('Error al registrar,cantidad de unidades incorrecta')</script>";
	echo("<script>window.location = '../Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
}

}
{
	//$MySQLiconn->ROLLBACK();
	echo"<script>alert('Tolerancia de metros excedida')</script>";
	echo("<script>window.location = '../Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
}




}
if(isset($_POST['save']))
{
	$map=$_SESSION['codigo'];
	$codigo=trim($_POST[$map]);
	
$proceso=$MySQLiconn->real_escape_string($_POST['comboProcesos']);
$nameProcess=$MySQLiconn->real_escape_string($_POST['comboProcesosName']);
	$tipo=$MySQLiconn->real_escape_string($_GET['tipo']);
	$tipo2=$tipo;

if(is_noop($codigo,$tipo,$MySQLiconn))
{
	$codigo=get_code($codigo,$tipo,$MySQLiconn);
	$_POST[$map]=$codigo;
}
if(is_numeric($codigo))
	{
	$Type=$MySQLiconn->query("SELECT tipo,(SELECT codigo from tbcodigosbarras where codigo='$codigo' and baja=1) as existeCodigo,(SELECT descripcionProceso from juegoprocesos where numeroProceso=(SELECT numeroProceso-1 from juegoprocesos where descripcionProceso='".$_POST['comboProcesosName']."' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id='$tipo')) and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id='$tipo') and baja=1 limit 1) as isType,(select (select descripcionProceso from procesos where id=t.proceso) as proceso from tbcodigosbarras t where codigo='$codigo') as procesCode,(SELECT $map from `tbpro".$_POST['comboProcesosName']."` where $map='$codigo' limit 1) as alreadyRegister from tbproduccion where tipo='$tipo2' and nombreProducto=(SELECT producto from tbcodigosbarras where codigo='$codigo') group by tipo");
	
	$rims=$Type->fetch_assoc();
	$empleado=$_POST['operador'];
	$ant=$rims['isType'];
	if($rims['isType']!=$rims['procesCode'])
				{				
					$codigo=convertCode($codigo,$ant,$tipo,$MySQLiconn);
					$Type=$MySQLiconn->query("SELECT tipo,(SELECT codigo from tbcodigosbarras where codigo='$codigo' and baja=1) as existeCodigo,(SELECT descripcionProceso from juegoprocesos where numeroProceso=(SELECT numeroProceso-1 from juegoprocesos where descripcionProceso='".$_POST['comboProcesosName']."' and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id='$tipo')) and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id='$tipo') and baja=1 limit 1) as isType,(select (select descripcionProceso from procesos where id=t.proceso) as proceso from tbcodigosbarras t where codigo='$codigo') as procesCode,(SELECT $map from `tbpro".$_POST['comboProcesosName']."` where $map='$codigo' limit 1) as alreadyRegister from tbproduccion where tipo='$tipo2' and nombreProducto=(SELECT producto from tbcodigosbarras where codigo='$codigo') group by tipo");
	$rims=$Type->fetch_assoc();
	$empleado=$_POST['operador'];
	$ant=$rims['isType'];
	$_POST[$map]=$codigo;
				}

	$MyEmp=$MySQLiconn->query("SELECT departamento,numemple,concat(nombre,' ',apellido) as nombre,ID from empleado where numemple='$empleado'");
	$emp=$MyEmp->fetch_assoc();
		//if($emp->num_rows>0)
	if(!empty($emp["departamento"]) && !empty($emp["departamento"]) && !empty($emp["departamento"]))
	{
		/*if($emp->departamento=="Producción" || substr(quitar_tildes($emp->departamento),0,4)==substr(ucwords($proceso),0,4))
		{*/

			if (!empty($rims['tipo']) && !empty($rims['existeCodigo']))
			{
				if($rims['isType']==$rims['procesCode'])//Verifica que coincida el codigo ingresado con el proceso que se quiere registrar
				{

	$sak=$MySQLiconn->query("SELECT producto,noop,divisiones from tbcodigosbarras where codigo='$codigo' and baja=1");//Busca el producto que corresponde al dato en el cual se ingresa el codigo de barras
	$rem=$sak->fetch_assoc();
	$producto=$rem['producto'];	
	$noop=$rem['noop'];
	$divisiones=$rem['divisiones'];
	$valiCil=0;
	$longitud=0;

					if(empty($rims['alreadyRegister']))//Valida si el codigo ya se registró
					{
						$proceso=$MySQLiconn->real_escape_string($_POST['comboProcesos']);//Se trae el proceso actual
						$tabla="`tbpro".$nameProcess."`";//Concatena el proceso para obtener el nombre de la tablar ala cual va a afectar

	

	//Por el momento se quitó la validación que comprobaba si el código de disco coincidia con el producto, se quitó para hacer más práctico el proceso.
	$isBS=1;
	/*if($proceso=="fusion")
	{
		$disco=$MySQLiconn->real_escape_string($_POST['disco']);
		$arrayDsc=parsearCodigo($disco);
		$arrayLot=parsearCodigo($codigo);
				$proscDsc=explode('|',$arrayDsc);
				$codeg=explode('|',$arrayLot);
				echo $disco;
				if($proscDsc[1]=="sliteo")
				{
					$stamp=$MySQLiconn->query("SELECT nombreBanda FROM impresiones WHERE id='".$codeg[8]."'");
					$stum=$stamp->fetch_assoc();
					$bandaProducto=$stum['nombreBanda'];
					$bans=$MySQLiconn->query("SELECT identificadorBS as ms FROM bandaspp WHERE IdBSPP='".$proscDsc[8]."'");
					$stom=$bans->fetch_assoc();
					$bandaDisco=$stom['ms'];
					if($bandaProducto==$bandaDisco)
					{
						$isBS=1;
					}
					/*$COL=$MySQLiconn->query("SELECT longitud FROM `pro".$codeg[1]."` WHERE noop='".$codeg[4]."' and tipo='".$codeg[6]."'");
					$lsp=$COL->fetch_assoc();
					$longitud=$lsp['longitud'];
					$Con=$MySQLiconn->query("SELECT total,longitud from prosliteo where noop='".$proscDsc[4]."' and tipo='BS'");
					$roa=$Con->fetch_assoc();
					$longdisco=$roa['longitud'];*/
					//if($longdisco>=$longitud)//Si el estatus total es 1,o sea,activa y la longitud es mayor o igual a la de la bobina que se quiere registrar,entonces se permite
					//{
					//	$isBS=1;
				//	}
/*					else
					{
						/*while($longdisco<$longitud)
						{
							echo "<script>window.open('addDisc.php','Agregar disco','width=300',height=500, top=100,left=100');</script>";
						}*/

/*						$isBS=0;
					}
					
				}
				else
				{
					$isBS=0;
				}
				
			
	}
	else
	{
	 $isBS=1;
	 }//Validacion para el juego de cireles,si es cero es que no hay juego de cilindros o si es correcto
	*/
	if(!empty($_POST['juegoCireles']))
	{
		$cil=$_POST['juegoCireles'];
		$eg=$MySQLiconn->query("SELECT producto from juegoscireles where id='$cil' and baja=1");
		$ag=$eg->fetch_assoc();
		if($ag['producto']==$producto)
		{
			$valiCir=1;
		}
		else
{
	echo"<script>alert('El juego de cireles no coincide con este producto')</script>";
	echo("<script>window.location ='Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
		
	}
	else
	{
		$valiCir=1;
	}
//////////////////////////////////////////// Validación de que el juego de cilindros sea el correcto

	if(!empty($_POST['juegoCilindros']))
	{
		$cil=$_POST['juegoCilindros'];
		$eg=$MySQLiconn->query("SELECT descripcionImpresion from juegoscilindros where IDCilindro='$cil' and baja=1");
		$ag=$eg->fetch_assoc();
		if($ag['descripcionImpresion']==$producto)
		{
			$valiCil=1;
		}
		else
{
	echo"<script>alert('El juego de cilindros no coincide con este producto')</script>";
	echo("<script>window.location ='Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
		
	}
	else
	{
		$valiCil=1;
	}
/////////////////////////////////Validación para verificar que el suaje sea el correcto//////////
if(!empty($_POST['suaje']))
	{
		$suj=$_POST['suaje'];
		$eg=$MySQLiconn->query("SELECT descripcionImpresion from suaje where identificadorSuaje='$suj' and baja=1");
		$ag=$eg->fetch_assoc();
		if($ag['descripcionImpresion']==$producto)
		{
			$valiSuj=1;
		}
		else
{
	echo"<script>alert('El número de suaje no coincide con este producto')</script>";
	echo("<script>window.location ='Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
		
	}
	else
	{
		$valiSuj=1;
	}
	///////////////////////////
	if($valiCil==1)
	{
		if($valiCir==1)
		{

			$MySQLiconn->begin_transaction();
			mysqli_autocommit($MySQLiconn,FALSE);

if($isBS==1)
{
// el ID 2 es refilado
	if($divisiones>1 or $proceso=="2" or $proceso=="3")
	{
		$Sic =$MySQLiconn->query("INSERT INTO ".$tabla."(producto,tipo) VALUES('$producto','$tipo2')");//Hace un insert,creando un nuevo registro
	}
	else
	{
		$Sic =$MySQLiconn->query("INSERT INTO ".$tabla."(producto,tipo,rollo_padre) VALUES('$producto','$tipo2',0)");//Hace un insert,creando un nuevo registro
	}
	

	$SQL = $MySQLiconn->query("SELECT * from juegoparametros where identificadorJuego=(select packParametros from procesos where descripcionProceso='".$nameProcess."') and baja=1 and numParametro!='G'");//Se trae los parametros que tiene que insertar

	$operador="";
	$maquina="";
	$disco="";
	while($ram = $SQL->fetch_assoc()){
		$map=$ram['nombreparametro'];
		${$ram['nombreparametro']}=$MySQLiconn->real_escape_string($_POST[$map]);
		if($_SESSION['codigo']==$map)
		{
			$comparador=${$ram['nombreparametro']};
		}
		if($map=="operador")
		{
			//$operador=$emp->nombre.'|'.$emp->numemple;
			$seil =$MySQLiconn->query("UPDATE ".$tabla." set ".$map."='".$emp["ID"]."'  where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");
		}
		else if($map=="maquina")
		{
			$maquina=${$ram['nombreparametro']};
			$seil1 =$MySQLiconn->query("UPDATE ".$tabla." set ".$map."='".$maquina."'  where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");
		}
		else if($map=="disco")
		{
			$disco=${$ram['nombreparametro']};
				$seil2 =$MySQLiconn->query("UPDATE ".$tabla." set ".$map."='".$disco."'  where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");
		}

		else
		{
			$seil3 =$MySQLiconn->query("UPDATE ".$tabla." set ".$map."='".${$ram['nombreparametro']}."'  where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");
		}
		
		$seil4 =$MySQLiconn->query("UPDATE ".$tabla." set noop='$noop'  where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");
}
}
else
{
	echo"<script>alert('Error en el registro del disco longitud o disco incorrecto')</script>";
	//echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;

}
		
}
	}
	$validacion=$MySQLiconn->query("SELECT nombreparametro from juegoparametros WHERE identificadorJuego=(SELECT packparametros from procesos where descripcionProceso='".$nameProcess."')and numParametro='G'");
	$validar=0;
	$validarFormulario=$validacion->num_rows;
	if($validarFormulario==0)
	{
		$validar=1;
	}
	if($divisiones==0)//Si no hay divisiones o parametros por ajustar directamente las unidades son las mismas del proceso anterior
	{
		
		$unidades=calcularUnidades($nameProcess,$noop,$tipo2,$MySQLiconn);
		$soil=$MySQLiconn->query("UPDATE ".$tabla." set unidades='$unidades' where noop='$noop'");

		$moil=$MySQLiconn->query("UPDATE tbmerma set longOut='".$longTotal."',unidadesOut='$unidades' where codigo='".$newCode."'");
		$SIM=$MySQLiconn->query("UPDATE `tbpro".$ant."` set total=0 where noop like '$noop' and tipo='$tipo2'");
		

	}
	else if($validar==1)
	{

		$unidades=calcularUnidades($nameProcess,$noop,$tipo2,$MySQLiconn);
		$soil=$MySQLiconn->query("UPDATE ".$tabla." set unidades='$unidades' where noop='$noop' and tipo='$tipo2'");


		$SIM=$MySQLiconn->query("UPDATE `tbpro".$ant."` set total=0 where noop like '$noop' and tipo='$tipo2'");
	}


	

$sak=$MySQLiconn->query("SELECT producto from tbcodigosbarras where codigo='".$comparador."' and baja=1");//Busca el producto que corresponde al dato en el cual se ingresa el codigo de barras
$rem=$sak->fetch_assoc();
$producto=$rem['producto'];	
$Sir =$MySQLiconn->query("UPDATE ".$tabla." set producto='$producto' where id=(select id from (select ".$tabla.".id from ".$tabla." order by ".$tabla.".id desc limit 1) as consulta)");//Actualiza el registro que se guardo,colocando el producto
//echo $divisiones;
if($divisiones>1 or $proceso==2 or $proceso==3)
{//Si divisiones es mayor a 1,quiere decie que ese codigo se va a dividir, actualización 29/07/2019, se agregó $proceso==2 para que también agregue divisiones cuando solo es una repetición

	for($j=1;$j<=$divisiones;$j++){
		if($nameProcess=="fusion")
		{
			$map=$_SESSION['codigo'];
			$Sit =$MySQLiconn->query("INSERT INTO ".$tabla."(producto,noop,operador,maquina,disco,$map,tipo,rollo_padre) VALUES('$producto','".$noop.'-'.$j."','".$emp["ID"]."','".$maquina."','".$disco."','".$codigo."','$tipo2',0)");
			$arrayDsc=parsearCodigo($disco,$MySQLiconn);
			if(!empty($arrayDsc))//Con este codigo se actualiza el disco segun los metros que le quedaron
			{
				$proscDsc=explode('|',$arrayDsc);
				if($proscDsc[1]=="sliteo")
				{
					/*$pit=$MySQLiconn->query("UPDATE prosliteo set longitud=longitud-'".$longitud."', peso=peso-((".$longitud."/longitud)*peso) where noop='".$proscDsc[4]."' and tipo='".$proscDsc[6]."'");
					$EM=$MySQLiconn->query("SELECT longitud FROM prosliteo where noop='".$proscDsc[4]."' and tipo='".$proscDsc[6]."'");
					$cdgd=$EM->fetch_assoc();
					$longitud=0;
					if($cdgd['longitud']<=0)
					{*/
						$rit=$MySQLiconn->query("UPDATE tbprosliteo set total=0 where noop='".$proscDsc[4]."' and tipo='".$proscDsc[6]."'");
					//}
					
				}
				
			}
			
		}
		else
		{
			$bric =$MySQLiconn->query("INSERT INTO ".$tabla."(producto,noop,operador,maquina,".$map.",tipo,rollo_padre) VALUES('$producto','".$noop.'-'.$j."','".$emp["ID"]."','$maquina','".$codigo."','$tipo2',0)");
				
		}
	}
	
}
	/*else
	{
		echo"<script>alert('Algunos datos no fueron ingresados,no se puede completar la operación')</script>";
		echo("<script>window.location = 'Produccion_Registro.php';</script>");
	}*/
	





if(isset($Sic) and !$Sic or isset($seil) and !$seil or isset($soil) and !$soil or  isset($moil) and !$moil or isset($SIM) and !$SIM or isset($Sir) and !$Sir or  isset($Sit) and !$Sit or isset($pit) and !$pit or isset($mit) and !$mit or  isset($bric) and !$bric or isset($seil1) and !$seil1 or isset($sei2) and !$seil2 or isset($seil3) and !$seil3 or isset($seil4) and !$seil4)
{
	$MySQLiconn->ROLLBACK();
	echo"<script>alert('Algo salio mal,contacte con el encargado de TI')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
} else{
	 //Mandamos un mensaje de exito:
	$MySQLiconn->COMMIT();
	mysqli_autocommit($MySQLiconn,TRUE);
	//echo"<script>alert('Registrado correctamente')</script>";
}
}

	else//Else de codigo duplicado
	{
		echo"<script>alert('Este código ya fue registrado')</script>";
		if($nameProcess!="corte")
		{
			$BAU=$MySQLiconn->query("SELECT total,sum(unidades) as suma FROM `tbpro".$nameProcess."` WHERE $map='$codigo' ORDER BY id ASC ");
		}
		else if($nameProcess=="corte")
		{
			$BAU=$MySQLiconn->query("SELECT total,sum(unidades) as suma FROM `tbpro".$nameProcess."` WHERE noop like '".$noop."-%' and rollo_padre=0 and tipo='$tipo' ORDER BY id ASC ");
		}
		
		$ruk=$BAU->fetch_assoc();
		if($ruk['total']==1 and $ruk['suma']==0)
		{
				//$operador=$emp->nombre.'|'.$emp->numemple;
				$MySQLiconn->query("UPDATE `tbpro".$nameProcess."` SET operador='".$emp["ID"]."',maquina='".$_POST['maquina']."',fecha=NOW() WHERE $map='$codigo'");
		}
		else if(!isset($_SESSION['logan']))
		{ 	
			echo"<script>alert('Código capturado, no se puede modificar')</script>";
			echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");

			exit;
		}
	}
}
else
{
	echo"<script>alert('El código no coincide con este proceso')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
}
else
{
	echo"<script>alert('El código no coincide con este tipo de producto o ya no esta disponible')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
/*}
else
{
	echo"<script>alert('La información del empleado es incorrecta')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}*/

}
else
{
	echo"<script>alert('Código de empleado incorrecto o no existente')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
}
else
{
	echo"<script>alert('El código de barras ingresado no es válido')</script>";
	echo("<script>window.location = 'Produccion_Registro.php?tipo=$tipo&proceso=$proceso';</script>");
	exit;
}
}

///////////////////Fin del codigo de guardar
if(isset($_GET['edit']))
{

	$proceso='impresion';
	$map=$_GET['edit'];
	$SQL = $MySQLiconn->query("SELECT * FROM `tbpro".$proceso."` WHERE ".$_GET['edit']."='".$_POST[$map]."'");
	$getROW = $SQL->fetch_assoc();

}
if(isset($_POST['codes']))
{

	$proceso=$_GET['inputs'];
	$sendGet=$_SESSION['etiquetasInd'];
	$proceso=explode("|", $proceso,3);
	$producto=$proceso[1];
	$proceso=$proceso[0];
	$documento="etiq".$proceso;
	$name_document = '../etiquetas/'.$documento.'In.php';

	if (file_exists($name_document)) {
		$label=register_cdg($name_document,$sendGet,$proceso,$MySQLiconn);
		echo $label;
	} else {
		echo "<script>window.location='../generarEtiquetasDinamicas.php?etiquetasInd=$sendGet&proceso=".$proceso."';</script>";
	}
}

?>
<script language="JavaScript">
	function redireccionar() {

	
		setTimeout('history.back()',10000);
	}
</script>

