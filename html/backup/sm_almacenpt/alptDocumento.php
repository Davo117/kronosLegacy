<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60101';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $prodDocumento_iddocumento = $_POST['text_iddocumento'];
    $prodDocumento_fchdocumento = $_POST['text_fchdocumento'];
    $prodDocumento_documento = $_POST['textarea_documento'];

    if ($prodDocumento_fchdocumento == '')
    { $prodDocumento_subcdgdocumento = date('ymd'); 
      $prodDocumento_fchdocumento = date('Y-m-d'); }
    else
    { $fchdocumento = str_replace("-", "", $prodDocumento_fchdocumento);

      $dia = str_pad(substr($fchdocumento,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchdocumento,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchdocumento,2,2),2,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $prodDocumento_subcdgdocumento = $ano.$mes.$dia;
        $prodDocumento_fchdocumento = '20'.$ano.'-'.$mes.'-'.$dia; }
      else
      { $prodDocumento_subcdgdocumento = date('ymd'); 
        $prodDocumento_fchdocumento = date('Y-m-d'); }
    }

    if ($_GET['cdgdocumento'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $prodDocumento_cdgdocumento = $_GET['cdgdocumento'];
        
        $link_mysqli = conectar();
        $prodDocumentoSelect = $link_mysqli->query("
          SELECT * FROM proddocumento
          WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");
          
        if ($prodDocumentoSelect->num_rows > 0)
        { $regProdDocumento = $prodDocumentoSelect->fetch_object();        
    
          $prodDocumento_iddocumento = $regProdDocumento->iddocumento;
          $prodDocumento_fchdocumento = $regProdDocumento->fchdocumento;
          $prodDocumento_documento = $regProdDocumento->documento;
          $prodDocumento_cdgdocumento = $regProdDocumento->cdgdocumento;
          $prodDocumento_sttdocumento = $regProdDocumento->sttdocumento; 
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($prodDocumento_sttdocumento == '0')
              { $prodDocumento_newsttdocumento = '1'; }
            
              if ($prodDocumento_sttdocumento == '1')
              { $prodDocumento_newsttdocumento = '0'; }
              
              if ($prodDocumento_newsttdocumento != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE proddocumento
                  SET sttdocumento = '".$prodDocumento_newsttdocumento."'
                  WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' fue actualizado exitosamente en su status."; }
                else
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' NO fue actualizado en su status."; } 
              } 
              else
              { $msg_alert = 'Este documento no puede ser afectado en su status.'; }
              
            } else
            { $msg_alert = $msg_norewrite; }
          }
          
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $prodSubLoteSelect = $link_mysqli->query("
                SELECT * FROM prodbobina
                WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");
              
              if ($prodSubLoteSelect->num_rows > 0)  
              { $msg_alert = "El documento '".$prodDocumento_iddocumento."' NO fue desechado por que no esta vacio."; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM proddocumento                  
                  WHERE (cdgdocumento = '".$prodDocumento_cdgdocumento."' AND
                    sttdocumento = '0')");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' fue desechado exitosamente."; }
                else
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' NO fue desechado."; }               
              }                
            } else
            { $msg_alert = $msg_nodelete; }
          }          
        }
        else
        { $msg_alert = 'El documento al que se hace referencia no existe, o fue eliminado.'; }
      } else
      { $msg_alert = $msg_noread; }
    }

    if ($_POST['submit_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($prodDocumento_iddocumento != '')
        { if ($prodDocumento_fchdocumento != '')
          { if ($prodDocumento_documento != '')
            { $link_mysqli = conectar();
              $prodDocumentoSelect = $link_mysqli->query("
                SELECT * FROM proddocumento
                WHERE iddocumento = '".$prodDocumento_iddocumento."'");
              
              if ($prodDocumentoSelect->num_rows > 0)
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE proddocumento
                  SET documento = '".$prodDocumento_documento."'
                  WHERE (iddocumento = '".$prodDocumento_iddocumento."' AND
                    sttdocumento = '1')");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' fue actualizado exitosamente."; }
                else
                { $msg_alert = "El documento '".$prodDocumento_iddocumento."' NO fue actualizado."; }                  
              } else
              { for ($id_indice = 1; $id_indice <= 999; $id_indice++) 
                { $prodDocumento_cdgdocumento = $prodDocumento_subcdgdocumento.str_pad($id_indice,3,'0',STR_PAD_LEFT);
                
                  $link_mysqli = conectar();
                  $prodDocumentoSelect = $link_mysqli->query("
                    SELECT * FROM proddocumento
                    WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");
                    
                  if ($prodDocumentoSelect->num_rows == 0)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      INSERT INTO proddocumento
                        (iddocumento, fchdocumento, documento, cdgdocumento)
                      VALUES
                        ('".$prodDocumento_iddocumento."', '".$prodDocumento_fchdocumento."', '".$prodDocumento_documento."', '".$prodDocumento_cdgdocumento."')");
                        
                    if ($link_mysqli->affected_rows > 0)
                    { $msg_alert = "El documento '".$prodDocumento_iddocumento."' fue insertado exitosamente."; }
                    else
                    { $msg_alert = "El documento '".$prodDocumento_iddocumento."' NO fue insertado."; }
                    
                    $id_indice = 1000; }
                  
                  $prodDocumentoSelect->close;          
                }                  
              }
            } else
            { $msg_alert = 'Es necesario indicar alguna descripcion o referencia del documeno.';
              $autofocus_documento = 'autofocus'; }                  
          } else
          { $msg_alert = 'Es necesario la fecha del documento.';
            $autofocus_fchdocumento = 'autofocus'; }               
        } else
        { $msg_alert = 'Es necesario indicar el folio del documento.';
          $autofocus_iddocumento = 'autofocus'; }        
      } else
      { $msg_alert = $msg_nowrite; }      
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['checkbox_vertodo'])
      { $vertodo = 'checked';

        $link_mysqli = conectar();
        $prodDocumentoSelect = $link_mysqli->query("
          SELECT * FROM proddocumento
          ORDER BY sttdocumento DESC,
            fchdocumento,            
            iddocumento"); }
      else
      { $link_mysqli = conectar();
        $prodDocumentoSelect = $link_mysqli->query("
          SELECT * FROM proddocumento
          WHERE sttdocumento = '1'
          ORDER BY fchdocumento,          
            iddocumento"); }

      if ($prodDocumentoSelect->num_rows > 0)
      { $id_documento = 1;
        while ($regProdDocumento = $prodDocumentoSelect->fetch_object())
        { $prodDocumentos_iddocumento[$id_documento] = $regProdDocumento->iddocumento;
          $prodDocumentos_fchdocumento[$id_documento] = $regProdDocumento->fchdocumento;          
          $prodDocumentos_cdgdocumento[$id_documento] = $regProdDocumento->cdgdocumento;
          $prodDocumentos_sttdocumento[$id_documento] = $regProdDocumento->sttdocumento;

        $id_documento++; }

        $numdocumentos = $prodDocumentoSelect->num_rows; }

        $prodDocumentoSelect->close;
    } else
    { $msg_alert = $msg_noread; }
    
    echo '
    <form id="form_proddocumento" name="form_proddocumento" method="POST" action="prodDocumento.php">
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="label_iddocumento">Documento</label><br/>
              <input type="text" id="text_iddocumento" name="text_iddocumento" style="width:120px" value="'.$prodDocumento_iddocumento.'" title="Folio del documento" '.$autofocus_iddocumento.' required/></td></tr>
          <tr><td><label for="label_fchdocumento">Fecha documento</label><br/>
              <input type="date" id="text_fchdocumento" name="text_fchdocumento" style="width:120px" value="'.$prodDocumento_fchdocumento.'" title="Fecha documento" '.$autofocus_fchdocumento.' required/></td></tr>
          <tr><td><label for="label_documento">Descripci&oacute;n</label><br/>
              <textarea id="textarea_documento" name="textarea_documento" style="width:240px" title="Descripcion del documento" '.$autofocus_documento.' required>'.$prodDocumento_documento.'</textarea></td></tr>
        </tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>';
      
    echo '
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="5" align="right">
              <input type="checkbox" id="checkbox_vertodo" name="checkbox_vertodo" '.$vertodo.' onclick="document.form_proddocumento.submit()" />
              <label for="label_vertodo">Ver todo</label></th></tr>
          <tr><th><label for="label_ttliddocumento">Documento</label></th>
            <th><label for="label_ttlfchdocumento">Fecha</label></th>            
            <th colspan="5"><label for="label_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';
      
    for($id_documento = 1; $id_documento <= $numdocumentos; $id_documento++)  
    { echo '
          <tr><td>'.$prodDocumentos_iddocumento[$id_documento].'</td>
            <td>'.$prodDocumentos_fchdocumento[$id_documento].'</td>';
            
      if ((int)$prodDocumentos_sttdocumento[$id_documento] > 0)      
      { if ((int)$prodDocumentos_sttdocumento[$id_documento] > 1)
        { echo '
            <td colspan="3"></td>
            <td align="center"><a href="pdf/prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&sttbobina=9" target="blank">'.$png_acrobat.'</a></td>
            <td align="center">'.$png_power_blue.'</td>'; }
        else
        { echo '
            <td align="center"><a href="prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'">'.$png_search.'</a></td>
            <td align="center"><a href="prodPacking.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'">'.$png_link.'</a></td>
            <td align="center"><a href="prodPackingFree.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'">'.$png_box_open.'</a></td>
            <td align="center"><a href="pdf/prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&sttbobina=8" target="blank">'.$png_acrobat.'</a></td>
            <td align="center"><a href="prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&proceso=update">'.$png_power_blue.'</a></td>'; } 
      } else
      { echo '
            <td align="center"><a href="prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td colspan="2"></td>
            <td align="center"><a href="pdf/prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&sttbobina=8" target="blank">'.$png_acrobat.'</a></td>
            <td align="center"><a href="prodDocumento.php?cdgdocumento='.$prodDocumentos_cdgdocumento[$id_documento].'&proceso=update">'.$png_power_black.'</a></td>'; }

      echo '</tr>'; }
        
    echo '
        </tbody>
        <tfoot>
          <tr><td colspan="7" align="right"><label for="label_piedefiltro">['.$numdocumentos.'] documentos encontrados</label></td></tr>
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
