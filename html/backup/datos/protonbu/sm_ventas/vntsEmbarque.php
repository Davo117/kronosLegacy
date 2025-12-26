<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40210';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_POST['hidden_cdgembarque'])
    { $vntsEmbarque_cdgembarque = $_POST['hidden_cdgembarque']; } 
    
    if ($_GET['cdgsucursal'])
    { $vntsEmbarque_cdgsucursal = $_GET['cdgsucursal']; }
    else
    { $vntsEmbarque_cdgsucursal = $_POST['select_cdgsucursal']; }

    if ($_POST['text_transporte'])
    { $vntsEmbarque_transporte = trim($_POST['text_transporte']); }
    else
    { $text_transporte = 'autofocus'; }

    if ($_POST['text_referencia'])
    { $vntsEmbarque_referencia = trim($_POST['text_referencia']); }
    else
    { $text_referencia = 'autofocus'; }

    if ($_POST['date_fchembarque'])
    { $vntsEmbarque_fchembarque = $_POST['date_fchembarque']; }
    else
    { $vntsEmbarque_fchembarque = date('Y-m-d'); }

    if ($_POST['text_observacion'])
    { $vntsEmbarque_observacion = $_POST['text_observacion']; }
    else
    { $vntsEmbarque_observacion = 'Sin observaciones'; }  

    if ($_GET['cdgembarque'])
    { $link_mysqli = conectar();
      $vntsEmbarqueSelect = $link_mysqli->query("
        SELECT * FROM vntsembarque
        WHERE cdgembarque = '".$_GET['cdgembarque']."'");

      if ($vntsEmbarqueSelect->num_rows > 0)
      { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

        $vntsEmbarque_cdgembarque = $regVntsEmbarque->cdgembarque;
        $vntsEmbarque_cdgsucursal = $regVntsEmbarque->cdgsucursal;
        $vntsEmbarque_cdgproducto = $regVntsEmbarque->cdgproducto;
        $vntsEmbarque_referencia = $regVntsEmbarque->referencia;
        $vntsEmbarque_fchembarque = $regVntsEmbarque->fchembarque;
        $vntsEmbarque_observacion = $regVntsEmbarque->observacion;
      }
    }

    // Productos
    if ($_POST['select_cdgproducto'])
    { $vntsEmbarque_cdgproducto = $_POST['select_cdgproducto']; }

    // Empaques
    if ($_POST['select_cdgempaque'])
    { $vntsEmbarque_cdgempaque = $_POST['select_cdgempaque']; }


    $link_mysqli = conectar(); 
    $pdtoDisenoSelect = $link_mysqli->query("
      SELECT * FROM pdtodiseno
      WHERE sttdiseno = '1'     
      ORDER BY diseno,
        iddiseno");
    
    $idDiseno = 1;
    while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
    { $pdtoDisenos_iddiseno[$idDiseno] = $regPdtoDiseno->iddiseno;
      $pdtoDisenos_diseno[$idDiseno] = $regPdtoDiseno->diseno;
      $pdtoDisenos_cdgdiseno[$idDiseno] = $regPdtoDiseno->cdgdiseno; 
      
      $link_mysqli = conectar(); 
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT * FROM pdtoimpresion
        WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' AND
          sttimpresion = '1'
        ORDER BY impresion,
          idimpresion");
      
      $idImpresion = 1;
      while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
      { $pdtoImpresiones_idimpresion[$idDiseno][$idImpresion] = $regPdtoImpresion->idimpresion;
        $pdtoImpresiones_impresion[$idDiseno][$idImpresion] = $regPdtoImpresion->impresion;
        $pdtoImpresiones_ancho[$idDiseno][$idImpresion] = $regPdtoImpresion->ancho;
        $pdtoImpresiones_alpaso[$idDiseno][$idImpresion] = $regPdtoImpresion->alpaso;
        $pdtoImpresiones_ceja[$idDiseno][$idImpresion] = $regPdtoImpresion->ceja;
        $pdtoImpresiones_tolerancia[$idDiseno][$idImpresion] = $regPdtoImpresion->tolerancia;      
        $pdtoImpresiones_corte[$idDiseno][$idImpresion] = $regPdtoImpresion->corte;
        $pdtoImpresiones_cdgimpresion[$idDiseno][$idImpresion] = $regPdtoImpresion->cdgimpresion;  
        $vntsEmbarque_productos[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->impresion;

        $idImpresion++; }

      $numImpresiones[$idDiseno] = $pdtoImpresionSelect->num_rows;         

      $idDiseno++; } 

    $numDisenos = $pdtoDisenoSelect->num_rows; 

  
    $link_mysqli = conectar();
    $pdtoEmpaqueSelect = $link_mysqli->query("
      SELECT * FROM pdtoempaque 
      WHERE sttempaque = '1'
      ORDER BY idempaque");

    $idEmpaque = 1;
    while ($regPdtoEmpaque = $pdtoEmpaqueSelect->fetch_object())
    { $pdtoEmpaque_idempaque[$idEmpaque] = $regPdtoEmpaque->idempaque;
      $pdtoEmpaque_empaque[$idEmpaque] = $regPdtoEmpaque->empaque;
      $pdtoEmpaque_cdgempaque[$idEmpaque] = $regPdtoEmpaque->cdgempaque; 

      $idEmpaque++; }

    $numEmpaques = $pdtoEmpaqueSelect->num_rows;    

    // Embarques
    $link_mysqli = conectar(); 
    $vntsEmbarqueSelect = $link_mysqli->query("
      SELECT vntsoc.oc, vntsembarque.cdgembarque
      FROM vntsoc, vntsoclote, vntsembarque
      WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND vntsoclote.cdglote = vntsembarque.cdglote");

    while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
    { $vntsEmbarque_oc[$regVntsEmbarque->cdgembarque] = $regVntsEmbarque->oc; }
    // -----------------------------------------

    if ($_POST['submit_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if($vntsEmbarque_cdgsucursal != '')
        { if($vntsEmbarque_cdgproducto != '')
          { if($vntsEmbarque_cdgempaque != '')
            { if($vntsEmbarque_fchembarque != '')
              { if($vntsEmbarque_referencia != '')
                { $vntsEmbarque_fchembarque = valorFecha($vntsEmbarque_fchembarque);

                  $link_mysqli = conectar();
                  $vntsEmbarqueSelect = $link_mysqli->query("
                    SELECT * FROM vntsembarque
                    WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'");

                  if ($vntsEmbarqueSelect->num_rows > 0)
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE vntsembarque 
                      SET fchembarque = '".$vntsEmbarque_fchembarque."',
                        observacion = '".$vntsEmbarque_observacion."',
                        cdgsucursal = '".$vntsEmbarque_cdgsucursal."',
                        transporte = '".$vntsEmbarque_transporte."',
                        referencia = '".$vntsEmbarque_referencia."'
                      WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'");

                    if ($link_mysqli->affected_rows > 0)
                    { $msg_modulo .= '<br/> El embarque fue actualizado.'; 
                    } else
                    { $msg_modulo .= '<br/> El embarque NO reporto cambios.'; }
                  } else
                  { for ($id = 1; $id <= 10000; $id++)
                    { $vntsEmbarque_newcdgembarque = date('ymd').str_pad($id,4,'0',STR_PAD_LEFT);

                      if ($id >= 10000)
                      { $msg_modulo .= '<br/> Los codigos disponibles para embarques se han agotado.'; }
                      else
                      { $link_mysqli = conectar();
                        $vntsEmbarqueSelect = $link_mysqli->query("
                          SELECT * FROM vntsembarque
                          WHERE cdgembarque = '".$vntsEmbarque_newcdgembarque."'");

                        if ($vntsEmbarqueSelect->num_rows > 0)
                        { // Codigo utilizado 
                        } else
                        { $link_mysqli = conectar();
                          $link_mysqli->query("
                            INSERT vntsembarque 
                              (cdgembarque, cdgsucursal, cdgproducto, cdgempaque, transporte, referencia, fchembarque, observacion)
                            VALUES
                              ('".$vntsEmbarque_newcdgembarque."', '".$vntsEmbarque_cdgsucursal."', '".$vntsEmbarque_cdgproducto."', '".$vntsEmbarque_cdgempaque."', '".$vntsEmbarque_transporte."', '".$vntsEmbarque_referencia."', '".$vntsEmbarque_fchembarque."', '".$vntsEmbarque_observacion."')");

                          if ($link_mysqli->affected_rows > 0)
                          { $msg_modulo .= '<br/> El embarque fue insertado.'; 
                          } else
                          { $msg_modulo .= '<br/> El embarque NO fue insertado.'; }

                          $id = 10000;
                        }
                      }
                    }
                  }

                  $vntsEmbarque_cdgembarque = '';
                } else          
                { $text_referencia = 'autofocus'; 
                  $msg_modulo .= '<br/> Es necesario indicar la referencia'; }

              } else
              { $date_fchembarque = 'autofocus'; 
                $msg_modulo .= '<br/> Es necesario indicar la fecha del embarque.'; }            

            } else
            { $select_cdgempaque = 'autofocus'; 
              $msg_modulo .= '<br/> Es necesario seleccionar un tipo de empaque.'; }

          } else
          { $select_cdgproducto = 'autofocus'; 
            $msg_modulo .= '<br/> Es necesario seleccionar un producto.'; }

        } else
        { $select_cdgsucursal = 'autofocus'; 
          $msg_modulo .= '<br/> Es necesario seleccionar una sucursal.'; }
        
      } else
      { $msg_modulo .= '<br/> No cuentas con permisos de escritura en este modulo.'; }
    }

    if ($_GET['cdgembarque'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsEmbarqueSelect = $link_mysqli->query("
          SELECT * FROM vntsembarque
          WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'");

        if ($vntsEmbarqueSelect->num_rows > 0)
        { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

          $vntsEmbarque_cdgembarque = $regVntsEmbarque->cdgembarque;
          $vntsEmbarque_cdgsucursal = $regVntsEmbarque->cdgsucursal;
          $vntsEmbarque_cdgproducto = $regVntsEmbarque->cdgproducto;
          $vntsEmbarque_cdgtpoempaque = $regVntsEmbarque->cdgempaque;
          $vntsEmbarque_transporte = $regVntsEmbarque->transporte;
          $vntsEmbarque_referencia = $regVntsEmbarque->referencia;
          $vntsEmbarque_fchembarque = $regVntsEmbarque->fchembarque;
          $vntsEmbarque_observacion = $regVntsEmbarque->observacion;
          $vntsEmbarque_sttembarque = $regVntsEmbarque->sttembarque;

          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($vntsEmbarque_sttembarque == '1')
              { $vntsEmbarque_newsttembarque = '0'; }
            
              if ($vntsEmbarque_sttembarque == '0')
              { $vntsEmbarque_newsttembarque = '1'; }
              
              if ($vntsEmbarque_newsttembarque != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE vntsembarque
                  SET sttembarque = '".$vntsEmbarque_newsttembarque."' 
                  WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'"); 
              } else
              { $msg_modulo .= '<br/> El embarque no puede ser modificado en su status.'; }
                
              if ($link_mysqli->affected_rows > 0)
              { $msg_modulo .= '<br/> El embarque fue actualizado en su status.'; }
              else
              { $msg_modulo .= '<br/> El embarque NO fue actualizado (status).'; }
            } else
            { $msg_modulo = $msg_norewrite; }            
          }

          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $pdtoMezclaSelect = $link_mysqli->query("
                SELECT * FROM alptempaque
                WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'");
                
              if ($pdtoMezclaSelect->num_rows > 0)
              { $msg_modulo .= '<br/> El embarque cuenta con empaques ligados, no pudo ser eliminada.'; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM vntsembarque
                  WHERE cdgembarque = '".$vntsEmbarque_cdgembarque."'
                  AND sttembarque = '0'");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_modulo .= '<br/> El embarque fue eliminado con exito.'; }
                else
                { $msg_modulo .= '<br/> El embarque NO fue eliminado.'; }
              }
            }
          }
        } else
        { $msg_modulo .= '<br/> El embarque seleccionado no pudo ser encontrado..'; }
      } else
      { $msg_modulo .= '<br/> No cuentas con permisos de lectura en este modulo.'; }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { $link_mysqli = conectar();
      $vntsClienteSelect = $link_mysqli->query("
        SELECT * FROM vntscliente
        WHERE sttcliente = '1'
        ORDER BY cliente");

      if ($vntsClienteSelect->num_rows > 0)
      { $id_cliente = 1;

        while ($regVntsCliente = $vntsClienteSelect->fetch_object())
        { $vntsCliente_idcliente[$id_cliente] = $regVntsCliente->idcliente;
          $vntsCliente_cliente[$id_cliente] = $regVntsCliente->cliente;
          $vntsCliente_cdgcliente[$id_cliente] = $regVntsCliente->cdgcliente;

          $link_mysqli = conectar();
          $vntsSucursalSelect = $link_mysqli->query("
            SELECT * FROM vntssucursal
            WHERE cdgcliente = '".$vntsCliente_cdgcliente[$id_cliente]."' AND
              sttsucursal = '1'
            ORDER BY sucursal");

          if ($vntsSucursalSelect->num_rows > 0)
          { $id_sucursal = 1;

            while ($regVntsSucursal = $vntsSucursalSelect->fetch_object())
            { $vntsSucursal_idsucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->idsucursal;
              $vntsSucursal_sucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->sucursal;
              $vntsSucursal_transporte[$id_cliente][$id_sucursal] = $regVntsSucursal->transporte;
              $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->cdgsucursal;

              $vntsSucursales_sucursal[$regVntsSucursal->cdgsucursal] = $regVntsSucursal->sucursal;

              $id_sucursal++; }

            $num_sucursales[$id_cliente] = $vntsSucursalSelect->num_rows; }

          $id_cliente++; }

        $num_clientes = $vntsClienteSelect->num_rows; } 

      if ($_POST['chk_vertodos'])
      { $vertodo = 'checked'; 
        // Filtrado completo
        $link_mysqli = conectar();
        $vntsEmbarqueSelect = $link_mysqli->query("
          SELECT * FROM vntsembarque
          ORDER BY cdgembarque DESC"); }
      else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $vntsEmbarqueSelect = $link_mysqli->query("
          SELECT * FROM vntsembarque            
          WHERE vntsembarque.sttembarque = '1'
          ORDER BY cdgembarque DESC"); }
    
      if ($vntsEmbarqueSelect->num_rows > 0)
      { $idEmbarque = 1;
        while ($regVntsEmbarque = $vntsEmbarqueSelect->fetch_object())
        { $vntsEmbarques_cdgembarque[$idEmbarque] = $regVntsEmbarque->cdgembarque; 
          $vntsEmbarques_cdgsucursal[$idEmbarque] = $regVntsEmbarque->cdgsucursal;
          $vntsEmbarques_cdgproducto[$idEmbarque] = $regVntsEmbarque->cdgproducto;
          $vntsEmbarques_cdgtpoempaque[$idEmbarque] = $regVntsEmbarque->cdgempaque;
          $vntsEmbarques_referencia[$idEmbarque] = $regVntsEmbarque->referencia;          
          $vntsEmbarques_fchembarque[$idEmbarque] = $regVntsEmbarque->fchembarque;
          $vntsEmbarques_sttembarque[$idEmbarque] = $regVntsEmbarque->sttembarque; 

          $idEmbarque++; }

        $num_embarques = $vntsEmbarqueSelect->num_rows; }

    } else
    { $msg_modulo .= '<br/> No cuentas con permisos de lectura en este modulo.'; }

    echo '
      <form id="form_ventas" name="form_ventas" method="POST" action="vntsEmbarque.php" />
        <input type="hidden" id="hidden_cdgembarque" name="hidden_cdgembarque" value="'.$vntsEmbarque_cdgembarque.'" />

        <table align="center">
          <thead>
            <tr><th colspan="3"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
          </thead>
          <tbody>
            <tr><td colspan="3">
                <a href="vntsSucursal.php?cdgsucursal='.$vntsEmbarque_cdgsucursal.'"><strong>Sucursal</strong></a><br/>
                <select id="select_cdgsucursal" name="select_cdgsucursal" onchange="document.form_ventas.submit()" style="width:300px;" '.$select_cdgsucursal.' required >
                  <option value=""> Elije una sucursal </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
                  <optgroup label="'.$vntsCliente_cliente[$id_cliente].'">';
      
      for ($id_sucursal = 1; $id_sucursal <= $num_sucursales[$id_cliente]; $id_sucursal++)
      { echo '
                    <option value="'.$vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal].'"';

        if ($vntsEmbarque_cdgsucursal == $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal])
        { if ($vntsEmbarque_transporte == '') 
          { $vntsEmbarque_transporte = $vntsSucursal_transporte[$id_cliente][$id_sucursal]; }

          echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$id_cliente][$id_sucursal].'</option>'; } 
      
      echo '
                  </optgroup>'; }

    echo '
                </select></td></tr>
            <tr><td colspan="3">
              <label for="lbl_cdgbloque"><a href="../sm_producto/pdtoImpresion.php?cdgimpresion='.$vntsEmbarque_cdgproducto.'"><strong>Producto</strong></a></label><br/>
              <select id="select_cdgproducto" name="select_cdgproducto" onchange="document.form_ventas.submit()" style="width:300px;" '.$select_cdgproducto.' required >
                <option value="">Selecciona una opcion</option>';
    
    for ($idDiseno = 1; $idDiseno <= $numDisenos; $idDiseno++) 
    { echo '
                <optgroup label="'.$pdtoDisenos_iddiseno[$idDiseno].'">';

      for ($idImpresion = 1; $idImpresion <= $numImpresiones[$idDiseno]; $idImpresion++) 
      { echo '
                  <option value="'.$pdtoImpresiones_cdgimpresion[$idDiseno][$idImpresion].'"';
            
        if ($vntsEmbarque_cdgproducto == $pdtoImpresiones_cdgimpresion[$idDiseno][$idImpresion]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresiones_impresion[$idDiseno][$idImpresion].' ('.$pdtoImpresiones_idimpresion[$idDiseno][$idImpresion].')</option>'; }

      echo '
                </optgroup>'; }
    
    echo '
              </select></td></tr>
            <tr><td colspan="3">
              <label for="lbl_cdgempaque"><strong>Empaque</strong></label><br/>
              <select style="width:140px" id="select_cdgempaque" name="select_cdgempaque">
                  <option value="">Selecciona una opcion</option>';

    for ($idEmpaque = 1; $idEmpaque <= $numEmpaques; $idEmpaque++) 
    { echo '
                    <option value="'.$pdtoEmpaque_cdgempaque[$idEmpaque].'"';
            
      if ($vntsEmbarque_cdgtpoempaque == $pdtoEmpaque_cdgempaque[$idEmpaque]) 
      { echo ' selected="selected"'; }

      echo '>'.$pdtoEmpaque_empaque[$idEmpaque].'</option>'; }
    
    echo '
                </select></td></tr>
            <tr><td colspan="3"><label for="label_observacion"><strong>Obsevaciones</strong></label><br/>
                <textarea id="text_observacion" name="text_observacion" rows="3" style="width:300px;">'.$vntsEmbarque_observacion.'</textarea></td></tr>
            <tr><td colspan="2"><label for="label_transporte"><strong>Transporte</strong></label><br/>
                <input type="text" id="text_transporte" name="text_transporte" value="'.$vntsEmbarque_transporte.'" style="width:200px;" '.$text_transporte.' required /></td></tr>
            <tr><td><label for="label_referencia"><strong>Referencia</strong></label><br/>
                <input type="text" id="text_referencia" name="text_referencia" value="'.$vntsEmbarque_referencia.'" style="width:120px;" '.$text_referencia.' required /></td>
              <td><label for="label_fchembarque"><strong>Fecha embarque</strong></label><br/>
                <input type="date" id="date_fchembarque" name="date_fchembarque" value="'.$vntsEmbarque_fchembarque.'" style="width:95px;" '.$date_fchembarque.' required /></td></tr>
          </tbody>
          <tfoot>
            <tr><td colspan="3" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
          </tfoot>
        </table><br/>';

    if ($msg_modulo != '')
    { echo '
    <div align="center"><strong>'.$msg_modulo.'</strong></div>'; }        

    echo '
        <table align="center">
        <thead>
          <tr><td colspan="6"></td>            
            <th colspan="6" align="right">
              <input type="checkbox" name="chk_vertodos" id="chk_vertodos" onclick="document.form_ventas.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th><label for="lbl_ttlsucursal">Sucursal</label></th>
            <th><label for="lbl_ttlproducto">Producto</label></th>
            <th><label for="lbl_ttlembarque">Embarque</label></th>
            <th><label for="lbl_ttlreferencia">Referencia</label></th>
            <th><label for="lbl_ttloc">O.C.</label></th>
            <th><label for="lbl_ttlfchembarque">Fecha Embarque</label></th>            
            <th colspan="7" align="center"><label for="lbl_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>';

    if ($num_embarques > 0)
    { for ($idEmbarque=1; $idEmbarque<=$num_embarques; $idEmbarque++)
      { echo '
          <tr align="center">
            <td align="left"><strong>'.$vntsSucursales_sucursal[$vntsEmbarques_cdgsucursal[$idEmbarque]].'</strong></td>            
            <td align="left">'.$vntsEmbarque_productos[$vntsEmbarques_cdgproducto[$idEmbarque]].'</td>
            <td align="left">'.$vntsEmbarques_cdgembarque[$idEmbarque].'</td>
            <td align="left">'.$vntsEmbarques_referencia[$idEmbarque].'</td>
            <td align="right"><strong>'.$vntsEmbarque_oc[$vntsEmbarques_cdgembarque[$idEmbarque]].'</strong></td>
            <td align="left">'.$vntsEmbarques_fchembarque[$idEmbarque].'</td>';

        if ((int)$vntsEmbarques_sttembarque[$idEmbarque] > 0)
        { echo '
            <td><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'">'.$png_search.'</a></td>
            <td><a href="vntsEmbarqueMaker.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'&cdgproducto='.$vntsEmbarques_cdgproducto[$idEmbarque].'&cdgtpoempaque='.$vntsEmbarques_cdgtpoempaque[$idEmbarque].'">'.$png_link.'</a></td>
            <td><a href="pdf/vntsEmbarquePdf.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'" target="_blank">'.$png_acrobat.'</a></td>
            <td><a href="pdf/vntsEmbarqueLbl.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'">'.$png_delivery.'</a></td>
            <td><a href="../sm_produccion/excel/prodEmbarque.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'">'.$jpg_excel.'</a></td>            
            <td><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'&proceso=update">'.$png_power_blue.'</a></td>'; }
        else
         { echo '
            <td><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td><a href="vntsEmbarqueMaker.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'">'.$png_link.'</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarques_cdgembarque[$idEmbarque].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>';
      }      
    }

    echo '
        </tbody>
        <tfoot>
          <tr><th colspan="12" align="right">
              <label for="lbl_ppgdatos">['.$num_embarques.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>'; 

  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }  
  
 ?>