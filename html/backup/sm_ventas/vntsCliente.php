<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40100';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $vntsCliente_cdgcliente = trim($_POST['hidden_cdgcliente']);

    $vntsCliente_idcliente = trim($_POST['text_idcliente']);
    $vntsCliente_cliente = trim($_POST['text_cliente']);
    $vntsCliente_domicilio = trim($_POST['text_domicilio']);
    $vntsCliente_colonia = trim($_POST['text_colonia']);
    $vntsCliente_cdgpostal = trim($_POST['text_cdgpostal']);
    $vntsCliente_cdgciudad = $_POST['select_cdgciudad'];
    $vntsCliente_telefono = trim($_POST['text_telefono']);

    if ($_POST['select_cdgciudad'])
    { $vntsCliente_cdgciudad = $_POST['select_cdgciudad']; }

    if ($_POST['submit_salvar'])
    { $msg_modulo = 'Salvando ...';
      
      if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($vntsCliente_cliente != '')
        { if ($vntsCliente_idcliente != '')
          { if ($vntsCliente_domicilio != '')
            { if ($vntsCliente_colonia != '')
              { if ($vntsCliente_cdgpostal != '')
                { if ($vntsCliente_cdgciudad != '')
                  { // Verificar si el registro esta cargado
                    if ($vntsCliente_cdgcliente != '')
                    { $link_mysqli = conectar();
                      $vntsClienteSelect = $link_mysqli->query("
                        SELECT * FROM vntscliente
                        WHERE cdgcliente = '".$vntsCliente_cdgcliente."'");
                    } else
                    { $link_mysqli = conectar();
                      $vntsClienteSelect = $link_mysqli->query("
                        SELECT * FROM vntscliente
                        WHERE idcliente = '".$vntsCliente_idcliente."'"); }

                    if ($vntsClienteSelect->num_rows == 0)
                    { for ($id = 1000; $id <= 10000; $id++)
                      { $vntsCliente_cdgcliente = str_pad($id,4,'0',STR_PAD_LEFT);

                        if ($id >= 10000)
                        { $msg_modulo = 'Los codigos disponibles para clientes se han agotado.'; }
                        else
                        { $link_mysqli = conectar();
                          $vntsClienteSelect = $link_mysqli->query("
                            SELECT *  FROM  vntscliente
                            WHERE cdgcliente = '".$vntsCliente_cdgcliente."'");

                          if ($vntsClienteSelect->num_rows == 0)
                          { $link_mysqli = conectar();
                            $link_mysqli->query("
                              INSERT INTO vntscliente
                                (idcliente, cliente, domicilio, colonia, cdgpostal, cdgciudad, telefono, cdgcliente)
                              VALUES
                                ('".$vntsCliente_idcliente."','".$vntsCliente_cliente."','".$vntsCliente_domicilio."','".$vntsCliente_colonia."','".$vntsCliente_cdgpostal."','".$vntsCliente_cdgciudad."','".$vntsCliente_telefono."','".$vntsCliente_cdgcliente."')");

                            if ($link_mysqli->affected_rows > 0)
                            { $msg_modulo = 'El cliente fue INSERTADO satisfactoriamente.'; }
                            else
                            { $msg_modulo = 'El cliente NO fue insertado'; }

                            $id = 10000;

                          }
                        }
                      }
                    } else
                    { if ($vntsCliente_cdgcliente != '')
                      { $link_mysqli = conectar();
                        $link_mysqli->query("
                          UPDATE vntscliente
                          SET idcliente = '".$vntsCliente_idcliente."',
                            cliente = '".$vntsCliente_cliente."',
                            domicilio = '".$vntsCliente_domicilio."',
                            colonia = '".$vntsCliente_colonia."',
                            cdgpostal = '".$vntsCliente_cdgpostal."',
                            cdgciudad = '".$vntsCliente_cdgciudad."',
                            telefono = '".$vntsCliente_telefono."'
                          WHERE cdgcliente = '".$vntsCliente_cdgcliente."' AND
                            sttcliente = '1'");

                        if ($link_mysqli->affected_rows > 0)
                        { $msg_modulo = 'El cliente fue ACTUALIZADO satisfactoriamente. (CR)'; }
                        else
                        { $msg_modulo = 'El cliente NO fue actualizado. (CR) <br/> No presento cambios o su status no permite modificaciones'; }

                        $vntsCliente_cdgcliente = '';
                      } else
                      { $link_mysqli = conectar();
                        $link_mysqli->query("
                          UPDATE vntscliente
                          SET cliente = '".$vntsCliente_cliente."',
                            domicilio = '".$vntsCliente_domicilio."',
                            colonia = '".$vntsCliente_colonia."',
                            cdgpostal = '".$vntsCliente_cdgpostal."',
                            cdgciudad = '".$vntsCliente_cdgciudad."',
                            telefono = '".$vntsCliente_telefono."'
                          WHERE idcliente = '".$vntsCliente_idcliente."' AND
                            sttcliente = '1'");

                        if ($link_mysqli->affected_rows > 0)
                        { $msg_modulo = 'El cliente fue ACTUALIZADO satisfactoriamente.'; }
                        else
                        { $msg_modulo = 'El cliente NO fue actualizado. <br/> No presento cambios o su status no permite modificaciones.'; }
                      }
                    }

                    $vntsCliente_cdgcliente = '';
                    
                  } else
                  { $msg_modulo = 'Es necesario indicar una ciudad para este cliente.';
                    $select_cdgciudad = 'autofocus'; }
                } else
                { $msg_modulo = 'El c&oacute; postal del cliente no puede estar vac&iacute;o.';
                  $text_cdgpostal = 'autofocus'; }
              } else
              { $msg_modulo = 'La colonia del cliente no puede estar vac&iacute;a.';
                $text_colonia = 'autofocus'; }
            } else
            { $msg_modulo = 'El domicilio del cliente no puede estar vac&iacute;o.';
              $text_domocilio = 'autofocus'; }
          } else
          { $msg_modulo = 'El registro del cliente no puede estar vac&iacute;o.';
            $text_idcliente = 'autofocus'; }
        } else
        { $msg_modulo = 'La raz&oacute;n social del cliente no puede estar vac&iacute;a.';
          $text_cliente = 'autofocus'; }
      } else
      { $msg_modulo = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgcliente'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsClienteSelect = $link_mysqli->query("
          SELECT * FROM vntscliente
          WHERE cdgcliente = '".$_GET['cdgcliente']."'");
          
        if ($vntsClienteSelect->num_rows >= 1)
        { $msg_modulo = 'Registro encontrado y cargado.';
          
          $regVntsCliente = $vntsClienteSelect->fetch_object();

          $vntsCliente_idcliente = $regVntsCliente->idcliente;
          $vntsCliente_cliente = $regVntsCliente->cliente;          
          $vntsCliente_domicilio = $regVntsCliente->domicilio;
          $vntsCliente_colonia = $regVntsCliente->colonia;          
          $vntsCliente_cdgpostal = $regVntsCliente->cdgpostal;
          $vntsCliente_cdgciudad = $regVntsCliente->cdgciudad;
          $vntsCliente_telefono = $regVntsCliente->telefono;
          $vntsCliente_cdgcliente = $regVntsCliente->cdgcliente;
          $vntsCliente_sttcliente = $regVntsCliente->sttcliente;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsCliente_sttcliente == '1')
              { $vntsCliente_newsttcliente = '0'; }
              
              if ($vntsCliente_sttcliente == '0')
              { $vntsCliente_newsttcliente = '1'; }
              
              if ($vntsCliente_newsttcliente != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntscliente 
                  SET sttcliente = '".$vntsCliente_newsttcliente."'
                  WHERE cdgcliente = '".$vntsCliente_cdgcliente."'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El cliente fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El cliente NO fue ACTUALIZADO.'; }
                
              } else
              { $msg_modulo = 'El cliente que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_modulo = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $vntsSucursalSelect = $link_mysqli->query("
                SELECT * FROM vntssucursal
                WHERE cdgcliente = '".$vntsCliente_cdgcliente."'");
              
              if ($vntsSucursalSelect->num_rows > 0)
              { $msg_modulo = 'El cliente NO fue eliminado por que existen sucursales asociadas.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntscliente
                  WHERE cdgcliente = '".$vntsCliente_cdgcliente."' AND
                    sttcliente = '0'");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo = 'El cliente fue ELIMINADO satisfactoriamente.'; }
                else
                { $msg_modulo = 'El cliente NO fue eliminado.'; }
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
	    ORDER BY sttcliente DESC,
	      cliente");

	    $num_clientes = $vntsClienteSelect->num_rows;

	    if ($num_clientes > 0)
	    { while ($regVntsCliente = $vntsClienteSelect->fetch_object())
	  	  { $id_cliente++;

  	  	  $vntsClientes_idcliente[$id_cliente] = $regVntsCliente->idcliente;
  	  	  $vntsClientes_cliente[$id_cliente] = $regVntsCliente->cliente;
          $vntsClientes_telefono[$id_cliente] = $regVntsCliente->telefono;
	  	    $vntsClientes_cdgcliente[$id_cliente] = $regVntsCliente->cdgcliente;
          $vntsClientes_sttcliente[$id_cliente] = $regVntsCliente->sttcliente; }
          
      } else
 	    { $msg_modulo = 'No cuentas con registros en el modulo.'; }
    } else
    { $msg_modulo = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsCliente.php" />
      <input type="hidden" id="hidden_cdgcliente" name="hidden_cdgcliente" value="'.$vntsCliente_cdgcliente.'" />
      
      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_cliente"><strong>Raz&oacute;n social</strong></label><br/>
              <input type="text" id="text_cliente" name="text_cliente" value="'.$vntsCliente_cliente.'" style="width:360px;" '.$text_cliente.' required /></td>
            <td><label for="label_idcliente"><strong>Registro</strong></label><br/>
              <input type="text" id="text_idcliente" name="text_idcliente" value="'.$vntsCliente_idcliente.'" style="width:110px;" '.$text_idcliente.' required /></td></tr>
          <tr><td colspan="2"><label for="label_domicilio"><strong>Domicilio</strong></label><br/>
              <input type="text" id="text_domicilio" name="text_domicilio" value="'.$vntsCliente_domicilio.'" style="width:420px;" '.$text_domicilio.' required /></td></tr>
          <tr><td><label for="label_colonia"><strong>Colonia</strong></label><br/>
              <input type="text" id="text_colonia" name="text_colonia" value="'.$vntsCliente_colonia.'" style="width:320px;" '.$text_colonia.' required /></td>
            <td><label for="label_cdgpostal"><strong>C&oacute;digo postal</strong></label><br/>
              <input type="text" id="text_cdgpostal" name="text_cdgpostal" value="'.$vntsCliente_cdgpostal.'" style="width:110px;" '.$text_cdgpostal.' required /></td></tr>
          <tr><td><a href="../sm_sistema/mapaCiudad.php?cdgciudad='.$vntsCliente_cdgciudad.'"><strong>Ciudad</strong></a><br/>
              <select id="select_cdgciudad" name="select_cdgciudad" onchange="document.form_ventas.submit()" style="width:280px;" '.$select_cdgciudad.' required >
                <option value=""> Elije una ciudad </option>';

    for ($id_estado = 1; $id_estado <= $num_estados; $id_estado++)
    { echo '
                <optgroup label="'.$mapaEstado_estado[$id_estado].'">';

      for ($id_ciudad = 1; $id_ciudad <= $num_ciudades[$id_estado]; $id_ciudad++)
      { echo '
                  <option value="'.$mapaCiudad_cdgciudad[$id_estado][$id_ciudad].'"';

        if ($vntsCliente_cdgciudad == $mapaCiudad_cdgciudad[$id_estado][$id_ciudad])
        { echo ' selected="selected"'; }

        echo '>'.$mapaCiudad_ciudad[$id_estado][$id_ciudad].', '.$mapaEstado_idestado[$id_estado].'</option>'; } 

      echo '
                </optgroup>'; }

    echo '
              </select></td>
            <td><label for="label_telefono"><strong>Tel&eacute;fono</strong></label><br/>
              <input type="text" id="text_telefono" name="text_telefono" value="'.$vntsCliente_telefono.'" style="width:110px;" '.$text_telefono.' required /></td></tr>
        </tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '

      <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    if ($num_clientes > 0)
    { echo '

      <table align="center">
        <thead>
          <tr><th>Cliente</th>
            <th>Registro</th>
            <th>Tel&eacute;fono</th>
            <th colspan="4">Operaciones</th></tr>
        </thead>
        <tbody>';

      for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
      { echo '
          <tr align="center">
            <td align="left">'.$vntsClientes_cliente[$id_cliente].'</td>
            <td align="left"><strong>'.$vntsClientes_idcliente[$id_cliente].'</strong></td>
            <td align="left">'.$vntsClientes_telefono[$id_cliente].'</td>';
            
        if ($vntsClientes_sttcliente[$id_cliente] == '1')
        { echo '
            <td><a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'">'.$png_search.'</a></td>
            <td><a href="vntsSucursal.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'">'.$png_folder_open.'</a></td>
            <td><a href="vntsClienteCntc.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'">'.$png_user_group.'</a></td>
            <td><a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
        
        if ($vntsClientes_sttcliente[$id_cliente] == '0')
        { echo '
            <td><a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2"></td>            
            <td><a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$id_cliente].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
      }

      echo'
        </tbody>
        <tfoot>
          <tr><td colspan="7" align="right">['.$num_clientes.'] registros encontrados</td></tr>
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