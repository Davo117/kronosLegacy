<?php
 include '../../Database/db.php'; 
require('../../fpdf/fpdflbl.php');
require('../Controlador_empaque/functionsEmpaque.php');
//Documento aun en fase beta 21/06/2018
  if ($_GET['etiquetasInd']) 
  { 

    //$labelEmpaque_cdgempaque = $_GET['cdgempaque'];
    $idPaquete = $_GET['etiquetasInd'];
    //echo $idPaquete;
     $datos=parsearCodigoEmpaque($idPaquete,$MySQLiconn);
        $datos=explode('|', $datos);
        $idProducto=$datos[4];
        $referencia=$datos[2];
        $tipo=$datos[5];
    $TipoEmpaque=$_GET['empaque'];
    $alptEmpaqueSelect = $MySQLiconn->query("
      SELECT e.codigo,(SELECT nombreParametro from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso=(SELECT descripcionProceso from juegoprocesos where numeroProceso!=0 and identificadorJuego=(SELECT juegoprocesos from tipoproducto where id=(SELECT tipo from producto where ID=(SELECT descripcionDisenio from impresiones where id='$idProducto'))) and baja=1 order by numeroProceso desc limit 1))) as referencial,(select descripcionProceso from procesos where id=c.proceso) as proceso
      FROM ensambleempaques e inner join tbcodigosbarras c  ON e.codigo=c.codigo 
      WHERE e.referencia=(SELECT referencia FROM $TipoEmpaque where codigo='".$idPaquete."') and e.producto=(SELECT producto from $TipoEmpaque where codigo='".$idPaquete."' )");
    if ($alptEmpaqueSelect->num_rows > 0)
    { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();
      $proceso = $regAlptEmpaque->proceso;
     // $referencial=$regAlptEmpaque->referencial;
$alptEmpaque_longitud=0;
$alptEmpaque_peso =0;
$alptEmpaque_piezas=0;
      if ($TipoEmpaque == 'rollo')
      { // Busqueda del empaque entre los empaque de rollo (Banda continua)   
       
        $packingListSelect = $MySQLiconn->query("
          SELECT `pro".$proceso."`.noop,
                 (select descripcion from producto where ID=impresiones.descripcionDisenio) as diseno,
                 impresiones.id as idimpresion,
                 impresiones.codigoimpresion as impresion,
                 impresiones.descripcionImpresion as desimpresion,
                 impresiones.alturaEtiqueta as alto,
                 `pro".$proceso."`.unidades as cantidad,
                 `pro".$proceso."`.longitud,
                 impresiones.anchoEtiqueta as amplitud,
                 `pro".$proceso."`.peso,
                 ensambleempaques.codigo as cdgrollo,
                 ".$TipoEmpaque.".referencia as noempaque,
                 ".$TipoEmpaque.".peso AS pbruto,
                 ensambleempaques.refEnsamble as nocontrol
                 FROM `tbpro".$proceso."` `pro".$proceso."` inner join tbcodigosbarras codigosbarras on `pro".$proceso."`.noop=codigosbarras.noop inner join  ensambleempaques on ensambleempaques.codigo=codigosbarras.codigo inner join ".$TipoEmpaque." on ".$TipoEmpaque.".codigo=ensambleempaques.codEmpaque inner join impresiones on impresiones.id=".$TipoEmpaque.".producto where ".$TipoEmpaque.".codigo='".$idPaquete."' and  ".$TipoEmpaque.".producto=".$idProducto." and `pro".$proceso."`.tipo='".$tipo."'");
        if(!$packingListSelect)
        {
         /* $packingListSelect = $MySQLiconn->query("
          SELECT `pro".$proceso."`.noop,
                 impresiones.descripcionDisenio as diseno,
                 impresiones.id as idimpresion,
                 impresiones.codigo as impresion,
                 impresiones.alturaEtiqueta as alto,
                 `pro".$proceso."`.unidades as cantidad,
                 `pro".$proceso."`.longitud,
                 `pro".$proceso."`.peso,
                 ensambleempaques.codigo as cdgrollo,
                 ".$TipoEmpaque.".referencia as noempaque,
                 ".$TipoEmpaque.".peso AS pbruto,
                 ".$TipoEmpaque.".id as nocontrol
            FROM `tbpro".$proceso."` `pro".$proceso."`,
                 impresiones,
                 ensambleempaques,
                 ".$TipoEmpaque."
            WHERE ensambleempaques.referencia=(SELECT referencia FROM ".$TipoEmpaque." where codigo='".$idPaquete."') and ensambleempaques.producto=(SELECT producto from ".$TipoEmpaque." where codigo='".$idPaquete."' ) and impresiones.id=".$TipoEmpaque.".producto and  `pro".$proceso."`.noop=(SELECT noop from codigosbarras where codigo=(select codigo from ensambleempaques where referencia=(select referencia from ".$TipoEmpaque." where codigo='".$idPaquete."')))"); */
            $packingListSelect = $MySQLiconn->query("
          SELECT `pro".$proceso."`.noop,
                 (select descripcion from producto where ID=impresiones.descripcionDisenio) as diseno,
                 impresiones.id as idimpresion,
                 impresiones.codigoimpresion as impresion,
                 impresiones.descripcionImpresion as desimpresion,
                 impresiones.alturaEtiqueta as alto,
                 `pro".$proceso."`.unidades as cantidad,
                 `pro".$proceso."`.longitud,
                 impresiones.anchoEtiqueta as amplitud,
                 ensambleempaques.codigo as cdgrollo,
                 ".$TipoEmpaque.".referencia as noempaque,
                 ".$TipoEmpaque.".peso AS pbruto,
                 ensambleempaques.refEnsamble as nocontrol
                 FROM `tbpro".$proceso."` `pro".$proceso."` inner join tbcodigosbarras codigosbarras on `pro".$proceso."`.noop=codigosbarras.noop inner join  ensambleempaques on ensambleempaques.codigo=codigosbarras.codigo inner join ".$TipoEmpaque." on ".$TipoEmpaque.".codigo=ensambleempaques.codEmpaque inner join impresiones on impresiones.id=".$TipoEmpaque.".producto where ".$TipoEmpaque.".codigo='".$idPaquete."' and  ".$TipoEmpaque.".producto=".$idProducto."");
        }
        if ($packingListSelect->num_rows > 0)
        { $pdf=new FPDF('P','mm','lbl4x2'); 
          $pdf->AddFont('3of9','','free3of9.php');
        
          while ($regPackingList = $packingListSelect->fetch_object())
          { if (!isset($alptEmpaque_noempaque)) { $alptEmpaque_noempaque = $regPackingList->noempaque; }
            if (!isset($alptEmpaque_idimpresion)) { $alptEmpaque_idimpresion = $regPackingList->idimpresion; }
            if (!isset($alptEmpaque_impresion)) { $alptEmpaque_impresion = $regPackingList->diseno.' | '.$regPackingList->desimpresion; }
            if (!isset($alptEmpaque_pbruto)) { $alptEmpaque_pbruto = $regPackingList->pbruto; }


            $alptEmpaque_longitud += $regPackingList->longitud;
            $alptEmpaque_peso += $regPackingList->peso;
            $alptEmpaque_piezas += $regPackingList->cantidad;

            $pdf->AddPage();

            $pdf->Cell(15,3,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(33,3,'',0,1,'R'); 

            $pdf->Cell(10,8,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(38,8,$regPackingList->noempaque.'.R'.$regPackingList->nocontrol,0,1,'R'); 


            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Lote',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->noop,0,1,'R'); 

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Longitud',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

            if(isset($regPackingList->amplitud))
            {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Ancho plano',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->amplitud.' mm',0,1,'R'); 
            }
            
            if(!empty($regPackingList->peso))
            {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Peso',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($regPackingList->peso,3).' kgs',0,1,'R'); 
            }
            

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Producto',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->impresion,0,1,'R'); 

            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Corte',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            $pdf->Cell(28,4,$regPackingList->alto.' mm',0,1,'R');
            
            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Piezas aprox',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            if($regPackingList->impresion=="Cristal Gota Gde.Im/Trn.Autoadh.")
            {
               $pdf->Cell(28,4,round($regPackingList->cantidad*1000),0,1,'R'); 
            }
            else
            {
               $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
            }
           
            $pdf->Ln(2);

            $pdf->SetFont('3of9','',27);            
            $pdf->Cell(48,5,'*'.$regPackingList->cdgrollo.'*',0,1,'R');
            $pdf->SetFont('Arial','',8);       
            $pdf->Cell(56,3,$regPackingList->cdgrollo,0,1,'C');      

            //DUPLICADO

            $pdf->SetY(0);

            $pdf->Cell(66,3,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(33,3,'',0,1,'R');       

            $pdf->Cell(53,8,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(40,8,$regPackingList->noempaque.'.R'.$regPackingList->nocontrol,0,1,'L'); 

             $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Lote',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->noop,0,1,'R'); 

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Longitud',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($regPackingList->longitud,2).' mts',0,1,'R'); 

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Ancho plano',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->amplitud.' mm',0,1,'R'); 
            if(!empty($regPackingList->peso))
            {
               $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Peso',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($regPackingList->peso,3).' kgs',0,1,'R'); 
            }
           
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Producto',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$regPackingList->impresion,0,1,'R'); 

            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Corte',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            $pdf->Cell(28,4,$regPackingList->alto.' mm',0,1,'R');
            
            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Piezas aprox',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            if($regPackingList->impresion=="Cristal Gota Gde.Im/Trn.Autoadh.")
            {
              $pdf->Cell(28,4,round($regPackingList->cantidad*1000),0,1,'R'); 
            }
            else
            {
              $pdf->Cell(28,4,number_format($regPackingList->cantidad*1000),0,1,'R'); 
            }
            
            $pdf->Ln(2);

            $pdf->SetFont('3of9','',27);  
            $pdf->Cell(53,4,'',0,0,'L');          
            $pdf->Cell(48,5,'*'.$regPackingList->cdgrollo.'*',0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(53,4,'',0,0,'L');       
            $pdf->Cell(40,3,$regPackingList->cdgrollo,0,1,'C');

            $pdf->SetY(0);
            $pdf->Line(50.5,2.5,51,4);
            $pdf->Line(50.5,5.5,51,7);
            $pdf->Line(50.5,8.5,51,10);
            $pdf->Line(50.5,11.5,51,13);
            $pdf->Line(50.5,14.5,51,16);
            $pdf->Line(50.5,17.5,51,19);
            $pdf->Line(50.5,20.5,51,22);
            $pdf->Line(50.5,23.5,51,25);
            $pdf->Line(50.5,26.5,51,28);
            $pdf->Line(50.5,29.5,51,31);
            $pdf->Line(50.5,32.5,51,34);
            $pdf->Line(50.5,35.5,51,37);
            $pdf->Line(50.5,38.5,51,40);
            $pdf->Line(50.5,41.5,51,43);
            $pdf->Line(50.5,44.5,51,46);
            $pdf->Line(50.5,47.5,51,49); }

          $pdf->AddPage();

          $pdf->SetFont('Arial','B',12);
          $pdf->SetY(4);
          $pdf->Cell(10,5,'',0,0,'L');
          $pdf->Cell(18,5,'Empaque ',0,1,'L');
          $pdf->Cell(10,4,'',0,0,'L');
          $pdf->SetFont('Arial','I',11);
          $pdf->Cell(18,4,$packingListSelect->num_rows.' Rollos',0,1,'L');

          $pdf->SetY(4);
          $pdf->SetFont('Arial','B',36);
          $pdf->Cell(80,12,$alptEmpaque_noempaque,0,1,'R'); 

          $pdf->SetY(16);
          $pdf->Cell(10,4,'',0,0,'L');
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(18,4,'Producto',0,1,'L');
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(10,5,'',0,0,'L');
          $pdf->Cell(94,5,$alptEmpaque_impresion,0,1,'L');

          $pdf->Cell(10,4,'',0,0,'L');
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(18,4,'Longitud ',0,0,'L');
          $pdf->SetFont('Arial','B',11);
          $pdf->Cell(40,4,number_format($alptEmpaque_longitud,2).' Mts',0,1,'R');

          $pdf->Cell(10,4,'',0,0,'L');
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
          $pdf->SetFont('Arial','B',11);
          $pdf->Cell(40,4,number_format($alptEmpaque_piezas*1000),0,1,'R');

          $pdf->Ln(4); 

          $pdf->SetFont('3of9','',26);
          $pdf->Cell(98,6,'*'.$idPaquete.'*',0,1,'C');

          $pdf->Ln(2); 

          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(10,4,'',0,0,'L');
          $pdf->Cell(80,4,'Temperatura de almacenamiento menor a 30 grados.',1,1,'C');

          $pdf->Output();
        } else
        { echo '<html>
          <head>    
            <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" /> 
          </head>
          <body>
            <div><h1>El empaque de rollo no fue encontrado</h1></div>
          </body>
        </html>'; }
      }

      if ($TipoEmpaque == 'caja')
      { 
        $numero=$_GET['etiquetasInd'];

$consul=$MySQLiconn->query("SELECT c.*,(select descripcionImpresion from impresiones where id=c.producto) as productoc from caja c where c.codigo='$numero'");//Esta consulta tiene longitud,carece de dinamicidad

$contador=0;//Se inicializa un contador

  if ($consul->num_rows > 0)
  { 
    $pdf=new FPDF('P','mm','lbl4x2'); 
    $pdf->AddFont('3of9','','free3of9.php');

    while ($regPackingList = $consul->fetch_object())
    {
      $pdf->AddPage();

    $pdf->Cell(15,3,'',0,1,'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(18,12,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',36);
    $pdf->Cell(40,12,''.$regPackingList->referencia,0,1,'R'); 

    $pdf->SetY(15);
    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
      if(strlen($regPackingList->productoc)<=40)
    {
    $pdf->SetFont('Arial','B',12);
  }
  else if(strlen($regPackingList->productoc)>40)
  {
    $pdf->SetFont('Arial','B',9);
  }
    $pdf->Cell(4,5,'',0,0,'L');
    $pdf->Cell(94,5,$regPackingList->productoc,0,1,'L');
    $pdf->Ln(1);

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(58,4,$regPackingList->noElementos.' Paquetes',0,1,'R');    
    
    //$pdf->Cell(4,4,'',0,0,'L');
    //$pdf->SetFont('Arial','B',9);
    //$pdf->Cell(18,4,'Peso ',0,0,'L');
    //$pdf->SetFont('Arial','B',11);
    //$pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R');   

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,4,$regPackingList->piezas.'Millares',0,1,'R');
     if($regPackingList->peso>0)
    {
       $pdf->SetY(20);
      
      
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(70,6,'',0,0,'L'); 
      $pdf->Cell(15,6,$regPackingList->peso."kgs",0,1,'L');
      $pdf->Ln(1);
    }

    $pdf->Ln(4);

    $pdf->SetFont('3of9','',34);

     $pdf->Cell(98,6,'*'.$numero.'*',0,1,'C'); } 
     $pdf->Ln(1);
     $pdf->SetFont('Arial','',8); 
     $pdf->Cell(50,3,'',0,0,'R'); 
     $pdf->Cell(5,3,$numero,0,1,'C'); 

    $pdf->Ln(2);

    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->Cell(90,4,'Temperatura de almacenamiento menor a 30 grados.',1,1,'C');

      // Imagen LOGOTIPO
      if (file_exists('../../pictures/logo-labro.jpg')==true) 
      { $pdf->Image('../../pictures/logo-labro.jpg',4,34,14); }       
  
    $pdf->Output(); //$pdf->Output($alptEmpaque_impresion.' Empaque C'.$alptEmpaque_noempaque.' '.$_GET['cdgempaque'].'.pdf', 'I');
  } else
  { echo '<html>
  <head>    
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="screen" /> 
  </head>
  <body>
  </body>
</html>';}  
      }
    } else
    { echo '<html>
    <head>    
      <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" /> 
    </head>
    <body>
      <div><h1>El empaque no fue encontrado</h1></div>
    </body>
  </html>';

    exit; }    
  } else
  { echo '<html>
    <head>
      <link rel="stylesheet" type="text/css" href="../../css/2014.css" media="screen" /> 
    </head>
    <body>
      <div><h1>El empaque no fue indicado</h1></div>
    </body>
  </html>'; }

//&tpoempaque=Q

  
?>
