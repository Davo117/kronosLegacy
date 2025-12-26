<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '40211';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_GET['cdgempaque'])
    { if ($_GET['proceso'] == 'download')
      { $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT * FROM alptempaque 
          WHERE cdgempaque = '".$_GET['cdgempaque']."'");

        if ($alptEmpaqueSelect->num_rows > 0)
        { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

          $link_mysqli = conectar();
          $vntsSurtidoSelect = $link_mysqli->query("
            SELECT * FROM vntsembarque
            WHERE cdgembarque = '".$regAlptEmpaque->cdgembarque."' AND
              cdglote = ''");

          if ($vntsSurtidoSelect->num_rows > 0)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE alptempaque
              SET cdgembarque = '',
                sttempaque = '1'
              WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                cdgembarque = '".$regAlptEmpaque->cdgembarque."'"); }
          else
          { $msg_modulo .= "El embarque no puede ser modificado, ya fue ligado una orden de compra."; } 
        } else
        { $msg_modulo .= "Empaque no encontrado."; }
      }
    }

    if ($_GET['cdgembarque'])
    { $_SESSION['vntsembarque_cdgembarque'] = $_GET['cdgembarque'];
      $_SESSION['vntsembarque_cdgproducto'] = $_GET['cdgproducto'];
      $_SESSION['vntsembarque_cdgtpoempaque'] = $_GET['cdgtpoempaque']; }

    if ($_SESSION['vntsembarque_cdgembarque'])
    { $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT alptempaque.noempaque,
            alptempaque.tpoempaque,
            pdtoimpresion.impresion,
       SUM((prodrollo.longitud/pdtodiseno.alto)) AS cantidad,
        SUM(prodrollo.longitud) AS longitud, 
        SUM(prodrollo.peso) AS peso,
            alptempaque.peso AS pesob,
            alptempaque.cdgempaque
          FROM alptempaque,
            alptempaquer,
            prodrollo,
            pdtodiseno,
            pdtoimpresion
          WHERE alptempaque.cdgproducto = '".$_SESSION['vntsembarque_cdgproducto']."' AND
            alptempaque.cdgempaque = alptempaquer.cdgempaque AND
            alptempaquer.cdgrollo = prodrollo.cdgrollo AND
            prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
            pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
            alptempaque.sttempaque = '1'
          GROUP BY alptempaque.noempaque,
            alptempaque.tpoempaque,
            pdtoimpresion.impresion
          ORDER BY pdtoimpresion.impresion,
            alptempaque.noempaque");
        
        $num_empaquesQ = 0;
        if ($alptEmpaqueSelect->num_rows > 0)
        { $id_empaqueQ = 1;
          while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
          { $vntsEmbarqueQ_cantidadpqt = 0;

            $link_mysqli = conectar();
            $alptEmpaqueRSelect = $link_mysqli->query("
              SELECT alptempaquer.nocontrol, alptempaquer.cdgrollo,
                (prodrollo.longitud/pdtodiseno.alto) AS cantidad,
                prodrollo.longitud, prodrollo.peso, prodrollo.bandera
              FROM alptempaquer, prodrollo, pdtodiseno, pdtoimpresion
              WHERE alptempaquer.cdgrollo = prodrollo.cdgrollo AND
                alptempaquer.cdgproducto = pdtoimpresion.cdgimpresion AND
                pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
                alptempaquer.cdgempaque = '".$regVntsEmpaque->cdgempaque."'
              ORDER BY alptempaquer.nocontrol");
            
            while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
            { $vntsEmbarqueQ_cantidadpqt += number_format($regAlptEmpaqueR->cantidad,3); }  

            $vntsEmbarqueQD_noempaque[$id_empaqueQ] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            $vntsEmbarqueQD_producto[$id_empaqueQ] = $regVntsEmpaque->impresion;
            //$vntsEmbarqueQD_cantidad[$id_empaqueQ] = $regVntsEmpaque->cantidad;
            $vntsEmbarqueQD_cantidad[$id_empaqueQ] = $vntsEmbarqueQ_cantidadpqt;
            $vntsEmbarqueQD_longitud[$id_empaqueQ] = $regVntsEmpaque->longitud;
            $vntsEmbarqueQD_peso[$id_empaqueQ] = $regVntsEmpaque->peso;
            $vntsEmbarqueQD_pesob[$id_empaqueQ] = $regVntsEmpaque->pesob;
            $vntsEmbarqueQD_cdgempaque[$id_empaqueQ] = $regVntsEmpaque->cdgempaque;

            $id_empaqueQ++; }

          $num_empaquesQ = $alptEmpaqueSelect->num_rows;
        }
      

      $link_mysqli = conectar();
        $alptEmpaqueSelect = $link_mysqli->query("
          SELECT alptempaque.noempaque,
            alptempaque.tpoempaque,
            pdtoimpresion.impresion,
            alptempaque.peso,
           SUM(prodpaquete.cantidad) AS cantidad,          
            alptempaque.cdgempaque
          FROM alptempaque,
            alptempaquep,
            prodpaquete,
            pdtoimpresion
          WHERE alptempaque.cdgproducto = '".$_SESSION['vntsembarque_cdgproducto']."' AND
            alptempaque.cdgempaque = alptempaquep.cdgempaque AND
            alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
            prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
            alptempaque.sttempaque = '1'
          GROUP BY alptempaque.noempaque,
            alptempaque.tpoempaque,
            pdtoimpresion.impresion
          ORDER BY pdtoimpresion.impresion,
            alptempaque.noempaque");
        
        $num_empaquesC = 0;
        if ($alptEmpaqueSelect->num_rows > 0)
        { $id_empaqueC = 1;
          while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
          { $vntsEmbarqueCD_noempaque[$id_empaqueC] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            $vntsEmbarqueCD_producto[$id_empaqueC] = $regVntsEmpaque->impresion;           
            $vntsEmbarqueCD_cantidad[$id_empaqueC] = $regVntsEmpaque->cantidad;
            $vntsEmbarqueCD_peso[$id_empaqueC] = $regVntsEmpaque->peso;
            $vntsEmbarqueCD_cdgempaque[$id_empaqueC] = $regVntsEmpaque->cdgempaque;

            $id_empaqueC++; }

          $num_empaquesC = $alptEmpaqueSelect->num_rows;
        }
      

      if ($_POST['submit_salvar'])
      { $link_mysqli = conectar();
        $vntsSurtidoSelect = $link_mysqli->query("
          SELECT * FROM vntsembarque
          WHERE cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND 
            cdglote != ''");

        if ($vntsSurtidoSelect->num_rows > 0)
        { $msg_modulo = "El embarque no puede ser modificado, ya fue ligado una orden de compra.";
        } else
        { for ($id_empaqueQ=1; $id_empaqueQ<=$num_empaquesQ; $id_empaqueQ++)
          { if (isset($_REQUEST['chk_'.$vntsEmbarqueQD_cdgempaque[$id_empaqueQ]]))
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE alptempaque
                SET cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."',
                  sttempaque = 'E'
                WHERE cdgempaque = '".$vntsEmbarqueQD_cdgempaque[$id_empaqueQ]."'");
            }
          }

          for ($id_empaqueC=1; $id_empaqueC<=$num_empaquesC; $id_empaqueC++)
          { if (isset($_REQUEST['chk_'.$vntsEmbarqueCD_cdgempaque[$id_empaqueC]]))
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE alptempaque
                SET cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."',
                  sttempaque = 'E'
                WHERE cdgempaque = '".$vntsEmbarqueCD_cdgempaque[$id_empaqueC]."'");
            } 
          } 
        }
      }

      if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $vntsEmbarqueSelect = $link_mysqli->query("
          SELECT vntsembarque.cdgembarque,
            vntssucursal.sucursal,
            vntsembarque.cdgproducto,
            vntsembarque.cdgempaque,
            vntsembarque.referencia,
            vntsembarque.fchembarque,
            vntsembarque.sttembarque,
            pdtoimpresion.impresion
          FROM vntsembarque,
            vntssucursal,
            pdtoimpresion
          WHERE vntsembarque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
            vntsembarque.cdgsucursal = vntssucursal.cdgsucursal AND
            vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion");

        if ($vntsEmbarqueSelect->num_rows > 0)
        { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

          $vntsEmbarque_cdgembarque = $regVntsEmbarque->cdgembarque;
          $vntsEmbarque_sucursal = $regVntsEmbarque->sucursal;
          $vntsEmbarque_cdgproducto = $regVntsEmbarque->cdgproducto;
          $vntsEmbarque_cdgtpoempaque = $regVntsEmbarque->cdgempaque;
          $vntsEmbarque_referencia = $regVntsEmbarque->referencia;
          $vntsEmbarque_fchembarque = $regVntsEmbarque->fchembarque;          
          $vntsEmbarque_sttembarque = $regVntsEmbarque->sttembarque;
          $vntsEmbarque_producto = $regVntsEmbarque->impresion; }

        $link_mysqli = conectar();
        	$alptEmpaqueSelect = $link_mysqli->query("
            SELECT alptempaque.noempaque,
              alptempaque.tpoempaque,
              pdtoimpresion.impresion,
         SUM((prodrollo.longitud/pdtodiseno.alto)) AS cantidad,
          SUM(prodrollo.longitud) AS longitud, 
              SUM(prodrollo.peso) AS peso,
              alptempaque.peso AS pesob,
              alptempaque.cdgempaque
            FROM alptempaque,
              alptempaquer,
              prodrollo,
              pdtodiseno,
              pdtoimpresion
            WHERE alptempaque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
              alptempaque.cdgempaque = alptempaquer.cdgempaque AND
              alptempaquer.cdgrollo = prodrollo.cdgrollo AND
              prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
              pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno
            GROUP BY alptempaque.noempaque,
              alptempaque.tpoempaque,
              pdtoimpresion.impresion
            ORDER BY pdtoimpresion.impresion,
              alptempaque.noempaque");

          $num_empaquesQ = 0;
          if ($alptEmpaqueSelect->num_rows > 0)
          { $id_empaqueQ = 1;
            while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
            { $vntsEmbarqueQ_cantidadpqt = 0;

              $link_mysqli = conectar();
              $alptEmpaqueRSelect = $link_mysqli->query("
                SELECT alptempaquer.nocontrol, alptempaquer.cdgrollo,
                  (prodrollo.longitud/pdtodiseno.alto) AS cantidad,
                  prodrollo.longitud, prodrollo.peso, prodrollo.bandera
                FROM alptempaquer, prodrollo, pdtodiseno, pdtoimpresion
                WHERE alptempaquer.cdgrollo = prodrollo.cdgrollo AND
                  alptempaquer.cdgproducto = pdtoimpresion.cdgimpresion AND
                  pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
                  alptempaquer.cdgempaque = '".$regVntsEmpaque->cdgempaque."'
                ORDER BY alptempaquer.nocontrol");
              
              while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
              { $vntsEmbarqueQ_cantidadpqt += number_format($regAlptEmpaqueR->cantidad,3); }  

              $vntsEmbarqueQ_noempaque[$id_empaqueQ] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            	$vntsEmbarqueQ_producto[$id_empaqueQ] = $regVntsEmpaque->impresion;
            	//$vntsEmbarqueQ_cantidad[$id_empaqueQ] = $regVntsEmpaque->cantidad;
              $vntsEmbarqueQ_cantidad[$id_empaqueQ] = $vntsEmbarqueQ_cantidadpqt;
            	$vntsEmbarqueQ_longitud[$id_empaqueQ] = $regVntsEmpaque->longitud;
            	$vntsEmbarqueQ_peso[$id_empaqueQ] = $regVntsEmpaque->peso;
              $vntsEmbarqueQ_pesob[$id_empaqueQ] = $regVntsEmpaque->pesob;
              $vntsEmbarqueQ_cdgempaque[$id_empaqueQ] = $regVntsEmpaque->cdgempaque;

              $id_empaqueQ++; }

            $num_empaquesQ = $alptEmpaqueSelect->num_rows; }
        

        $link_mysqli = conectar();
        	$alptEmpaqueSelect = $link_mysqli->query("
            SELECT alptempaque.noempaque,
              alptempaque.tpoempaque,
              pdtoimpresion.impresion,
              alptempaque.peso,
          SUM(prodpaquete.cantidad) AS cantidad,
              alptempaque.cdgempaque
            FROM alptempaque,
              alptempaquep,
              prodpaquete,
              pdtoimpresion
            WHERE alptempaque.cdgembarque = '".$_SESSION['vntsembarque_cdgembarque']."' AND
              alptempaque.cdgempaque = alptempaquep.cdgempaque AND
              alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
              prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion
            GROUP BY alptempaque.noempaque,
              alptempaque.tpoempaque,
              pdtoimpresion.impresion
            ORDER BY pdtoimpresion.impresion,
              alptempaque.noempaque");

          $num_empaquesC = 0;
          if ($alptEmpaqueSelect->num_rows > 0)
          { $id_empaqueC = 1;
            while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
            { $vntsEmbarqueC_noempaque[$id_empaqueC] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
            	$vntsEmbarqueC_producto[$id_empaqueC] = $regVntsEmpaque->impresion;          	
            	$vntsEmbarqueC_peso[$id_empaqueC] = $regVntsEmpaque->peso;
              $vntsEmbarqueC_cantidad[$id_empaqueC] = $regVntsEmpaque->cantidad;
              $vntsEmbarqueC_cdgempaque[$id_empaqueC] = $regVntsEmpaque->cdgempaque;

              $id_empaqueC++; }

            $num_empaquesC = $alptEmpaqueSelect->num_rows; }
        }      
      

    echo '
    <form id="form_ventas" name="form_ventas" method="POST" action="vntsEmbarqueMaker.php" />
      <table align="center">
        <thead>
          <tr><th colspan="2"><label for="label_modulo">'.$sistModulo_modulo.'</label></th></tr>
        </thead>
        <tbody>
          <tr><td colspan="2">Orden de Embarque: <strong><a href="vntsEmbarque.php?cdgembarque='.$vntsEmbarque_cdgembarque.'">'.$vntsEmbarque_cdgembarque.'</a></strong></td></tr>
          <tr><td colspan="2">Sucursal: <strong>'.$vntsEmbarque_sucursal.'</strong></td></tr>
          <tr><td colspan="2">Referencia: <strong>'.$vntsEmbarque_referencia.'</strong></td></tr>
          <tr><td colspan="2">Embarque: <strong>'.$vntsEmbarque_fchembarque.'</strong></td></tr>      
          <tr><td colspan="2">Impresi&oacute;n: <strong>'.$vntsEmbarque_producto.'</strong></td></tr> 
        </tbody>
        <tfoot>
          <tr><td colspan="2" align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>          
        </tfoot>
      </table><br/>';

    if ($msg_modulo != '')
    { echo '

        <div align="center"><strong>Aviso</strong><br/>'.$msg_modulo.'</div><br/>'; }

    if ($num_empaquesQ > 0 AND $_SESSION['vntsembarque_cdgtpoempaque'] == 'Q')
    { echo '
      <table align="center">
        <thead>
          <tr><th colspan="7">Contenido del embarque en Quesos</th></tr>
          <tr><th>NoQueso</th>
            <th>Producto</th>
            <th>Millares</th>
            <th>Acumulado</th>
            <th>Longitud</th>
            <th>Peso</th>
            <th>Bruto</th>
            <th>Liberar</th></tr>
        </thead>
        <tbody>';
      
      for ($id_empaqueQ=1; $id_empaqueQ<=$num_empaquesQ; $id_empaqueQ++)
      { $vntsEmbarqueQ_acumulado += number_format($vntsEmbarqueQ_cantidad[$id_empaqueQ],3);

  	    echo '
          <tr align="right">
            <td>'.$vntsEmbarqueQ_noempaque[$id_empaqueQ].'</td>
            <td align="left">'.$vntsEmbarqueQ_producto[$id_empaqueQ].'</td>
            <td>'.number_format($vntsEmbarqueQ_cantidad[$id_empaqueQ],3).'</td>
            <td>'.number_format($vntsEmbarqueQ_acumulado,3).'</td>
            <td>'.number_format($vntsEmbarqueQ_longitud[$id_empaqueQ],2).'</td>
            <td>'.number_format($vntsEmbarqueQ_peso[$id_empaqueQ],3).'</td>
            <td>'.number_format($vntsEmbarqueQ_pesob[$id_empaqueQ],3).'</td>
            <td><a href="vntsEmbarqueMaker.php?cdgempaque='.$vntsEmbarqueQ_cdgempaque[$id_empaqueQ].'&proceso=download">'.$png_recycle_bin.'</a></td></tr>'; }

      echo '  
        </tbody>
        <tfoot>
          <tr><th colspan="8" align="right">
              <label for="lbl_ppgdatos">['.$num_empaquesQ.'] Empaques agregados</label></th></tr>        
        </tfoot>
      </table><br/>'; }
      
    if ($num_empaquesC > 0 AND $_SESSION['vntsembarque_cdgtpoempaque'] == 'C')
    { echo '
      <table align="center">
        <thead>
          <tr><th colspan="6">Contenido del embarque en Cajas</th></tr>
          <tr><th>NoCaja</th>
            <th>Producto</th>
            <th>Millares</th>
            <th>Acumulado</th>
            <th>Peso</th>
            <th>Liberar</th></tr>
        </thead>
        <tbody>';
      
      for ($id_empaqueC=1; $id_empaqueC<=$num_empaquesC; $id_empaqueC++)
      { $vntsEmbarqueC_acumulado += $vntsEmbarqueC_cantidad[$id_empaqueC];

        echo '
          <tr align="right">
            <td>'.$vntsEmbarqueC_noempaque[$id_empaqueC].'</td>
            <td align="left">'.$vntsEmbarqueC_producto[$id_empaqueC].'</td>
            <td>'.number_format($vntsEmbarqueC_cantidad[$id_empaqueC],3).'</td>
            <td>'.number_format($vntsEmbarqueC_acumulado,3).'</td>
            <td>'.number_format($vntsEmbarqueC_peso[$id_empaqueC],3).'</td>
            <td><a href="vntsEmbarqueMaker.php?cdgempaque='.$vntsEmbarqueC_cdgempaque[$id_empaqueC].'&proceso=download">'.$png_recycle_bin.'</a></td></tr>'; }

      echo '  
        </tbody>
        <tfoot>
          <tr><th colspan="7" align="right">
              <label for="lbl_ppgdatos">['.$num_empaquesC.'] Empaques agregados</label></th></tr>        
        </tfoot>
      </table><br/>'; }

    if ($_SESSION['vntsembarque_cdgtpoempaque'] == 'Q')
    {  // Bobinas sin asignar
      $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT alptempaque.noempaque,
          alptempaque.tpoempaque,
          pdtoimpresion.impresion,
     SUM((prodrollo.longitud/pdtodiseno.alto)) AS cantidad,
      SUM(prodrollo.longitud) AS longitud, 
          SUM(prodrollo.peso) AS peso,
          alptempaque.cdgempaque
        FROM alptempaque,
          alptempaquer,
          prodrollo,
          pdtodiseno,
          pdtoimpresion
        WHERE alptempaque.cdgproducto = '".$vntsEmbarque_cdgproducto."' AND
          alptempaque.cdgempaque = alptempaquer.cdgempaque AND
          alptempaquer.cdgrollo = prodrollo.cdgrollo AND
          prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
          pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
          alptempaque.sttempaque = '1'
        GROUP BY alptempaque.noempaque,
          alptempaque.tpoempaque,
          pdtoimpresion.impresion
        ORDER BY pdtoimpresion.impresion,
          alptempaque.noempaque");
      
      $num_empaquesQ = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $id_empaqueQ = 1;
        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $vntsEmbarqueQD_noempaque[$id_empaqueQ] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueQD_producto[$id_empaqueQ] = $regVntsEmpaque->impresion;
          $vntsEmbarqueQD_cantidad[$id_empaqueQ] = $regVntsEmpaque->cantidad;
          $vntsEmbarqueQD_longitud[$id_empaqueQ] = $regVntsEmpaque->longitud;
          $vntsEmbarqueQD_peso[$id_empaqueQ] = $regVntsEmpaque->peso;
          $vntsEmbarqueQD_cdgempaque[$id_empaqueQ] = $regVntsEmpaque->cdgempaque;

          $id_empaqueQ++; }

        $num_empaquesQ = $alptEmpaqueSelect->num_rows;
      }

      if ($num_empaquesQ > 0)
      { echo '
        <table align="center">
          <thead>
            <tr><th colspan="8">Quesos disponibles</th></tr>
            <tr><th></th>
              <th>NoQueso</th>
              <th>Producto</th>
              <th>Millares</th>
              <th>Acumulado</th>
              <th>Longitud</th>
              <th>Peso</th>
              <th>Bruto</th></tr>
          </thead>
          <tbody>';
        
        for ($id_empaqueQ=1; $id_empaqueQ<=$num_empaquesQ; $id_empaqueQ++)
        { $vntsEmbarqueQD_acumulado += number_format($vntsEmbarqueQD_cantidad[$id_empaqueQ],3);

          echo '
            <tr align="right">
              <td><input type="checkbox" id="chk_'.$vntsEmbarqueQD_cdgempaque[$id_empaqueQ].'" name="chk_'.$vntsEmbarqueQD_cdgempaque[$id_empaqueQ].'" '.$chkbox[$vntsEmpaqueQD_cdgempaque[$id_empaqueQ]].'/></td>
              <td>'.$vntsEmbarqueQD_noempaque[$id_empaqueQ].'</td>
              <td align="left">'.$vntsEmbarqueQD_producto[$id_empaqueQ].'</td>
              <td>'.number_format($vntsEmbarqueQD_cantidad[$id_empaqueQ],3).'</td>
              <td>'.number_format($vntsEmbarqueQD_acumulado,3).'</td>
              <td>'.number_format($vntsEmbarqueQD_longitud[$id_empaqueQ],2).'</td>
              <td>'.number_format($vntsEmbarqueQD_peso[$id_empaqueQ],3).'</td>
              <td>'.number_format($vntsEmbarqueQD_pesob[$id_empaqueQ],3).'</td></tr>'; }

        echo '  
          </tbody>
          <tfoot>
            <tr><th colspan="8" align="right">
                <label for="lbl_ppgdatos">['.$num_empaquesQ.'] Empaques disponibles</label></th></tr>        
          </tfoot>
        </table><br/>'; }    
    }

    if ($_SESSION['vntsembarque_cdgtpoempaque'] == 'C')
    { $link_mysqli = conectar();
      $alptEmpaqueSelect = $link_mysqli->query("
        SELECT alptempaque.noempaque,
          alptempaque.tpoempaque,
          pdtoimpresion.impresion,
      SUM(prodpaquete.cantidad) AS cantidad,
          alptempaque.cdgempaque
        FROM alptempaque,
          alptempaquep,
          prodpaquete,
          pdtoimpresion
        WHERE alptempaque.cdgproducto = '".$vntsEmbarque_cdgproducto."' AND
          alptempaque.cdgempaque = alptempaquep.cdgempaque AND
          alptempaquep.cdgpaquete = prodpaquete.cdgpaquete AND
          prodpaquete.cdgproducto = pdtoimpresion.cdgimpresion AND
          alptempaque.sttempaque = '1'
        GROUP BY alptempaque.noempaque,
          alptempaque.tpoempaque,
          pdtoimpresion.impresion
        ORDER BY pdtoimpresion.impresion,
          alptempaque.noempaque");
      
      $num_empaquesC = 0;
      if ($alptEmpaqueSelect->num_rows > 0)
      { $id_empaqueC = 1;
        while ($regVntsEmpaque = $alptEmpaqueSelect->fetch_object())
        { $vntsEmbarqueCD_noempaque[$id_empaqueC] = $regVntsEmpaque->tpoempaque.$regVntsEmpaque->noempaque;
          $vntsEmbarqueCD_producto[$id_empaqueC] = $regVntsEmpaque->impresion;           
          $vntsEmbarqueCD_cantidad[$id_empaqueC] = number_format($regVntsEmpaque->cantidad,3);
          $vntsEmbarqueCD_cdgempaque[$id_empaqueC] = $regVntsEmpaque->cdgempaque;

          $id_empaqueC++; }

        $num_empaquesC = $alptEmpaqueSelect->num_rows;
      }

      if ($num_empaquesC > 0)
      { echo '
        <table align="center">
          <thead>
            <tr><th colspan="6">Cajas disponibles</th></tr>
            <tr><th></th>
              <th>NoCaja</th>
              <th>Producto</th>
              <th>Millares</th>
              <th>Acumulado</th>
              <th>Peso</th></tr>
          </thead>
          <tbody>';
        
        for ($id_empaqueC=1; $id_empaqueC<=$num_empaquesC; $id_empaqueC++)
        { $vntsEmbarqueCD_acumulado += $vntsEmbarqueCD_cantidad[$id_empaqueC];

          echo '
            <tr align="right">
              <td><input type="checkbox" id="chk_'.$vntsEmbarqueCD_cdgempaque[$id_empaqueC].'" name="chk_'.$vntsEmbarqueCD_cdgempaque[$id_empaqueC].'" '.$chkbox[$vntsEmpaqueCD_cdgempaque[$id_empaqueC]].'/></td>
              <td>'.$vntsEmbarqueCD_noempaque[$id_empaqueC].'</td>
              <td align="left">'.$vntsEmbarqueCD_producto[$id_empaqueC].'</td>            
              <td>'.number_format($vntsEmbarqueCD_cantidad[$id_empaqueC],3).'</td>
              <td>'.number_format($vntsEmbarqueCD_acumulado,3).'</td>
              <td>'.number_format($vntsEmbarqueCD_peso[$id_empaqueC],3).'</td></tr>'; }

        echo '  
          </tbody>
          <tfoot>
            <tr><th colspan="6" align="right">
                <label for="lbl_ppgdatos">['.$num_empaquesC.'] Empaques disponibles</label></th></tr>        
          </tfoot>
        </table>'; }        
    }    

    echo '  
    </form>';
    } else
    { echo '
    <div align="center"><h1>Es necesario indicar un c&oacute;digo de embarque valido...</h1></div>'; }  
  } else
  { echo '
    <div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }
  
 ?>