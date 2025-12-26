<?php

  include '../../datos/mysql.php';	
  require('../../fpdf/fpdf.php');

  $link = conectar();

  if ($_GET['cdgproducto'])
  { $pdtoImpresionSelect = $link->query("
      SELECT pdtodiseno.diseno,
             pdtoimpresion.impresion
        FROM pdtodiseno,
             pdtoimpresion
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = '".$_GET['cdgproducto']."'");
    
    if ($pdtoImpresionSelect->num_rows > 0)
    { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

      $alptInventario_diseno = $regPdtoImpresion->diseno;
      $alptInventario_impresion = $regPdtoImpresion->impresion;

      $alptEmpaqueSelect = $link->query("
        SELECT alptempaque.cdgempaque,
        CONCAT(alptempaque.tpoempaque, alptempaque.noempaque) AS empaque,
           SUM(alptempaquep.cantidad) AS millares
          FROM alptempaque, 
               alptempaquep
         WHERE alptempaque.cdgempaque = alptempaquep.cdgempaque AND
               alptempaque.cdgproducto = '".$_GET['cdgproducto']."' AND
               alptempaque.tpoempaque = 'C' AND
               alptempaque.sttempaque = '1'
      GROUP BY alptempaque.cdgempaque");

      if ($alptEmpaqueSelect->num_rows > 0)
      { $item = 0;

        while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object()) 
        { $item++;

          $alptEmpaque_cdgempaque[$item] = $regAlptEmpaque->cdgempaque;
          $alptEmpaque_empaque[$item] = $regAlptEmpaque->empaque;
          $alptEmpaque_cantidad[$item] = $regAlptEmpaque->millares; }

        $nEmpaques = $alptEmpaqueSelect->num_rows; }
    }

    class PDF extends FPDF
    { // Cabecera de página
      function Header()
      { global $alptInventario_diseno;
        global $alptInventario_impresion;

        $this->SetFont('Arial','B',8);
        $this->SetFillColor(255,153,0);

        if (file_exists('../../img_sistema/logonew.jpg')==true)
        { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Inventario de producto terminado'),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode(''),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode(''),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode(''),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode(''),0,1,'L'); 

        $this->Ln(4);

        $this->SetFont('Arial','I',8);
        $this->Cell(40,4,utf8_decode('Diseño'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','B',8);
        $this->Cell(60,4,utf8_decode($alptInventario_diseno),0,1,'L');

        $this->SetFont('Arial','I',8);
        $this->Cell(40,4,utf8_decode('Impresión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','B',8);
        $this->Cell(60,4,utf8_decode($alptInventario_impresion),0,1,'L');

        $this->SetFont('Arial','I',8);
        $this->Cell(40,4,utf8_decode('Presentación'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);  
        $this->SetFont('Arial','B',8);
        $this->Cell(60,4,utf8_decode('Pieza cortada'),0,1,'L');

        $this->Ln(4); 

        $this->SetFont('Arial','B',12);
        $this->SetX(30);
        $this->Cell(30,6,'Empaque',0,0,'L');
        $this->Cell(40,6,'Barcode',0,0,'L');
        $this->Cell(40,6,'Cantidad',0,1,'R');
      }
    }

    $pdf = new PDF('P','mm','letter');
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddFont('3of9','','free3of9.php');
    $pdf->AliasNbPages();
    
    $pdf->AddPage();

    for ($item = 1; $item <= $nEmpaques; $item++)
    { $pdf->SetX(30);
      $pdf->SetFont('Arial','B',14);
      $pdf->Cell(30,8,$alptEmpaque_empaque[$item],0,0,'L');
      $pdf->SetFont('3of9','',24);
      $pdf->Cell(40,8,$alptEmpaque_cdgempaque[$item],0,0,'L');
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(40,8,number_format($alptEmpaque_cantidad[$item],3,'.',''),0,0,'R'); 
      $pdf->Cell(5,5,'',1,0,'R');
      $pdf->Ln(8);

      $alptEmpaques_cantidad += $alptEmpaque_cantidad[$item]; }

    $pdf->SetFont('Arial','B',14);
    $pdf->SetX(100);
    $pdf->Cell(40,8,number_format($alptEmpaques_cantidad,3,'.','').' mlls',0,1,'R');

    $pdf->Ln(8);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(195.9,4,'______________________________________________________',0,1,'C');
    $pdf->Cell(195.9,4,utf8_decode('Nombre y firma del responsable'),0,1,'C');

    $pdf->Output();
  } else
  { 
    echo '
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="/css/2014.css" media="screen" />
  </head>
  <body>
    <div id="contenedor">
      <label class="modulo_nombre">Sin existencias...</label>
    </div>
  </body>
</html>'; }
?>