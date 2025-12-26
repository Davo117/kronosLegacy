<?php
  include '../../Database/db.php'; 
include '../../fpdf/fpdf.php';
error_reporting(0);
$proceso=$_GET['proces'];
$tipo=$_GET['tipo'];
  $pdtoImpresionSelect = $MySQLiconn->query("
    SELECT pdtodiseno.descripcion as diseno,
           pdtoimpresion.descripcionImpresion as impresion,
           pdtoimpresion.codigoImpresion as cdgimpresion, 
           tipo.tipo
      FROM producto pdtodiseno,
           impresiones pdtoimpresion,
           tipoproducto tipo
     WHERE pdtodiseno.ID = pdtoimpresion.descripcionDisenio AND
           pdtoimpresion.id = '".$_GET['producto']."' and tipo.id=pdtodiseno.tipo");
  if ($pdtoImpresionSelect->num_rows > 0)
  { $regPdtoImpresion = $pdtoImpresionSelect->fetch_object();

    $_SESSION['prodpaquete_diseno'] = $regPdtoImpresion->diseno;
    $_SESSION['prodpaquete_impresion'] = $regPdtoImpresion->impresion;
    $pdtoImpresion_cdgimpresion = $regPdtoImpresion->cdgimpresion;
    $_SESSION['tipo_name']=$regPdtoImpresion->tipo; }

  
  
  $prodPaqueteSelect = $MySQLiconn->query("
    SELECT*FROM `tbpro$proceso` pr inner join codigosbarras c on pr.noop=c.noop and pr.tipo='".$tipo."' and rollo_padre=0 and c.tipo='".$_SESSION['tipo_name']."' and c.proceso='".$proceso."' and pr.total=1 and pr.producto='".$_GET['producto']."' order by pr.id desc");
  if ($prodPaqueteSelect->num_rows > 0)
  { 
    class PDF extends FPDF
    { // Cabeza de página
      function Header()
      { if (!isset($_SESSION['usuario']))
        { $_SESSION['usuario'] = 'Invitado'; }
        $proceso=$_GET['proces'];
        include '../../Database/db.php'; 
         $rest = $MySQLiconn->query("
      SELECT nombreparametro,leyenda,numParametro
      from juegoparametros 
      where identificadorJuego=(
                  select packParametros 
                  from procesos 
                  where descripcionProceso='".$proceso."') and baja=1 and (numParametro='C' or numParametro='G')");
    $rex=array();
    $run=array();
    $cont=0;
    while($rel = $rest->fetch_assoc())
    {
      $rex[$cont]['nombreparametro']=$rel['nombreparametro'];
      $rex[$cont]['leyenda']=$rel['leyenda'];
      $rex[$cont]['numParametro']=$rel['numParametro'];
      $cont++;
    }
    $numpar=count($rex);
    $bandera=0;
    for($w=$numpar;$w>=0;$w--)
    {
      if($rex[$w]['numParametro']!="C")
      {
      $run[$w]['nombreparametro']=$rex[$w]['nombreparametro'];
      $run[$w]['leyenda']=$rex[$w]['leyenda'];
      $run[$w]['numParametro']=$rex[$w]['numParametro'];
      }
      else
      {
        $bandera=$w;
        $run[$numpar]['nombreparametro']=$rex[$bandera]['nombreparametro'];
        $run[$numpar]['leyenda']=$rex[$bandera]['leyenda'];
        $run[$numpar]['numParametro']=$rex[$bandera]['numParametro'];
      }
    }
    $_SESSION['run']=$run;
    $_SESSION['numpar']=$numpar;
    
        if (file_exists('../../pictures/lolo.jpg')==true) 
        { $this->Image('../../pictures/lolo.jpg',15,10,0,12); }

        $this->SetFillColor(224,7,8);

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode('Listado de paquetes por producto'),0,1,'L');

        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Diseño'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodpaquete_diseno']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Impresión'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,utf8_decode($_SESSION['prodpaquete_impresion']),0,1,'L');
      
        $this->SetFont('Arial','B',8);
        $this->Cell(125,4,utf8_decode('Status'),0,0,'R');
        $this->Cell(0.5,4,'',0,0,'R',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(75,4,$proceso,0,1,'L');
       
        $this->Ln(4.15);

        $this->SetFillColor(180,180,180);
        $this->SetFont('arial','B',8);

        $this->SetFont('arial','B',8);
        $this->Cell(5,4,'',0,0,'L');
        $this->Cell(20,4,'NoOp',1,0,'L',true);
        $this->Cell(27,4,'Fecha movimiento',1,0,'L',true);
        $this->Cell(20,4,'Millares',1,0,'L',true);
      for($j=1;$j<=$numpar;$j++)
      {
        
      if($run[$j]['numParametro']!="C")
      {
        $this->Cell(20,4,$run[$j]['leyenda'],1,0,'L',true);
        
      }
      else
      {
        $this->Cell(30,4,utf8_decode('Código de barras'),1,1,'L',true);
      }
      }
      
    
        
        /*$this->Cell(20,4,'Ancho mm',1,0,'L',true);
        $this->Cell(20,4,'Alto mm',1,0,'L',true);
        $this->Cell(20,4,'Millares',1,0,'L',true);
        $this->Cell(30,4,'Movimiento',1,0,'L',true);        
        $this->Cell(25,4,utf8_decode('Código de barras'),1,1,'L',true);*/ } 

      function Footer()
      { $this->SetFont('arial','',9);
        $this->SetY(-8);
        $this->Cell(0,3,'Usuario: '.utf8_decode($_SESSION['usuario']).' | '.utf8_decode('página '.$this->PageNo().' de {nb}'),0,1,'R');
        $this->SetFont('arial','I',8);
        $this->Cell(0,3,'Consultado el '.date("Y-m-d").' a las '.date("G:i:s"),0,1,'R'); }      
    }

    $pdf = new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $item = 0;
    while ($regDataPdf = $prodPaqueteSelect->fetch_assoc())
    { $item++;

      $pdf->SetFont('arial','',5);
      $pdf->Cell(5,4,$item,0,0,'R');

      $pdf->SetFont('arial','',8);
      $pdf->Cell(20,4,$regDataPdf['noop'],1,0,'L');
      //$fecha=date();
      //$fecha=;
      $pdf->Cell(27,4,date("d/m/Y, g:i", strtotime($regDataPdf['fecha'])),1,0,'L');
      $pdf->Cell(20,4,number_format($regDataPdf['unidades'],3),1,0,'R');
     $run=$_SESSION['run'];

     for($j=1;$j<$_SESSION['numpar'];$j++)
      {
      //Esta linea de código hace que se caiga el servidor
        $pam=$run[$j]['nombreparametro'];
        if($run[$j]['nombreparametro']!='C')
        {
          $pdf->Cell(20,4,$regDataPdf[$pam],1,0,'R');
        } 
       
      }
      $pdf->Cell(30,4,$regDataPdf['codigo'],1,0,'R');
      
     //Purgar errores (Códigos que se hayan generaso mal)
     /* if($proceso=="corte")
      {
         $Sol=$MySQLiconn->query("SELECT id FROM tbprocorte where noop='".$regDataPdf['noop']."' and tipo='".$_GET['tipo']."' and date(fecha)<'2019-05-31'");
      $rol=$Sol->fetch_assoc();
      if(!empty($rol['id']))
      {
        $MySQLiconn->query("UPDATE `tbprocorte` SET total=0 WHERE noop='".$regDataPdf['noop']."' and tipo='".$_GET['tipo']."'");
        $MySQLiconn->query("UPDATE tbcodigosbarras SET baja=0 WHERE codigo='".$regDataPdf['codigo']."'");
      }
      }
     
      /*$pdf->Cell(20,4,number_format($regDataPdf->anchof,2),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->altura,2),1,0,'R');
      $pdf->Cell(20,4,number_format($regDataPdf->cantidad,3),1,0,'R');
      $pdf->Cell(30,4,$regDataPdf->fchmovimiento,1,0,'C');
      $pdf->Cell(25,4,$regDataPdf->cdgpaquete,1,0,'C');*/
      $pdf->Cell(5,4,'',1,1,'R');

      if(isset($regDataPdf['peso']))
      {
        $dataPdf_peso += $regDataPdf['peso'];
      }
      if(isset($regDataPdf['longitud']))
      {
        $dataPdf_longitud += $regDataPdf['longitud'];
      }
      if(isset($regDataPdf['bandera']))
      {
        $dataPdf_bandera+=$regDataPdf['bandera'];
      }
      $dataPdf_cantidad += $regDataPdf['unidades']; }

    $pdf->SetFillColor(180,180,180);

    $pdf->Cell(5,4,'',0,0,'R');
    $pdf->SetFont('arial','B',8);
    $pdf->Cell(47,4,'Totales',1,0,'R',true);    
    $pdf->Cell(20,4,number_format($dataPdf_cantidad,3),1,0,'R');
    for($j=1;$j<$_SESSION['numpar'];$j++)
      {
        $pam=$run[$j]['nombreparametro'];
        if($pam=="longitud")
        {
          $pdf->Cell(20,4,number_format($dataPdf_longitud,3),1,0,'R');
          
        }
        else if($pam=="peso")
        {
          $pdf->Cell(20,4,number_format($dataPdf_peso,3),1,0,'R');
        }
        else if($pam=="bandera")
        {
          $pdf->Cell(20,4,number_format($dataPdf_bandera,3),1,0,'R');
        }
        else
        {
          $pdf->Cell(20,4,'',1,0,'R');
        }
        
       
      }
   

    $pdf->Output('Listado de paquetes de '.$_SESSION['prodpaquete_impresion'].' en '.$_SESSION['status_paquete'].' '.date('Y-m-d G:i:s').'.pdf', 'I');
  } else
  { echo '<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Tablero de control</title>
    <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" />
  </head>
  <body>
    <div id="contenedor">';

    echo '
      <h1>Sin códigos disponibles.</h1>
    </div>
  </body>
</html>'; }
  
?>
