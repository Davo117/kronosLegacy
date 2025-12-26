<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40110';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    $vntsSucursal_cdgsucursal = trim($_POST['hidden_cdgsucursal']);

    $vntsSucursal_cdgcliente = $_POST['select_cdgcliente'];
    $vntsSucursal_idsucursal = trim($_POST['text_idsucursal']);
    $vntsSucursal_sucursal = trim($_POST['text_sucursal']);
    $vntsSucursal_domicilio = trim($_POST['text_domicilio']);
    $vntsSucursal_colonia = trim($_POST['text_colonia']);
    $vntsSucursal_cdgpostal = trim($_POST['text_cdgpostal']);
    $vntsSucursal_cdgciudad = $_POST['select_cdgciudad'];
    $vntsSucursal_telefono = trim($_POST['text_telefono']);
    $vntsSucursal_transporte = trim($_POST['text_transporte']);

    if ($_POST['select_cdgciudad'])
    { $vntsSucursal_cdgciudad = $_POST['select_cdgciudad']; }

    if ($_GET['cdgcliente'])
    { $vntsSucursal_cdgcliente = $_GET['cdgcliente']; }

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($vntsSucursal_cdgcliente != '') 
        { if ($vntsSucursal_idsucursal != '')
          { if ($vntsSucursal_sucursal != '')
            { if ($vntsSucursal_domicilio != '')
              { if ($vntsSucursal_colonia != '')
                { if ($vntsSucursal_cdgpostal != '')
                  { if ($vntsSucursal_cdgciudad != '')
                    { 
                      if ($vntsSucursal_cdgsucursal != '')
                      { $link_mysqli = conectar();
                        $vntsSucursalSelect = $link_mysqli->query("
                          SELECT * FROM vntssucursal
                          WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."'");
                      } else
                      { $link_mysqli = conectar();
                        $vntsSucursalSelect = $link_mysqli->query("
                          SELECT * FROM vntssucursal
                          WHERE cgcliente = '".$vntsSucursal_cdgcliente."' AND
                            idsucursal = '".$vntsSucursal_idsucursal."'"); }

                      if ($vntsSucursalSelect->num_rows == 0)
                      { for ($id = 1000; $id <= 10000; $id++)
                        { $vntsSucursal_cdgsucursal = $vntsSucursal_cdgcliente.str_pad($id,4,'0',STR_PAD_LEFT);

                          if ($id >= 10000)
                          { $msg_modulo = 'Los codigos disponibles para sucursales se han agotado.'; }
                          else
                          { $link_mysqli = conectar();
                            $vntsSucursalSelect = $link_mysqli->query("
                              SELECT *  FROM  vntssucursal
                              WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."'");

                            if ($vntsSucursalSelect->num_rows == 0)
                            { $link_mysqli = conectar();
                              $link_mysqli->query("
                                INSERT INTO vntssucursal
                                  (cdgcliente, idsucursal, sucursal, domicilio, colonia, cdgpostal, cdgciudad, telefono, transporte, cdgsucursal)
                                VALUES
                                  ('".$vntsSucursal_cdgcliente."','".$vntsSucursal_idsucursal."','".$vntsSucursal_sucursal."','".$vntsSucursal_domicilio."','".$vntsSucursal_colonia."','".$vntsSucursal_cdgpostal."','".$vntsSucursal_cdgciudad."','".$vntsSucursal_telefono."','".$vntsSucursal_transporte."','".$vntsSucursal_cdgsucursal."')");

                              if ($link_mysqli->affected_rows > 0)
                              { $msg_modulo = 'La sucursal fue INSERTADA satisfactoriamente.'; }
                              else
                              { $msg_modulo = 'La sucursal NO fue insertada'; }

                              $id = 10000;

                              
                            }
                          }
                        }
                      } else
                      { if ($vntsSucursal_cdgsucursal != '')
                        { $link_mysqli = conectar();
                          $link_mysqli->query("
                            UPDATE vntssucursal
                            SET idsucursal = '".$vntsSucursal_idsucursal."',
                              sucursal = '".$vntsSucursal_sucursal."',
                              domicilio = '".$vntsSucursal_domicilio."',
                              colonia = '".$vntsSucursal_colonia."',
                              cdgpostal = '".$vntsSucursal_cdgpostal."',
                              cdgciudad = '".$vntsSucursal_cdgciudad."',
                              telefono = '".$vntsSucursal_telefono."',
                              transporte = '".$vntsSucursal_transporte."'
                            WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."' AND
                              sttsucursal = '1'");

                          if ($link_mysqli->affected_rows > 0)
                          { $msg_modulo = 'La sucursal fue ACTUALIZADO satisfactoriamente. (CR)'; }
                          else
                          { $msg_modulo = 'La sucursal NO fue actualizado. (CR) <br/>No presento cambios o su status no permite modificaciones'; }
                        } else
                        { $link_mysqli = conectar();
                          $link_mysqli->query("
                            UPDATE vntssucursal
                            SET sucursal = '".$vntsSucursal_sucursal."',
                              domicilio = '".$vntsSucursal_domicilio."',
                              colonia = '".$vntsSucursal_colonia."',
                              cdgpostal = '".$vntsSucursal_cdgpostal."',
                              cdgciudad = '".$vntsSucursal_cdgciudad."',
                              telefono = '".$vntsSucursal_telefono."',
                              transporte = '".$vntsSucursal_transporte."'
                            WHERE cdgcliente = '".$vntsSucursal_cdgcliente."' AND
                              idsucursal = '".$vntsSucursal_idsucursal."' AND
                              sttsucursal = '1'");

                          if ($link_mysqli->affected_rows > 0)
                          { $msg_modulo = 'La sucursal fue ACTUALIZADA satisfactoriamente.'; }
                          else
                          { $msg_modulo = 'La sucursal NO fue actualizada. <br/>No presento cambios o su status no permite modificaciones.'; }
                        }
                      } 

                      $vntsSucursal_cdgsucursal = '';

                    } else
                    { $msg_modulo = 'Es necesario indicar una ciudad para esta sucursal.';
                      $select_cdgciudad = 'autofocus'; }
                  } else
                  { $msg_modulo = 'El c&oacute; postal de la sucursal no puede estar vac&iacute;o.';
                    $text_cdgpostal = 'autofocus'; }
                } else
                { $msg_modulo = 'La colonia de la sucursal no puede estar vac&iacute;a.';
                  $text_colonia = 'autofocus'; }
              } else
              { $msg_modulo = 'El domicilio de la sucursal no puede estar vac&iacute;o.';
                $text_domocilio = 'autofocus'; }
            } else
            { $msg_modulo = 'El nombre de la sucursal no puede estar vac&iacute;a.';
              $text_sucursal = 'autofocus'; }
          } else
          { $msg_modulo = 'El c&oacute;digo de la sucursal no puede estar vac&iacute;o.';
            $text_idsucursal = 'autofocus'; }
        } else
        { $msg_modulo = 'Es necesario indicar a que cliente pertenece la sucursal.'; 
          $select_cdgcliente = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgsucursal'])
    {	if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsSucursalSelect = $link_mysqli->query("
          SELECT * FROM vntssucursal
          WHERE cdgsucursal = '".$_GET['cdgsucursal']."'");
          
        if ($vntsSucursalSelect->num_rows >= 1)
        {	$msg_modulo = 'Registro encontrado y cargado';
          
          $regVntsSucursal = $vntsSucursalSelect->fetch_object();

          $vntsSucursal_cdgcliente = $regVntsSucursal->cdgcliente;
          $vntsSucursal_idsucursal = $regVntsSucursal->idsucursal;
          $vntsSucursal_sucursal = $regVntsSucursal->sucursal;          
          $vntsSucursal_domicilio = $regVntsSucursal->domicilio;
          $vntsSucursal_colonia = $regVntsSucursal->colonia;          
          $vntsSucursal_cdgpostal = $regVntsSucursal->cdgpostal;
          $vntsSucursal_cdgciudad = $regVntsSucursal->cdgciudad;
          $vntsSucursal_telefono = $regVntsSucursal->telefono;
          $vntsSucursal_transporte = $regVntsSucursal->transporte;          
          $vntsSucursal_cdgsucursal = $regVntsSucursal->cdgsucursal;
          $vntsSucursal_sttsucursal = $regVntsSucursal->sttsucursal;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            {	if ($vntsSucursal_sttsucursal == '1')
              { $vntsSucursal_newsttsucursal = '0'; }
              
              if ($vntsSucursal_sttsucursal == '0')
              { $vntsSucursal_newsttsucursal = '1'; }
              
              if ($vntsSucursal_newsttsucursal != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntssucursal 
                  SET sttsucursal = '".$vntsSucursal_newsttsucursal."'
                  WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."'");
                  
                if ($link_mysqli->affected_rows > 0)
                {	$msg_modulo = 'La sucursal fue ACTUALIZADA satisfactoriamente.'; }
                else
                {	$msg_modulo = 'La sucursal NO fue ACTUALIZADA.'; }
                
              } else
              { $msg_modulo = 'La sucursal que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            {	$link_mysqli = conectar();
              $vntsSucursalSelect = $link_mysqli->query("
                SELECT * FROM vntsembarque
                WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."'");
              
              if ($vntsSucursalSelect->num_rows > 0)
              {	$msg_modulo = '<La sucursal NO fue eliminada por que existen embarques asociados.'; }
              else
              {	$link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntssucursal
                  WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."' AND
                    sttsucursal = '0'");
                
                if ($link_mysqli->affected_rows > 0)
                {	$msg_modulo = 'La sucursal fue ELIMINADA satisfactoriamente.'; }
                else
                {	$msg_modulo = 'La sucursal NO fue eliminada.'; }
              }
            } else
            { $msg_modulo = 'No cuentas con permisos para remover en este modulo.'; }           
          }
        }
      } else
      { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }         
    }           

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $mapaEstadoSelect = $link_mysqli->query("
        SELECT * FROM mapaestado
        WHERE (sttestado = '1' OR sttestado = '9')
        ORDER BY estado");
      
      if ($mapaEstadoSelect->num_rows > 0)
      { $id_estado = 1;
      
        while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
        { $mapaEstado_idestado[$id_estado] = $regMapaEstado->idestado;
          $mapaEstado_estado[$id_estado] = $regMapaEstado->estado;
          $mapaEstado_cdgestado[$id_estado] = $regMapaEstado->cdgestado; 
          
          $link_mysqli = conectar();
          $mapaCiudadSelect = $link_mysqli->query("
            SELECT * FROM mapaciudad
            WHERE cdgestado = '".$mapaEstado_cdgestado[$id_estado]."' AND
              (sttciudad = '1' OR sttciudad = '9')
            ORDER BY ciudad");   
              
          if ($mapaCiudadSelect->num_rows > 0)
          { $id_ciudad = 1;
          
            while ($regMapaCiudad = $mapaCiudadSelect->fetch_object())
            { $mapaCiudad_idciudad[$id_estado][$id_ciudad] = $regMapaCiudad->idciudad;
              $mapaCiudad_ciudad[$id_estado][$id_ciudad] = $regMapaCiudad->ciudad;
              $mapaCiudad_cdgciudad[$id_estado][$id_ciudad] = $regMapaCiudad->cdgciudad;
              
              $id_ciudad++; }
          }
          
          $num_ciudades[$id_estado] = $mapaCiudadSelect->num_rows;
          
          $id_estado++; }
      }
      
      $num_estados = $mapaEstadoSelect->num_rows;

      $link_mysqli = conectar();
      $vntsClienteSelect = $link_mysqli->query("
      SELECT * FROM vntscliente
      WHERE sttcliente = '1'
      ORDER BY sttcliente DESC,
        cliente");

      $num_clientes = $vntsClienteSelect->num_rows;

      if ($num_clientes > 0)
      { while ($regVntsCliente = $vntsClienteSelect->fetch_object())
        { $id_cliente++;

          $vntsClientes_idcliente[$id_cliente] = $regVntsCliente->idcliente;
          $vntsClientes_cliente[$id_cliente] = $regVntsCliente->cliente;
          $vntsClientes_cdgcliente[$id_cliente] = $regVntsCliente->cdgcliente;
          $vntsClientes_sttcliente[$id_cliente] = $regVntsCliente->sttcliente; }
          
      } else
      { $msg_modulo = 'No cuentas con clientes para agregar sucursales.'; }

      $link_mysqli = conectar();
      $vntsSucursalSelect = $link_mysqli->query("
	    SELECT * FROM vntssucursal
      WHERE cdgcliente = '".$vntsSucursal_cdgcliente."' 
	    ORDER BY sttsucursal DESC,
	      sucursal");

	    $num_sucursales = $vntsSucursalSelect->num_rows;

	    if ($num_sucursales > 0)
	    { while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
	  	  { $id_sucursal++;

  	  	  $vntsSucursales_idsucursal[$id_sucursal] = $regVntsSucursal->idsucursal;
  	  	  $vntsSucursales_sucursal[$id_sucursal] = $regVntsSucursal->sucursal;
          $vntsSucursales_telefono[$id_sucursal] = $regVntsSucursal->telefono;
	  	    $vntsSucursales_cdgsucursal[$id_sucursal] = $regVntsSucursal->cdgsucursal;
          $vntsSucursales_sttsucursal[$id_sucursal] = $regVntsSucursal->sttsucursal; }
          
      } else
 	    { $msg_modulo = 'No cuentas con sucursales para este cliente.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }
    
    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsSucursal.php" />
      <input type="hidden" id="hidden_cdgsucursal" name="hidden_cdgsucursal" value="'.$vntsSucursal_cdgsucursal.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2"><a href="vntsCliente.php?cdgcliente='.$vntsSucursal_cdgcliente.'"><strong>Cliente</strong></a><br/>
              <select id="select_cdgcliente" name="select_cdgcliente" onchange="document.form_ventas.submit()" style="width:360px;" '.$select_cdgcliente.' required >
                <option value=""> Elije un cliente </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
                <option value="'.$vntsClientes_cdgcliente[$id_cliente].'"';

      if ($vntsSucursal_cdgcliente == $vntsClientes_cdgcliente[$id_cliente])
      { echo ' selected="selected"'; }

      echo '>'.$vntsClientes_cliente[$id_cliente].' ('.$vntsClientes_idcliente[$id_cliente].')</option>'; } 

    echo '
              </select></td></tr>
          <tr><td><label for="label_sucursal"><strong>Sucursal</strong></label><br/>
              <input type="text" id="text_sucursal" name="text_sucursal" value="'.$vntsSucursal_sucursal.'" style="width:360px;" '.$text_sucursal.' required /></td>
            <td><label for="label_idsucursal"><strong>Referencia</strong></label><br/>
              <input type="text" id="text_idsucursal" name="text_idsucursal" value="'.$vntsSucursal_idsucursal.'" style="width:110px;" '.$text_idsucursal.' required /></td></tr>            
          <tr><td colspan="2"><label for="label_domicilio"><strong>Domicilio</strong></label><br/>
              <input type="text" id="text_domicilio" name="text_domicilio" value="'.$vntsSucursal_domicilio.'" style="width:420px;" '.$text_domicilio.' required /></td></tr>
          <tr><td><label for="label_colonia"><strong>Colonia</strong></label><br/>
              <input type="text" id="text_colonia" name="text_colonia" value="'.$vntsSucursal_colonia.'" style="width:320px;" '.$text_colonia.' required /></td>
            <td><label for="label_cdgpostal"><strong>C&oacute;digo postal</strong></label><br/>
              <input type="text" id="text_cdgpostal" name="text_cdgpostal" value="'.$vntsSucursal_cdgpostal.'" style="width:110px;" '.$text_cdgpostal.' required /></td></tr>
          <tr><td><a href="../sm_sistema/mapaCiudad.php?cdgciudad='.$vntsSucursal_cdgciudad.'"><strong>Ciudad</strong></a><br/>
              <select id="select_cdgciudad" name="select_cdgciudad" onchange="document.form_ventas.submit()"  style="width:280px;" '.$select_cdgciudad.' required >
                <option value=""> Elije una ciudad </option>';

    for ($id_estado = 1; $id_estado <= $num_estados; $id_estado++)
    { echo '
                <optgroup label="'.$mapaEstado_estado[$id_estado].'">';

      for ($id_ciudad = 1; $id_ciudad <= $num_ciudades[$id_estado]; $id_ciudad++)
      { echo '
                  <option value="'.$mapaCiudad_cdgciudad[$id_estado][$id_ciudad].'"';

        if ($vntsSucursal_cdgciudad == $mapaCiudad_cdgciudad[$id_estado][$id_ciudad])
        { echo ' selected="selected"'; }

        echo '>'.$mapaCiudad_ciudad[$id_estado][$id_ciudad].', '.$mapaEstado_idestado[$id_estado].'</option>'; } 

      echo '
                </optgroup>'; }

    echo '
              </select></td>
            <td><label for="label_telefono"><strong>Tel&eacute;fono</strong></label><br/>
              <input type="text" id="text_telefono" name="text_telefono" value="'.$vntsSucursal_telefono.'" style="width:110px;" '.$text_telefono.' required /></td></tr>
          <tr><td colspan="2"><label for="label_transporte"><strong>Transporte</strong></label><br/>
              <input type="text" id="text_transporte" name="text_transporte" value="'.$vntsSucursal_transporte.'" style="width:200px;" '.$text_transporte.' required /></td></tr>              
        </tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '

      <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    if ($num_sucursales > 0)
    { echo '

      <table align="center">
        <thead>
          <tr><th>Sucursal</th>
            <th>Referencia</th>
            <th>Tel&eacute;fono</th>
            <th colspan="5">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_sucursal = 1; $id_sucursal <= $num_sucursales; $id_sucursal++)
      { echo '
          <tr align="center">
            <td align="left">'.$vntsSucursales_sucursal[$id_sucursal].'</td>
            <td align="left"><strong>'.$vntsSucursales_idsucursal[$id_sucursal].'</strong></td>
            <td align="left">'.$vntsSucursales_telefono[$id_sucursal].'</td>';
            
        if ($vntsSucursales_sttsucursal[$id_sucursal] == '1')
        { echo '
            <td><a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'">'.$png_search.'</a></td>
            <td><a href="vntsOC.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'">'.$png_shopping_cart.'</a></td>
            <td><a href="vntsEmbarque.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'">'.$png_delivery.'</a></td>
            <td><a href="vntsSucursalCntc.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'">'.$png_user_group.'</a></td>
            <td><a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
        
        if ($vntsSucursales_sttsucursal[$id_sucursal] == '0')
        { echo '
            <td><a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="3"></td>
            <td><a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$id_sucursal].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
      }

      echo'
        </tbody>
        <tfoot>
          <tr><td colspan="8" align="right">['.$num_sucursales.'] registros encontrados</td></tr>
        </tfoot>
      </table>'; }

    echo '
    </form>';

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>
</html>