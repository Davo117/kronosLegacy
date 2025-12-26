<?php
  require '../../datos/mysql.php';
	require('../../fpdf/fpdf.php');
	
  $seguimiento_fchdesde = valorFecha($_POST['txt_fchdesde']);
  $seguimiento_fchhasta = valorFecha($_POST['txt_fchhasta']);
  
  if ($seguimiento_fchdesde == '') { $seguimiento_fchdesde = date("Y-m-d"); }
  if ($seguimiento_fchhasta == '') { $seguimiento_fchhasta = date("Y-m-d"); }   
  
  $link_mysqli = conectar();
  $ingeOperacionSelect = $link_mysqli->query("
    SELECT * FROM ingeoperacion
    WHERE cdgoperacion = '".$_POST['slc_cdgoperacion']."'");
  $regIngeOperacion = $ingeOperacionSelect->fetch_object();
  $_POST['operacion'] = $regIngeOperacion->operacion;   

  $_GET['numMaxCrd'] = $numMaxCrd; // Cantidad de tallas disponibles 
  
  $numcosto = 1;
  $link_mysqli = conectar();	
  $pdtoCostoSelect = $link_mysqli->query("CALL pdtoCostoSelectAll()"); 
  while ($regPdtoCosto = $pdtoCostoSelect->fetch_object())
  { $_GET['costo'][$numcosto] = $regPdtoCosto->costo;
    $_GET['cdgcosto'][$numcosto] = $regPdtoCosto->cdgcosto;
    
    $numcosto++; }
  
  $_GET['numcostos'] = $numcosto;  
  $numcostos = $numcosto;  // Cantidad de costos disponibles  

  for ($numcrd=1;$numcrd<=$numMaxCrd; $numcrd++)
  { $link_mysqli = conectar();
    $prodSubLoteCntdSelect = $link_mysqli->query("
      SELECT prodsubloteope.cdgsublote,
        SUM(prodsublotecntd.cantidad) AS cantidad
      FROM prodsubloteope,
        prodsublotecntd
      WHERE (prodsubloteope.fchmovimiento BETWEEN '".$seguimiento_fchdesde."' AND '".$seguimiento_fchhasta."') 
      AND prodsubloteope.cdgsublote = prodsublotecntd.cdgsublote
      AND prodsubloteope.cdgoperacion = '".$_POST['slc_cdgoperacion']."' 
      AND prodsublotecntd.idtalla LIKE '".$numcrd."%'      
      GROUP BY prodsubloteope.cdgsublote");
    
    if ($prodSubLoteCntdSelect->num_rows > 0)
    { while ($regProdSubLoteCntd = $prodSubLoteCntdSelect->fetch_object())
      { $prodInventarioSeccion_cantidadcrd[$regProdSubLoteCntd->cdgsublote][$numcrd] = $regProdSubLoteCntd->cantidad; }
    }
  }  
  
  $link_mysqli = conectar();
  $prodInventarioCostosSelect = $link_mysqli->query("
    SELECT pdtocostos.cdgproducto, 
      pdtocostos.cdgcosto, 
      pdtocostos.cdgtalla, 
      pdtocostos.costo 
    FROM prodsubloteope,
      pdtocostos
    WHERE (prodsubloteope.fchmovimiento BETWEEN '".$seguimiento_fchdesde."' AND '".$seguimiento_fchhasta."')
    AND (prodsubloteope.cdgoperacion = '".$_POST['slc_cdgoperacion']."'
    AND prodsubloteope.cdgproducto = pdtocostos.cdgproducto)
    GROUP BY pdtocostos.cdgproducto, 
      pdtocostos.cdgcosto, 
      pdtocostos.cdgtalla, 
      pdtocostos.costo");
      
  if ($prodInventarioCostosSelect->num_rows > 0)
  { while ($regProdInventariosCostos = $prodInventarioCostosSelect->fetch_object())
    { $prodInventarioSeccion_costos[$regProdInventariosCostos->cdgproducto][$regProdInventariosCostos->cdgcosto][$regProdInventariosCostos->cdgtalla] = $regProdInventariosCostos->costo; }
  } 
  
  $numgrupo = 1; 
  $link_mysqli = conectar();
  $prodProgramaSelect = $link_mysqli->query("
    SELECT SUBSTRING(cdgproducto,1,3) AS cdgmarca,
      pdtomarca.marca
    FROM prodsubloteope,
      pdtomarca
    WHERE (prodsubloteope.fchmovimiento BETWEEN '".$seguimiento_fchdesde."' AND '".$seguimiento_fchhasta."')
    AND prodsubloteope.cdgoperacion = '".$_POST['slc_cdgoperacion']."'
    AND SUBSTRING(cdgproducto,1,3) = pdtomarca.cdgmarca
    GROUP BY SUBSTRING(cdgproducto,1,3)
    ORDER BY SUBSTRING(cdgproducto,1,3)");
      
  while ($regProdPrograma = $prodProgramaSelect->fetch_object())
  { $prodInventarioSeccion_grupo[$numgrupo] = $regProdPrograma->cdgmarca;    
    $prodInventarioSeccion_marca[$numgrupo] = $regProdPrograma->marca;    
    
    $numgrupo++; }
    
  $numgrupos = $numgrupo;  // Cantidad de grupos disponibles
    //*/
	class PDF extends FPDF
	{ function Header()
		{	if ($_SESSION['usuario'] == '')
      { $_SESSION['usuario'] = 'Invitado'; }
      
      $this->SetFont('arial','B',8);
			$this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,1,'R');
      $this->Cell(0,4,'Inventario costeado del '.$_POST['txt_fchdesde'].' al '.$_POST['txt_fchhasta'].' de la operacion '.$_POST['operacion'],0,1,'R');
      
			$this->SetFillColor(180,180,180);
			$this->SetFont('arial','B',9);
      		
      $this->Cell(57,5,'',0,0,'L');       
      for ($numcosto=1; $numcosto<$_GET['numcostos']; $numcosto++)
      { $this->Cell(46,5,$_GET['costo'][$numcosto],1,0,'C'); }          
      	
      $this->Ln(); // Salto de Linea
      
      $this->SetFont('arial','B',8);
			$this->Cell(16,5,'O.F.',1,0,'L',true);
      $this->Cell(14,5,'Modelo',1,0,'L',true);
      
      for ($numcrd=1; $numcrd<=$_GET['numMaxCrd']; $numcrd++)
			{ $this->Cell(5,5,'C'.$numcrd,1,0,'C',true); }
      
			$this->Cell(7,5,'Prs',1,0,'L',true);
      
      for ($numcosto=1; $numcosto<$_GET['numcostos']; $numcosto++)
      { for ($numcrd=1; $numcrd<=$_GET['numMaxCrd']; $numcrd++)
        { $this->Cell(11.5,5,'C'.$numcrd,1,0,'C',true); }        
      }
      
      $this->Ln(); // Salto de Linea 
		}
	}							
		
	$pdf=new PDF('P','mm','Letter');				
	$pdf->AliasNbPages();
	$pdf->SetDisplayMode(real, continuous); 
  $pdf->SetFont('arial','',7);
  $pdf->AddPage();
  
  $pdf->SetFillColor(210,210,210);
  for ($numgrupo=1; $numgrupo<=$numgrupos; $numgrupo++)
  { $link_mysqli = conectar();
    $prodInventarioSubLoteSelect = $link_mysqli->query("
    SELECT prodsubloteope.cdgsublote,
      vntspedidolote.idof,
      prodsublote.idsublote,     
      prodsubloteope.cdgproducto,
      vntspedidolote.cdgarticulo,
      pdtoarticulo.idmodelo,
      SUM(prodsublotecntd.cantidad) AS cantidad
    FROM prodsubloteope,
      vntspedidolote,
      prodsublote,
      prodsublotecntd,
      pdtoarticulo
    WHERE (prodsubloteope.fchmovimiento BETWEEN '".$seguimiento_fchdesde."' AND '".$seguimiento_fchhasta."') AND   
      prodsubloteope.cdgoperacion = '".$_POST['slc_cdgoperacion']."' AND
      SUBSTRING(prodsubloteope.cdgproducto,1,3) = '".$prodInventarioSeccion_grupo[$numgrupo]."' AND  
      prodsubloteope.cdgsublote = prodsublote.cdgsublote AND
      vntspedidolote.cdglote = prodsublote.cdglote AND      
      prodsubloteope.cdgsublote = prodsublotecntd.cdgsublote AND
      vntspedidolote.cdgarticulo = pdtoarticulo.cdgarticulo
    GROUP BY prodsubloteope.cdgsublote
    ORDER BY prodsubloteope.cdgsublote");
    
    if ($prodInventarioSubLoteSelect->num_rows > 0)
    { while ($regProdInventarioSubLote = $prodInventarioSubLoteSelect->fetch_object())
      { $pdf->Cell(16,4,$regProdInventarioSubLote->idof.'-'.$regProdInventarioSubLote->idsublote,1,0,'L');
        $pdf->Cell(14,4,$regProdInventarioSubLote->idmodelo,1,0,'L');  
        
        for ($numcrd=1; $numcrd<=$numMaxCrd; $numcrd++)
			  { $pdf->Cell(5,4,$prodInventarioSeccion_cantidadcrd[$regProdInventarioSubLote->cdgsublote][$numcrd],1,0,'R'); }
              
        $pdf->Cell(7,4,$regProdInventarioSubLote->cantidad,1,0,'R'); 
        
        for ($numcosto=1; $numcosto<$numcostos; $numcosto++)
        { for ($numcrd=1; $numcrd<=$numMaxCrd; $numcrd++)
          { $prodInventarioSeccion_cdgtalla = $numcrd*100;
            
            $costoxcorrida = ($prodInventarioSeccion_cantidadcrd[$regProdInventarioSubLote->cdgsublote][$numcrd]*($prodInventarioSeccion_costos[$regProdInventarioSubLote->cdgproducto][$_GET['cdgcosto'][$numcosto]][$prodInventarioSeccion_cdgtalla]));
            if ($costoxcorrida > 0)
            { $pdf->Cell(11.5,4,number_format($costoxcorrida,2),1,0,'R'); }
            else
            { $pdf->Cell(11.5,4,'',1,0,'R'); }
            
            $prodInventarioSeccion_costosgrupo[$numgrupo][$numcosto] += $costoxcorrida;            
            $prodInventarioSeccion_costos[$numcosto] += $costoxcorrida;
            
            $prodInventarioSeccion_costos['0'] += $costoxcorrida;
          }  
        }
        
        $pdf->Ln(); // Salto de Linea         
        
        $prodInventarioSeccion_cantidadgrupo[$numgrupo] += $regProdInventarioSubLote->cantidad;
        
        $prodInventarioSeccion_cantidad += $regProdInventarioSubLote->cantidad; 
      }
  
      $pdf->SetFont('arial','B',8);
      $pdf->Cell(30,4,$prodInventarioSeccion_marca[$numgrupo],0,0,'R',true);
      
      for ($numcrd=1; $numcrd<=$numMaxCrd; $numcrd++)
      { $pdf->Cell(5,4,'',0,0,'R',true); }
      
      $pdf->Cell(7,4,$prodInventarioSeccion_cantidadgrupo[$numgrupo],0,0,'R',true); 
            
      for ($numcosto=1; $numcosto<$numcostos; $numcosto++)
      { $pdf->Cell(46,4,number_format($prodInventarioSeccion_costosgrupo[$numgrupo][$numcosto],2),0,0,'R',true); } 
            
      $pdf->Ln(); // Salto de Linea 
      $pdf->SetFont('arial','',7);
    }  //*/
    
    $pdf->Ln(); // Salto de Linea 
  } 
  
  $pdf->SetFont('arial','B',9);
  $pdf->SetFillColor(180,180,180);

  $pdf->Cell(50,4,'Pares',1,0,'L',true);
  $pdf->Cell(20,4,$prodInventarioSeccion_cantidad,1,1,'R',true); 
  $pdf->Ln(); // Salto de Linea  
  
  $pdf->Cell(70,4,'Costos al '.$_GET['porcentaje'].'%',1,1,'L',true);
  for ($numcosto=1; $numcosto<$numcostos; $numcosto++)
  { $pdf->Cell(50,4,$_GET['costo'][$numcosto],1,0,'L');
    $pdf->Cell(20,4,number_format($prodInventarioSeccion_costos[$numcosto],2),1,1,'R'); }
     
  $pdf->Cell(50,4,'Costos total',1,0,'L',true);
  $pdf->Cell(20,4,number_format($prodInventarioSeccion_costos['0'],2),1,1,'R',true);  
        
  $pdf->Ln(); // Salto de Linea  
        
	$pdf->Output('Inventario costeado al '.$_GET['fchinventario'].' de la seccion '.$_GET['seccion'], 'I'); 
?>
