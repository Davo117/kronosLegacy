<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Sucursales por cliente</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40110';
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
        <form id="login" action="vntsSucursal.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; } 
    
    $vntsSucursal_cdgsucursal = trim($_POST['hidden_cdgsucursal']);

    $vntsSucursal_cdgcliente = $_POST['slctCdgCliente'];
    $vntsSucursal_idsucursal = trim($_POST['textIdSucursal']);
    $vntsSucursal_sucursal = trim($_POST['textSucursal']);
    $vntsSucursal_domicilio = trim($_POST['textDomicilio']);
    $vntsSucursal_colonia = trim($_POST['textColonia']);
    $vntsSucursal_cdgpostal = trim($_POST['textCdgPOstal']);
    $vntsSucursal_cdgciudad = $_POST['slctCdgCiudad'];
    $vntsSucursal_telefono = trim($_POST['textTelefono']);
    $vntsSucursal_transporte = trim($_POST['textTransporte']);

    if ($_POST['slctCdgCiudad']) { $vntsSucursal_cdgciudad = $_POST['slctCdgCiudad']; }

    if ($_GET['cdgcliente']) { $vntsSucursal_cdgcliente = $_GET['cdgcliente']; }

    if ($_GET['cdgsucursal'])
    { if (substr($sistModulo_permiso,0,1) != 'r')
      { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }         
      else
      { $vntsSucursalSelect = $link->query("
          SELECT * FROM vntssucursal
           WHERE cdgsucursal = '".$_GET['cdgsucursal']."'");
          
        if ($vntsSucursalSelect->num_rows >= 1)
        { $regVntsSucursal = $vntsSucursalSelect->fetch_object();

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
          { if (substr($sistModulo_permiso,0,2) != 'rw')
            { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; }
            else
            { if ($vntsSucursal_sttsucursal == '1')
              { $vntsSucursal_newsttsucursal = '0'; }
              
              if ($vntsSucursal_sttsucursal == '0')
              { $vntsSucursal_newsttsucursal = '1'; }
              
              if ($vntsSucursal_newsttsucursal != '')
              { $link->query("
                  UPDATE vntssucursal 
                     SET sttsucursal = '".$vntsSucursal_newsttsucursal."'
                   WHERE cdgsucursal = '".$_GET['cdgsucursal']."'");
                  
                if ($link->affected_rows > 0)
                { $msg_alert = 'La sucursal fue ACTUALIZADA satisfactoriamente.'; }
                else
                { $msg_alert = 'La sucursal NO fue ACTUALIZADA.'; }
                
              } else
              { $msg_alert = 'La sucursal que seleccionaste, no tiene permitido cambiar de status.'; }
            }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }           
            else
            { $vntsSucursalSelect = $link->query("
                SELECT * FROM vntsembarque
                 WHERE cdgsucursal = '".$_GET['cdgsucursal']."'");
              
              if ($vntsSucursalSelect->num_rows > 0)
              { $msg_alert = '<La sucursal NO fue eliminada por que existen embarques asociados.'; }
              else
              { $vntsContactoSelect = $link->query("
                SELECT * FROM vntscontacto
                 WHERE cdgcliente = '".$_GET['cdgsucursal']."'");

                if ($vntsContactoSelect->num_rows > 0)
                { $msg_alert = 'El cliente NO fue eliminado por que existen contactos asociadas.'; }
                else
                { $link->query("
                    DELETE FROM vntssucursal
                     WHERE cdgsucursal = '".$_GET['cdgsucursal']."' AND
                           sttsucursal = '0'");
                
                  if ($link->affected_rows > 0)
                  { $msg_alert = 'La sucursal fue ELIMINADA satisfactoriamente.'; }
                  else
                  { $msg_alert = 'La sucursal NO fue eliminada.'; }
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
      { $vntsSucursalSelect = $link->query("
          SELECT * FROM vntssucursal
           WHERE cdgsucursal = '".$vntsSucursal_cdgsucursal."' OR idsucursal = '".$vntsSucursal_idsucursal."'");

        if ($vntsSucursalSelect->num_rows > 0)
        { if ($vntsSucursal_cdgsucursal != '')
          { $link->query("
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
          } else
          { $link->query("
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
                     sttsucursal = '1'"); }

          if ($link->affected_rows > 0)
          { $msg_alert = 'La sucursal fue ACTUALIZADO satisfactoriamente.'; }
          else
          { $msg_alert = 'La sucursal NO fue actualizado.\nNo presento cambios o su status no permite modificaciones'; }          
        } else
        { for ($titem = 1000; $titem < 10000; $titem++)
          { $vntsSucursal_cdgsucursal = $vntsSucursal_cdgcliente.str_pad($titem,4,'0',STR_PAD_LEFT);

            $link->query("
              INSERT INTO vntssucursal
                (cdgcliente, idsucursal, sucursal, domicilio, colonia, cdgpostal, cdgciudad, telefono, transporte, cdgsucursal)
              VALUES
                ('".$vntsSucursal_cdgcliente."','".$vntsSucursal_idsucursal."','".$vntsSucursal_sucursal."','".$vntsSucursal_domicilio."','".$vntsSucursal_colonia."','".$vntsSucursal_cdgpostal."','".$vntsSucursal_cdgciudad."','".$vntsSucursal_telefono."','".$vntsSucursal_transporte."','".$vntsSucursal_cdgsucursal."')");

            if ($link->affected_rows > 0)
            { $msg_alert = 'La sucursal fue INSERTADA satisfactoriamente.'; 
              break; }
          }
        }
      } 

      $vntsSucursal_cdgsucursal = ''; }

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
            $mapaCiudad_cdgciudad[$item][$subItem] = $regMapaCiudad->cdgciudad; }
        
          $nCiudades[$item] = $mapaCiudadSelect->num_rows; }
      }

      $nEstados = $mapaEstadoSelect->num_rows; }
    
    // Combo de clientes
    $vntsClienteSelect = $link->query("
      SELECT * FROM vntscliente
       WHERE sttcliente = '1'
    ORDER BY sttcliente DESC,
             cliente");

    if ($vntsClienteSelect->num_rows > 0)
    { $item = 0;
      while ($regVntsCliente = $vntsClienteSelect->fetch_object())
      { $item++;

        $vntsClientes_idcliente[$item] = $regVntsCliente->idcliente;
        $vntsClientes_cliente[$item] = $regVntsCliente->cliente;
        $vntsClientes_cdgcliente[$item] = $regVntsCliente->cdgcliente;
        $vntsClientes_sttcliente[$item] = $regVntsCliente->sttcliente; }
        
      $nClientes = $vntsClienteSelect->num_rows; }

    // Catálogo de sucursales por cliente
    if ($_POST['chk_vertodo'])
    { $vertodo = 'checked'; 

      $vntsSucursalSelect = $link->query("
        SELECT * FROM vntssucursal
         WHERE cdgcliente = '".$vntsSucursal_cdgcliente."' 
      ORDER BY sttsucursal DESC,
               sucursal");
    } else
    { $vntsSucursalSelect = $link->query("
        SELECT * FROM vntssucursal
         WHERE cdgcliente = '".$vntsSucursal_cdgcliente."' AND
               sttsucursal = '1'
      ORDER BY sucursal"); }

    if ($vntsSucursalSelect->num_rows > 0)
    { $item = 0;
      while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
  	  { $item++;

	  	  $vntsSucursales_idsucursal[$item] = $regVntsSucursal->idsucursal;
	  	  $vntsSucursales_sucursal[$item] = $regVntsSucursal->sucursal;
        $vntsSucursales_telefono[$item] = $regVntsSucursal->telefono;
  	    $vntsSucursales_cdgsucursal[$item] = $regVntsSucursal->cdgsucursal;
        $vntsSucursales_sttsucursal[$item] = $regVntsSucursal->sttsucursal; }
        
      $nSucursales = $vntsSucursalSelect->num_rows; }
    
    echo '
      <div class="bloque">
        <form id="formulario" name="formulario" method="POST" action="vntsSucursal.php" />
          <input type="hidden" id="hidden_cdgsucursal" name="hidden_cdgsucursal" value="'.$vntsSucursal_cdgsucursal.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Sucursales por cliente</label>
          </article>
          <input type="checkbox" id="chk_vertodo" name="chk_vertodo" onclick="document.formulario.submit()" '.$vertodo.'><label>ver todo</label>
          <!--<a href="ayuda.php#Sucursales">'.$_help_blue.'</a>-->

          <section class="subbloque">
            <article>
              <a href="vntsCliente.php?cdgcliente='.$vntsSucursal_cdgcliente.'"><label>Cliente</label></a><br>
              <select id="slctCdgCliente" name="slctCdgCliente" onchange="document.formulario.submit()" required>
                <option value="">-Clientes-</option>';

    for ($item = 1; $item <= $nClientes; $item++)
    { echo '
                <option value="'.$vntsClientes_cdgcliente[$item].'"';

      if ($vntsSucursal_cdgcliente == $vntsClientes_cdgcliente[$item])
      { echo ' selected="selected"'; }

      echo '>'.$vntsClientes_cliente[$item].'</option>'; } 

    echo '
              </select>
            </article>

            <article>
              <label>Identificador</label><br>
              <input type="text" id="textIdSucursal" name="textIdSucursal" value="'.$vntsSucursal_idsucursal.'" placeholder="Código" required />
            </article>

            <article>
              <label>Nombre</label><br>
              <input type="text" id="textSucursal" name="textSucursal" value="'.$vntsSucursal_sucursal.'" placeholder="Sucursal" required />
            </article>

            <article>
              <label>Domicilio</label><br>
              <input type="text" id="textDomicilio" name="textDomicilio" value="'.$vntsSucursal_domicilio.'" placeholder="Calle y número" required />
            </article>

            <article>
              <label>Colonia</label><br>
              <input type="text" id="textColonia" name="textColonia" value="'.$vntsSucursal_colonia.'" placeholder="Colonia" required />
            </article>

            <article>
              <a href="/sm_sistema/mapaCiudad.php?cdgciudad='.$vntsSucursal_cdgciudad.'"><label>Ciudad</label></a><br>
              <select id="slctCdgCiudad" name="slctCdgCiudad" onchange="document.formulario.submit()" required >
                <option value="">-Ciudad-</option>';

    for ($item = 1; $item <= $nEstados; $item++)
    { echo '
                <optgroup label="'.$mapaEstado_estado[$item].'">';

      for ($subItem = 1; $subItem <= $nCiudades[$item]; $subItem++)
      { echo '
                  <option value="'.$mapaCiudad_cdgciudad[$item][$subItem].'"';

        if ($vntsSucursal_cdgciudad == $mapaCiudad_cdgciudad[$item][$subItem])
        { echo ' selected="selected"'; }

        echo '>'.$mapaCiudad_ciudad[$item][$subItem].', '.$mapaEstado_idestado[$item].'</option>'; } 

      echo '
                </optgroup>'; }

    echo '
              </select>
            </article>

            <article>
              <label>Código postal</label><br>
              <input type="text" id="textCdgPOstal" name="textCdgPOstal" value="'.$vntsSucursal_cdgpostal.'" placeholder="zipcode" required />
            </article>

            <article>
              <label>Teléfono</label><br>
              <input type="text" id="textTelefono" name="textTelefono" value="'.$vntsSucursal_telefono.'" placeholder="00 (000) 000 0000" required />            
            </article>

            <article>
              <label>Transporte</label><br>
              <input type="text" id="textTransporte" name="textTransporte" value="'.$vntsSucursal_transporte.'" placeholder="Camión" required />            
            </article>

            <article><br>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>  
      </div>';
    
      if ($nSucursales > 0)
      { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Catálogo de sucursales por cliente</label>
        </article>
        <label>[<b>'.$nSucursales.'</b>] Encontrado(s)</label>';
    
    for ($item = 1; $item <= $nSucursales; $item++)
    { echo '
        <section class="listado">
          <article>';

      if ((int)$vntsSucursales_sttsucursal[$item] > 0)
      { echo '
            <a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'">'.$_search.'</a></td>
            <a href="vntsSucursalCntc.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'">'.$_user_group.'</a></td>
            <a href="vntsOC.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'">'.$_shopping_cart.'</a></td>
            <a href="vntsEmbarque.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'">'.$_delivery.'</a></td>
            <a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'&proceso=update">'.$_power_blue.'</a></td></tr>'; 
      } else
      { echo '
            <a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'&proceso=delete">'.$_recycle_bin.'</a></td>
            <a href="vntsSucursal.php?cdgsucursal='.$vntsSucursales_cdgsucursal[$item].'&proceso=update">'.$_power_black.'</a></td></tr>'; }

        echo '
          </article>

          <article style="text-align:right">
            <label>Identificador</label><br/>
            <label>Sucursal</label><br/>
            <label>Teléfono</label>
          </article>

          <article>
            <label class="textId">'.$vntsSucursales_idsucursal[$item].'</label><br/>
            <label><b>'.$vntsSucursales_sucursal[$item].'</b></label><br/>
            <label class="textNOmbre">'.$vntsSucursales_telefono[$item].'</label>
          </article>
        </section>'; }

    echo '
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
