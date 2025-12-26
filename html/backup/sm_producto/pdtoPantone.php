<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '20901';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);     

    $pdtoTinta_idtinta = trim($_POST['txt_idtinta']);
    $pdtoTinta_tinta = trim($_POST['txt_tinta']); 
    $pdtoTinta_cdghex = trim($_POST['txt_cdghex']); 
    $pdtoTinta_proveedor = trim($_POST['txt_proveedor']); 
    $pdtoTinta_cdgproveedor = trim($_POST['txt_cdgproveedor']); 
    
  
    if ($_GET['cdgtinta'])
    { $link_mysqli = conectar();
      $pdtoTintaSelect = $link_mysqli->query("
        SELECT * FROM pantoneGoeXplorer
        WHERE cdgpantone = '".$_GET['cdgtinta']."'");
    
      if ($pdtoTintaSelect->num_rows > 0)
      { $regPdtoTinta = $pdtoTintaSelect->fetch_object();

        $pdtoTinta_idtinta = $regPdtoTinta->idtinta;
        $pdtoTinta_tinta = $regPdtoTinta->tinta;
        $pdtoTinta_proveedor = $regPdtoTinta->proveedor;
        $pdtoTinta_cdgproveedor = $regPdtoTinta->cdgproveedor;
        $pdtoTinta_cdghex = $regPdtoTinta->cdghex;
        $pdtoTinta_cdgtinta = $regPdtoTinta->cdgtinta;
        $pdtoTinta_stttinta = $regPdtoTinta->stttinta;

        if ($_GET['proceso'] == 'update')
        { if ($pdtoTinta_stttinta == '1')
          { $pdtoTinta_newstttinta = '0'; }
      
          if ($pdtoTinta_stttinta == '0')
          { $pdtoTinta_newstttinta = '1'; }
        
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE pdtotinta
            SET stttinta = '".$pdtoTinta_newstttinta."' 
            WHERE cdgtinta = '".$pdtoTinta_cdgtinta."'");
          
          if ($link_mysqli->affected_rows > 0)
          { $msg_alert = 'La tinta fue actualizada en su status.'; }
        }

        if ($_GET['proceso'] == 'delete')
        { $link_mysqli = conectar();
          $pdtoImpresionTntSelect = $link_mysqli->query("
            SELECT * FROM pdtoimpresiontnt
            WHERE cdgtinta = '".$pdtoTinta_cdgtinta."'");
          
          if ($pdtoImpresionTntSelect->num_rows > 0)
          { $msg_alert = 'La tinta tiene impresiones ligadas y no pudo ser eliminada.'; }
          else
          { $link_mysqli = conectar();
            $link_mysqli->query("
              DELETE FROM pdtotinta
              WHERE cdgtinta = '".$pdtoTinta_cdgtinta."'
              AND stttinta = '0'");
            
            if ($link_mysqli->affected_rows > 0)
            { $msg_alert = 'La tinta fue eliminada con exito.'; }
            else
            { $msg_alert = 'La tinta no fue eliminada.'; }
          }
        }
      }
    } 

    if ($_POST['btn_submit'])
    { if (strlen($pdtoTinta_idtinta) > 0 AND strlen($pdtoTinta_tinta) > 0)
      { $link_mysqli = conectar();
        $pdtoTintaSelect = $link_mysqli->query("
          SELECT * FROM pdtotinta
          WHERE idtinta = '".$pdtoTinta_idtinta."'");

        if ($pdtoTintaSelect->num_rows > 0)
        {	$regPdtoTinta = $pdtoTintaSelect->fetch_object();

          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE pdtotinta
            SET tinta = '".$pdtoTinta_tinta."',
              proveedor = '".$pdtoTinta_proveedor."',
              cdgproveedor = '".$pdtoTinta_cdgproveedor."',
              cdghex = '".$pdtoTinta_cdghex."'
            WHERE cdgtinta = '".$regPdtoTinta->cdgtinta."' AND 
              stttinta = '1'");

          if ($link_mysqli->affected_rows > 0) 
          { $msg_alert = 'La tinta fue actualizada con exito.'; }
        }
        else
        { for ($cdgtinta = 1; $cdgtinta <= 999999; $cdgtinta++)
          { $pdtoTinta_cdgtinta = str_pad($cdgtinta,6,'0',STR_PAD_LEFT);

            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO pdtotinta
                (idtinta, tinta, proveedor, cdgproveedor, cdghex, cdgtinta)
              VALUES
                ('".$pdtoTinta_idtinta."', '".$pdtoTinta_tinta."', '".$pdtoTinta_proveedor."', '".$pdtoTinta_cdgproveedor."', '".$pdtoTinta_cdghex."', '".$pdtoTinta_cdgtinta."')");

            if ($link_mysqli->affected_rows > 0)
            { $msg_alert = 'La tinta fue insertada con exito.';
              $cdgtinta = 1000000; }
          }
        }
      }
    }

    $link_mysqli = conectar();
    $pdtoTintaSelect = $link_mysqli->query("
      SELECT * FROM pantone
      ORDER BY pagina,
        pantone");
    
    $idTinta = 1;
    while ($regPdtoTinta = $pdtoTintaSelect->fetch_object())
    { $pdtoTintas_idtinta[$idTinta] = $regPdtoTinta->idpantone;
      $pdtoTintas_tinta[$idTinta] = $regPdtoTinta->pantone;      
      $pdtoTintas_pagina[$idTinta] = $regPdtoTinta->pagina;
      
      $pdtoTintas_R[$idTinta] = $regPdtoTinta->R_SOLID;
      $pdtoTintas_RH[$idTinta] = dechex($regPdtoTinta->R_SOLID);
      $pdtoTintas_G[$idTinta] = $regPdtoTinta->G_SOLID;
      $pdtoTintas_GH[$idTinta] = dechex($regPdtoTinta->G_SOLID);
      $pdtoTintas_B[$idTinta] = $regPdtoTinta->B_SOLID;
      $pdtoTintas_BH[$idTinta] = dechex($regPdtoTinta->B_SOLID);

      $pdtoTintas_hexadecimal[$idTinta] = $regPdtoTinta->HTML;
      $pdtoTintas_cdgtinta[$idTinta] = $regPdtoTinta->cdgpantone;

      $idTinta++; }

    $numTintas = $pdtoTintaSelect->num_rows;
    
  echo '
    <form id="frm_pdtotinta" name="frm_pdtotinta" method="POST" action="pdtoPantone.php">
      <table align="center">
        <thead>
          <tr><th colspan="2" align="left">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_idtinta">idTinta</label><br/>
              <input type="text" style="width:120px;" maxlength="24" id="txt_idtinta" name="txt_idtinta" value="'.$pdtoTinta_idtinta.'" title="Identificador de tinta" required/></td>
            <td><label for="lbl_cdghex">Hexadecimal</label><br/>
              <input type="text" style="width:180px;" maxlength="24" id="txt_cdghex" name="txt_cdghex" value="'.$pdtoTinta_cdghex.'" title="Valor hexadecimal del *Pantone" required/></td></tr>
          <tr><td><label for="lbl_tinta">Tinta</label><br/>
              <input type="text" style="width:120px;" maxlength="60" id="txt_tinta" name="txt_tinta" value="'.$pdtoTinta_tinta.'" title="Nombre del *Pantone" required/></td>
            <td><label for="lbl_proveedor">Proveedor</label><br/>
              <input type="text" style="width:180px;" maxlength="60" id="txt_proveedor" name="txt_proveedor" value="'.$pdtoTinta_proveedor.'" title="Proveedor" /></td></tr>
          <tr><td colspan="2"><label for="lbl_cdgproveedor">Tinta Proveedor</label><br/>
              <input type="text" style="width:315px;" maxlength="60" id="txt_cdgproveedor" name="txt_cdgproveedor" value="'.$pdtoTinta_cdgproveedor.'" title="Tinta segun el proveedor" /></td></tr>
        <tbody>
        <tfoot>
          <tr><td align="right" colspan="2"><input type="submit" id="btn_submit" name="btn_submit" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>

      <table align="center">
        <thead>
          <tr align="left">
            <th><label for="lbl_ttlidtinta">idTinta</label></th>
            <th><label for="lbl_ttltinta">Tinta</label></th>
            <th><label for="lbl_ttlcdgproveedor">C&oacute;digo proveedor</label></th>
            <th colspan="2"><label for="lbl_ttlcdghex">Pantone(*hex)</label></th>
            <th colspan="2"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

  if ($numTintas > 0)
  { for ($idTinta=1; $idTinta<=$numTintas; $idTinta++)
    { echo '
          <tr align="center">
            <td align="left"><strong>'.$pdtoTintas_idtinta[$idTinta].'</strong></td>
            <td align="left">'.$pdtoTintas_tinta[$idTinta].'</td>
            <td align="left">'.$pdtoTintas_pagina[$idTinta].' '.$pdtoTintas_hexadecimal[$idTinta].'</td>
            <td bgcolor="#'.$pdtoTintas_hexadecimal[$idTinta].'"></td>
            <td bgcolor="#'.str_pad($pdtoTintas_RH[$idTinta],2,'0',STR_PAD_LEFT).str_pad($pdtoTintas_GH[$idTinta],2,'0',STR_PAD_LEFT).str_pad($pdtoTintas_BH[$idTinta],2,'0',STR_PAD_LEFT).'"</td>
            <td>'.strtoupper(str_pad($pdtoTintas_RH[$idTinta],2,'0',STR_PAD_LEFT).str_pad($pdtoTintas_GH[$idTinta],2,'0',STR_PAD_LEFT).str_pad($pdtoTintas_BH[$idTinta],2,'0',STR_PAD_LEFT)).'</td>
            <td>rgb('.$pdtoTintas_R[$idTinta].','.$pdtoTintas_G[$idTinta].','.$pdtoTintas_B[$idTinta].') #'.$pdtoTintas_RH[$idTinta].$pdtoTintas_GH[$idTinta].$pdtoTintas_BH[$idTinta].'</td>
            <td><a href="pdtoPantone.php?cdgtinta='.$pdtoTintas_cdgtinta[$idTinta].'">'.$png_search.'</a></td></tr>';
    } 
  }

  echo '
        </tbody>
        <tfoot>
          <tr><th colspan="5" align="right">
              <label for="lbl_ppgdatos">Ver todos ['.$numTintas.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

  if ($msg_alert != '')
  { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
?>

  </body>	
</html>