<?php
 if($tipo!="1")
      {
        
  if($procesoSiguiente=="refilado")
{
  $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 

       if(!empty($regPackingList->anchuraBloque) and $regPackingList->refParcial!=1)
        {
          $medida=round($regPackingList->anchuraBloque/$regPackingList->refParcial);
        }
        if(!empty($regPackingList->amplitud) and $regPackingList->refParcial!=1)
        {
          $medida=round($regPackingList->amplitud/$regPackingList->refParcial);
        }
      if($medida>0)
      {
          $pdf->Cell(18,4,$medida.' mm',0,1,'R');
      }
      else
      {
        $medida=$regPackingList->anchoPelicula;
        $pdf->Cell(18,4,$medida.' mm',0,1,'R');

      }
    
      
     $pdf->Cell(52,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(28,4,'Bobinas totales',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      if($regPackingList->refParcial!=1)
      {
        $pdf->Cell(18,4,round($regPackingList->refParcial),0,1,'R');
      }
      else if(!empty($regPackingList->anchuraBloque))
      {
        $pdf->Cell(18,4,round($regPackingList->anchuraBloque/$regPackingList->anchoPelicula),0,1,'R');
      }
      else if(!empty($regPackingList->amplitud))
      {
        $pdf->Cell(18,4,round($regPackingList->amplitud/$regPackingList->anchoPelicula),0,1,'R');
      }
           
        
     
             
}
 else if($procesoSiguiente=="fusion")
      {
         $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Ancho',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->anchoEtiqueta.' mm',0,1,'R');
      
      $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Empalme',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->espaciofusion.' mm',0,1,'R');

      }
      else if($procesoSiguiente=="corte")
      {
           $pdf->Cell(60,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Altura',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(20,4,$regPackingList->alturaEtiqueta.' mm',0,1,'R');
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
        $Skul=$MySQLiconn->query("SELECT millaresPorRollo from impresiones where descripcionImpresion='$producto'");
        $sen=$Skul->fetch_object();

         $pdf->Cell(55,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Millares por rollo',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(26,4,$regPackingList->millaresPorRollo.'',0,1,'R');
      
      $pdf->Cell(55,4,'',0,0,'L'); 
      $pdf->SetFont('Arial','',10); 
      $pdf->Cell(12,4,'Rollos',0,0,'L');
      $pdf->SetFont('Arial','B',12); 
      $pdf->Cell(26,4,ceil($regPackingList->unidades/$regPackingList->millaresPorRollo).'',0,1,'R');

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
      $pdf->Cell(18,4,$regPackingList->anchura.' mm',0,1,'R');
      }
?>