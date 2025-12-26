<!DOCTYPE html>
<html>
  <head> 		  
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />     
  </head> 
  <body><?php
  
  include '../datos/mysql.php';
   
  // --> Nombre de los dias
	$diatxt[1] = "Lunes";
	$diatxt[2] = "Martes";
	$diatxt[3] = "Miercoles";
	$diatxt[4] = "Jueves";
	$diatxt[5] = "Viernes";
	$diatxt[6] = "Sabado";	  	
	$diatxt[7] = "Domingo";
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
  
  if ($_POST['date_dsdfecha'])
  { $prodCalEntrega_dsdfecha = $_POST['date_dsdfecha']; }
  else
  { $prodCalEntrega_dsdfecha = date(); }

  if ($_POST['date_dsdfecha']) { $prodCalEntrega_dsdrngfecha = $_POST['date_dsdfecha']; }
  if ($_POST['date_hstfecha']) { $prodCalEntrega_hstrngfecha = $_POST['date_hstfecha']; }

  $prodCalEntrega_dsdrngfecha = ValidarFecha($prodCalEntrega_dsdrngfecha);
  $prodCalEntrega_hstrngfecha = ValidarFecha($prodCalEntrega_hstrngfecha);

  if (($_POST['hide_dsdfecha'] AND $_POST['hide_hstfecha']) AND ($_POST['slc_cdgsucursal'] OR $_POST['slc_cdgproducto']))
  { $prodCalEntrega_cdgsucursal = $_POST['slc_cdgsucursal'];
    $prodCalEntrega_cdgproducto = $_POST['slc_cdgproducto'];
    $prodCalEntrega_dsdfecha = $_POST['hide_dsdfecha'];
    $prodCalEntrega_hstfecha = $_POST['hide_hstfecha'];

    $prodCalEntrega_idmesprevio = substr($prodCalEntrega_dsdfecha,5,2); 
    $prodCalEntrega_idanoprevio = substr($prodCalEntrega_dsdfecha,0,4); 

    if ($prodCalEntrega_idmesprevio == 12)
    { $prodCalEntrega_idano = $prodCalEntrega_idanoprevio+1;
      $prodCalEntrega_idmes = 1; 
    } else
    { $prodCalEntrega_idano = $prodCalEntrega_idanoprevio;
      $prodCalEntrega_idmes = $prodCalEntrega_idmesprevio+1; } 

    $prodCalEntrega_idmessiguiente = substr($prodCalEntrega_hstfecha,5,2);
    $prodCalEntrega_idanosiguiente = substr($prodCalEntrega_hstfecha,0,4);
  } else
  { $prodCalEntrega_cdgsucursal = $_GET['cdgsucursal'];
    $prodCalEntrega_cdgproducto = $_GET['cdgproducto'];

    if ($_GET['idano'] AND $_GET['idmes'])
    { $prodCalEntrega_idano = $_GET['idano'];
      $prodCalEntrega_idmes = $_GET['idmes']; }
    else
    { $prodCalEntrega_idano = date("Y"); 
      $prodCalEntrega_idmes = date("n"); }
      
    if ($prodCalEntrega_idmes == 1)


    { $prodCalEntrega_idmesprevio = 12; 
      $prodCalEntrega_idanoprevio = $prodCalEntrega_idano-1; 

      $prodCalEntrega_idmessiguiente = 2;
      $prodCalEntrega_idanosiguiente = $prodCalEntrega_idano;
    }
    else
    { if ($prodCalEntrega_idmes == 12)
      { $prodCalEntrega_idmesprevio = 11; 
        $prodCalEntrega_idanoprevio = $prodCalEntrega_idano; 

        $prodCalEntrega_idmessiguiente = 1;
        $prodCalEntrega_idanosiguiente = $prodCalEntrega_idano+1;
      }
      else
      { $prodCalEntrega_idmesprevio = $prodCalEntrega_idmes-1; 
        $prodCalEntrega_idanoprevio = $prodCalEntrega_idano; 

        $prodCalEntrega_idmessiguiente = $prodCalEntrega_idmes+1;
        $prodCalEntrega_idanosiguiente = $prodCalEntrega_idano;
      }
    }
  }

	$prodCalEntrega_diasmesprevio = date("t", mktime(0,0,0,$prodCalEntrega_idmesprevio,1,$prodCalEntrega_idanoprevio));
	$prodCalEntrega_diasmessiguiente = date("t", mktime(0,0,0,$prodCalEntrega_idmessiguiente,1,$prodCalEntrega_idanosiguiente));
	$prodCalEntrega_diasmes = date("t", mktime(0,0,0,$prodCalEntrega_idmes,1,$prodCalEntrega_idano));
	$prodCalEntrega_dia1mes = date("N", mktime(0,0,0,$prodCalEntrega_idmes,1,$prodCalEntrega_idano));

  if ($prodCalEntrega_cdgsucursal != '' AND $prodCalEntrega_cdgproducto != '')
  { // Busqueda de compromisos por fecha embarque 
    $link_mysqli = conectar();
    $prodCalEntregaSelectLote= $link_mysqli->query("
      SELECT vntsoclote.fchentrega,
    SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
      FROM vntsoc,
        vntsoclote
      WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
        vntsoc.cdgsucursal = '".$prodCalEntrega_cdgsucursal."' AND
        vntsoclote.cdgproducto = '".$prodCalEntrega_cdgproducto."' AND        
        vntsoclote.fchentrega BETWEEN '".$prodCalEntrega_idanoprevio."-".str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalEntrega_idanosiguiente."-".str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
      GROUP BY vntsoclote.fchentrega");

      if ($prodCalEntregaSelectLote->num_rows > 0)
      { while($regProdCalEntrega = $prodCalEntregaSelectLote->fetch_object())
        { $prodFchCalEntrega[$regProdCalEntrega->fchentrega] = $regProdCalEntrega->cantidad; }
      } 
  } else
  { if ($prodCalEntrega_cdgsucursal == '' AND $prodCalEntrega_cdgproducto == '')
    { // Busqueda de compromisos por fecha embarque 
      $link_mysqli = conectar();
      $prodCalEntregaSelectLote= $link_mysqli->query("
        SELECT vntsoclote.fchentrega,
      SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
        FROM vntsoclote
        WHERE vntsoclote.fchentrega BETWEEN '".$prodCalEntrega_idanoprevio."-".str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalEntrega_idanosiguiente."-".str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
        GROUP BY vntsoclote.fchentrega");

        if ($prodCalEntregaSelectLote->num_rows > 0)
        { while($regProdCalEntrega = $prodCalEntregaSelectLote->fetch_object())
          { $prodFchCalEntrega[$regProdCalEntrega->fchentrega] = $regProdCalEntrega->cantidad; }
        }      
    } else
    { if ($prodCalEntrega_cdgsucursal != '')
      { // Busqueda de compromisos por fecha embarque 
        $link_mysqli = conectar();
        $prodCalEntregaSelectLote= $link_mysqli->query("
          SELECT vntsoclote.fchentrega,
        SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoc,
            vntsoclote
          WHERE vntsoc.cdgoc = vntsoclote.cdgoc AND
            vntsoc.cdgsucursal = '".$prodCalEntrega_cdgsucursal."' AND            
            vntsoclote.fchentrega BETWEEN '".$prodCalEntrega_idanoprevio."-".str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalEntrega_idanosiguiente."-".str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
          GROUP BY vntsoclote.fchentrega");

          if ($prodCalEntregaSelectLote->num_rows > 0)
          { while($regProdCalEntrega = $prodCalEntregaSelectLote->fetch_object())
            { $prodFchCalEntrega[$regProdCalEntrega->fchentrega] = $regProdCalEntrega->cantidad; }
          } 
      }
      
      if ($prodCalEntrega_cdgproducto != '')
      { // Busqueda de compromisos por fecha embarque 
        $link_mysqli = conectar();
        $prodCalEntregaSelectLote= $link_mysqli->query("
          SELECT vntsoclote.fchentrega,
        SUM(vntsoclote.cantidad-vntsoclote.surtido) AS cantidad
          FROM vntsoclote
          WHERE vntsoclote.cdgproducto = '".$prodCalEntrega_cdgproducto."' AND            
            vntsoclote.fchentrega BETWEEN '".$prodCalEntrega_idanoprevio."-".str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalEntrega_idanosiguiente."-".str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31'
          GROUP BY vntsoclote.fchentrega");

          if ($prodCalEntregaSelectLote->num_rows > 0)
          { while($regProdCalEntrega = $prodCalEntregaSelectLote->fetch_object())
            { $prodFchCalEntrega[$regProdCalEntrega->fchentrega] = $regProdCalEntrega->cantidad; }
          } 
      }
    }
  }

      $link_mysqli = conectar();
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
              $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal] = $regVntsSucursal->cdgsucursal;

              $vntsSucursales_sucursal[$regVntsSucursal->cdgsucursal] = $regVntsSucursal->sucursal;

              $id_sucursal++; }

            $num_sucursales[$id_cliente] = $vntsSucursalSelect->num_rows; }

          $id_cliente++; }

        $num_clientes = $vntsClienteSelect->num_rows; }  

    $link_mysqli = conectar(); 
    $pdtoProyectoSelect = $link_mysqli->query("
      SELECT * FROM pdtoproyecto
      WHERE sttproyecto = '1'
      ORDER BY proyecto,
        idproyecto");
    
    if ($pdtoProyectoSelect->num_rows > 0)
    { $id_proyecto = 1;
      while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object()) 
      { $pdtoProyecto_idproyecto[$id_proyecto] = $regPdtoProyecto->idproyecto;
        $pdtoProyecto_proyecto[$id_proyecto] = $regPdtoProyecto->proyecto;
        $pdtoProyecto_cdgproyecto[$id_proyecto] = $regPdtoProyecto->cdgproyecto; 
        
        $link_mysqli = conectar(); 
        $pdtoImpresionSelect = $link_mysqli->query("
          SELECT * FROM pdtoimpresion
          WHERE cdgproyecto = '".$regPdtoProyecto->cdgproyecto."' AND 
            sttimpresion = '1'
          ORDER BY impresion,
            idimpresion");
        
        if ($pdtoProyectoSelect->num_rows > 0)
        { $id_impresion = 1;
          while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
          { $pdtoImpresion_idimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->idimpresion;
            $pdtoImpresion_impresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->impresion;
            $pdtoImpresion_ancho[$id_proyecto][$id_impresion] = $regPdtoImpresion->ancho;
            $pdtoImpresion_alpaso[$id_proyecto][$id_impresion] = $regPdtoImpresion->alpaso;
            $pdtoImpresion_ceja[$id_proyecto][$id_impresion] = $regPdtoImpresion->ceja;
            $pdtoImpresion_tolerancia[$id_proyecto][$id_impresion] = $regPdtoImpresion->tolerancia;      
            $pdtoImpresion_corte[$id_proyecto][$id_impresion] = $regPdtoImpresion->corte;
            $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->cdgimpresion;  

            $id_impresion++; }

          $numimpresiones[$id_proyecto] = $pdtoImpresionSelect->num_rows; }

        $id_proyecto++; } 

      $numproyectos = $pdtoProyectoSelect->num_rows; }       
  
  // Armado de la pagina
  echo '
    <br/>
    <table align="center">
      <thead>
        <tr align="center">
          <th><a href="vntsCalEntrega.php?idano='.$prodCalEntrega_idanoprevio.'&idmes='.$prodCalEntrega_idmesprevio.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'&cdgproducto='.$prodCalEntrega_cdgproducto.'">'.$png_button_blue_rew.'</a></th>
          <th>'.$mestxt[$prodCalEntrega_idmesprevio].'<br />'.$prodCalEntrega_idanoprevio.'</th>
          <th colspan="4"><h1>'.$mestxt[$prodCalEntrega_idmes].' '.$prodCalEntrega_idano.'</h1></th>
          <th>'.$mestxt[$prodCalEntrega_idmessiguiente].'<br />'.$prodCalEntrega_idanosiguiente.'</th>
          <th><a href="vntsCalEntrega.php?idano='.$prodCalEntrega_idanosiguiente.'&idmes='.$prodCalEntrega_idmessiguiente.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'">'.$png_button_blue_ffw.'</a></th>
        </tr>
        <tr>';

	for ($dianum = 1; $dianum<=7; $dianum++)
	{	echo '	  
          <th>'.$diatxt[$dianum].'</th>'; }

	echo '	  
          <th>Semana</th>
        </tr>
      </thead>
      
      <tbody>';
    
	$prodCalEntrega_iddiamp = (($prodCalEntrega_diasmesprevio+1)-($prodCalEntrega_dia1mes-1));
	$prodCalEntrega_iddia = 1;
	$prodCalEntrega_iddiams = 1;

	for ($prodCalEntrega_idsemana = 1; !$prodCalEntrega_idsemanas; $prodCalEntrega_idsemana++)
	{	echo '
        <tr>';
			
		if ($prodCalEntrega_idsemana == 1)  
		{ for ($dianum = 1; $dianum<=7; $dianum++)
			{	if ($dianum >= $prodCalEntrega_dia1mes)
				{ if (checkdate($prodCalEntrega_idmes, $prodCalEntrega_iddia, $prodCalEntrega_idano))
					{ $prodCalEntrega_iddiames = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT);  
						$prodCalEntrega_idsemana = date("W", mktime(0,0,0,$prodCalEntrega_idmes,$prodCalEntrega_iddia,$prodCalEntrega_idano)); 
            
            if ($dianum == 1) 
            { $prodCalEntrega_inisemana = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT); 
              $prodFchCalEntrega_smn = $prodFchCalEntrega[$prodCalEntrega_iddiames]; } 
            else
            { $prodFchCalEntrega_smn = $prodFchCalEntrega_smn+$prodFchCalEntrega[$prodCalEntrega_iddiames]; }
						
            if ($dianum == 7) 
            { $prodCalEntrega_finsemana = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT); }  
				  
						echo '
          <td style="background:orange;"> 
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:orange;">'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalEntrega[$prodCalEntrega_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_iddiames.'&hstfecha='.$prodCalEntrega_iddiames.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
						if (($prodCalEntrega_iddia) >= $prodCalEntrega_diasmes) 
            { $prodCalEntrega_idsemanas = 1; }	           
				 
						$prodCalEntrega_iddia++; 
					}
				}
				else
				{ if (checkdate($prodCalEntrega_idmesprevio, $prodCalEntrega_iddiamp, $prodCalEntrega_idanoprevio))
					{ $prodCalEntrega_iddiames = $prodCalEntrega_idanoprevio.'-'.str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiamp, 2, '0', STR_PAD_LEFT);
						$prodCalEntrega_idsemana = date("W", mktime(0,0,0,$prodCalEntrega_idmesprevio,$prodCalEntrega_iddiamp,$prodCalEntrega_idanoprevio)); 
            
            if ($dianum == 1) 
            { $prodCalEntrega_inisemana = $prodCalEntrega_idanoprevio.'-'.str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiamp, 2, '0', STR_PAD_LEFT);  
              $prodFchCalEntrega_smn = $prodFchCalEntrega[$prodCalEntrega_iddiames]; } 
            else
            { $prodFchCalEntrega_smn = $prodFchCalEntrega_smn+$prodFchCalEntrega[$prodCalEntrega_iddiames]; }
              
						if ($dianum == 7) 
            { $prodCalEntrega_finsemana = $prodCalEntrega_idanoprevio.'-'.str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiamp, 2, '0', STR_PAD_LEFT); } 
								
						echo '
          <td style="background:OrangeRed;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:OrangeRed;">'.str_pad($prodCalEntrega_iddiamp, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalEntrega[$prodCalEntrega_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_iddiames.'&hstfecha='.$prodCalEntrega_iddiames.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
						$prodCalEntrega_iddiamp++; 
					}  
				}
      }
		}
		else
		{ for ($dianum = 1; $dianum<=7; $dianum++)
			{	if (checkdate($prodCalEntrega_idmes, $prodCalEntrega_iddia, $prodCalEntrega_idano))
				{ $prodCalEntrega_iddiames = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT);
					$prodCalEntrega_idsemana = date("W", mktime(0,0,0,$prodCalEntrega_idmes,$prodCalEntrega_iddia,$prodCalEntrega_idano)); 
          
          if ($dianum == 1) 
          { $prodCalEntrega_inisemana = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT);  
            $prodFchCalEntrega_smn = $prodFchCalEntrega[$prodCalEntrega_iddiames]; } 
          else
          { $prodFchCalEntrega_smn = $prodFchCalEntrega_smn+$prodFchCalEntrega[$prodCalEntrega_iddiames]; }  
					
          if ($dianum == 7) 
          { $prodCalEntrega_finsemana = $prodCalEntrega_idano.'-'.str_pad($prodCalEntrega_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT); }  
				  
					echo '
          <td style="background:orange;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:orange;">'.str_pad($prodCalEntrega_iddia, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalEntrega[$prodCalEntrega_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_iddiames.'&hstfecha='.$prodCalEntrega_iddiames.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
					if (($prodCalEntrega_iddia) >= $prodCalEntrega_diasmes) 
          { $prodCalEntrega_idsemanas = 1; }	           
				 
					$prodCalEntrega_iddia++; 
				} 				
				else
				{ if (checkdate($prodCalEntrega_idmessiguiente, $prodCalEntrega_iddiams, $prodCalEntrega_idanosiguiente))
					{ $prodCalEntrega_iddiames = $prodCalEntrega_idanosiguiente.'-'.str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiams, 2, '0', STR_PAD_LEFT);
						$prodCalEntrega_idsemana = date("W", mktime(0,0,0,$prodCalEntrega_idmessiguiente,$prodCalEntrega_iddiams,$prodCalEntrega_idanosiguiente)); 

            if ($dianum == 1) 
            { $prodCalEntrega_inisemana = $prodCalEntrega_idanosiguiente.'-'.str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiams, 2, '0', STR_PAD_LEFT);  
              $prodFchCalEntrega_smn = $prodFchCalEntrega[$prodCalEntrega_iddiames]; } 
            else
            { $prodFchCalEntrega_smn = $prodFchCalEntrega_smn+$prodFchCalEntrega[$prodCalEntrega_iddiames]; }
              
						if ($dianum == 7) 
            { $prodCalEntrega_finsemana = $prodCalEntrega_idanosiguiente.'-'.str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalEntrega_iddiams, 2, '0', STR_PAD_LEFT); } 				
				
						echo '
          <td style="background:OliveDrab;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:OliveDrab;">'.str_pad($prodCalEntrega_iddiams, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalEntrega[$prodCalEntrega_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_iddiames.'&hstfecha='.$prodCalEntrega_iddiames.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>              
            </table>
          </td>'; 
			   
						$prodCalEntrega_iddiams++; 
					}  
				}	
			}
		}
    
		echo '	  
          <td style="background:Gray;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:Gray;">'.str_pad($prodCalEntrega_idsemana, 2, '0', STR_PAD_LEFT).'</th>
                </tr>				    
              </thead>
              
              <tbody>';
                  
    if ($prodFchCalEntrega_smn <= 0)
    { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
    else
    { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_inisemana.'&hstfecha='.$prodCalEntrega_finsemana.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>
                </tr>'; }              
                
    echo '	                  
              </tbody> 

              <tfoot>
              </tfoot>
            </table>
          </td>
        </tr>';
	}		  
  
	echo '	
      </tbody>';

  echo '
      <tfoot>
        <form id="form_ventas" name="form_ventas" method="POST" action="vntsCalEntrega.php" onSubmit="return validarNewItem()">
          <input type="hidden" name="hide_dsdfecha" id="hide_dsdfecha" value="'.$prodCalEntrega_idanoprevio."-".str_pad($prodCalEntrega_idmesprevio, 2, '0', STR_PAD_LEFT)."-01".'">
          <input type="hidden" name="hide_hstfecha" id="hide_hstfecha" value="'.$prodCalEntrega_idanosiguiente."-".str_pad($prodCalEntrega_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31".'">

        <tr><td align="left" colspan="2"><strong>Sucursal</strong><br/>
            <select id="slc_cdgsucursal" name="slc_cdgsucursal" onchange="document.form_ventas.submit()" style="width:120px;">
              <option value=""> Elije una sucursal </option>';

    for ($id_cliente = 1; $id_cliente <= $num_clientes; $id_cliente++)
    { echo '
              <optgroup label="'.$vntsCliente_cliente[$id_cliente].'">';
      
      for ($id_sucursal = 1; $id_sucursal <= $num_sucursales[$id_cliente]; $id_sucursal++)
      { echo '
              <option value="'.$vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal].'"';

        if ($prodCalEntrega_cdgsucursal == $vntsSucursal_cdgsucursal[$id_cliente][$id_sucursal])
        { echo ' selected="selected"'; }

          echo '>'.$vntsSucursal_sucursal[$id_cliente][$id_sucursal].'</option>'; } 
      
      echo '
              </optgroup>'; }

    echo '
            </select></td>
          <td align="left" colspan="2"><strong>Producto</strong><br/>
            <select id="slc_cdgproducto" name="slc_cdgproducto" onchange="document.form_ventas.submit()" style="width:120px;">
              <option value="">Selecciona una opcion</option>';
    
    for ($id_proyecto = 1; $id_proyecto <= $numproyectos; $id_proyecto++) 
    { echo '
              <optgroup label="'.$pdtoProyecto_proyecto[$id_proyecto].'">';

      for ($id_impresion = 1; $id_impresion <= $numimpresiones[$id_proyecto]; $id_impresion++) 
      { echo '
              <option value="'.$pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion].'"';
            
        if ($prodCalEntrega_cdgproducto == $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$id_proyecto][$id_impresion].'</option>'; }

      echo '
              </optgroup>'; }
    
    echo '
            </select></td>
          <td align="center" colspan="4"><strong>Rango de fechas</strong> <a href="pdf/vntsCalEntregaPdf.php?dsdfecha='.$prodCalEntrega_dsdrngfecha.'&hstfecha='.$prodCalEntrega_hstrngfecha.'&cdgsucursal='.$prodCalEntrega_cdgsucursal.'&cdgproducto='.$prodCalEntrega_cdgproducto.'" target="_blank">'.$png_acrobat.'</a><br/>
            <label for="label_dsdfecha"><strong>Desde</strong></label>
            <input type="date" id="date_dsdfecha" name="date_dsdfecha" value="'.$prodCalEntrega_dsdrngfecha.'" onchange="document.form_ventas.submit()" style="width:140px;" />
            <label for="label_hstfecha"><strong>Hasta</strong></label>
            <input type="date" id="date_hstfecha" name="date_hstfecha" value="'.$prodCalEntrega_hstrngfecha.'" onchange="document.form_ventas.submit()" style="width:140px;" /></td></tr>  
        </form>    
      </tfoot>
    </table>';
    
?>

  </body>	
</html>