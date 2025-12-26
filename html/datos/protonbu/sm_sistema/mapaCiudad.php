<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '9MC00';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { $mapaCiudad_cdgciudad = trim($_POST['hidden_cdgciudad']);

        $mapaCiudad_cdgestado = trim($_POST['select_cdgestado']);
        $mapaCiudad_idciudad = trim($_POST['text_idciudad']);
        $mapaCiudad_ciudad = trim($_POST['text_ciudad']);

        if ($mapaCiudad_cdgestado != '')
        { if ($mapaCiudad_idciudad != '')
          { if ($mapaCiudad_ciudad != '')
            { if ($mapaCiudad_cdgciudad != '')
              { $link_mysqli = conectar();
                $mapaCiudadSelect = $link_mysqli->query("
                  SELECT * FROM mapaciudad
                  WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");
              } else
              { $link_mysqli = conectar();
                $mapaCiudadSelect = $link_mysqli->query("
                  SELECT * FROM mapaciudad
                  WHERE cdgestado = '".$mapaCiudad_cdgestado."' AND
                    idciudad = '".$mapaCiudad_idciudad."'"); }

              if ($mapaCiudadSelect->num_rows == 0)
              { for ($id = 1000; $id <= 10000; $id++)
                { $mapaCiudad_cdgciudad = $mapaCiudad_cdgestado.str_pad($id,4,'0',STR_PAD_LEFT);

                  if ($id >= 10000)
                  { $msg_modulo = 'Los codigos disponibles para ciudades en este estado se han agotado.'; }
                  else
                  { $link_mysqli = conectar();
                    $mapaCiudadSelect = $link_mysqli->query("
                      SELECT * FROM mapaciudad
                      WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");

                    if ($mapaCiudadSelect->num_rows == 0)
                    { $link_mysqli = conectar();
                      $link_mysqli->query("
                        INSERT INTO mapaciudad
                          (cdgestado, idciudad, ciudad, cdgciudad)
                        VALUES
                          ('".$mapaCiudad_cdgestado."','".$mapaCiudad_idciudad."','".$mapaCiudad_ciudad."','".$mapaCiudad_cdgciudad."')");

                      if ($link_mysqli->affected_rows > 0)
                      { $msg_modulo = 'La ciudad fue INSERTADA satisfactoriamente.'; }
                      else
                      { $msg_modulo = 'La ciudad NO fue insertada'; }

                      $id = 10000;

                      $mapaCiudad_cdgciudad = '';
                    }
                  }
                }
              } else
              { if ($mapaCiudad_cdgciudad != '')
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE mapaciudad
                    SET idciudad = '".$mapaCiudad_idciudad."',
                      ciudad = '".$mapaCiudad_ciudad."'
                    WHERE cdgciudad = '".$mapaCiudad_cdgciudad."' AND
                      sttciudad = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'La ciudad fue ACTUALIZADA satisfactoriamente. (CR)'; }
                  else
                  { $msg_modulo = 'La ciudad NO fue actualizada. (CR) <br/> No presento cambios o su status no permite modificaciones'; }
                } else
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    UPDATE mapaciudad
                    SET ciudad = '".$mapaCiudad_ciudad."'
                    WHERE cdgestado = '".$mapaCiudad_cdgestado."' AND
                      idciudad = '".$mapaCiudad_idciudad."' AND
                      sttciudad = '1'");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_modulo = 'La ciudad fue ACTUALIZADA satisfactoriamente.'; }
                  else
                  { $msg_modulo = 'La ciudad NO fue actualizada. <br/> No presento cambios o su status no permite modificaciones.'; }
                }
              }
            } else
            { $msg_modulo = 'El nombre de la ciudad no puede estar vac&iacute;o.';
              $text_ciudad = 'autofocus'; }
          } else
          { $msg_modulo = 'El c&oacute;digo de la ciudad no puede estar vac&iacute;o.';
            $text_idciudad = 'autofocus'; }
        } else
        { $msg_modulo = 'Es necesario seleccionar algun estado.';
          $select_cdgestado = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_GET['cdgciudad'])
      {	$link_mysqli = conectar();
        $mapaCiudadSelect = $link_mysqli->query("
          SELECT * FROM mapaciudad
          WHERE cdgciudad = '".$_GET['cdgciudad']."'");

        if ($mapaCiudadSelect->num_rows >= 1)
        { $msg_modulo = 'Registro encontrado';

          $regMapaCiudad = $mapaCiudadSelect->fetch_object();

          $mapaCiudad_cdgestado = $regMapaCiudad->cdgestado;
          $mapaCiudad_idciudad = $regMapaCiudad->idciudad;
          $mapaCiudad_ciudad = $regMapaCiudad->ciudad;
          $mapaCiudad_pais = $regMapaCiudad->pais;
          $mapaCiudad_cdgciudad = $regMapaCiudad->cdgciudad;
          $mapaCiudad_sttciudad = $regMapaCiudad->sttciudad; }
      }

	  if ($_GET['proceso'] == 'update')
	  { if (substr($sistModulo_permiso,0,2) == 'rw')
		{ if ($mapaCiudad_sttciudad == '1')
		  { $mapaCiudad_newsttciudad = '0'; }

		  if ($mapaCiudad_sttciudad == '0')
		  { $mapaCiudad_newsttciudad = '1'; }

		  if ($mapaCiudad_sttciudad == '9')
		  { $mapaCiudad_newsttciudad = '8'; }

		  if ($mapaCiudad_sttciudad == '8')
		  { $mapaCiudad_newsttciudad = '9'; }

		  if ($mapaCiudad_newsttciudad != '')
		  { $link_mysqli = conectar();
        $vntsClienteSelect = $link_mysqli->query("
        SELECT * FROM vntscliente
        WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");

        if ($vntsClienteSelect->num_rows > 0)
        { $msg_modulo = 'El ciudad NO fue actualizada por que existen clientes asociados.'; }
        else
        { $link_mysqli = conectar();
          $vntsSucursalSelect = $link_mysqli->query("
            SELECT * FROM vntssucursal
            WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");
          
          if ($vntsSucursalSelect->num_rows > 0)
          { $msg_modulo = 'El ciudad NO fue actualizada por que existen sucursales asociadas.'; }
          else
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE mapaciudad
              SET sttciudad = '".$mapaCiudad_newsttciudad."'
              WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");

            if ($link_mysqli->affected_rows > 0)
            { $msg_modulo = 'El ciudad fue ACTUALIZADO satisfactoriamente.'; }
            else
            { $msg_modulo = 'El ciudad NO fue ACTUALIZADO.'; }
          }
        }
		  } else
		  { $msg_modulo = 'El ciudad que seleccionaste, no tiene permitido cambiar de status.'; }
		} else
		{ $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
	  }

	  if ($_GET['proceso'] == 'delete')
	  { if (substr($sistModulo_permiso,0,3) == 'rwx')
      { $link_mysqli = conectar();
        $vntsClienteSelect = $link_mysqli->query("
        SELECT * FROM vntscliente
        WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");

        if ($vntsClienteSelect->num_rows > 0)
        {	$msg_modulo = 'El ciudad NO fue eliminado por que existen clientes asociados.'; }
        else
        {	$link_mysqli = conectar();
          $vntsSucursalSelect = $link_mysqli->query("
            SELECT * FROM vntssucursal
            WHERE cdgciudad = '".$mapaCiudad_cdgciudad."'");
          
          if ($vntsSucursalSelect->num_rows > 0)
          {	$msg_modulo = 'El ciudad NO fue eliminado por que existen sucursales asociadas.'; }
          else
          {	$link_mysqli = conectar();
            $link_mysqli->query("
            DELETE FROM mapaciudad
            WHERE cdgciudad = '".$mapaCiudad_cdgciudad."' AND
            sttciudad = '0'");

            if ($link_mysqli->affected_rows > 0)
            {	$msg_modulo = 'El ciudad fue ELIMINADO satisfactoriamente.'; }
            else
            {	$msg_modulo = 'El ciudad NO fue eliminado.'; }
          }
        }
      } else
      { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }
	  }

      if ($_GET['cdgestado'])
      { $mapaCiudad_cdgestado = $_GET['cdgestado']; }

      if ($_POST['select_cdgestado'])
      { $mapaCiudad_cdgestado = $_POST['select_cdgestado']; }

	  $link_mysqli = conectar();
      $mapaCiudadSelect = $link_mysqli->query("
	    SELECT * FROM mapaciudad
	    WHERE cdgestado = '".$mapaCiudad_cdgestado."'
	    ORDER BY sttciudad DESC,
	      ciudad");

	  $num_ciudades = $mapaCiudadSelect->num_rows;

	  if ($num_ciudades > 0)
	  { while ($regMapaCiudad = $mapaCiudadSelect->fetch_object())
	    { $id_ciudad++;

  	     $mapaCiudades_ciudad[$id_ciudad] = $regMapaCiudad->ciudad;
          $mapaCiudades_cdgciudad[$id_ciudad] = $regMapaCiudad->cdgciudad;
          $mapaCiudades_sttciudad[$id_ciudad] = $regMapaCiudad->sttciudad; }

      } else
 	  { $msg_modulo .= '<br/> No cuentas con registros en el estado seleccionado.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    $link_mysqli = conectar();
    $mapaEstadoSelect = $link_mysqli->query("
      SELECT * FROM mapaestado
      WHERE sttestado = '1' OR sttestado = '9'");
      
    if ($mapaEstadoSelect->num_rows > 0)
    { while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
      { $id_estado++;
      
        $mapaEstado_idestado[$id_estado] = $regMapaEstado->idestado;
        $mapaEstado_estado[$id_estado] = $regMapaEstado->estado;
        $mapaEstado_cdgestado[$id_estado] = $regMapaEstado->cdgestado; }
    }
    
    $num_estados = $mapaEstadoSelect->num_rows;

    echo '
    <form id="form_mapa" name="form_mapa" method="POST" action="mapaCiudad.php" />
      <input type="hidden" id="hidden_cdgciudad" name="hidden_cdgciudad" value="'.$mapaCiudad_cdgciudad.'" />

      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
		  <tr><td colspan="2"><a href="mapaEstado.php?cdgestado='.$mapaCiudad_cdgestado.'"><strong>Estado</strong></a><br/>
              <select id="select_cdgestado" name="select_cdgestado" onchange="document.form_mapa.submit()">
                <option value="" selected="selected">- Elije un estado -</option>';

    for ($id_estado = 1; $id_estado <= $num_estados; $id_estado++)
    { echo '
                <option value="'.$mapaEstado_cdgestado[$id_estado].'"';

      if ($mapaCiudad_cdgestado == $mapaEstado_cdgestado[$id_estado])
      { echo ' selected="selected"'; }

      echo '>'.$mapaEstado_estado[$id_estado].' ['.$mapaEstado_idestado[$id_estado].']</option>'; }

    echo '
              </select></td></tr>
		  <tr><td><label for="label_idciudad"><strong>C&oacute;digo</strong></label><br/>
			  <input type="text" id="text_idciudad" name="text_idciudad" value="'.$mapaCiudad_idciudad.'" style="width:70px;" '.$text_idciudad.' required /></td>
			<td><label for="label_ciudad"><strong>Ciudad</strong></label><br/>
			  <input type="text" id="text_ciudad" name="text_ciudad" value="'.$mapaCiudad_ciudad.'" style="width:240px;" '.$text_ciudad.' required /></td></tr>
        </tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '
      <div align="center"><strong>'.$msg_modulo.'</strong></div><br/>'; }

    if ($num_ciudades > 0)
    { echo '
      <table align="center">
        <thead>
          <tr><th colspan="3">Filtro de ciudades por estado</th></tr>
          <tr><th>Ciudad</th>
            <th colspan="2">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_ciudad = 1; $id_ciudad <= $num_ciudades; $id_ciudad++)
      { echo '
          <tr align="center">
            <td align="left">'.$mapaCiudades_ciudad[$id_ciudad].'</td>';

        if ($mapaCiudades_sttciudad[$id_ciudad] == '9')
        { echo '
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'">'.$png_search.'</a></td>
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }

        if ($mapaCiudades_sttciudad[$id_ciudad] == '8')
        { echo '
            <td></td>            
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }

        if ($mapaCiudades_sttciudad[$id_ciudad] == '1')
        { echo '
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'">'.$png_search.'</a></td>
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }

        if ($mapaCiudades_sttciudad[$id_ciudad] == '0')
        { echo '
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="mapaCiudad.php?cdgciudad='.$mapaCiudades_cdgciudad[$id_ciudad].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
      }

      echo'
        </tbody>
        <tfoot>
          <tr><td colspan="3" align="right">['.$num_ciudades.'] registros encontrados</td></tr>
        </tfoot>
      </table>'; }

    echo '
    </form>';

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>
