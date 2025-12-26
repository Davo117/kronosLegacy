<?php
  session_start();
  //require('../fpdf/fpdf.php');
  require('../../fpdf/fpdflbl.php');
  include('../Controlador_produccion/db_produccion.php');
  include('../Controlador_produccion/functions.php');

  ob_clean();
  $numero=$_GET['etiquetas'];
  $proceso=explode("|", $numero,3);
  $producto=$proceso[1];
  $proceso=$proceso[0];
  $SQL=$MySQLiconn->query("
    SELECT numeroProceso 
    from juegoprocesos 
    where identificadorJuego=(
      select juegoprocesos 
      from tipoproducto 
      where id=(
        select tipo 
        from producto 
        where ID=(
          select descripcionDisenio 
          from impresiones 
          where id='".$producto."'))) and descripcionProceso='".$proceso."'");
    
    $roa=$SQL->fetch_array();
    $noProces=$roa['numeroProceso'];
    $cons=$MySQLiconn->query("SELECT id,descripcionProceso as actual,(SELECT descripcionProceso from juegoprocesos where identificadorJuego=(select juegoprocesos from tipoproducto where 
      id=(select tipo from producto where 
      ID=(select descripcionDisenio from impresiones where id='".$producto."'))) and numeroProceso='$noProces'+1 and baja=1) as siguiente,numeroProceso from juegoprocesos where numeroProceso=".$noProces." and 
      identificadorJuego=(select juegoprocesos from tipoproducto where 
        id=(select tipo from producto where 
          ID=(select descripcionDisenio from impresiones where id='".$producto."')))");

    $consRo=$cons->fetch_array();
  $idProceso=$consRo['id'];//El id del proceso actual
  $numeroProceso=$consRo['numeroProceso'];//numero del proceso que se esta registrando
  $procesoSiguiente=$consRo['siguiente'];//el proceso siguiente para colocarlo en la etiqueta
  $procesoActual=$consRo['actual'];//la descripcion del proceso que se esta registrando

  $consul=$MySQLiconn->query("SELECT p.producto,p.rollo as codigo,c.lote from `tbprocorte` p inner join tbcodigosbarras c on c.codigo=p.rollo where p.total=1 and p.producto='".$producto."' and p.rollo_padre=0 and c.proceso=6 and c.baja=1");

  if ($consul->num_rows > 0) { 
    $pdf=new FPDF('P','mm','lbl4x1');
    $pdf->AddFont('3of9','','free3of9.php');

    $nPaquetes=0;
    //$regCodePaquete = $consul->fetch_object();

    $nCodigos=$consul->num_rows;
    //Se agregan los codigos al arreglo
    $codePaquete_cdgpaquete = array($nCodigos);

    for($i=1;$regCodePaquete=$consul->fetch_object();$i++) {
      $lotesin=$regCodePaquete->lote;
      $Pem=$MySQLiconn->query("SELECT alturaEtiqueta,anchoEtiqueta,descripcionImpresion,millaresPorPaquete,logproveedor,haslote FROM impresiones WHERE id='".$regCodePaquete->producto."'");
      $ras=$Pem->fetch_array();
      $ancho=$ras['anchoEtiqueta'];
      $parte=$ras['logproveedor'];
      $codigo=$ras['descripcionImpresion'];
      $mpp=$ras['millaresPorPaquete']*1000;
      $haslote=$ras['haslote'];
      $squir=$MySQLiconn->query("SELECT referencia from ensambleempaques WHERE codigo='".$regCodePaquete->codigo."'");
      $rowi=$squir->fetch_assoc();

      if(empty($rowi['referencia'])) {
        $codePaquete_cdgpaquete[$i] =$regCodePaquete->codigo; 
        $codePaquete_paquete[$i] = $regCodePaquete->codigo;
      } else {
        $codePaquete_cdgpaquete[$i] ="0"; 
        $codePaquete_paquete[$i] ="0";
      }
    }

    $nPaquetes =ceil($nCodigos/2);
    for ($item = 1; $item <= $nPaquetes; $item++) { 
      $pdf->AddPage();
      $pdf->SetFont('Arial','',7);
      $pdf->Cell(53,7,utf8_decode($codigo),0,0,'C');
        // Código de barras
      $pdf->SetY(7);
      $pdf->SetX(6);

      
      if(!empty($codePaquete_cdgpaquete[(($item*2)-1)])) {
        $pdf->SetFont('3of9','',28);
        $pdf->Cell(39,6,'*'.$codePaquete_cdgpaquete[(($item*2)-1)].'*',0,0,'C'); 
      } else {
        $pdf->ln();
      }

      if(!empty($codePaquete_cdgpaquete[(($item*2))])) {
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(62,-7,utf8_decode($codigo),0,0,'C');
        $pdf->SetFont('3of9','',28);
        $pdf->Cell(-21,6,'',0,0,'C');
        $pdf->Cell(-15,6,'*'.$codePaquete_cdgpaquete[($item*2)].'*',0,1,'C');
      } else {
        $pdf->ln();
      }

      if(!empty($codePaquete_paquete[(($item*2)-1)])) {
        $pdf->SetFont('Arial','',7); 
        $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_paquete[(($item*2)-1)],0,0,'C');
        if(empty($codePaquete_paquete[(($item*2))])) {
          $pdf->ln();
          $pdf->Cell(50,3,"cont.:".$mpp."pzs"." / ".$parte,0,1,'C'); 
          if($haslote==1)
          {
            $pdf->Cell(50,3,"lote:".$lotesin,0,1,'C'); 
          }
        }
      } else {
        $pdf->ln();
      }

      if(!empty($codePaquete_paquete[(($item*2))])) {
        $pdf->Cell(4,6,'',0,0,'C');
        $pdf->Cell(50,4,date('d').date('Gi').$codePaquete_paquete[($item*2)],0,1,'C');
        $pdf->Cell(50,3,"cont.:".$mpp."pzs"." / ".$parte,0,0,'C'); 
        $pdf->Cell(50,3,"cont.:".$mpp."pzs"." / ".$parte,0,1,'C');
        
        if($haslote==1) {
          $pdf->Cell(50,3,"lote:".$lotesin,0,0,'C'); 
          $pdf->Cell(50,3,"lote:".$lotesin,0,1,'C'); 
        }  
      }
    }

    $pdf->Output();
  } else { 
  echo '<html>
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="../../css/global.css" /> 
</head>
<body>
<p  class="message">Sin códigos disponibles</p>
</body>
<script type="text/javascript">
<!--
     var ventana = window.self; 
     ventana.opener = window.self; 
     setTimeout("window.close()", 1500);
//-->
</script>
</html>'; } 
?>
