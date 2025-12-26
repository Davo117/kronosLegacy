<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '9ME00';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']); 
  
    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { $mapaEstado_cdgestado = trim($_POST['hidden_cdgestado']);

        $mapaEstado_idestado = trim($_POST['text_idestado']);
        $mapaEstado_estado = trim($_POST['text_estado']);
        $mapaEstado_pais = trim($_POST['text_pais']);

        if ($mapaEstado_estado != '')
        { if ($mapaEstado_idestado != '')
          { if ($mapaEstado_pais != '')
            { if ($mapaEstado_cdgestado != '')
              { $link_mysqli = conectar();
                $mapaEstadoSelect = $link_mysqli->query("
                  SELECT * FROM mapaestado
                  WHERE cdgestado = '".$mapaEstado_cdgestado."'");
              } else
              { $link_mysqli = conectar();
                $mapaEstadoSelect = $link_mysqli->query("
                  SELECT * FROM mapaestado
                  WHERE idestado = '".$mapaEstado_idestado."'"); }

              if ($mapaEstadoSelect->num_rows == 0)
              { for ($id = 100; $id <= 1000; $id++)
                { $mapaEstado_cdgestado = str_pad($id,3,'0',STR_PAD_LEFT);

                  if ($id >= 1000)
                  { $msg_modulo = 'Los codigos disponibles para estados se han agotado.'; }
                  else
                  { $link_mysqli = conectar();
                    $mapaEstadoSelect = $link_mysqli->query("
                      SELECT * FROM mapaestado
                      WHERE cdgestado = '".$mapaEstado_cdgestado."'");

                    if ($mapaEstadoSelect->num_rows == 0)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO mapaestado
                          (idestado, estado, pais, cdgestado)
                        VALUES
                          ('".$mapaEstado_idestado."','".$mapaEstado_estado."','".$mapaEstado_pais."','".$mapaEstado_cdgestado."')");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_modulo = 'El estado fue INSERTADO satisfactoriamente.'; }
                      else
                      { $msg_modulo = 'El estado NO fue insertado'; }

                      $id = 1000;

                      $mapaEstado_cdgestado = '';
                    }
                  }
                }
              } else
              { if ($mapaEstado_cdgestado != '')
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE mapaestado
                    SET idestado = '".$mapaEstado_idestado."',
                      estado = '".$mapaEstado_estado."',
                      pais = '".$mapaEstado_pais."'
                    WHERE cdgestado = '".$mapaEstado_cdgestado."' AND
                      sttestado = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El estado fue ACTUALIZADO satisfactoriamente. (CR)'; }
                  else
                  { $msg_modulo = 'El estado NO fue actualizado. (CR) <br/> No presento cambios o su status no permite modificaciones'; }
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE mapaestado
                    SET estado = '".$mapaEstado_estado."',
                      pais = '".$mapaEstado_pais."'
                    WHERE idestado = '".$mapaEstado_idestado."' AND
                      sttestado = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'El estado fue ACTUALIZADO satisfactoriamente.'; }
                  else
                  { $msg_modulo = 'El estado NO fue actualizado. <br/> No presento cambios o su status no permite modificaciones.'; }
                }
              }
            } else
            { $msg_modulo = 'El pais del estado no puede estar vac&iacute;o.';
              $text_pais = 'autofocus'; }
          } else
          { $msg_modulo = 'El abreviado del estado no puede estar vac&iacute;o.';
            $text_idestado = 'autofocus'; }
        } else
        { $msg_modulo = 'El nombre del estado no puede estar vac&iacute;o.';
          $text_estado = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgestado'])
      {	$link_mysqli = conectar();
        $mapaEstadoSelect = $link_mysqli->query("
          SELECT * FROM mapaestado
          WHERE cdgestado = '".$_GET['cdgestado']."'");
          
        if ($mapaEstadoSelect->num_rows >= 1)
        { $msg_modulo = 'Registro encontrado';
          
          $regMapaEstado = $mapaEstadoSelect->fetch_object();

          $mapaEstado_idestado = $regMapaEstado->idestado;
          $mapaEstado_estado = $regMapaEstado->estado;          
          $mapaEstado_pais = $regMapaEstado->pais;
          $mapaEstado_cdgestado = $regMapaEstado->cdgestado;
          $mapaEstado_sttestado = $regMapaEstado->sttestado; }
      } 

	  if ($_GET['proceso'] == 'update')
	  { if (substr($sistModulo_permiso,0,2) == 'rw')
		{ if ($mapaEstado_sttestado == '1')
		  { $mapaEstado_newsttestado = '0'; }
		  
		  if ($mapaEstado_sttestado == '0')
		  { $mapaEstado_newsttestado = '1'; }

		  if ($mapaEstado_sttestado == '9')
		  { $mapaEstado_newsttestado = '8'; }
		  
		  if ($mapaEstado_sttestado == '8')
		  { $mapaEstado_newsttestado = '9'; }          
		  
		  if ($mapaEstado_newsttestado != '')
		  { $link_mysqli = conectar();
			$link_mysqli->query("
			  UPDATE mapaestado 
			  SET sttestado = '".$mapaEstado_newsttestado."'
			  WHERE cdgestado = '".$mapaEstado_cdgestado."'");

			if ($link_mysqli->affected_rows > 0)
			{ $msg_modulo = 'El estado fue ACTUALIZADO satisfactoriamente.'; }
			else
			{ $msg_modulo = 'El estado NO fue ACTUALIZADO.'; }
			
		  } else
		  { $msg_modulo = 'El estado que seleccionaste, no tiene permitido cambiar de status.'; }
		} else
		{ $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
	  }

	  if ($_GET['proceso'] == 'delete')
	  { if (substr($sistModulo_permiso,0,3) == 'rwx')
		{ $link_mysqli = conectar();
		  $mapaCiudadSelect = $link_mysqli->query("
			SELECT * FROM mapaciudad
			WHERE cdgestado = '".$mapaEstado_cdgestado."'");
		  
		  if ($mapaCiudadSelect->num_rows > 0)
		  {	$msg_modulo = 'El estado NO fue eliminado por que existen ciudades asociadas.'; }
		  else
		  {	$link_mysqli = conectar();
			$link_mysqli->query("
			  DELETE FROM mapaestado
			  WHERE cdgestado = '".$mapaEstado_cdgestado."' AND
				sttestado = '0'");
			
			if ($link_mysqli->affected_rows > 0)
			{	$msg_modulo = 'El estado fue ELIMINADO satisfactoriamente.'; }
			else
			{	$msg_modulo = 'El estado NO fue eliminado.'; }
		  }
		} else
		{ $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }           
	  }
     
      $link_mysqli = conectar();
      $mapaEstadoSelect = $link_mysqli->query("
	    SELECT * FROM mapaestado
	    ORDER BY sttestado DESC,
	      pais,
	      estado");

	    $num_estados = $mapaEstadoSelect->num_rows;

	    if ($num_estados > 0)
	    { while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
	  	  { $id_estado++;

  	  	  $mapaEstados_idestado[$id_estado] = $regMapaEstado->idestado;
  	  	  $mapaEstados_estado[$id_estado] = $regMapaEstado->estado;
	  	  $mapaEstados_pais[$id_estado] = $regMapaEstado->pais;
          $mapaEstados_cdgestado[$id_estado] = $regMapaEstado->cdgestado;
          $mapaEstados_sttestado[$id_estado] = $regMapaEstado->sttestado; }
          
      } else
 	    { $msg_modulo = 'No cuentas con registros en el modulo.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
    <form id="form_mapa" name="form_mapa" method="POST" action="mapaEstado.php" />
      <input type="hidden" id="hidden_cdgestado" name="hidden_cdgestado" value="'.$mapaEstado_cdgestado.'" />

      <table align="center">
        <thead>
          <tr><th colspan="3"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
		  <tr><td><label for="label_estado"><strong>Estado</strong></label><br/>
			  <input type="text" id="text_estado" name="text_estado" value="'.$mapaEstado_estado.'" style="width:240px;" '.$text_estado.' required /></td>
		    <td><label for="label_idestado"><strong>Abreviado</strong></label><br/>
			  <input type="text" id="text_idestado" name="text_idestado" value="'.$mapaEstado_idestado.'" style="width:70px;" '.$text_idestado.' required /></td>
			<td><label for="label_pais"><strong>Pais</strong></label><br/>
			  <input type="text" id="text_pais" name="text_pais" value="'.$mapaEstado_pais.'"style="width:120px;" '.$text_pais.' required  /></td></tr>													
        </tbody>
        <tfoot>
          <tr><td colspan="3" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '
      <div align="center"><strong>'.$msg_modulo.'</strong></div><br/>'; }

    if ($num_estados > 0)
    { echo '
      <table align="center">
        <thead>
          <tr><th>Estado</th>
            <th>Pais</th>
            <th colspan="3">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_estado = 1; $id_estado <= $num_estados; $id_estado++)
      { echo '
          <tr align="center">
            <td align="left">'.$mapaEstados_estado[$id_estado].' ['.$mapaEstados_idestado[$id_estado].']</td>
            <td align="left">'.$mapaEstados_pais[$id_estado].'</td>';
            
        if ($mapaEstados_sttestado[$id_estado] == '9')
        { echo '
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'">'.$png_search.'</a></td>
            <td><a href="mapaCiudad.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'">'.$png_link.'</a></td>
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }

        if ($mapaEstados_sttestado[$id_estado] == '8')
        { echo '
            <td></td>
            <td></td>
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }            

        if ($mapaEstados_sttestado[$id_estado] == '1')
        { echo '
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'">'.$png_search.'</a></td>
            <td><a href="mapaCiudad.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'">'.$png_link.'</a></td>
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
        
        if ($mapaEstados_sttestado[$id_estado] == '0')
        { echo '
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="mapaEstado.php?cdgestado='.$mapaEstados_cdgestado[$id_estado].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
      }

      echo'
        </tbody>
        <tfoot>
          <tr><td colspan="5" align="right">['.$num_estados.'] registros encontrados</td></tr>
        </tfoot>
      </table>'; }

    echo '
    </form>';
  
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>
