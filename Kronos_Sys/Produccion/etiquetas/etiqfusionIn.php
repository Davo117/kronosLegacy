<?php
  require('../../fpdf/fpdflbl.php');

  if(!empty($_GET['etiquetasInd'])) {
    $contador=0;
    // Se inicializa un contador, que, dependiendo de si el documento tendrá divisiones o no, 
    // este va a ser uno, para mostrar el registro unico, o se inicializará en 1,
    // para no mostrar el ultimo registro,
    // que es el registro padre de los modulos que se dividen
    
    $regPackingList = base64_decode($_GET['etiquetasInd']);
    $idtipo=$_GET['idtipo'];
    
    /* Deshacemos el trabajo hecho por 'serialize' */
    $regPackingList = unserialize($regPackingList);
    if($idtipo=="1") {
      $decision="Metros aprox.";
      $marcador="BS.";
    } else {
      $decision="Piezas aprox.";
      $marcador=substr($idtipo,0,3);
      $marcador=strtoupper($marcador).".";
    }

    $limit=count($regPackingList);
    if ($limit > 0) { 
        $pdf=new FPDF('P','mm','lbl4x2'); 
        $pdf->AddFont('3of9','','free3of9.php');

        for($i=0;$i<count($regPackingList);$i++) { 
          $procesoSiguiente=$regPackingList[$i]['procesoSiguiente'];

          $pdf->AddPage();

          $pdf->SetY(22);
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(48,3,'Producto',0,1,'L');
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(98,3,$regPackingList[$i]['descripcionDisenio'],0,1,'L'); 
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(98,3,$regPackingList[$i]['descripcionImpresion'],0,1,'L');       
          
          $pdf->Ln(0.5);

          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(18,3,'Informacion',0,1,'L');

          $pdf->SetFont('Arial','',8);
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(18,3,'Longitud',0,0,'L');
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(28,3,number_format($regPackingList[$i]['longitud'],2).' mts',0,1,'R'); 

          $pdf->SetFont('Arial','',8);
          $pdf->Cell(14,3,'',0,0,'L');
          $pdf->Cell(18,3,'Ancho plano',0,0,'L');
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell(28,3,$regPackingList[$i]['amplitud'].' mm',0,1,'R'); 

          $pdf->SetFont('Arial','',8);
          $pdf->Cell(14,3,'',0,0,'L'); 
          $pdf->Cell(28,3,'Piezas aprox',0,0,'L');
          $pdf->SetFont('Arial','B',8); 
          $pdf->Cell(18,3,number_format($regPackingList[$i]['unidades']*1000),0,1,'R'); 
         
          $pdf->SetY(-7);     
          $pdf->Cell(14,5,'',0,0,'L');      
          $pdf->SetFont('Arial','B',14);
          $pdf->Cell(68,5,'No.OP: '.$marcador.$regPackingList[$i]['noop'],0,1,'R');

          // Información
          $pdf->SetY(31.5);
          
          $pdf->SetFont('Arial','B',8); 
          $pdf->Cell(64,3,'',0,0,'L'); 
          $pdf->Cell(22,3,$procesoSiguiente ,0,1,'L');
          
          //$pdf->Ln(3);

          $pdf->Cell(64,3,'',0,0,'L'); 
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(46,3,'v25.410.957',0,1,'L'); 

          $pdf->Cell(64,3,'',0,0,'L'); 
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(46,3,$regPackingList[$i]['fecha'],0,1,'L'); 

          //Coloca estos datos dependiendo del proceso que siga
          include('armado_etiquetas.php');

          // Código de barras
          $pdf->SetY(12);
          //$pdf->SetFont('3of9','',34);
          $pdf->SetFont('3of9','',40);
          $pdf->Cell(50,5,'',0,0,'R'); 
          $pdf->Cell(40,5,'*'.$regPackingList[$i]['codigoBarras'].'*',0,1,'R');
          
          $pdf->Ln(2);
          $pdf->SetFont('Arial','',8); 
          $pdf->Cell(50,3,'',0,0,'R'); 
          $pdf->Cell(40,3,$regPackingList[$i]['codigoBarras'],0,1,'C'); 

          // Imagen LOGOTIPO
          //if (file_exists('../../pictures/logo-labro.jpg')==true) 
          //{ $pdf->Image('../../pictures/logo-labro.jpg',14,12,14); }
        }

        $pdf->Output();
    } else { 
      echo '<html>
      <head>    
        <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
      </head>
      <body>
        <p>Referencia inválida</p>
      </body>
    </html>';
    }
  } else {
    echo '<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../../css/global.css" /> 
  </head>
  <body>
    <p class="message">Tiempo de espera agotado,vuelva a generar los códigos</p>
  </body>
</html>';
  }

  ob_end_flush();
?>