<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Clientes</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40100';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="vntsCliente.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 

    $vntsCliente_cdgcliente = trim($_POST['hidden_cdgcliente']);

    $vntsCliente_idcliente = trim($_POST['textIdCliente']);
    $vntsCliente_cliente = trim($_POST['textCliente']);
    $vntsCliente_domicilio = trim($_POST['textDomicilio']);
    $vntsCliente_colonia = trim($_POST['textColonia']);
    $vntsCliente_cdgpostal = trim($_POST['textCdgPostal']);
    $vntsCliente_cdgciudad = $_POST['slctCdgCiudad'];
    $vntsCliente_telefono = trim($_POST['textTelefono']);

    if ($_POST['slctCdgCiudad']) { $vntsCliente_cdgciudad = $_POST['slctCdgCiudad']; }

    if ($_GET['cdgcliente'])
    { if (substr($sistModulo_permiso,0,1) != 'r')
      { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }        
      else
      { $vntsClienteSelect = $link->query("
          SELECT * FROM vntscliente
           WHERE cdgcliente = '".$_GET['cdgcliente']."'");
          
        if ($vntsClienteSelect->num_rows > 0)
        { $regVntsCliente = $vntsClienteSelect->fetch_object();

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
          { if (substr($sistModulo_permiso,0,2) != 'rw')
            { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; }
            else
            { if ($vntsCliente_sttcliente == '1')
              { $vntsCliente_newsttcliente = '0'; }
              
              if ($vntsCliente_sttcliente == '0')
              { $vntsCliente_newsttcliente = '1'; }
              
              if ($vntsCliente_newsttcliente != '')
              { $link->query("
                  UPDATE vntscliente 
                     SET sttcliente = '".$vntsCliente_newsttcliente."'
                   WHERE cdgcliente = '".$_GET['cdgcliente']."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'El cliente fue ACTUALIZADO satisfactoriamente.'; }
                else
                { $msg_alert = 'El cliente NO fue ACTUALIZADO.'; }
                
              } else
              { $msg_alert = 'El cliente que seleccionaste, no tiene permitido cambiar de status.'; }
            }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) != 'rwx')
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }           
            else
            { $vntsSucursalSelect = $link->query("
                SELECT * FROM vntssucursal
                 WHERE cdgcliente = '".$_GET['cdgcliente']."'");
              
              if ($vntsSucursalSelect->num_rows > 0)
              { $msg_alert = 'El cliente NO fue eliminado por que existen sucursales asociadas.'; }
              else
              { $vntsContactoSelect = $link->query("
                SELECT * FROM vntscontacto
                 WHERE cdgcliente = '".$_GET['cdgcliente']."'");

                if ($vntsContactoSelect->num_rows > 0)
                { $msg_alert = 'El cliente NO fue eliminado por que existen contactos asociadas.'; }
                else
                { $link->query("
                    DELETE FROM vntscliente
                    WHERE cdgcliente = '".$_GET['cdgcliente']."' AND
                      sttcliente = '0'");
                  
                  if ($link->affected_rows > 0)
                  { $msg_alert = 'El cliente fue ELIMINADO satisfactoriamente.'; }
                  else
                  { $msg_alert = 'El cliente NO fue eliminado.'; }
                }
              }
            }
          }
        }
      }
    }

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) != 'rw')
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
      else
      { $vntsClienteSelect = $link->query("
          SELECT * FROM vntscliente
           WHERE cdgcliente = '".$vntsCliente_cdgcliente."' OR idcliente = '".$vntsCliente_idcliente."'");

        if ($vntsClienteSelect->num_rows > 0)
        { if ($vntsCliente_cdgcliente != '')
          { $link->query("
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
          } else
          { $link->query("
              UPDATE vntscliente
                 SET cliente = '".$vntsCliente_cliente."',
                     domicilio = '".$vntsCliente_domicilio."',
                     colonia = '".$vntsCliente_colonia."',
                     cdgpostal = '".$vntsCliente_cdgpostal."',
                     cdgciudad = '".$vntsCliente_cdgciudad."',
                     telefono = '".$vntsCliente_telefono."'
               WHERE idcliente = '".$vntsCliente_idcliente."' AND
                     sttcliente = '1'"); }

          if ($link->affected_rows > 0)
          { $msg_alert = 'El cliente fue ACTUALIZADO satisfactoriamente.'; }
          else
          { $msg_alert = 'El cliente NO fue actualizado.\nNo presento cambios o su status no permite modificaciones'; }
        } else
        { for ($item = 1000; $item < 10000; $item++)
          { $vntsCliente_cdgcliente = str_pad($item,4,'0',STR_PAD_LEFT);

            $link->query("
              INSERT INTO vntscliente
                (idcliente, cliente, domicilio, colonia, cdgpostal, cdgciudad, telefono, cdgcliente)
              VALUES
                ('".$vntsCliente_idcliente."','".$vntsCliente_cliente."','".$vntsCliente_domicilio."','".$vntsCliente_colonia."','".$vntsCliente_cdgpostal."','".$vntsCliente_cdgciudad."','".$vntsCliente_telefono."','".$vntsCliente_cdgcliente."')");

            if ($link->affected_rows > 0)
            { $msg_alert = 'El cliente fue INSERTADO satisfactoriamente.'; 
              break; }
          }
        }
      }

      $vntsCliente_cdgcliente = ''; }

    // Generar combo con estados y ciudades
    $mapaEstadoSelect = $link->query("
      SELECT * FROM mapaestado
       WHERE (sttestado = '1' OR sttestado = '9')
    ORDER BY estado");
    
    if ($mapaEstadoSelect->num_rows > 0)
    { $item = 0;    
      while ($regMapaEstado = $mapaEstadoSelect->fetch_object())
      { $item++;

        $mapaEstado_idestado[$item] = $regMapaEstado->idestado;
        $mapaEstado_estado[$item] = $regMapaEstado->estado;
        $mapaEstado_cdgestado[$item] = $regMapaEstado->cdgestado; 
        
        $mapaCiudadSelect = $link->query("
          SELECT * FROM mapaciudad
           WHERE cdgestado = '".$mapaEstado_cdgestado[$item]."' AND
                (sttciudad = '1' OR sttciudad = '9')
        ORDER BY ciudad");
            
        if ($mapaCiudadSelect->num_rows > 0)
        { $subItem = 0;
          while ($regMapaCiudad = $mapaCiudadSelect->fetch_object())
          { $subItem++;

            $mapaCiudad_idciudad[$item][$subItem] = $regMapaCiudad->idciudad;
            $mapaCiudad_ciudad[$item][$subItem] = $regMapaCiudad->ciudad;
            $mapaCiudad_cdgciudad[$item][$subItem] = $regMapaCiudad->cdgciudad;

            $mapaCiudads_ciudad[$regMapaCiudad->cdgciudad] = $regMapaCiudad->ciudad; }
        
          $nCiudades[$item] = $mapaCiudadSelect->num_rows; }
      }

      $nEstados = $mapaEstadoSelect->num_rows; }
    
    // Catalogo de clientes
    if ($_POST['chk_vertodo'])
    { $vertodo = 'checked'; 

      $vntsClienteSelect = $link->query("
        SELECT * FROM vntscliente
      ORDER BY sttcliente DESC,
               cliente");
    } else
    { $vntsClienteSelect = $link->query("
        SELECT * FROM vntscliente
         WHERE sttcliente = '1'
      ORDER BY cliente"); }

    if ($vntsClienteSelect->num_rows > 0)
    { $item = 0;
      while ($regVntsCliente = $vntsClienteSelect->fetch_object())
      { $item++;

        $vntsClientes_idcliente[$item] = $regVntsCliente->idcliente;
        $vntsClientes_cliente[$item] = $regVntsCliente->cliente;
        $vntsClientes_telefono[$item] = $regVntsCliente->telefono;
        $vntsClientes_cdgcliente[$item] = $regVntsCliente->cdgcliente;
        $vntsClientes_sttcliente[$item] = $regVntsCliente->sttcliente; }

      $nClientes = $vntsClienteSelect->num_rows; }

    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="vntsCliente.php" />
          <input type="hidden" id="hidden_cdgcliente" name="hidden_cdgcliente" value="'.$vntsCliente_cdgcliente.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Clientes</label>
          </article>
          <input type="checkbox" id="chk_vertodo" name="chk_vertodo" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <!--<a href="ayuda.php#Clientes">'.$_help_blue.'</a>-->

          <section class="subbloque">
            <article>
              <label>Identificador</label><br>
              <input type="text" id="textIdCliente" name="textIdCliente" value="'.$vntsCliente_idcliente.'" placeholder="RFC" autofocus required />
            </article>
            
            <article>
              <label>Nombre</label><br>
              <input type="text" id="textCliente" name="textCliente" value="'.$vntsCliente_cliente.'" placeholder="Razón social" required />
            </article>

            <article>
              <label>Domicilio</label><br>
              <input type="text" id="textDomicilio" name="textDomicilio" value="'.$vntsCliente_domicilio.'" placeholder="Calle y número" required />
            </article>

            <article>
              <label>Colonia</label><br>
              <input type="text" id="textColonia" name="textColonia" value="'.$vntsCliente_colonia.'" placeholder="Colonia" required />
            </article>

            <article>
              <a href="/sm_sistema/mapaCiudad.php?cdgciudad='.$vntsCliente_cdgciudad.'"><label>Ciudad</label></a><br>
              <select id="slctCdgCiudad" name="slctCdgCiudad" onchange="document.formulario.submit()" required >
                <option value="">-Ciudad-</option>';

    for ($item = 1; $item <= $nEstados; $item++)
    { echo '
                <optgroup label="'.$mapaEstado_estado[$item].'">';

      for ($subItem = 1; $subItem <= $nCiudades[$item]; $subItem++)
      { echo '
                  <option value="'.$mapaCiudad_cdgciudad[$item][$subItem].'"';

        if ($vntsCliente_cdgciudad == $mapaCiudad_cdgciudad[$item][$subItem])
        { echo ' selected="selected"'; }

        echo '>'.$mapaCiudad_ciudad[$item][$subItem].', '.$mapaEstado_idestado[$item].'</option>'; } 

      echo '
                </optgroup>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Código postal</label><br>
              <input type="text" id="textCdgPostal" name="textCdgPostal" value="'.$vntsCliente_cdgpostal.'" placeholder="zipcode" required />
            </article>

            <article>
              <label>Teléfono</label><br>
              <input type="text" id="textTelefono" name="textTelefono" value="'.$vntsCliente_telefono.'" placeholder="00 (000) 000 0000" required />            
            </article>
            
            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nClientes > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de clientes</label>
        </article>
        <label>[<b>'.$nClientes.'</b>] Encontrado(s)</label>';

      for ($item = 1; $item <= $nClientes; $item++)
      { echo '
        <section class="listado">
          <article>';

        if ((int)$vntsClientes_sttcliente[$item] > 0)
        { echo '
            <a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'">'.$_search.'</a>
            <a href="vntsClienteCntc.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'">'.$_user_group.'</a>
            <a href="vntsSucursal.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'">'.$_forder_open.'</a>      
            <a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'&proceso=update">'.$_power_blue.'</a>'; 
        } else
        { echo '
            <a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="vntsCliente.php?cdgcliente='.$vntsClientes_cdgcliente[$item].'&proceso=update">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article style="text-align:right">
            <label>Identificador</label><br/>
            <label>Cliente</label><br/>
            <label>Teléfono</label>
          </article>

          <article>
            <label class="textId">'.$vntsClientes_idcliente[$item].'</label><br/>
            <label><b>'.$vntsClientes_cliente[$item].'</b></label><br/>
            <label class="textNOmbre">'.$vntsClientes_telefono[$item].'</label>
          </article>
        </section>'; }

      echo'
      </div>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>

    </div>
  </body>
</html>
