<?php
  include '../../datos/mysql.php';
  include '../../fpdf/fpdf.php';

  $link = conectar();

  $rptproducto = 'Todos';
  $rptoperacion = 'Todas';

  if ($_GET[cdgproducto])
  { $queryselectproducto = $link->query("
      SELECT pdtodiseno.alto,
             pdtodiseno.alpaso,
             pdtoimpresion.impresion
        FROM pdtodiseno,
             pdtoimpresion
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = '".$_GET[cdgproducto]."'");

    if ($queryselectproducto->num_rows > 0)
    { $regproducto = $queryselectproducto->fetch_object();

      $rptproducto = $regproducto->impresion;
      $rptproducto_alto = $regproducto->alto;
      $rptproducto_alpaso = $regproducto->alpaso; }
  }

  if ($_GET[cdgoperacion])
  { $queryselectoperacion = $link->query("
      SELECT * FROM prodoperacion
       WHERE cdgoperacion = '".$_GET[cdgoperacion]."'"); 

    if ($queryselectoperacion->num_rows > 0)
    { $regoperacion = $queryselectoperacion->fetch_object();

      $rptoperacion = $regoperacion->operacion; }
  }

  if ($_GET[fchini] == '') { $_GET[fchini] = date('Y-m-d'); }
  if ($_GET[fchfin] == '') { $_GET[fchfin] = date('Y-m-d'); }

  $query20001p = "
    SELECT rechempleado.empleado,
           prodlote.noop,
           prodloteope.longin,
          (prodloteope.longin*pdtodiseno.alpaso)/pdtodiseno.alto AS mllrin,
           prodloteope.longout,
          (prodloteope.longout*pdtodiseno.alpaso)/pdtodiseno.alto AS mllrout,
           prodloteope.fchmovimiento
      FROM rechempleado,
           pdtodiseno,
           pdtoimpresion,
           prodlote,
           prodloteope 
     WHERE prodloteope.cdgempleado = rechempleado.cdgempleado AND
          (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
           prodlote.cdgproducto = '".$_GET[cdgproducto]."' AND
          (prodlote.cdglote = prodloteope.cdglote AND
           prodloteope.cdgoperacion = '20001') AND 
          (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
  ORDER BY prodloteope.fchmovimiento";

  $query30001p = "
    SELECT rechempleado.empleado,
           CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
           prodbobinaope.longitud AS longin,
          (prodbobinaope.longitud/pdtodiseno.alto) AS mllrin,
           prodbobinaope.longitudfin AS longout,
          (prodbobinaope.longitudfin/pdtodiseno.alto) AS mllrout,
           prodbobinaope.fchmovimiento
      FROM rechempleado,
           pdtodiseno,
           pdtoimpresion,
           prodlote,
           prodbobina,
           prodbobinaope 
     WHERE prodbobinaope.cdgempleado = rechempleado.cdgempleado AND
          (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = prodbobina.cdgproducto) AND
           prodbobina.cdgproducto = '".$_GET[cdgproducto]."' AND
          (prodlote.cdglote = prodbobina.cdglote AND
           prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
           prodbobinaope.cdgoperacion = '30001') AND 
          (prodbobinaope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
  ORDER BY prodbobinaope.fchmovimiento";

  $query20001 = "
    SELECT rechempleado.empleado,
           prodlote.noop,
           pdtoimpresion.impresion AS producto,
           prodloteope.longin,
          (prodloteope.longin*pdtodiseno.alpaso)/pdtodiseno.alto AS mllrin,
           prodloteope.longout,
          (prodloteope.longout*pdtodiseno.alpaso)/pdtodiseno.alto AS mllrout,
           prodloteope.fchmovimiento
      FROM rechempleado,
           pdtodiseno,
           pdtoimpresion,
           prodlote,
           prodloteope 
     WHERE prodloteope.cdgempleado = rechempleado.cdgempleado AND
          (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
          (prodlote.cdglote = prodloteope.cdglote AND
           prodloteope.cdgoperacion = '20001') AND 
          (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
  ORDER BY prodloteope.fchmovimiento";

   $query30001 = "
    SELECT rechempleado.empleado,
           CONCAT(prodlote.noop,'-',prodbobina.bobina) AS noop,
           pdtoimpresion.impresion AS producto,
           prodbobinaope.longitud AS longin,
          (prodbobinaope.longitud/pdtodiseno.alto) AS mllrin,
           prodbobinaope.longitudfin AS longout,
          (prodbobinaope.longitudfin/pdtodiseno.alto) AS mllrout,
           prodbobinaope.fchmovimiento
      FROM rechempleado,
           pdtodiseno,
           pdtoimpresion,
           prodlote,
           prodbobina,
           prodbobinaope 
     WHERE prodbobinaope.cdgempleado = rechempleado.cdgempleado AND
          (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
           pdtoimpresion.cdgimpresion = prodbobina.cdgproducto) AND                   
          (prodlote.cdglote = prodbobina.cdglote AND
           prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
           prodbobinaope.cdgoperacion = '30001') AND 
          (prodbobinaope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
  ORDER BY prodbobinaope.fchmovimiento";

  if ($_GET[cdgproducto])  
  { if ($_GET[cdgoperacion])
    { # Filtrar producto y operacion
      switch ($_GET[cdgoperacion]) {
        case '20001':
          $queryselect20001 = $link->query($query20001p);
        break;

        case '30001':
          $queryselect30001 = $link->query($query30001p);
        break;

        default:
          # Filtrar solo por producto
        break;
      } 
    } else
    { $queryselect20001 = $link->query($query20001p);

      $queryselect30001 = $link->query($query30001p); }
  } else
  { if ($_GET[cdgoperacion])
    { # Filtrar por operacion
      switch ($_GET[cdgoperacion]) {
        case '20001':
          $queryselect20001 = $link->query($query20001);
        break;

        case '30001':
          $queryselect30001 = $link->query($query30001);
        break;
        default:
          # Filtrar todo...
        break;
      }
    } else
    { $queryselect20001 = $link->query($query20001);

      $queryselect30001 = $link->query($query30001); }
  }

  class PDF extends FPDF
  { function Header()
    { global $rptproducto;
      global $rptoperacion;

      if ($_SESSION['usuario'] == '') { $_SESSION['usuario'] = 'Invitado'; }

      if (file_exists('../../img_sistema/logonew.jpg')==true)
      { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(180,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Reporte de producción'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(180,4,utf8_decode('Producto'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$rptproducto,0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(180,4,utf8_decode('Operación'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$rptoperacion,0,1,'L');          

      $this->SetFont('Arial','B',8);
      $this->Cell(180,4,utf8_decode('Fechas'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,$_GET[fchini].' al '.$_GET[fchfin],0,1,'L');

      $this->Ln(4.15);

      $this->SetFont('Arial','B',12);
      $this->Cell(60,7,'Empleado',0,0,'C');
      $this->Cell(20,7,'NoOP',0,0,'C');
      $this->Cell(76,7,'Producto',0,0,'C');

      $this->SetFont('Arial','B',9);
      $this->Cell(32,3,'Entradas',0,0,'C');
      $this->Cell(32,3,'Salidas',0,0,'C');
      $this->Cell(40,3,'Fecha',0,1,'C');

      $this->Cell(156,4,'',0,0,'C');
      $this->Cell(16,4,'Metros',0,0,'C');
      $this->Cell(16,4,'Millares',0,0,'C');
      $this->Cell(16,4,'Metros',0,0,'C');
      $this->Cell(16,4,'Millares',0,0,'C');
      $this->Cell(40,4,'Movimiento',0,1,'C');

      $this->Line(10,$this->GetY(),265,$this->GetY());
      $this->Ln(1);
    }
  }

  $pdf=new PDF('L','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddPage();

  if ($queryselect20001->num_rows > 0)
  { $nrow = 0;
    while ($regoperacion = $queryselect20001->fetch_object())
    { $nrow++;

      $pdf->SetFont('Arial','',7);
      $pdf->Cell(6,4,$nrow,0,0,'R');

      if (($nrow%2) == 1) { $pdf->SetFont('Arial','I',8); }
      else { $pdf->SetFont('Arial','B',8); }

      $pdf->Cell(54,4,$regoperacion->empleado,0,0,'L');
      $pdf->Cell(20,4,$regoperacion->noop,0,0,'C');
      $pdf->Cell(76,4,$regoperacion->producto,0,0,'L');
      $pdf->Cell(16,4,number_format($regoperacion->longin,2,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->mllrin,3,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->longout,2,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->mllrout,3,'.',''),0,0,'R');
      $pdf->Cell(40,4,$regoperacion->fchmovimiento,0,1,'C'); 

      $rptmllrin += number_format($regoperacion->mllrin,3,'.','');
      $rptmllrout += number_format($regoperacion->mllrout,3,'.',''); }

    $pdf->Line(10,$pdf->GetY(),265,$pdf->GetY());
    $pdf->Ln(1); 

    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(126);
    $pdf->Cell(40,6,utf8_decode('Impresión'),0,0,'L');
    $pdf->Cell(32,6,$rptmllrin,0,0,'R');
    $pdf->Cell(32,6,$rptmllrout,0,0,'R');
    $pdf->Cell(16,6,number_format((100-(($rptmllrout*100)/$rptmllrin)),2,'.',''),0,1,'R');
    $pdf->Ln(2);
  }

  if ($queryselect30001->num_rows > 0)
  { $rptmllrin = 0;
    $rptmllrout = 0;

    $nrow = 0;
    while ($regoperacion = $queryselect30001->fetch_object())
    { $nrow++;

      $pdf->SetFont('Arial','',7);
      $pdf->Cell(6,4,$nrow,0,0,'R');

      if (($nrow%2) == 1) { $pdf->SetFont('Arial','I',8); }
      else { $pdf->SetFont('Arial','B',8); }

      $pdf->Cell(54,4,$regoperacion->empleado,0,0,'L');
      $pdf->Cell(20,4,$regoperacion->noop,0,0,'C');
      $pdf->Cell(76,4,$regoperacion->producto,0,0,'L');
      $pdf->Cell(16,4,number_format($regoperacion->longin,2,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->mllrin,3,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->longout,2,'.',''),0,0,'R');
      $pdf->Cell(16,4,number_format($regoperacion->mllrout,3,'.',''),0,0,'R');
      $pdf->Cell(40,4,$regoperacion->fchmovimiento,0,1,'C'); 

      $rptmllrin += number_format($regoperacion->mllrin,3,'.','');
      $rptmllrout += number_format($regoperacion->mllrout,3,'.',''); }

    $pdf->Line(10,$pdf->GetY(),265,$pdf->GetY());
    $pdf->Ln(1); 

    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(126);
    $pdf->Cell(40,6,utf8_decode('Refilado'),0,0,'L');
    $pdf->Cell(32,6,$rptmllrin,0,0,'R');
    $pdf->Cell(32,6,$rptmllrout,0,0,'R');
    $pdf->Cell(16,6,number_format((100-(($rptmllrout*100)/$rptmllrin)),2,'.',''),0,1,'R');
    $pdf->Ln(2);
  }  
/*
  if ($_GET['cdgproducto'])
  { $queryselectproducto = $link->query("
      SELECT pdtodiseno.alto,
             pdtodiseno.alpaso,
             pdtoimpresion.impresion
        FROM pdtodiseno,
             pdtoimpresion
       WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = '".$_GET['cdgproducto']."'");

    if ($queryselectproducto->num_rows > 0)
    { $regproducto = $queryselectproducto->fetch_object();

      $rptproducto = $regproducto->impresion;
      $rptproducto_alto = $regproducto->alto;
      $rptproducto_alpaso = $regproducto->alpaso;


      $queryMerma = $link->query("
        SELECT SUM(proglote.longitud) AS inside,
               SUM(((proglote.longitud/pdtodiseno.alto)*pdtodiseno.alpaso)) AS insidep,
               SUBSTR(prodloteope.fchoperacion,1,7) AS mes
          FROM pdtodiseno,
               pdtoimpresion,
               proglote,
               prodlote, 
               prodloteope
         WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
               (proglote.cdglote = prodlote.cdglote AND
                prodlote.cdglote = prodloteope.cdglote AND
                prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
                prodloteope.cdgoperacion = '20001') AND
               (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
GROUP BY SUBSTR(prodloteope.fchoperacion,1,7)");  

      if ($queryMerma->num_rows > 0)
      { $query2Merma = $link->query("
          SELECT SUM(prodlote.longitud) AS outside,
                 SUM(((prodlote.longitud/pdtodiseno.alto)*pdtodiseno.alpaso)) AS outsidep,
                 SUBSTR(prodloteope.fchoperacion,1,7) AS mes
            FROM pdtodiseno,
                 pdtoimpresion,
                 proglote,
                 prodlote, 
                 prodloteope
          WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
                 pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND
                (proglote.cdglote = prodlote.cdglote AND
                 prodlote.sttlote != 'C' AND
                 prodlote.cdglote = prodloteope.cdglote AND 
                 prodlote.cdgproducto = '".$_GET['cdgproducto']."' AND
                 prodloteope.cdgoperacion = '20001') AND
                (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
        GROUP BY SUBSTR(prodloteope.fchoperacion,1,7)");

        if ($query2Merma->num_rows > 0)
        { while($reg2Mes = $query2Merma->fetch_object())
          { $outside[$reg2Mes->mes] = $reg2Mes->outside; 
            $outsidep[$reg2Mes->mes] = $reg2Mes->outsidep; }
        }

        $pdf->SetFont('arial','I',10);
        $pdf->Cell(32,4,utf8_decode('Impresión'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Entradas'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Salidas'),0,1,'L');

        $pdf->SetFont('arial','B',8);
        $pdf->Cell(12,4,'Mes',0,0,'C',true); 
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(20,4,'Porcentaje',0,1,'C',true);

        $pdf->SetFont('arial','',8);
        while($regMes = $queryMerma->fetch_object())
        { $sumaMtrsInside['20001'][$_GET['cdgproducto']] += $regMes->inside;
          $sumaMllsInside['20001'][$_GET['cdgproducto']] += $regMes->insidep;
          $sumaMtrsOutside['20001'][$_GET['cdgproducto']] += $outside[$regMes->mes];
          $sumaMllsOutside['20001'][$_GET['cdgproducto']] += $outsidep[$regMes->mes];

          $pdf->Cell(12,4,$regMes->mes,0,0,'R'); 
          $pdf->Cell(40,4,number_format($regMes->inside),0,0,'R');
          $pdf->Cell(40,4,number_format($regMes->insidep,3,'.',','),0,0,'R');
          $pdf->Cell(40,4,number_format($outside[$regMes->mes]),0,0,'R');
          $pdf->Cell(40,4,number_format($outsidep[$regMes->mes],3,'.',','),0,0,'R');
          $pdf->Cell(20,4,number_format((100-(($outside[$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',0,1,'R'); } 

        $pdf->SetFont('arial','I',9); 
        $pdf->Cell(12,4,'Totales',0,0,'L'); 
        $pdf->Cell(40,4,number_format($sumaMtrsInside['20001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsInside['20001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMtrsOutside['20001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsOutside['20001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(20,4,number_format((100-(($sumaMllsOutside['20001'][$_GET['cdgproducto']]*100)/$sumaMllsInside['20001'][$_GET['cdgproducto']])),2,'.','').' %',0,1,'R'); }
      
      $queryMerma = $link->query("
        SELECT SUM(prodlote.longitud) AS inside, 
               SUBSTR(prodbobinaope.fchoperacion,1,7) AS mes
          FROM prodlote,
               prodbobina, 
               prodbobinaope               
         WHERE prodlote.cdglote = prodbobina.cdglote AND
              (prodbobinaope.cdgbobina = prodbobina.cdgbobina AND 
               prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
               prodbobinaope.cdgoperacion = '30001')
      GROUP BY SUBSTR(prodbobinaope.fchoperacion,1,7)");

      if ($queryMerma->num_rows > 0)
      { $query2Merma = $link->query("
          SELECT SUM(prodbobina.longitud) AS outside, 
                 SUBSTR(prodbobinaope.fchoperacion,1,7) AS mes
            FROM prodbobina, 
                 prodbobinaope,
                 prodlote
           WHERE prodlote.cdglote = prodbobina.cdglote AND
                (prodbobinaope.cdgbobina = prodbobina.cdgbobina AND 
                 prodbobina.cdgproducto = '".$_GET['cdgproducto']."' AND
                 prodbobinaope.cdgoperacion = '30001')
        GROUP BY SUBSTR(prodbobinaope.fchoperacion,1,7)");

        if ($query2Merma->num_rows > 0)
        { while($reg2Mes = $query2Merma->fetch_object())
          { $outside[$reg2Mes->mes] = $reg2Mes->outside; }
        } 

        $pdf->Ln(2);
        $pdf->SetFont('arial','I',10);
        $pdf->Cell(32,4,utf8_decode('Sliteo'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Entradas'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Salidas'),0,1,'L');

        $pdf->SetFont('arial','B',8);
        $pdf->Cell(12,4,'Mes',0,0,'C',true); 
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(20,4,'Porcentaje',0,1,'C',true);

        $pdf->SetFont('arial','',8);
        while($regMes = $queryMerma->fetch_object())
        { $sumaMtrsInside['30001'][$_GET['cdgproducto']] += ($regMes->inside/$rptproducto_alpaso);
          $sumaMllsInside['30001'][$_GET['cdgproducto']] += ($regMes->inside/$rptproducto_alto);
          $sumaMtrsOutside['30001'][$_GET['cdgproducto']] += $outside[$regMes->mes];
          $sumaMllsOutside['30001'][$_GET['cdgproducto']] += ($outside[$regMes->mes]/$rptproducto_alto);

          $pdf->Cell(12,4,$regMes->mes,0,0,'R'); 
          $pdf->Cell(40,4,number_format(($regMes->inside/$rptproducto_alpaso)),0,0,'R');
          $pdf->Cell(40,4,number_format(($regMes->inside/$rptproducto_alto),3,'.',','),0,0,'R');
          $pdf->Cell(40,4,number_format($outside[$regMes->mes]),0,0,'R');
          $pdf->Cell(40,4,number_format(($outside[$regMes->mes]/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(20,4,number_format((100-(($outside[$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',0,1,'R'); } 

        $pdf->SetFont('arial','I',9); 
        $pdf->Cell(12,4,'Totales',0,0,'L'); 
        $pdf->Cell(40,4,number_format($sumaMtrsInside['30001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsInside['30001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMtrsOutside['30001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsOutside['30001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(20,4,number_format((100-(($sumaMllsOutside['30001'][$_GET['cdgproducto']]*100)/$sumaMllsInside['30001'][$_GET['cdgproducto']])),2,'.','').' %',0,1,'R'); }
      
      $queryMerma = $link->query("
        SELECT SUM(prodbobina.longitud) AS inside,
               SUBSTR(prodbobinaope.fchoperacion,1,7) AS mes
          FROM prodbobina,
               prodbobinaope
         WHERE prodbobinaope.cdgoperacion = '40001' AND
               prodbobinaope.cdgbobina = prodbobina.cdgbobina AND
               prodbobina.cdgproducto = '".$_GET['cdgproducto']."'
        GROUP BY SUBSTR(prodbobinaope.fchoperacion,1,7)");

      if ($queryMerma->num_rows > 0)
      { $query2Merma = $link->query("
          SELECT SUM(prodrollo.longitud) AS outside,
                 SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
            FROM prodrollo,
                 prodrolloope,
                 prodbobina
           WHERE prodbobina.cdgbobina = prodrollo.cdgbobina AND
                (prodrolloope.cdgoperacion = '40001' AND
                 prodrolloope.cdgrollo = prodrollo.cdgrollo AND
                 prodrollo.cdgproducto = '".$_GET['cdgproducto']."')
          GROUP BY SUBSTR(prodrolloope.fchoperacion,1,7)");

        if ($query2Merma->num_rows > 0)
        { while($reg2Mes = $query2Merma->fetch_object())
          { $outside[$reg2Mes->mes] = $reg2Mes->outside; }
        }  

        $pdf->Ln(2);
        $pdf->SetFont('arial','I',10);
        $pdf->Cell(32,4,utf8_decode('Fusión'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Entradas'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Salidas'),0,1,'L');

        $pdf->SetFont('arial','B',8);
        $pdf->Cell(12,4,'Mes',0,0,'C',true); 
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(20,4,'Porcentaje',0,1,'C',true);

        $pdf->SetFont('arial','',8);
        while($regMes = $queryMerma->fetch_object())
        { $sumaMtrsInside['40001'][$_GET['cdgproducto']] += $regMes->inside;
          $sumaMllsInside['40001'][$_GET['cdgproducto']] += ($regMes->inside/$rptproducto_alto);
          $sumaMtrsOutside['40001'][$_GET['cdgproducto']] += $outside[$regMes->mes];
          $sumaMllsOutside['40001'][$_GET['cdgproducto']] += ($outside[$regMes->mes]/$rptproducto_alto);

          $pdf->Cell(12,4,$regMes->mes,0,0,'R'); 
          $pdf->Cell(40,4,number_format($regMes->inside),0,0,'R');
          $pdf->Cell(40,4,number_format(($regMes->inside/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(40,4,number_format($outside[$regMes->mes]),0,0,'R');
          $pdf->Cell(40,4,number_format(($outside[$regMes->mes]/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(20,4,number_format((100-(($outside[$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',0,1,'R'); } 

        $pdf->SetFont('arial','I',9); 
        $pdf->Cell(12,4,'Totales',0,0,'L'); 
        $pdf->Cell(40,4,number_format($sumaMtrsInside['40001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsInside['40001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMtrsOutside['40001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsOutside['40001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(20,4,number_format((100-(($sumaMllsOutside['40001'][$_GET['cdgproducto']]*100)/$sumaMllsInside['40001'][$_GET['cdgproducto']])),2,'.','').' %',0,1,'R'); }

      $queryMerma = $link->query("
        SELECT SUM(prodrolloope.longitud) AS inside,
               SUM(prodrolloope.longitudfin) AS outside,
               SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
          FROM prodrollo,
               prodrolloope
         WHERE prodrolloope.cdgoperacion = '40006' AND
               prodrolloope.cdgrollo = prodrollo.cdgrollo AND
               prodrollo.cdgproducto = '".$_GET['cdgproducto']."'
        GROUP BY SUBSTR(prodrolloope.fchoperacion,1,7)");

      if ($queryMerma->num_rows > 0)
      { $pdf->Ln(2);
        $pdf->SetFont('arial','I',10);
        $pdf->Cell(32,4,utf8_decode('Revisión'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Entradas'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Salidas'),0,1,'L');

        $pdf->SetFont('arial','B',8);
        $pdf->Cell(12,4,'Mes',0,0,'C',true); 
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(20,4,'Porcentaje',0,1,'C',true);

        $pdf->SetFont('arial','',8);
        while($regMes = $queryMerma->fetch_object())
        { $sumaMtrsInside['40006'][$_GET['cdgproducto']] += ($regMes->inside);
          $sumaMllsInside['40006'][$_GET['cdgproducto']] += ($regMes->inside/($rptproducto_alto));
          $sumaMtrsOutside['40006'][$_GET['cdgproducto']] += ($regMes->outside);
          $sumaMllsOutside['40006'][$_GET['cdgproducto']] += ($regMes->outside/($rptproducto_alto));

          $pdf->Cell(12,4,$regMes->mes,0,0,'R'); 
          $pdf->Cell(40,4,number_format($regMes->inside),0,0,'R');
          $pdf->Cell(40,4,number_format(($regMes->inside/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(40,4,number_format($regMes->outside),0,0,'R');
          $pdf->Cell(40,4,number_format(($regMes->outside/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(20,4,number_format((100-(($regMes->outside*100)/$regMes->inside)),2,'.',',').' %',0,1,'R'); } 

        $pdf->SetFont('arial','I',9); 
        $pdf->Cell(12,4,'Totales',0,0,'L'); 
        $pdf->Cell(40,4,number_format($sumaMtrsInside['40006'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsInside['40006'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMtrsOutside['40006'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsOutside['40006'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(20,4,number_format((100-(($sumaMllsOutside['40006'][$_GET['cdgproducto']]*100)/$sumaMllsInside['40006'][$_GET['cdgproducto']])),2,'.','').' %',0,1,'R'); }

      $queryMerma = $link->query("
        SELECT SUM(prodrolloope.longitud) AS inside, 
            SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
          FROM pdtoimpresion, 
               prodrollo, 
               prodrolloope
         WHERE prodrolloope.cdgrollo = prodrollo.cdgrollo AND 
               prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND 
               pdtoimpresion.cdgimpresion = '".$_GET['cdgproducto']."' AND
               prodrolloope.cdgoperacion = '50001'
      GROUP BY SUBSTR(prodrolloope.fchoperacion,1,7)");

      if ($queryMerma->num_rows > 0)
      { $query2Merma = $link->query("
          SELECT SUM(prodpaquete.cantidad) AS outside, 
              SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
            FROM prodpaquete, 
                 prodrollo, 
                 prodrolloope
           WHERE prodpaquete.cdgrollo = prodrollo.cdgrollo AND
                 prodpaquete.sttpaquete != 'C' AND
                (prodrolloope.cdgoperacion = '50001' AND
                 prodrolloope.cdgrollo = prodrollo.cdgrollo AND              
                 prodpaquete.cdgproducto = '".$_GET['cdgproducto']."')             
        GROUP BY SUBSTR(prodrolloope.fchoperacion,1,7)"); 

        if ($query2Merma->num_rows > 0)
        { while($reg2Mes = $query2Merma->fetch_object())
          { $outside[$reg2Mes->mes] = $reg2Mes->outside; }
        } 

        $pdf->Ln();    
        $pdf->SetFont('arial','I',10);
        $pdf->Cell(32,4,utf8_decode('Corte'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Entradas'),0,0,'L');
        $pdf->Cell(80,4,utf8_decode('Salidas'),0,1,'L');

        $pdf->SetFont('arial','B',8);
        $pdf->Cell(12,4,'Mes',0,0,'C',true); 
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(40,4,'Metros',0,0,'C',true);
        $pdf->Cell(40,4,'Millares',0,0,'C',true);
        $pdf->Cell(20,4,'Porcentaje',0,1,'C',true);

        $pdf->SetFont('arial','',8);
        while($regMes = $queryMerma->fetch_object())
        { $sumaMtrsInside['50001'][$_GET['cdgproducto']] += ($regMes->inside);
          $sumaMllsInside['50001'][$_GET['cdgproducto']] += ($regMes->inside/($rptproducto_alto));
          $sumaMtrsOutside['50001'][$_GET['cdgproducto']] += ($outside[$regMes->mes]*$rptproducto_alto);
          $sumaMllsOutside['50001'][$_GET['cdgproducto']] += ($outside[$regMes->mes]);

          $pdf->Cell(12,4,$regMes->mes,0,0,'R'); 
          $pdf->Cell(40,4,number_format($regMes->inside),0,0,'R');
          $pdf->Cell(40,4,number_format(($regMes->inside/($rptproducto_alto)),3,'.',','),0,0,'R');
          $pdf->Cell(40,4,number_format($outside[$regMes->mes]*$rptproducto_alto),0,0,'R');
          $pdf->Cell(40,4,number_format(($outside[$regMes->mes]),3,'.',','),0,0,'R');
          $pdf->Cell(20,4,number_format((100-(($outside[$regMes->mes]*100)/($regMes->inside/$rptproducto_alto))),2,'.',',').' %',0,1,'R'); }

        $pdf->SetFont('arial','I',9); 
        $pdf->Cell(12,4,'Totales',0,0,'L'); 
        $pdf->Cell(40,4,number_format($sumaMtrsInside['50001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsInside['50001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMtrsOutside['50001'][$_GET['cdgproducto']]),0,0,'R');
        $pdf->Cell(40,4,number_format($sumaMllsOutside['50001'][$_GET['cdgproducto']],3,'.',','),0,0,'R');
        $pdf->Cell(20,4,number_format((100-(($sumaMllsOutside['50001'][$_GET['cdgproducto']]*100)/$sumaMllsInside['50001'][$_GET['cdgproducto']])),2,'.','').' %',0,1,'R'); }

      $pdf->Output('Reporte de merma de '.$rptproducto.'.pdf', 'I'); 
    }
  } else
  { // Reporte versión 2.0
   ///////////////////////////////////
    $link = conectar();

    class PDF extends FPDF
    { function Header()
      { global $rptproducto;

        if ($_SESSION['usuario'] == '')
        { $_SESSION['usuario'] = 'Invitado'; }

        if (file_exists('../../img_sistema/logonew.jpg')==true)
        { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

        $this->SetFillColor(255,153,0);

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Reporte de merma'),0,1,'L');
    
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Rango de fechas'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,$_GET[fchini].' : '.$_GET[fchfin],0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
        
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Junio 13, 2014'),0,1,'L');
        
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Gerente de producción'),0,1,'L'); 

        $this->Ln(4.15); }
    }

    $pdf=new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(real, continuous);
    $pdf->AddPage();
    $pdf->SetFillColor(253,208,134);

    $queryMerma = $link->query("
      SELECT prodlote.cdgproducto,
             pdtoimpresion.impresion,
         SUM(proglote.longitud/(pdtodiseno.alto/pdtodiseno.alpaso)) AS inside,
      SUBSTR(prodloteope.fchoperacion,1,7) AS mes
        FROM pdtodiseno,
             pdtoimpresion,
             proglote,
             prodlote, 
             prodloteope
       WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
            pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND proglote.cdglote = prodlote.cdglote AND
            (prodloteope.cdglote = prodlote.cdglote AND 
             prodloteope.cdgoperacion = '20001') AND
      (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodlote.cdgproducto,
SUBSTR(prodloteope.fchoperacion,1,7)"); 

    if ($queryMerma->num_rows > 0)
    { $query2Merma = $link->query("
      SELECT prodlote.cdgproducto,
             pdtoimpresion.impresion,
         SUM(prodlote.longitud/(pdtodiseno.alto/pdtodiseno.alpaso)) AS outside,
      SUBSTR(prodloteope.fchoperacion,1,7) AS mes
        FROM pdtodiseno,
             pdtoimpresion,
             proglote,
             prodlote, 
             prodloteope
       WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
            pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND proglote.cdglote = prodlote.cdglote AND
            (prodloteope.cdglote = prodlote.cdglote AND 
             prodloteope.cdgoperacion = '20001') AND
      (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodlote.cdgproducto,
SUBSTR(prodloteope.fchoperacion,1,7)");

      if ($query2Merma->num_rows > 0)
      { while($reg2Mes = $query2Merma->fetch_object())
        { $outside[$reg2Mes->cdgproducto][$reg2Mes->mes] = $reg2Mes->outside; }
      }

      $pdf->SetFont('arial','I',9);
      $pdf->Cell(70,4,utf8_decode('Producto'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Mes'),1,0,'C',true);
      $pdf->Cell(25,4,utf8_decode('Entradas (Mlls)'),1,0,'L',true);
      $pdf->Cell(25,4,utf8_decode('Salidas (Mlls)'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Porcentaje'),1,1,'L',true);

      $pdf->SetFont('arial','',8);
      while($regMes = $queryMerma->fetch_object())
      { $sumaInside['20001'] += $regMes->inside;
        $sumaOutside['20001'] += $outside[$regMes->cdgproducto][$regMes->mes];

        $pdf->Cell(70,4,$regMes->impresion,1,0,'L'); 
        $pdf->Cell(20,4,$regMes->mes,1,0,'C');
        $pdf->Cell(25,4,number_format($regMes->inside,3,'.',','),1,0,'R');
        $pdf->Cell(25,4,number_format($outside[$regMes->cdgproducto][$regMes->mes],3,'.',','),1,0,'R');
        $pdf->Cell(20,4,number_format(100-((($outside[$regMes->cdgproducto][$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',1,0,'R');
        $pdf->Ln(); } 

      $pdf->SetFont('arial','B',10);
      $pdf->Cell(90,6,utf8_decode('Acumulados de Impresión'),0,0,'R');
      $pdf->SetFont('arial','I',10);
      $pdf->Cell(25,6,number_format($sumaInside['20001'],3,'.',','),1,0,'R',true);
      $pdf->Cell(25,6,number_format($sumaOutside['20001'],3,'.',','),1,0,'R',true);
      $pdf->SetFont('arial','B',12);
      $pdf->Cell(20,6,number_format(100-((($sumaOutside['20001']*100)/$sumaInside['20001'])),2,'.',',').' %',1,1,'R',true); }

    $queryMerma = $link->query("
      SELECT prodlote.cdgproducto,
             pdtoimpresion.impresion,
         SUM(prodlote.longitud/(pdtodiseno.alto/pdtodiseno.alpaso)) AS inside,
      SUBSTR(prodloteope.fchoperacion,1,7) AS mes
        FROM pdtodiseno,
             pdtoimpresion,
             prodlote, 
             prodloteope
       WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodlote.cdgproducto) AND 
            (prodloteope.cdglote = prodlote.cdglote AND 
             prodloteope.cdgoperacion = '30001') AND
            (prodloteope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodlote.cdgproducto,
      SUBSTR(prodloteope.fchoperacion,1,7)"); 

    if ($queryMerma->num_rows > 0)
    { $query2Merma = $link->query("
      SELECT prodbobina.cdgproducto,
             pdtoimpresion.impresion,
         SUM(prodbobina.longitud/pdtodiseno.alto) AS outside,
      SUBSTR(prodbobinaope.fchoperacion,1,7) AS mes
        FROM pdtodiseno,
             pdtoimpresion,
             prodlote,            
             prodbobina, 
             prodbobinaope
       WHERE prodlote.cdglote = prodbobina.cdglote AND
            (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodbobina.cdgproducto) AND              
            (prodbobinaope.cdgbobina = prodbobina.cdgbobina AND 
             prodbobinaope.cdgoperacion = '30001') AND
            (prodbobinaope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodbobina.cdgproducto,
      SUBSTR(prodbobinaope.fchoperacion,1,7)");

      if ($query2Merma->num_rows > 0)
      { while($reg2Mes = $query2Merma->fetch_object())
        { $outside[$reg2Mes->cdgproducto][$reg2Mes->mes] = $reg2Mes->outside; }
      }

      $pdf->Ln(2);
      $pdf->SetFont('arial','I',9);
      $pdf->Cell(70,4,utf8_decode('Producto'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Mes'),1,0,'C',true);
      $pdf->Cell(25,4,utf8_decode('Entradas (Mlls)'),1,0,'L',true);
      $pdf->Cell(25,4,utf8_decode('Salidas (Mlls)'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Porcentaje'),1,1,'L',true);

      $pdf->SetFont('arial','',8);
      while($regMes = $queryMerma->fetch_object())
      { $sumaInside['30001'] += $regMes->inside;
        $sumaOutside['30001'] += $outside[$regMes->cdgproducto][$regMes->mes];

        $pdf->Cell(70,4,$regMes->impresion,1,0,'L'); 
        $pdf->Cell(20,4,$regMes->mes,1,0,'C');
        $pdf->Cell(25,4,number_format($regMes->inside,3,'.',','),1,0,'R');
        $pdf->Cell(25,4,number_format($outside[$regMes->cdgproducto][$regMes->mes],3,'.',','),1,0,'R');
        $pdf->Cell(20,4,number_format(100-((($outside[$regMes->cdgproducto][$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',1,0,'R');
        $pdf->Ln(); } 
    
      $pdf->SetFont('arial','B',10);
      $pdf->Cell(90,6,utf8_decode('Acumulados de Sliteo'),0,0,'R');
      $pdf->SetFont('arial','I',10);
      $pdf->Cell(25,6,number_format($sumaInside['30001'],3,'.',','),1,0,'R',true);
      $pdf->Cell(25,6,number_format($sumaOutside['30001'],3,'.',','),1,0,'R',true);
      $pdf->SetFont('arial','B',12);
      $pdf->Cell(20,6,number_format(100-((($sumaOutside['30001']*100)/$sumaInside['30001'])),2,'.',',').' %',1,1,'R',true); }

      $queryMerma = $link->query("
        SELECT prodbobina.cdgproducto,
               pdtoimpresion.impresion,
           SUM(prodbobina.longitud/pdtodiseno.alto) AS inside,
        SUBSTR(prodbobinaope.fchoperacion,1,7) AS mes
          FROM pdtodiseno,
               pdtoimpresion,
               prodbobina,
               prodbobinaope
         WHERE prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodbobina.cdgproducto) AND 
             prodbobinaope.cdgoperacion = '40001' AND
            (prodbobinaope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodbobina.cdgproducto,
      SUBSTR(prodbobinaope.fchoperacion,1,7)");

    if ($queryMerma->num_rows > 0)
    { $query2Merma = $link->query("
      SELECT prodrollo.cdgproducto,
             pdtoimpresion.impresion,
         SUM(prodrollo.longitud/pdtodiseno.alto) AS outside,
      SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
        FROM pdtodiseno,
             pdtoimpresion,
             prodbobina,
             prodrollo, 
             prodrolloope
       WHERE prodbobina.cdgbobina = prodrollo.cdgbobina AND
            (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
             pdtoimpresion.cdgimpresion = prodrollo.cdgproducto) AND             
            (prodrolloope.cdgrollo = prodrollo.cdgrollo AND 
             prodrolloope.cdgoperacion = '40001') AND
            (prodrolloope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
    GROUP BY prodrollo.cdgproducto,
      SUBSTR(prodrolloope.fchoperacion,1,7)");

      if ($query2Merma->num_rows > 0)
      { while($reg2Mes = $query2Merma->fetch_object())
        { $outside[$reg2Mes->cdgproducto][$reg2Mes->mes] = $reg2Mes->outside; }
      }

      $pdf->Ln(2);
      $pdf->SetFont('arial','I',9);
      $pdf->Cell(70,4,utf8_decode('Producto'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Mes'),1,0,'C',true);
      $pdf->Cell(25,4,utf8_decode('Entradas (Mlls)'),1,0,'L',true);
      $pdf->Cell(25,4,utf8_decode('Salidas (Mlls)'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Porcentaje'),1,1,'L',true);

      $pdf->SetFont('arial','',8);
      while($regMes = $queryMerma->fetch_object())
      { $sumaInside['40001'] += $regMes->inside;
        $sumaOutside['40001'] += $outside[$regMes->cdgproducto][$regMes->mes];

        $pdf->Cell(70,4,$regMes->impresion,1,0,'L'); 
        $pdf->Cell(20,4,$regMes->mes,1,0,'C');
        $pdf->Cell(25,4,number_format($regMes->inside,3,'.',','),1,0,'R');
        $pdf->Cell(25,4,number_format($outside[$regMes->cdgproducto][$regMes->mes],3,'.',','),1,0,'R');
        $pdf->Cell(20,4,number_format(100-((($outside[$regMes->cdgproducto][$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',1,0,'R');
        $pdf->Ln(); } 
    
      $pdf->SetFont('arial','B',10);
      $pdf->Cell(90,6,utf8_decode('Acumulados de Fusión'),0,0,'R');
      $pdf->SetFont('arial','I',10);
      $pdf->Cell(25,6,number_format($sumaInside['40001'],3,'.',','),1,0,'R',true);
      $pdf->Cell(25,6,number_format($sumaOutside['40001'],3,'.',','),1,0,'R',true);
      $pdf->SetFont('arial','B',12);
      $pdf->Cell(20,6,number_format(100-((($sumaOutside['40001']*100)/$sumaInside['40001'])),2,'.',',').' %',1,1,'R',true); }

      $queryMerma = $link->query("
        SELECT prodrollo.cdgproducto,
               pdtoimpresion.impresion,
           SUM(prodrolloope.longitud/pdtodiseno.alto) AS inside,
           SUM(prodrolloope.longitudfin/pdtodiseno.alto) AS outside,
        SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
          FROM pdtodiseno,
               pdtoimpresion,
               prodrollo,
               prodrolloope
         WHERE (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodrollo.cdgproducto) AND
               prodrolloope.cdgoperacion = '40006' AND
               prodrolloope.cdgrollo = prodrollo.cdgrollo AND
              (prodrolloope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
      GROUP BY prodrollo.cdgproducto,
        SUBSTR(prodrolloope.fchoperacion,1,7)");

    if ($queryMerma->num_rows > 0)
    { $pdf->Ln(2);
      $pdf->SetFont('arial','I',9);
      $pdf->Cell(70,4,utf8_decode('Producto'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Mes'),1,0,'C',true);
      $pdf->Cell(25,4,utf8_decode('Entradas (Mlls)'),1,0,'L',true);
      $pdf->Cell(25,4,utf8_decode('Salidas (Mlls)'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Porcentaje'),1,1,'L',true);

      $pdf->SetFont('arial','',8);
      while($regMes = $queryMerma->fetch_object())
      { $sumaInside['40006'] += $regMes->inside;
        $sumaOutside['40006'] += $regMes->outside;

        $pdf->Cell(70,4,$regMes->impresion,1,0,'L'); 
        $pdf->Cell(20,4,$regMes->mes,1,0,'C');
        $pdf->Cell(25,4,number_format($regMes->inside,3,'.',','),1,0,'R');
        $pdf->Cell(25,4,number_format($regMes->outside,3,'.',','),1,0,'R');
        $pdf->Cell(20,4,number_format(100-((($regMes->outside*100)/$regMes->inside)),2,'.',',').' %',1,0,'R');
        $pdf->Ln(); } 
    
      $pdf->SetFont('arial','B',10);
      $pdf->Cell(90,6,utf8_decode('Acumulados de Revisión'),0,0,'R');
      $pdf->SetFont('arial','I',10);
      $pdf->Cell(25,6,number_format($sumaInside['40006'],3,'.',','),1,0,'R',true);
      $pdf->Cell(25,6,number_format($sumaOutside['40006'],3,'.',','),1,0,'R',true);
      $pdf->SetFont('arial','B',12);
      $pdf->Cell(20,6,number_format(100-((($sumaOutside['40006']*100)/$sumaInside['40006'])),2,'.',',').' %',1,1,'R',true); }

      $queryMerma = $link->query("
        SELECT prodrollo.cdgproducto,
               pdtoimpresion.impresion,
           SUM(prodrollo.longitud/pdtodiseno.alto) AS inside, 
        SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
          FROM pdtodiseno,
               pdtoimpresion, 
               prodrollo, 
               prodrolloope
         WHERE prodrolloope.cdgrollo = prodrollo.cdgrollo AND 
              (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodrollo.cdgproducto) AND 
               prodrolloope.cdgoperacion = '50001' AND
              (prodrolloope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
      GROUP BY prodrollo.cdgproducto,
        SUBSTR(prodrolloope.fchoperacion,1,7)");

    if ($queryMerma->num_rows > 0)
    { $query2Merma = $link->query("
        SELECT prodpaquete.cdgproducto,
               pdtoimpresion.impresion,
           SUM(prodpaquete.cantidad) AS outside, 
        SUBSTR(prodrolloope.fchoperacion,1,7) AS mes
          FROM pdtodiseno,
               pdtoimpresion, 
               prodpaquete, 
               prodrollo,
               prodrolloope
         WHERE prodpaquete.cdgrollo = prodrollo.cdgrollo AND
               prodpaquete.sttpaquete != 'C' AND
              (prodrolloope.cdgoperacion = '50001' AND
               prodrolloope.cdgrollo = prodrollo.cdgrollo) AND
              (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
               pdtoimpresion.cdgimpresion = prodpaquete.cdgproducto) AND
              (prodrolloope.fchoperacion BETWEEN '".$_GET[fchini]."' AND '".$_GET[fchfin]."')
      GROUP BY prodpaquete.cdgproducto,
        SUBSTR(prodrolloope.fchoperacion,1,7)"); 

      if ($query2Merma->num_rows > 0)
      { while($reg2Mes = $query2Merma->fetch_object())
        { $outside[$reg2Mes->cdgproducto][$reg2Mes->mes] = $reg2Mes->outside; }
      }

      $pdf->Ln(2);
      $pdf->SetFont('arial','I',9);
      $pdf->Cell(70,4,utf8_decode('Producto'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Mes'),1,0,'C',true);
      $pdf->Cell(25,4,utf8_decode('Entradas (Mlls)'),1,0,'L',true);
      $pdf->Cell(25,4,utf8_decode('Salidas (Mlls)'),1,0,'L',true);
      $pdf->Cell(20,4,utf8_decode('Porcentaje'),1,1,'L',true);

      $pdf->SetFont('arial','',8);
      while($regMes = $queryMerma->fetch_object())
      { $sumaInside['50001'] += $regMes->inside;
        $sumaOutside['50001'] += $outside[$regMes->cdgproducto][$regMes->mes];

        $pdf->Cell(70,4,$regMes->impresion,1,0,'L'); 
        $pdf->Cell(20,4,$regMes->mes,1,0,'C');
        $pdf->Cell(25,4,number_format($regMes->inside,3,'.',','),1,0,'R');
        $pdf->Cell(25,4,number_format($outside[$regMes->cdgproducto][$regMes->mes],3,'.',','),1,0,'R');
        $pdf->Cell(20,4,number_format(100-((($outside[$regMes->cdgproducto][$regMes->mes]*100)/$regMes->inside)),2,'.',',').' %',1,0,'R');
        $pdf->Ln(); } 
    
      $pdf->SetFont('arial','B',10);
      $pdf->Cell(90,6,utf8_decode('Acumulados de Corte'),0,0,'R');
      $pdf->SetFont('arial','I',10);
      $pdf->Cell(25,6,number_format($sumaInside['50001'],3,'.',','),1,0,'R',true);
      $pdf->Cell(25,6,number_format($sumaOutside['50001'],3,'.',','),1,0,'R',true);
      $pdf->SetFont('arial','B',12);
      $pdf->Cell(20,6,number_format(100-((($sumaOutside['50001']*100)/$sumaInside['50001'])),2,'.',',').' %',1,1,'R',true); }

     } //*/
  $pdf->Output('Reporte de merma.pdf', 'I');
?>
