<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  // Captura de valores ////////////////////////////////////////////
  $prodCosto_idcosto = $_POST['txt_idcosto'];
  $prodCosto_costo = $_POST['txt_costo'];
  //////////////////////////////////////////////////////////////////

  // Busqueda de registros /////////////////////////////////////////
  if ($_GET['cdgcosto']) 
  { $link_mysqli = conectar();
  	$prodCostoSelect = $link_mysqli->query("
  	  SELECT * FROM prodcosto 
  	  WHERE cdgcosto = '".$_GET['cdgcosto']."'");

  	if ($prodCostoSelect->num_rows > 0) 
  	{ $regProdCosto = $prodCostoSelect->fetch_object();

      $prodCosto_idcosto = $regProdCosto->idcosto;
      $prodCosto_costo = $regProdCosto->costo;
      $prodCosto_cdgcosto = $regProdCosto->cdgcosto;
      $prodCosto_sttcosto = $regProdCosto->sttcosto; }

    if ($_GET['proceso'] == 'update') 
    { if ($prodCosto_sttcosto == '1') 
      { $prodCosto_newsttcosto = '0'; }
      
      if ($prodCosto_sttcosto == '0') 
      { $prodCosto_newsttcosto = '1'; }

      $link_mysqli = conectar();
      $link_mysqli->query("
      	UPDATE prodcosto
      	SET sttcosto = '".$prodCosto_newsttcosto."'
      	WHERE cdgcosto = '".$prodCosto_cdgcosto."'");

      	if ($link_mysqli->affected_rows > 0) 
      	{ $msg_alert = 'El costo \"'.$prodCosto_costo.'\" fue actualizado en su status.'; }
      	else 
      	{ $msg_alert = 'El costo \"'.$prodCosto_costo.'\" NO fue afectado.'; }
    }

    if ($_GET['proceso'] == 'delete') 
    { $link_mysqli = conectar();
      $prodProcCosto = $link_mysqli->query("
      	SELECT * FROM prodcostoproc
      	WHERE cdgcosto = '".$prodCosto_cdgcosto."'");

      if ($prodProcCosto->num_rows > 0) 
      { $msg_alert = 'El costo \"'.$prodCosto_costo.'\" tiene informacion ligada con el catalogo de mezclas, NO fue eliminado'; }
      else 
      { $link_mysqli = conectar();
      	$link_mysqli->query("
      	  DELETE FROM prodcosto
      	  WHERE cdgcosto = '".$prodCosto_cdgcosto."'
      	  AND sttcosto = '0'");

      	if ($link_mysqli->affected_rows > 0) 
      	{ $msg_alert = 'El costo \"'.$prodCosto_costo.'\" fue eliminado con exito.'; }
      	else 
      	{ $msg_alert = 'El costo \"'.$prodCosto_costo.'\" NO fue eliminado, ya que no se encontro en la base de datos.'; }
      }
    }
  } ////////////////////////////////////////////////////////////////

  // Busqueda de registros /////////////////////////////////////////
  if ($_POST['btn_salvar'])
  { $link_mysqli = conectar();  	
    $prodCostoSelect = $link_mysqli->query("
      SELECT * FROM prodcosto
      WHERE idcosto = '".$prodCosto_idcosto."'");

    if ($prodCostoSelect->num_rows > 0)
    { $regProdCosto = $prodCostoSelect->fetch_object();

      $link_mysqli = conectar();
      $link_mysqli->query("
      	UPDATE prodcosto
      	SET costo = '".$prodCosto_costo."'
      	WHERE cdgcosto = '".$regProdCosto->cdgcosto."'
      	AND sttcosto = '1'"); 

      if ($link_mysqli->affected_rows > 0) 
      { $msg_alert = 'El costo \"'.$prodCosto_idcosto.'\" fue actualizado de \"'.$regProdCosto->costo.'\" a \"'.$prodCosto_costo.'\".'; }
      else 
      { $msg_alert = 'El costo \"'.$prodCosto_idcosto.'\" NO fue actualizado.'; }
    } 
    else 
    { for ($cdgcosto = 1; $cdgcosto <= 10; $cdgcosto++) 
      { if ($cdgcosto > 9) 
        { $msg_alert = 'No fue posible insertar un nuevo costo, el tope ha sido alcanzado.'; } 
        else 
        { $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO prodcosto
              (idcosto, costo, cdgcosto)
            VALUES
              ('".$prodCosto_idcosto."', '".$prodCosto_costo."', '".$cdgcosto."')");
          
          if ($link_mysqli->affected_rows > 0) 
          { $msg_alert = 'El costo \"'.$prodCosto_idcosto.'\" fue insertado con exito.'; 
            $cdgcosto = 10; }      
        }
      }
    }
  } ////////////////////////////////////////////////////////////////

  echo '
    <form id="frm_prodcosto" name="frm_prodcosto" method="POST" action="prodCosto.php">
      <table align="center">
        <thead>
          <tr align="left"><th colspan="2">Cat&aacute;logo de costos</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_idcosto">Costo</label><br/>
              <input type="text" id="txt_idcosto" name="txt_idcosto" style="width:120px" maxlenght="24" value="'.$prodCosto_idcosto.'" title="Identificador de costo" autofocus required/></td>
            <td><label for="lbl_costo">Descripci&oacute;n</label><br/>
              <input type="text" id="txt_costo" name="txt_costo" style="width:240px" maxlenght="60" value="'.$prodCosto_costo.'" title="Descripcion del costo" required/></td></tr>
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="2"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table><br/>';

  if ($_POST['chk_vertodo'])
  { $vertodo = 'checked'; 

    $link_mysqli = conectar();
    $prodCostoSelect = $link_mysqli->query("
      SELECT * FROM prodcosto 
      ORDER BY sttcosto DESC,
        idcosto"); } 
  else 
  { $link_mysqli = conectar();
    $prodCostoSelect = $link_mysqli->query("
      SELECT * FROM prodcosto
      WHERE sttcosto = '1'
      ORDER BY idcosto"); }

  if ($prodCostoSelect->num_rows > 0) 
  { $id_costo = 1;
    while($regProdCosto = $prodCostoSelect->fetch_object()) 
    { $prodCostos_idcosto[$id_costo] = $regProdCosto->idcosto; 
      $prodCostos_costo[$id_costo] = $regProdCosto->costo; 
      $prodCostos_cdgcosto[$id_costo] = $regProdCosto->cdgcosto; 
      $prodCostos_sttcosto[$id_costo] = $regProdCosto->sttcosto; 

      $id_costo++; }

    $numcostos = $prodCostoSelect->num_rows; }

  $prodCostoSelect->close;


  echo '
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="3"><input type="checkbox" name="chk_vertodo" id="chk_vertodo" onclick="document.frm_prodcosto.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th>Costo</th>
            <th>Descripcion</th>
            <th colspan="3">Operacion</th></tr>
        </thead>
        <tbody>';

  if ($numcostos > 0) 
  { for ($id_costo = 1; $id_costo <= $numcostos; $id_costo++) 
    { echo '
          <tr><td>'.$prodCostos_idcosto[$id_costo].'</td>
            <td>'.$prodCostos_costo[$id_costo].'</td>';

      if ((int)$prodCostos_sttcosto[$id_costo] > 0) 
      { echo '
            <td><a href="prodCosto.php?cdgcosto='.$prodCostos_cdgcosto[$id_costo].'">'.$png_search.'</a></td>
            <td><a href="prodCosto.php?cdgcosto='.$prodCostos_cdgcosto[$id_costo].'">'.$png_link.'</a></td>
            <td><a href="prodCosto.php?cdgcosto='.$prodCostos_cdgcosto[$id_costo].'&proceso=update">'.$png_power_blue.'</a></td></tr>'; }
      else 
      { echo '
            <td><a href="prodCosto.php?cdgcosto='.$prodCostos_cdgcosto[$id_costo].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td></td>
            <td><a href="prodCosto.php?cdgcosto='.$prodCostos_cdgcosto[$id_costo].'&proceso=update">'.$png_power_black.'</a></td></tr>'; }
    }

    unset($prodCostos_idcosto);
    unset($prodCostos_costo);
    unset($prodCostos_cdgcosto);
    unset($prodCostos_sttcosto); }    

  echo '
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="5"><label for="lbl_numregistros">['.$numcostos.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }    

?>

  </body>
</html>