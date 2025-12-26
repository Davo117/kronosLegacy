<?php
 if($idtipo!="1")
      {
	if($procesoSiguiente=="refilado" || $procesoSiguiente=="sliteo")
{
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      if(!empty($regPackingList[$i]['anchuraBloque']) and $regPackingList[$i]['refParcial']!=1)
        {
          $medida=round($regPackingList[$i]['anchuraBloque']/$regPackingList[$i]['refParcial']);
        }
        else if(!empty($regPackingList[$i]['amplitud']) and $regPackingList[$i]['refParcial']!=1)
        {
           $medida=round($regPackingList[$i]['amplitud']/$regPackingList[$i]['refParcial']);
        }

      if($medida>0)
      {
          $pdf->Cell(18,4,$medida.' mm',0,1,'R');
      }
      else
      {
        $medida=$regPackingList[$i]['anchoPelicula'];
        $pdf->Cell(18,4,$medida.' mm',0,1,'R');

      }
    
      
      $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Rollos aprox.',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
        
    /*if($regPackingList[$i]['refParcial']!=1 and $procesoSiguiente!="sliteo")
      {
        $pdf->Cell(18,4,round($regPackingList[$i]['refParcial']),0,1,'R');
      }
      else if(!empty($regPackingList[$i]['anchuraBloque']) and $procesoSiguiente!="sliteo")
      {
        $pdf->Cell(18,4,round($regPackingList[$i]['anchuraBloque']/$regPackingList[$i]['anchoPelicula']),0,1,'R');
      }
      else if(!empty($regPackingList[$i]['amplitud']) and $procesoSiguiente!="sliteo")
      {
        $pdf->Cell(18,4,round($regPackingList[$i]['amplitud']/$regPackingList[$i]['anchoPelicula']),0,1,'R');
      }
      else if($procesoSiguiente=="sliteo")
      {*/
        $pdf->Cell(18,4,round($regPackingList[$i]['divisiones']),0,1,'R');
      /*}*/
        
     
             
}
 else if($procesoSiguiente=="fusion")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList[$i]['anchoEtiqueta'].' mm',0,1,'R');
      
      $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Empalme',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList[$i]['espaciofusion'].' mm',0,1,'R');

      }
      else if($procesoSiguiente=="corte")
      {
           $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Altura',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList[$i]['alturaEtiqueta'].' mm',0,1,'R');
      }
      else if($procesoSiguiente=="revision")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,utf8_decode('Producto para revisión'),0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      }
       else if($procesoSiguiente=="revision 2")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,utf8_decode('Producto para revisión 2'),0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      }
      else if($procesoSiguiente=="")
      {

      $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Producto para empaque',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      }
        else if($procesoSiguiente=="foliado")
      {
        $Skul=$MySQLiconn->query("SELECT millaresPorRollo from impresiones where descripcionImpresion='[$i]['producto']");
        $sen=$Skul->fetch_object();

         $pdf->Cell(55,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Millares por rollo',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(26,4,$regPackingList[$i]['millaresPorRollo'].'',0,1,'R');
      
      $pdf->Cell(55,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Rollos',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(26,4,ceil($regPackingList[$i]['unidades']/$regPackingList[$i]['millaresPorRollo']).'',0,1,'R');

      }
      else
      {

      }
    }
      else
      {
             $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(18,4,$regPackingList[$i]['anchura'].' mm',0,1,'R');
      }
?>