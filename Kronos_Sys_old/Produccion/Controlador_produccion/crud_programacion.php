	<?php
	@session_start();
	include_once 'db_produccion.php';
	if(!empty($_GET['q'])){

		/*

		*/

		$MySQLiconn->query("
			UPDATE lotestemporales 
			set baja=0 
			where noLote='".$_GET['q']."'");
		$result=$MySQLiconn->query("
			SELECT noLote,longitud,unidades,anchura,referencia,peso,(SELECT dato from cache where id=9) as tipo
			from lotestemporales
			where baja=0");
			?>
			<p style="text-align:justify;width:200px;margin-right:310px;">Lotes disponibles  (<?php echo $result->num_rows;?>)</p><br>
			<?php
			while($row=$result->fetch_array())
			{
				
				?>
				<div class='lotesDisponibles'>
					<p class='irrelevance'><input type='checkbox' onclick="cargarLote(this.value),cargarLote2(this.value)" value="<?php echo $row['noLote'] ?>"'><?php
					if($row['tipo']!="BS")
					{
						print("<br>No. de lote:&nbsp; <b class='bloqs'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'> ".number_format($row['unidades'],3)." &nbsp;</b>Millares aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
						print("</div><br>");
					}
					else
					{
						print("<br>No. de lote:&nbsp; <b class='bloqs'>".$row['noLote']."&nbsp; &nbsp; </b>Longitud:<b class='bloqs'>".$row['longitud']."</b><br><b class='bloqs'> ".number_format($row['unidades'],3)." &nbsp;</b>Metros aprox. &nbsp; Anchura: &nbsp;<b class='bloqs'>".$row['anchura']."</b><br> Referencia: &nbsp; <b class='bloqs'>".$row['referencia']."</b> &nbsp; Peso: &nbsp;<b class='bloqs'>".$row['peso']."</b> </input></p>");
						print("</div><br>");
					}
					

				}
			}
			?>
		</div>
		<?php

		if(isset($_POST['save']))
		{
			$tipo=$_GET['tipo'];
			$_SESSION['tipoProg']=$tipo;
			if($_SESSION['rol']=='Producción'){

		//Pasamos los parametros por medio de post

				$producto= $MySQLiconn->real_escape_string($_POST['comboproductos']);
				if($tipo!="BS")//Si es termoencogible declara juego de cilindros
				{
					if(!empty($_POST['comboCilindros']))
					{
						$juegoCilindros =$MySQLiconn->real_escape_string($_POST['comboCilindros']); 
						$consJuego=$MySQLiconn->query("
							SELECT alturaReal,repAlPaso 
							from juegoscilindros 
							where identificadorCilindro='$juegoCilindros'");
						$rowJuego=$consJuego->fetch_array();
///////////////			
						$alturaReal=$rowJuego['alturaReal'];
						$repAlPaso=$rowJuego['repAlPaso']; 
					}
					if(!empty($_POST['comboCireles']))
					{
						$juegoCireles=$_POST['comboCireles'];
						$consJuego=$MySQLiconn->query("
							SELECT impresiones.anchoEtiqueta,juegoscireles.alturaReal,impresiones.alturaEtiqueta,juegoscireles.repeticiones 
							from juegoscireles inner join impresiones on impresiones.descripcionImpresion=juegoscireles.descripcionImpresion
							where juegoscireles.identificadorJuego='$juegoCireles' and impresiones.descripcionImpresion='$producto'");
						$rowJuego=$consJuego->fetch_array();
///////////////			
						$alturaReal=$rowJuego['alturaReal'];
						$repeticiones=$rowJuego['repeticiones']; 
						$anchoEtiqueta=$rowJuego['anchoEtiqueta'];
						
					}
					
				}
				
				$maquina = $MySQLiconn->real_escape_string($_POST['comboMaquinas']);
				$fechaProduccion=$MySQLiconn->real_escape_string($_POST['fechaProduccion']);

				$SQL=$MySQLiconn->query("
					SELECT*
					FROM lotestemporales 
					where baja=1");
				if($SQL->num_rows>=1)
				{
					
/////////////
					$maquina = $MySQLiconn->real_escape_string($_POST['comboMaquinas']);
					if($tipo!="BS")
					{
						$SQL =$MySQLiconn->query("
							SELECT descripciondisenio 
							from impresiones 
							where descripcionImpresion='$producto'");
						$rew=$SQL->fetch_array();
						$disenio=$rew['descripciondisenio'];
					}
					else
					{
						$SQL =$MySQLiconn->query("
							SELECT identificadorBS 
							from bandaspp 
							where nombreBSPP='$producto'");
						$rew=$SQL->fetch_array();
						$disenio=$rew['identificadorBS'];
					}
					
					if($tipo=="Termoencogible")
					{
						$tipo="Termoencogible";
					}
					
					$fechaProduccion=$MySQLiconn->real_escape_string($_POST['fechaProduccion']);

						//Generación del codigo del juego de lotes para la operación
					$conc=substr($tipo,0,3);
					$juegoLote="JL".$conc.$fechaProduccion.date('U');
					

					$MySQLiconn->begin_transaction();
					mysqli_autocommit($MySQLiconn,FALSE);
					if($tipo!="BS")
					{
						if(!empty($_POST['comboCilindros']))
						{
							$QUERY =$MySQLiconn->query("
								INSERT INTO produccion(nombreProducto,juegoCilindros,maquina,disenio,fechaProduccion,juegoLotes,tipo,estado)
								VALUES('$producto','$juegoCilindros','$maquina','$disenio','$fechaProduccion','$juegoLote','$tipo',2)");
						}
						else if(!empty($_POST['comboCireles']))
						{
							$suaje=$_POST['comboSuaje'];
							$juegoCireles=$_POST['comboCireles'];
							
							$QUERY =$MySQLiconn->query("
								INSERT INTO produccion(nombreProducto,juegoCireles,suaje,maquina,disenio,fechaProduccion,juegoLotes,tipo,estado)
								VALUES('$producto','$juegoCireles','$suaje','$maquina','$disenio','$fechaProduccion','$juegoLote','$tipo',2)");
						}
						else
						{
							
							$QUERY =$MySQLiconn->query("
								INSERT INTO produccion(nombreProducto,maquina,disenio,fechaProduccion,juegoLotes,tipo,estado)
								VALUES('$producto','$maquina','$disenio','$fechaProduccion','$juegoLote','$tipo',2)");
						}
						
					}
					else if($tipo=="BS")
					{
						$QUERY =$MySQLiconn->query("
							INSERT INTO produccion(nombreProducto,maquina,disenio,fechaProduccion,juegoLotes,tipo,estado)
							VALUES('$producto','$maquina','$disenio','$fechaProduccion','$juegoLote','$tipo',2)");
					}
					

					$result =$MySQLiconn->query("SELECT*
						from lotestemporales
						where baja=1");
					$contador=0;
					$contadorUnidades=0;

					while($row=$result->fetch_array())
					{
						if(!empty($_POST['comboCilindros']))
						{
							$unidades=($row['longitud']*$repAlPaso)/$alturaReal;
						}
						else if(!empty($_POST['comboCireles']))
						{
							$unidades=($row['longitud']*$repeticiones)/$alturaReal;
							if(empty($unidades))
							{
								$unidades=$row['unidades'];
							}
						}
						else
						{
							$unidades=$row['unidades'];
						}
							///////////////////////// Aqui se genera el  OP
						$SQL =$MySQLic->query("call getnoop('$producto')");
						$ro=$SQL->fetch_array();
						$noop=$ro['noop'];	
						$MySQLic->next_result();
						$isntOp=1;
							while($isntOp==1)//se asegura de generar un NoOP que no exista
							{
								$as=$MySQLiconn->query("SELECT noop from lotes where noop='$noop' and tipo='$tipo'");
								$rot=$as->fetch_array();
								if(empty($rot['noop']))
								{
									$isntOp=0;
								}
								else
								{
									$noop=$noop+1;
								}	
							}
							
							
						///////////////////////////
							
							$SQU =$MySQLiconn->query("
								UPDATE  lotes 
								set estado='1', unidades='$unidades',noLote='".$row['noLote']."',anchuraBloque='".$row['anchura']."',juegoLote='$juegoLote',tipo='".$_GET['tipo']."', noop='$noop' where referenciaLote='".$row['referencia']."'");

							$contador=$contador+1;
							$contadorUnidades=$contadorUnidades+$unidades;
							
						}
						$SKU =$MySQLiconn->query("
							UPDATE produccion 
							set cantLotes='$contador',unidades='$contadorUnidades' 
							where juegoLotes='$juegoLote'");

						if(!$SQL or !$SKU or !$SQU or !$QUERY)
						{
							$MySQLiconn->rollback();
							echo "<script>alert('Ocurrió un error durante el registro,contacte al encargado de sistemas')</script>";
							echo "<script>window.location='Produccion_programacion.php?tipo=$tipo';</script>";
						} else{
							$MySQLiconn->commit();
							echo"<script>alert('Programación realizada')</script>";
							echo "<script>window.location='Produccion_programacion.php?tipo=$tipo';</script>";
						}

					}
					else
					{
						echo"<script>alert('No puede existir una programación vacía,operación no exitosa')</script>";
						echo "<script>window.location='Produccion_programacion.php?tipo=$tipo';</script>";
					}

				}
				else
				{
					echo"<script>alert('No cuentas con los permisos para realizar esta acción')</script>";
					if(empty($tipo))
					{
						echo "<script>window.location='Produccion_programacion.php?tipo=Termoencogible';</script>";
					}
					else
					{
						echo "<script>window.location='Produccion_programacion.php?tipo=$tipo';</script>";
					}
					
				}
			}

			if(!empty($_GET['c']))
			{
				
				if($_GET['c']=="Termoencogible"){
					$MySQLiconn->query("
						UPDATE cache 
						set dato='Termoencogible' 
						where id=9");

				}
				else
				{
					$MySQLiconn->query("
						UPDATE cache 
						set dato='".$_GET['c']."' where id=9");
				}

			}
			if(!empty($_GET['cil']))
			{
				?>
				<select  required class="combosMenores" class="form-control" name="comboCilindros" id="comboCilindros">
					<?php
					$resultado=$MySQLiconn->query("
						SELECT identificadorCilindro,proveedor 
						from juegoscilindros 
						where descripcionImpresion='".$_GET['cil']."' and baja=1");
						?>
						<option value="">--</option>
						<?php
						while($row=$resultado->fetch_array())
						{
							?>
							<option value="<?php echo $row['identificadorCilindro'];?>"><?php  echo "[".$row['identificadorCilindro']."]".$row['proveedor'];?></option>
							<?php
						}
						?>
					</select>
					<?php

				}
				if(!empty($_GET['cir']))
				{
					?>
					<select required class="combosMenores" class="form-control" name="comboCireles" id="comboCireles">
						<?php
						$resultado=$MySQLiconn->query("
							SELECT identificadorJuego as identificadorCilindro,num_dientes as proveedor 
							from juegoscireles 
							where descripcionImpresion='".$_GET['cir']."' and baja=1 and producto=(SELECT codigoImpresion FROM impresiones WHERE descripcionImpresion='".$_GET['cir']."')");
							?>
							<option value="">--</option>
							<?php
							while($row=$resultado->fetch_array())
							{
								?>
								<option value="<?php echo $row['identificadorCilindro'];?>"><?php  echo "[".$row['identificadorCilindro']."]".$row['proveedor'];?></option>
								<?php
							}
							?>
						</select>
						<?php

					}
					if(!empty($_GET['suj']))
					{
						?>
						<select class="combosMenores" name="comboSuaje" id="comboSuaje">
							<?php
							$resultado=$MySQLiconn->query("SELECT concat('[',suaje.identificadorSuaje,'] ',suaje.proveedor) as suaje,suaje.identificadorSuaje from suaje where baja=1 and suaje.descripcionImpresion='".$_GET['suj']."' and suaje.proceso='".$_SESSION['first']."'");
							?>
							<option value="">--</option>
							<?php
							while($row=$resultado->fetch_array())
							{
								?>
								<option value="<?php echo $row['identificadorSuaje'];?>"><?php echo $row['suaje'];?></option>
								<?php
							}
							?>
						</select>
						<?php

					}
					if(isset($_GET['return']))
					{
						$SQ=$MySQLiconn->query("
							SELECT  l.juegoLote,l.unidades,l.referenciaLote,l.noop,p.cantLotes  from lotes l left join produccion p on p.juegoLotes=l.juegoLote where l.idLote='".$_GET['return']."'");
						

						$rem=$SQ->fetch_array();

						$SQL=$MySQLiconn->query("DELETE FROM codigosbarras WHERE noop='".$rem['noop']."' and lote='".$rem['referenciaLote']."'");
						$SQLi=$MySQLiconn->query("
							UPDATE  produccion 
							set cantLotes=cantLotes-1,unidades=unidades-".$rem['unidades']." where juegoLotes='".$rem['juegoLote']."'");

						$SQL=$MySQLiconn->query("
							UPDATE  lotes 
							set estado=2,unidades=0,noLote='',anchuraBloque='',juegoLote='', noop='' where idLote='".$_GET['return']."'");
						

						if($rem['cantLotes']<=1)
						{
							$MySQLiconn->query("DELETE FROM produccion WHERE juegoLotes='".$rem['juegoLote']."'");
						}
						echo "<script>alert('El lote: ´".$_GET['return']."´ fué eliminado de esta programación');</script>";
						echo "<script>window.location='Produccion_programacion.php?tipo=".$_GET['tipo']."';</script>";

					}
					if(isset($_GET['generar']))
					{
						$_SESSION['lotesImprimir']=$_GET['generar'];
						header("Location:generarEtiquetas.php");
					}
