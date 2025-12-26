<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Calendario de Entregas</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Termoencogible</h1></label>
      </section><?php
  
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '60CET';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }
  }  

  // --> Nombre de los dias
  $diatxt[0] = "Domingo";
  $diatxt[1] = "Lunes";
  $diatxt[2] = "Martes";
  $diatxt[3] = "Miercoles";
  $diatxt[4] = "Jueves";
  $diatxt[5] = "Viernes";
  $diatxt[6] = "Sabado";  
  // <-- Nombre de los dias

  // --> Nombre de los meses
  $mestxt[1] = "Enero";  
  $mestxt[2] = "Febrero";
  $mestxt[3] = "Marzo";
  $mestxt[4] = "Abril";
  $mestxt[5] = "Mayo";
  $mestxt[6] = "Junio";
  $mestxt[7] = "Julio";
  $mestxt[8] = "Agosto";
  $mestxt[9] = "Septiembre";
  $mestxt[10] = "Octubre";
  $mestxt[11] = "Noviembre";
  $mestxt[12] = "Diciembre";  
  // <-- Nombre de los meses
  
  if ($_POST['dateDsdFecha'])
  { $prodEntregas_dsdfecha = $_POST['dateDsdFecha']; }
  else
  { $prodEntregas_dsdfecha = date(); }

  if ($_POST['dateDsdFecha']) { $prodEntregas_dsdrngfecha = $_POST['dateDsdFecha']; }
  if ($_POST['dateHstFecha']) { $prodEntregas_hstrngfecha = $_POST['dateHstFecha']; }

  $prodEntregas_dsdrngfecha = ValidarFecha($prodEntregas_dsdrngfecha);
  $prodEntregas_hstrngfecha = ValidarFecha($prodEntregas_hstrngfecha);

  if (($_POST['hideDsdFecha'] AND $_POST['hideHstFecha']) AND ($_POST['slctCdgSucursal'] OR $_POST['slctCdgProducto']))
  { $prodEntregas_cdgsucursal = $_POST['slctCdgSucursal'];
    $prodEntregas_cdgproducto = $_POST['slctCdgProducto'];
    $prodEntregas_dsdfecha = $_POST['hideDsdFecha'];
    $prodEntregas_hstfecha = $_POST['hideHstFecha'];

    $prodEntregas_idmesprevio = substr($prodEntregas_dsdfecha,5,2); 
    $prodEntregas_idanoprevio = substr($prodEntregas_dsdfecha,0,4); 

    if ($prodEntregas_idmesprevio == 12)
    { $prodEntregas_idano = $prodEntregas_idanoprevio+1;
      $prodEntregas_idmes = 1; 
    } else
    { $prodEntregas_idano = $prodEntregas_idanoprevio;
      $prodEntregas_idmes = $prodEntregas_idmesprevio+1; } 

    $prodEntregas_idmessiguiente = substr($prodEntregas_hstfecha,5,2);
    $prodEntregas_idanosiguiente = substr($prodEntregas_hstfecha,0,4);
  } else
  { $prodEntregas_cdgsucursal = $_GET['cdgsucursal'];
    $prodEntregas_cdgproducto = $_GET['cdgproducto'];

    if ($_GET['idano'] AND $_GET['idmes'])
    { $prodEntregas_idano = $_GET['idano'];
      $prodEntregas_idmes = $_GET['idmes']; }
    else
    { $prodEntregas_idano = date("Y"); 
      $prodEntregas_idmes = date("n"); }
      
    if ($prodEntregas_idmes == 1)


    { $prodEntregas_idmesprevio = 12; 
      $prodEntregas_idanoprevio = $prodEntregas_idano-1; 

      $prodEntregas_idmessiguiente = 2;
      $prodEntregas_idanosiguiente = $prodEntregas_idano;
    } else
    { if ($prodEntregas_idmes == 12)
      { $prodEntregas_idmesprevio = 11; 
        $prodEntregas_idanoprevio = $prodEntregas_idano; 

        $prodEntregas_idmessiguiente = 1;
        $prodEntregas_idanosiguiente = $prodEntregas_idano+1;
      } else
      { $prodEntregas_idmesprevio = $prodEntregas_idmes-1; 
        $prodEntregas_idanoprevio = $prodEntregas_idano; 

        $prodEntregas_idmessiguiente = $prodEntregas_idmes+1;
        $prodEntregas_idanosiguiente = $prodEntregas_idano;
      }
    }
  }

  $prodEntregas_diasmesprevio = date("t", mktime(0,0,0,$prodEntregas_idmesprevio,1,$prodEntregas_idanoprevio));
  $prodEntregas_diasmessiguiente = date("t", mktime(0,0,0,$prodEntregas_idmessiguiente,1,$prodEntregas_idanosiguiente));
  $prodEntregas_diasmes = date("t", mktime(0,0,0,$prodEntregas_idmes,1,$prodEntregas_idano));
  $prodEntregas_dia1mes = date("N", mktime(0,0,0,$prodEntregas_idmes,1,$prodEntregas_idano));

  if ($prodEntregas_dia1mes == 7)
  { $prodEntregas_dia1mes = 0; }

  if ($prodEntregas_cdgsucursal == '')
  { if ($prodEntregas_cdgproducto == '')
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoclote.fchembarque,
           SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoclote
         WHERE vntsoclote.sttlote = '1' AND
               vntsoclote.fchembarque BETWEEN '".$prodEntregas_idanoprevio."-".str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodEntregas_idanosiguiente."-".str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
      GROUP BY vntsoclote.fchembarque");

      if ($vntsOCLoteSelect->num_rows > 0)
      { while($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
        { $prodEntregas_pendientes[$regVntsOCLote->fchembarque] = $regVntsOCLote->cantidad; }
      } 
    } else
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoclote.fchembarque,
           SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoclote
         WHERE vntsoclote.cdgproducto = '".$prodEntregas_cdgproducto."' AND
               vntsoclote.sttlote = '1' AND
               vntsoclote.fchembarque BETWEEN '".$prodEntregas_idanoprevio."-".str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodEntregas_idanosiguiente."-".str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
      GROUP BY vntsoclote.fchembarque");

      if ($vntsOCLoteSelect->num_rows > 0)
      { while($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
        { $prodEntregas_pendientes[$regVntsOCLote->fchembarque] = $regVntsOCLote->cantidad; }
      }
    }
  } else
  { if ($prodEntregas_cdgproducto == '')
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoclote.fchembarque,
           SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoc,
               vntsoclote
         WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoc.cdgsucursal = '".$prodEntregas_cdgsucursal."' AND
               vntsoclote.sttlote = '1' AND
               vntsoclote.fchembarque BETWEEN '".$prodEntregas_idanoprevio."-".str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodEntregas_idanosiguiente."-".str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
      GROUP BY vntsoclote.fchembarque");

      if ($vntsOCLoteSelect->num_rows > 0)
      { while($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
        { $prodEntregas_pendientes[$regVntsOCLote->fchembarque] = $regVntsOCLote->cantidad; }
      }
    } else
    { $vntsOCLoteSelect = $link->query("
        SELECT vntsoclote.fchembarque,
           SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoc,
               vntsoclote
         WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
               vntsoc.cdgsucursal = '".$prodEntregas_cdgsucursal."' AND
               vntsoclote.cdgproducto = '".$prodEntregas_cdgproducto."' AND
               vntsoclote.sttlote = '1' AND
               vntsoclote.fchembarque BETWEEN '".$prodEntregas_idanoprevio."-".str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodEntregas_idanosiguiente."-".str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
      GROUP BY vntsoclote.fchembarque");

      if ($vntsOCLoteSelect->num_rows > 0)
      { while($regVntsOCLote = $vntsOCLoteSelect->fetch_object())
        { $prodEntregas_pendientes[$regVntsOCLote->fchembarque] = $regVntsOCLote->cantidad; }
      }      
    }
  }
     
  $vntsClienteSelect = $link->query("
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

        $nSucursales[$item] = $vntsSucursalSelect->num_rows; }
    }

    $nClientes = $vntsClienteSelect->num_rows; }

  $pdtoDisenoSelect = $link->query("
    SELECT * FROM pdtodiseno
     WHERE sttdiseno = '1'
  ORDER BY diseno,
           iddiseno");
  
  if ($pdtoDisenoSelect->num_rows > 0)
  { $item = 0;

    while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
    { $item++;

      $pdtoDiseno_iddiseno[$item] = $regPdtoDiseno->iddiseno;
      $pdtoDiseno_diseno[$item] = $regPdtoDiseno->diseno;
      $pdtoDiseno_cdgdiseno[$item] = $regPdtoDiseno->cdgdiseno; 
      
      $pdtoImpresionSelect = $link->query("
        SELECT * FROM pdtoimpresion
         WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' AND 
               sttimpresion = '1'
      ORDER BY impresion,
               idimpresion");
      
      if ($pdtoDisenoSelect->num_rows > 0)
      { $subItem = 0;

        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
        { $subItem++;

          $pdtoImpresion_idimpresion[$item][$subItem] = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion[$item][$subItem] = $regPdtoImpresion->impresion;
          $pdtoImpresion_cdgimpresion[$item][$subItem] = $regPdtoImpresion->cdgimpresion; }

        $nImpresiones[$item] = $pdtoImpresionSelect->num_rows; }
    } 

    $nDisenos = $pdtoDisenoSelect->num_rows; }       
 
  // Armado de la pagina
  echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Calendario de Entregas</label>
        </article>

        <section class="listado">
          <table align="center">
            <thead>
              <tr align="center">
                <th><a href="prodEntregas.php?idano='.$prodEntregas_idanoprevio.'&idmes='.$prodEntregas_idmesprevio.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'&cdgproducto='.$prodEntregas_cdgproducto.'">'.$png_button_blue_rew.'</a></th>
                <th>'.$mestxt[$prodEntregas_idmesprevio].'<br />'.$prodEntregas_idanoprevio.'</th>
                <th colspan="4"><h1>'.$mestxt[$prodEntregas_idmes].' '.$prodEntregas_idano.'</h1></th>
                <th>'.$mestxt[$prodEntregas_idmessiguiente].'<br />'.$prodEntregas_idanosiguiente.'</th>
                <th><a href="prodEntregas.php?idano='.$prodEntregas_idanosiguiente.'&idmes='.$prodEntregas_idmessiguiente.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'">'.$png_button_blue_ffw.'</a></th>
              </tr>
              <tr>';

  for ($dianum = 0; $dianum<=6; $dianum++)
  {  echo '    
                <th>'.$diatxt[$dianum].'</th>'; }

  echo '    
                <th>Semana</th>
              </tr>
            </thead>
      
            <tbody>';
    
  $prodEntregas_iddiamp = (($prodEntregas_diasmesprevio+1)-($prodEntregas_dia1mes));
  $prodEntregas_iddia = 1;
  $prodEntregas_iddiams = 1;

  for ($prodEntregas_idsemana = 1; !$prodEntregas_idsemanas; $prodEntregas_idsemana++)
  {  echo '
              <tr>';
      
    if ($prodEntregas_idsemana == 1)  
    { for ($dianum = 0; $dianum<=6; $dianum++)
      {  if ($dianum >= $prodEntregas_dia1mes)
        { if (checkdate($prodEntregas_idmes, $prodEntregas_iddia, $prodEntregas_idano))
          { $prodEntregas_iddiames = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT);  
            $prodEntregas_idsemana = date("W", mktime(0,0,0,$prodEntregas_idmes,$prodEntregas_iddia,$prodEntregas_idano)); 
            
            if ($dianum == 0) 
            { $prodEntregas_inisemana = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT); 
              $prodEntregas_pendientes_smn = $prodEntregas_pendientes[$prodEntregas_iddiames]; 
            } else
            { $prodEntregas_pendientes_smn = $prodEntregas_pendientes_smn+$prodEntregas_pendientes[$prodEntregas_iddiames]; }
            
            if ($dianum == 6) 
            { $prodEntregas_finsemana = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT); }  
          
            echo '
                <td> 
                  <article class="subbloque" style="background-color:#F2F5A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>
                    
                    <tbody>';
                  
            if ($prodEntregas_pendientes[$prodEntregas_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td><a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_iddiames.'&hstfecha='.$prodEntregas_iddiames.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a></td>
                        <td></td>
                      </tr>'; }              
                
            echo '                    
                    </tbody>
                    
                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>'; 
           
            if (($prodEntregas_iddia) >= $prodEntregas_diasmes) 
            { $prodEntregas_idsemanas = 1; }             
         
            $prodEntregas_iddia++; 
          }
        } else
        { if (checkdate($prodEntregas_idmesprevio, $prodEntregas_iddiamp, $prodEntregas_idanoprevio))
          { $prodEntregas_iddiames = $prodEntregas_idanoprevio.'-'.str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiamp, 2, '0', STR_PAD_LEFT);
            $prodEntregas_idsemana = date("W", mktime(0,0,0,$prodEntregas_idmesprevio,$prodEntregas_iddiamp,$prodEntregas_idanoprevio)); 
            
            if ($dianum == 0) 
            { $prodEntregas_inisemana = $prodEntregas_idanoprevio.'-'.str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiamp, 2, '0', STR_PAD_LEFT);  
              $prodEntregas_pendientes_smn = $prodEntregas_pendientes[$prodEntregas_iddiames]; 
            } else
            { $prodEntregas_pendientes_smn = $prodEntregas_pendientes_smn+$prodEntregas_pendientes[$prodEntregas_iddiames]; }
              
            if ($dianum == 6) 
            { $prodEntregas_finsemana = $prodEntregas_idanoprevio.'-'.str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiamp, 2, '0', STR_PAD_LEFT); } 
                
            echo '
                <td>
                  <article class="subbloque" style="background-color:#F5D0A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodEntregas_iddiamp, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>
                    
                    <tbody>';
                        
            if ($prodEntregas_pendientes[$prodEntregas_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>                          
                        <td><a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_iddiames.'&hstfecha='.$prodEntregas_iddiames.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a></td>
                        <td></td>
                      </tr>'; }              
                
            echo '                    
                    </tbody>
                    
                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>'; 
         
            $prodEntregas_iddiamp++; 
          }  
        }
      }
    } else
    { for ($dianum = 0; $dianum<=6; $dianum++)
      {  if (checkdate($prodEntregas_idmes, $prodEntregas_iddia, $prodEntregas_idano))
        { $prodEntregas_iddiames = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT);
          $prodEntregas_idsemana = date("W", mktime(0,0,0,$prodEntregas_idmes,$prodEntregas_iddia,$prodEntregas_idano)); 
          
          if ($dianum == 0) 
          { $prodEntregas_inisemana = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT);  
            $prodEntregas_pendientes_smn = $prodEntregas_pendientes[$prodEntregas_iddiames]; 
          } else
          { $prodEntregas_pendientes_smn = $prodEntregas_pendientes_smn+$prodEntregas_pendientes[$prodEntregas_iddiames]; }  
          
          if ($dianum == 6) 
          { $prodEntregas_finsemana = $prodEntregas_idano.'-'.str_pad($prodEntregas_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT); }  
          
          echo '
                <td>
                  <article class="subbloque" style="background-color:#F2F5A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodEntregas_iddia, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>
                    
                    <tbody>';
                  
            if ($prodEntregas_pendientes[$prodEntregas_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_iddiames.'&hstfecha='.$prodEntregas_iddiames.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a></td>                     
                      </tr>'; }
                
            echo '
                    </tbody>
                    
                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>'; 
         
          if (($prodEntregas_iddia) >= $prodEntregas_diasmes) 
          { $prodEntregas_idsemanas = 1; }             
         
          $prodEntregas_iddia++; 
        } else
        { if (checkdate($prodEntregas_idmessiguiente, $prodEntregas_iddiams, $prodEntregas_idanosiguiente))
          { $prodEntregas_iddiames = $prodEntregas_idanosiguiente.'-'.str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiams, 2, '0', STR_PAD_LEFT);
            $prodEntregas_idsemana = date("W", mktime(0,0,0,$prodEntregas_idmessiguiente,$prodEntregas_iddiams,$prodEntregas_idanosiguiente)); 

            if ($dianum == 0) 
            { $prodEntregas_inisemana = $prodEntregas_idanosiguiente.'-'.str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiams, 2, '0', STR_PAD_LEFT);  
              $prodEntregas_pendientes_smn = $prodEntregas_pendientes[$prodEntregas_iddiames]; 
            } else
            { $prodEntregas_pendientes_smn = $prodEntregas_pendientes_smn+$prodEntregas_pendientes[$prodEntregas_iddiames]; }
              
            if ($dianum == 6) 
            { $prodEntregas_finsemana = $prodEntregas_idanosiguiente.'-'.str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodEntregas_iddiams, 2, '0', STR_PAD_LEFT); }         
        
            echo '
                <td>
                  <article class="subbloque" style="background-color:#BEF781">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodEntregas_iddiams, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>
                    
                    <tbody>';
                  
            if ($prodEntregas_pendientes[$prodEntregas_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_iddiames.'&hstfecha='.$prodEntregas_iddiames.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a></td>                     
                      </tr>'; }              
                
            echo '                    
                    </tbody>
                    
                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>'; 
               
            $prodEntregas_iddiams++; 
          }  
        }  
      }
    }
    
    echo '
                <td>
                  <article class="subbloque">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodEntregas_idsemana, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>
                    
                    <tbody>';
                  
    if ($prodEntregas_pendientes_smn <= 0)
    { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>'; 
    } else
    { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_inisemana.'&hstfecha='.$prodEntregas_finsemana.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a></td>
                      </tr>'; }              
                
    echo '                    
                    </tbody> 

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>
              </tr>'; }
  
  echo '  
            </tbody>

            <tfoot>
              <tr align="left">
                <td colspan="8">
                  <form id="formProdEntregas" name="formProdEntregas" method="POST" action="prodEntregas.php" onSubmit="return validarNewItem()">
                    <input type="hidden" name="hideDsdFecha" id="hideDsdFecha" value="'.$prodEntregas_idanoprevio."-".str_pad($prodEntregas_idmesprevio, 2, '0', STR_PAD_LEFT)."-01".'">
                    <input type="hidden" name="hideHstFecha" id="hideHstFecha" value="'.$prodEntregas_idanosiguiente."-".str_pad($prodEntregas_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31".'">

                    <article>
                      <label><b>Sucursal</b></label><br/>
                      <select id="slctCdgSucursal" name="slctCdgSucursal" onchange="document.formProdEntregas.submit()" >
                        <option value="">-</option>';

    for ($item = 1; $item <= $nClientes; $item++)
    { echo '
                       <optgroup label="'.$vntsCliente_cliente[$item].'">';
      
      for ($subItem = 1; $subItem <= $nSucursales[$item]; $subItem++)
      { echo '
                       <option value="'.$vntsSucursal_cdgsucursal[$item][$subItem].'"';

        if ($prodEntregas_cdgsucursal == $vntsSucursal_cdgsucursal[$item][$subItem])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$item][$subItem].'</option>'; } 
      
      echo '
                        </optgroup>'; }

    echo '
                      </select>
                    </article>

                    <article>
                      <label><b><a href="../sm_producto/pdtoimpresion.php?cdgimpresion='.$prodEntregas_cdgproducto.'">Productos</a></b></label><br/>
                      <select id="slctCdgProducto" name="slctCdgProducto" onchange="document.formProdEntregas.submit()" >
                        <option value="">-</option>';
    
    for ($item = 1; $item <= $nDisenos; $item++) 
    { echo '
                        <optgroup label="'.$pdtoDiseno_diseno[$item].'">';

      for ($subItem = 1; $subItem <= $nImpresiones[$item]; $subItem++) 
      { echo '
                        <option value="'.$pdtoImpresion_cdgimpresion[$item][$subItem].'"';
            
        if ($prodEntregas_cdgproducto == $pdtoImpresion_cdgimpresion[$item][$subItem]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$item][$subItem].'</option>'; }

      echo '
                        </optgroup>'; }
    
    echo '
                      </select>
                    </article>
                    
                    <article>
                      <label><b>Desde</b></label><br/>
                      <input type="date" id="dateDsdFecha" name="dateDsdFecha" value="'.$prodEntregas_dsdrngfecha.'" style="width:140px" onchange="document.formProdEntregas.submit()" />
                    </article>

                    <article>
                      <label><b>Hasta</b></label><br/>
                      <input type="date" id="dateHstFecha" name="dateHstFecha" value="'.$prodEntregas_hstrngfecha.'" style="width:140px" onchange="document.formProdEntregas.submit()" />
                    </article>

                    <article>
                      <a href="pdf/prodEntregasPdf.php?dsdfecha='.$prodEntregas_dsdrngfecha.'&hstfecha='.$prodEntregas_hstrngfecha.'&cdgsucursal='.$prodEntregas_cdgsucursal.'&cdgproducto='.$prodEntregas_cdgproducto.'" target="_blank">'.$_search.'</a>
                    </article>
                  </form>
                </td>
              </tr>
            </tfoot>
          </table>
        <section>
      </div>';
?>
    </div>
  </body>  
</html>
