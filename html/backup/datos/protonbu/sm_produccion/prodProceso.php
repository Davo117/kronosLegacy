<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // Captura de valores ////////////////////////////////////////////
  $prodProceso_idproceso = $_POST['txt_idproceso'];
  $prodProceso_proceso = $_POST['txt_proceso'];
  //////////////////////////////////////////////////////////////////

  // Busqueda de registros /////////////////////////////////////////
  if ($_GET['cdgproceso']) 
  { $link_mysqli = conectar();
  	$prodProcesoSelect = $link_mysqli->query("
  	  SELECT * FROM prodproceso 
  	  WHERE cdgproceso = '".$_GET['cdgproceso']."'");

  	if ($prodProcesoSelect->num_rows > 0) 
  	{ $regProdProceso = $prodProcesoSelect->fetch_object();

      $prodProceso_idproceso = $regProdProceso->idproceso;
      $prodProceso_proceso = $regProdProceso->proceso;
      $prodProceso_cdgproceso = $regProdProceso->cdgproceso;
      $prodProceso_sttproceso = $regProdProceso->sttproceso; }

    if ($_GET['proceso'] == 'update') 
    { if ($prodProceso_sttproceso == '1') 
      { $prodProceso_newsttproceso = '0'; }
      
      if ($prodProceso_sttproceso == '0') 
      { $prodProceso_newsttproceso = '1'; }

      if ($prodProceso_newsttproceso != '')
      { $link_mysqli = conectar();
        $link_mysqli->query("
      	  UPDATE prodproceso
      	  SET sttproceso = '".$prodProceso_newsttproceso."'
      	  WHERE cdgproceso = '".$prodProceso_cdgproceso."'");

      	  if ($link_mysqli->affected_rows > 0) 
      	  { $msg_alert = 'El proceso \"'.$prodProceso_proceso.'\" fue actualizado en su status.'; }
      	  else 
      	  { $msg_alert = 'El proceso \"'.$prodProceso_proceso.'\" NO fue afectado.'; } 
      }      
    }

    if ($_GET['proceso'] == 'delete') 
    { $link_mysqli = conectar();
      $prodOperacion = $link_mysqli->query("
      	SELECT * FROM prodoperacion
      	WHERE cdgproceso = '".$prodProceso_cdgproceso."'");

      if ($prodOperacion->num_rows > 0) 
      { $msg_alert = 'El proceso \"'.$prodProceso_proceso.'\" tiene informacion ligada con el catalogo de operaciones, NO fue eliminado'; }
      else 
      { $link_mysqli = conectar();
      	$link_mysqli->query("
      	  DELETE FROM prodproceso
      	  WHERE cdgproceso = '".$prodProceso_cdgproceso."'
      	  AND sttproceso = '0'");

      	if ($link_mysqli->affected_rows > 0) 
      	{ $msg_alert = 'El proceso \"'.$prodProceso_proceso.'\" fue eliminado con exito.'; }
      	else 
      	{ $msg_alert = 'El proceso \"'.$prodProceso_proceso.'\" NO fue eliminado, ya que no se encontro en la base de datos.'; }
      }
    }
  } ////////////////////////////////////////////////////////////////

  // Busqueda de registros /////////////////////////////////////////
  if ($_POST['btn_salvar'])
  { $link_mysqli = conectar();  	
    $prodProcesoSelect = $link_mysqli->query("
      SELECT * FROM prodproceso
      WHERE idproceso = '".$prodProceso_idproceso."'");

    if ($prodProcesoSelect->num_rows > 0)
    { $regProdProceso = $prodProcesoSelect->fetch_object();

      $link_mysqli = conectar();
      $link_mysqli->query("
      	UPDATE prodproceso
      	SET proceso = '".$prodProceso_proceso."'
      	WHERE cdgproceso = '".$regProdProceso->cdgproceso."'
      	AND sttproceso = '1'"); 

      if ($link_mysqli->affected_rows > 0) 
      { $msg_alert = 'El proceso \"'.$prodProceso_idproceso.'\" fue actualizado de \"'.$regProdProceso->proceso.'\" a \"'.$prodProceso_proceso.'\".'; }
      else 
      { $msg_alert = 'El proceso \"'.$prodProceso_idproceso.'\" NO fue actualizado.'; }
    } 
    else 
    { for ($cdgproceso = 1; $cdgproceso <= 100; $cdgproceso++) 
      { $pdtoProceso_cdgproceso = str_pad($cdgproceso,2,'0',STR_PAD_LEFT);

        if ($cdgproceso > 99) 
        { $msg_alert = 'No fue posible insertar un nuevo proceso, el tope ha sido alcanzado.'; } 
        else 
        { $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO prodproceso
              (idproceso, proceso, cdgproceso)
            VALUES
              ('".$prodProceso_idproceso."', '".$prodProceso_proceso."', '".$pdtoProceso_cdgproceso."')");
          
          if ($link_mysqli->affected_rows > 0) 
          { $msg_alert = 'El proceso \"'.$prodProceso_idproceso.'\" fue insertado con exito.'; 
            $cdgproceso = 100; }      
        }
      }
    }
  } ////////////////////////////////////////////////////////////////

  echo '
    <form id="frm_prodproceso" name="frm_prodproceso" method="POST" action="prodProceso.php">
      <table align="center">
        <thead>
          <tr align="left"><th colspan="2">Cat&aacute;logo de procesos</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_idproceso">Proceso</label><br/>
              <input type="text" id="txt_idproceso" name="txt_idproceso" style="width:120px" maxlenght="24" value="'.$prodProceso_idproceso.'" title="Identificador de proceso" autofocus required/></td>
            <td><label for="lbl_proceso">Descripci&oacute;n</label><br/>
              <input type="text" id="txt_proceso" name="txt_proceso" style="width:240px" maxlenght="60" value="'.$prodProceso_proceso.'" title="Descripcion del proceso" required/></td></tr>
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="2"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table><br/>';

  if ($_POST['chk_vertodo'])
  { $vertodo = 'checked'; 

    $link_mysqli = conectar();
    $prodProcesoSelect = $link_mysqli->query("
      SELECT * FROM prodproceso 
      ORDER BY sttproceso DESC,
        cdgproceso"); } 
  else 
  { $link_mysqli = conectar();
    $prodProcesoSelect = $link_mysqli->query("
      SELECT * FROM prodproceso
      WHERE sttproceso >= '1'
      ORDER BY sttproceso DESC,
        cdgproceso"); }

  if ($prodProcesoSelect->num_rows > 0) 
  { $id_proceso = 1;
    while($regProdProceso = $prodProcesoSelect->fetch_object()) 
    { $prodProcesos_idproceso[$id_proceso] = $regProdProceso->idproceso; 
      $prodProcesos_proceso[$id_proceso] = $regProdProceso->proceso; 
      $prodProcesos_cdgproceso[$id_proceso] = $regProdProceso->cdgproceso; 
      $prodProcesos_sttproceso[$id_proceso] = $regProdProceso->sttproceso; 

      $id_proceso++; }

    $numprocesos = $prodProcesoSelect->num_rows; }

  $prodProcesoSelect->close;


  echo '
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="4"><input type="checkbox" name="chk_vertodo" id="chk_vertodo" onclick="document.frm_prodproceso.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th>Proceso</th>
            <th>Descripcion</th>
            <th colspan="4">Operaciones</th></tr>
        </thead>
        <tbody>';

  if ($numprocesos > 0) 
  { for ($id_proceso = 1; $id_proceso <= $numprocesos; $id_proceso++) 
    { echo '
          <tr><td>'.$prodProcesos_idproceso[$id_proceso].'</td>
            <td>'.$prodProcesos_proceso[$id_proceso].'</td>';

      if ((int)$prodProcesos_sttproceso[$id_proceso] > 0) 
      { echo '
            <td><a href="prodProceso.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'">'.$png_search.'</a></td>
            <td><a href="prodOperacion.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'">'.$png_link.'</a></td>
            <td><a href="prodMaquina.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'">'.$png_chip.'</a></td>
            <td><a href="prodProceso.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
      else 
      { echo '
            <td><a href="prodProceso.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2"></td>
            <td><a href="prodProceso.php?cdgproceso='.$prodProcesos_cdgproceso[$id_proceso].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
    }

    unset($prodProcesos_idproceso);
    unset($prodProcesos_proceso);
    unset($prodProcesos_cdgproceso);
    unset($prodProcesos_sttproceso); }    

  echo '
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="6"><label for="lbl_numregistros">['.$numprocesos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

?>

  </body>
</html>