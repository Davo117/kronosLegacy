<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Ordenes de compra</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40200';
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
        <form id="login" action="vntsOC.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 

      exit; }

    $vntsOC_cdgoc = trim($_POST['hddnCdgOC']);

    if ($_GET['cdgsucursal'])
    { $vntsOC_cdgsucursal = $_GET['cdgsucursal']; }
    else
    { $vntsOC_cdgsucursal = $_POST['slctCdgSucursal']; }

    if ($_POST['text_oc']) { $vntsOC_oc = $_POST['text_oc']; }    
    if ($_POST['dateFchDocumento']) { $vntsOC_fchdocumento = $_POST['dateFchDocumento']; }
    if ($_POST['dateFchRecepcion']) { $vntsOC_fchrecepcion = $_POST['dateFchRecepcion']; }

    $vntsOC_fchdocumento = ValidarFecha($vntsOC_fchdocumento);
    $vntsOC_fchrecepcion = ValidarFecha($vntsOC_fchrecepcion);

    if ($_POST['bttnSalvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if($vntsOC_cdgsucursal != '')
        { if($vntsOC_oc != '')
          { // Verificar si el registro esta cargado
            if ($vntsOC_cdgoc != '')
            { $vntsOCSelect = $link->query("
                SELECT * FROM vntsoc
                 WHERE cdgoc = '".$vntsOC_cdgoc."'"); 
            } else
            { $vntsOCSelect = $link->query("
                SELECT * FROM vntsoc 
                 WHERE cdgsucursal = '".$vntsOC_cdgsucursal."' AND
                       oc = '".$vntsOC_oc."'"); }

            if ($vntsOCSelect->num_rows == 0)
            { for ($id = 1; $id <= 999; $id++)
              { $vntsOC_cdgoc = date('ymd').str_pad($id,3,'0',STR_PAD_LEFT);

                if ($id >= 1000)
                { $msg_alert = 'Los codigos disponibles para documentos se han agotado.'; }
                else
                { $link->query("
                    INSERT vntsoc 
                      (cdgsucursal, oc, fchcaptura, fchdocumento, fchrecepcion, cdgoc)
                    VALUES
                      ('".$vntsOC_cdgsucursal."', '".$vntsOC_oc."', '".date('Y-m-d')."', '".$vntsOC_fchdocumento."', '".$vntsOC_fchrecepcion."', '".$vntsOC_cdgoc."')");

                  if ($link->affected_rows > 0)
                  { $msg_alert = 'La orden de compra fue insertada.'; 

                    break; }
                }
              }
            } else
            { if ($vntsOC_cdgoc != '')
              { $link->query("
                  UPDATE vntsoc 
                     SET oc = '".$vntsOC_oc."',
                         fchdocumento = '".$vntsOC_fchdocumento."',
                         fchrecepcion = '".$vntsOC_fchrecepcion."'
                   WHERE cdgoc = '".$vntsOC_cdgoc."'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'La Orden de Compra fue ACTUALIZADA.';
                } else
                { $msg_alert = 'La Orden de Compra NO fue actualizada.'; }
              } else
              { $msg_alert = 'La Orden de Compra ya existe, debe ser cargada previamente para ser modificada.'; }
            }

            $vntsOC_cdgoc = '';

          } else
          { $msg_alert = 'Es necesario indicar la referencia de O.C.'; }
        } else
        { $msg_alert = 'Es necesario seleccionar una sucursal.'; }
      } else
      { $msg_alert = 'No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgoc'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { 
        $vntsOCSelect = $link->query("
          SELECT * FROM vntsoc
          WHERE cdgoc = '".$_GET['cdgoc']."'");

        if ($vntsOCSelect->num_rows >= 1)
        { $regVntsOC = $vntsOCSelect->fetch_object();

          $vntsOC_cdgsucursal = $regVntsOC->cdgsucursal;
          $vntsOC_oc = $regVntsOC->oc;
          $vntsOC_fchdocumento = $regVntsOC->fchdocumento;
          $vntsOC_fchrecepcion = $regVntsOC->fchrecepcion;
          $vntsOC_cdgoc = $regVntsOC->cdgoc;
          $vntsOC_sttoc = $regVntsOC->sttoc;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsOC_sttoc == '1')
              { $vntsOC_newsttoc = '0'; }
              
              if ($vntsOC_sttoc == '0')
              { $vntsOC_newsttoc = '1'; }
              
              if ($vntsOC_newsttoc != '')
              { $link->query("
                  UPDATE vntsoc
                     SET sttoc = '".$vntsOC_newsttoc."'
                   WHERE cdgoc = '".$vntsOC_cdgoc."'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'La Orden de Compra fue ACTUALIZADA satisfactoriamente.'; }
                else
                { $msg_alert = 'La Orden de Compra NO fue ACTUALIZADA.'; }
              } else
              { $msg_alert = 'La Orden de Compra que seleccionaste, no tiene permitido cambiar de status.'; }
            } else
            { $msg_alert = 'No cuentas con permisos de reescritura en este modulo.'; }
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $vntsOCSelect = $link->query("
                SELECT * FROM vntsoclote
                WHERE cdgoc = '".$vntsOC_cdgoc."'");

              if ($vntsOCSelect->num_rows > 0)
              { $msg_alert = 'La Orden de Compra NO fue eliminada por que existen registros asociados.'; }
              else
              { $link->query("
                  DELETE FROM vntsocreque
                  WHERE cdgoc = '".$vntsOC_cdgoc."'");

                $link->query("
                  DELETE FROM vntsoc
                  WHERE cdgoc = '".$vntsOC_cdgoc."' AND
                    sttoc = '0'");

                if ($link->affected_rows > 0)
                { $msg_alert = 'La Orden de Compra fue ELIMINADA satisfactoriamente.'; }
                else
                { $msg_alert = 'La Orden de Compra NO fue eliminada.'; }
              }
            } else
            { $msg_alert = 'No cuentas con permisos para remover en este modulo.'; }
          }
        }

      } else
      { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $vntsClienteSelect = $link->query("
        SELECT * FROM vntscliente
         WHERE sttcliente = '1'
      ORDER BY cliente");

      if ($vntsClienteSelect->num_rows > 0)
      { $item = 0;

        while ($regVntsCliente = $vntsClienteSelect->fetch_object())
        { $item++;

          $vntsCliente_idcliente[$item] = $regVntsCliente->idcliente;
          $vntsCliente_cliente[$item] = $regVntsCliente->cliente;
          $vntsCliente_cdgcliente[$item] = $regVntsCliente->cdgcliente;

          $vntsSucursalSelect = $link->query("
            SELECT * FROM vntssucursal
             WHERE cdgcliente = '".$vntsCliente_cdgcliente[$item]."' AND
                   sttsucursal = '1'
          ORDER BY sucursal");

          if ($vntsSucursalSelect->num_rows > 0)
          { $subItem = 0;

            while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
            { $subItem++;

              $vntsSucursal_idsucursal[$item][$subItem] = $regVntsSucursal->idsucursal;
              $vntsSucursal_sucursal[$item][$subItem] = $regVntsSucursal->sucursal;
              $vntsSucursal_cdgsucursal[$item][$subItem] = $regVntsSucursal->cdgsucursal;

              $vntsSucursales_sucursal[$regVntsSucursal->cdgsucursal] = $regVntsSucursal->sucursal; }

            $nSUcursales[$item] = $vntsSucursalSelect->num_rows; }
        }

        $nClientes = $vntsClienteSelect->num_rows; }

      if ($_POST['chckVerTodo'])
      { $vertodo = 'checked';
        // Filtrado completo
        $vntsOCSelect = $link->query("
          SELECT * FROM vntsoc
        ORDER BY fchdocumento DESC,
                 sttoc DESC,
                 oc"); 
      } else
      { // Buscar coincidencias
        $vntsOCSelect = $link->query("
          SELECT * FROM vntsoc
           WHERE sttoc = '1'
        ORDER BY fchdocumento DESC,
                 oc"); }

      if ($vntsOCSelect->num_rows > 0)
      { $item = 0;

        while ($regVntsOC = $vntsOCSelect->fetch_object())
        { $item++;

          $vntsOCs_cdgsucursal[$item] = $regVntsOC->cdgsucursal;
          $vntsOCs_oc[$item] = $regVntsOC->oc;
          $vntsOCs_fchdocumento[$item] = $regVntsOC->fchdocumento;
          $vntsOCs_fchrecepcion[$item] = $regVntsOC->fchrecepcion;
          $vntsOCs_cdgoc[$item] = $regVntsOC->cdgoc; 
          $vntsOCs_sttoc[$item] = $regVntsOC->sttoc; }

        $nOCs = $vntsOCSelect->num_rows; }

    } else
    { $msg_alert = 'No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <div class="bloque">
        <form id="formOC" name="formOC" method="POST" action="vntsOC.php" />
          <input type="hidden" id="hddnCdgOC" name="hddnCdgOC" value="'.$vntsOC_cdgoc.'" />

          <article class="subbloque">
            <label class="modulo_nombre">Orden de compra</label>
          </article>
          <input type="checkbox" id="chckVerTodo" name="chckVerTodo" onclick="document.formOC.submit()" '.$vertodo.'><label>ver todo</label>
          <!--<a href="ayuda.php#Ordenes">'.$_help_blue.'</a>--> 

          <section class="subbloque">
            <article>
              <a href="vntsSucursal.php?cdgsucursal='.$vntsOC_cdgsucursal.'">Sucursal</a><br/>
              <select id="slctCdgSucursal" name="slctCdgSucursal" onchange="document.formOC.submit()" required >
                <option value=""> Elije una sucursal </option>';

    for ($item = 1; $item <= $nClientes; $item++)
    { echo '
                <optgroup label="'.$vntsCliente_cliente[$item].'">';
      
      for ($subItem = 1; $subItem <= $nSUcursales[$item]; $subItem++)
      { echo '
                  <option value="'.$vntsSucursal_cdgsucursal[$item][$subItem].'"';

        if ($vntsOC_cdgsucursal == $vntsSucursal_cdgsucursal[$item][$subItem])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$item][$subItem].'</option>'; } 
      
      echo '
                </optgroup>'; }

    echo '
              </select>
            </article>

            <article>
              <label>O.C.</label><br/>
              <input type="text" id="text_oc" name="text_oc" value="'.$vntsOC_oc.'" required />
            </article>

            <article>
              <label>Fecha documento</label><br/>
              <input type="date" id="dateFchDocumento" name="dateFchDocumento" value="'.$vntsOC_fchdocumento.'" required />
            </article>

            <article>
              <label>Fecha recepción</label><br/>
              <input type="date" id="dateFchRecepcion" name="dateFchRecepcion" value="'.$vntsOC_fchrecepcion.'" required />
            </article>

            <article><br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form>
      </div>';

    if ($nOCs > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Ordenes de compra</label>
        </article>
        <label>[<b>'.$nOCs.'</b>] Encontrada(s)</label>'; 

      for ($item=1; $item<=$nOCs; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">';

        if ((int)$vntsOCs_sttoc[$item] > 0)
        { echo '
            <a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'">'.$_search.'</a>
            <a href="vntsOCreque.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'">'.$_exchange.'</a>
            <a href="vntsOClote.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'">'.$_puzzle.'</a>
            <a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'&proceso=update">'.$_power_blue.'</a>'; }
        else
         { echo '
            <a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'&proceso=delete">'.$_recycle_bin.'</a>
            <a href="vntsOC.php?cdgoc='.$vntsOCs_cdgoc[$item].'&vertodo='.$vertodo.'&proceso=update">'.$_power_black.'</a>'; }

      echo '
          </article>

          <article style="text-align:right">
            <label>Sucursal</label><br/>
            <label>O.C:</label><br/>
            <label>Documento</label>
          </article>

          <article>
            <label class="textNombre">'.$vntsSucursales_sucursal[$vntsOCs_cdgsucursal[$item]].'</label><br/>
            <label><b>'.$vntsOCs_oc[$item].'</b></label><br/>
            <label>'.$vntsOCs_fchdocumento[$item].'</label>
          </article>
        </section>'; }

      echo'
      </div>'; }

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
      <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>
    </div>
  </body>
</html>
