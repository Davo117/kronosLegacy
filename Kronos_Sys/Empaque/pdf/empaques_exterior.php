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
                 impresiones.logproveedor as prov,
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
                 date(".$TipoEmpaque.".fechamov) as fecha,
                 DATE(DATE_ADD(".$TipoEmpaque.".fechamov, INTERVAL 6 MONTH)) as caduca,
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
                 impresiones.logproveedor as prov,
                 impresiones.descripcionImpresion as desimpresion,
                 impresiones.alturaEtiqueta as alto,
                 `pro".$proceso."`.unidades as cantidad,
                 `pro".$proceso."`.longitud,
                 impresiones.anchoEtiqueta as amplitud,
                 ensambleempaques.codigo as cdgrollo,
                 ".$TipoEmpaque.".referencia as noempaque,
                 ".$TipoEmpaque.".peso AS pbruto,
                 date(".$TipoEmpaque.".fechamov) as fecha,
                 DATE(DATE_ADD(".$TipoEmpaque.".fechamov, INTERVAL 6 MONTH)) as caduca,
                 ensambleempaques.refEnsamble as nocontrol
                 FROM `tbpro".$proceso."` `pro".$proceso."` inner join tbcodigosbarras codigosbarras on `pro".$proceso."`.noop=codigosbarras.noop inner join  ensambleempaques on ensambleempaques.codigo=codigosbarras.codigo inner join ".$TipoEmpaque." on ".$TipoEmpaque.".codigo=ensambleempaques.codEmpaque inner join impresiones on impresiones.id=".$TipoEmpaque.".producto where ".$TipoEmpaque.".codigo='".$idPaquete."' and  ".$TipoEmpaque.".producto=".$idProducto."");
            
        }
        if ($packingListSelect->num_rows > 0)
        { $pdf=new FPDF('P','mm','lbl4x2'); 
          $pdf->AddFont('3of9','','free3of9.php');
          $contador=1;
          $arrays=array();
          while ($regPackingList = $packingListSelect->fetch_object())
          { if (!isset($alptEmpaque_noempaque)) { $alptEmpaque_noempaque = $regPackingList->noempaque; }
            if (!isset($alptEmpaque_idimpresion)) { $alptEmpaque_idimpresion = $regPackingList->idimpresion; }
            if (!isset($alptEmpaque_impresion)) { $alptEmpaque_impresion = $regPackingList->diseno.' | '.$regPackingList->desimpresion; }
            if (!isset($alptEmpaque_pbruto)) { $alptEmpaque_pbruto = $regPackingList->pbruto; }
            if (!isset($alptEmpaque_prov)) { $alptEmpaque_prov = $regPackingList->prov; }
            if (!isset($alptEmpaque_fecha)) { $alptEmpaque_fecha = $regPackingList->fecha; }
            if (!isset($alptEmpaque_caduca)) { $alptEmpaque_caduca = $regPackingList->caduca; }


            $alptEmpaque_longitud += $regPackingList->longitud;
            $alptEmpaque_peso += $regPackingList->peso;
            $alptEmpaque_piezas += $regPackingList->cantidad;
            $arrays[$contador]["noControl"]=$regPackingList->noempaque.'.R'.$regPackingList->nocontrol;
            $arrays[$contador]["noop"]=$regPackingList->noop;
            $arrays[$contador]["longitud"]=$regPackingList->longitud;
            $arrays[$contador]["amplitud"]=$regPackingList->amplitud;
            $arrays[$contador]["peso"]=$regPackingList->peso;
            $arrays[$contador]["impresion"]=$regPackingList->impresion;
            $arrays[$contador]["alto"]=$regPackingList->alto;
            $arrays[$contador]["cantidad"]=$regPackingList->cantidad;
            $arrays[$contador]["cdgrollo"]=$regPackingList->cdgrollo;
            $contador++;
        }
        $giros=ceil(count($arrays)/2);
        for($i=1;$i<=$giros;$i++)
        {
            $pdf->AddPage();

            $pdf->Cell(15,1,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(33,1,'',0,1,'R'); 

            //ECastillo 27/01/2022

            $pdf->SetFont('Arial','',6);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Grupo Labro/Grupo Ceyla',0,1,'L');
            //

            $pdf->Cell(10,6,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(38,6,$arrays[($i*2)-1]['noControl'],0,1,'R'); 


            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'NoOp',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$arrays[($i*2)-1]['noop'],0,1,'R'); 

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Longitud',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($arrays[($i*2)-1]['longitud'],2).' mts',0,1,'R'); 

            if(isset($arrays[($i*2)-1]['amplitud']))
            {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Ancho plano',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$arrays[($i*2)-1]['amplitud'].' mm',0,1,'R'); 
            }
            
            if(!empty($arrays[($i*2)-1]['peso']))
            {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Peso',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($arrays[($i*2)-1]['peso'],3).' kgs',0,1,'R'); 
            }
            

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'P.C.:',0,0,'L');
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$alptEmpaque_prov,0,1,'L'); 
            
            //$pdf->Cell(28,4,'2111342015',0,1,'R'); 

            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Corte',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            $pdf->Cell(28,4,$arrays[($i*2)-1]['alto'].' mm',0,1,'R');
            
            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->Cell(10,4,'Piezas aprox',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            $pdf->Cell(28,4,number_format($arrays[($i*2)-1]['cantidad']*1000),0,1,'R'); 
           
            $pdf->Ln(2);

            $pdf->SetFont('3of9','',27);            
            $pdf->Cell(48,5,'*'.$arrays[($i*2)-1]['cdgrollo'].'*',0,1,'R');
            $pdf->SetFont('Arial','',8);       
            $pdf->Cell(56,3,$arrays[($i*2)-1]['cdgrollo'],0,1,'C');      

            //DUPLICADO

            $pdf->SetY(0);

            $pdf->Cell(66,1,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(33,1,'',0,1,'R');       

            $pdf->Cell(53,6,'',0,0,'L');      
            $pdf->SetFont('Arial','B',18);
            $pdf->Cell(40,6,$arrays[$i*2]['noControl'],0,1,'L'); 

             $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'NoOp',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$arrays[$i*2]['noop'],0,1,'R'); 

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Longitud',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($arrays[$i*2]['longitud'],2).' mts',0,1,'R'); 

            if(isset($arrays[$i*2]['amplitud']))
            {
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Ancho plano',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$arrays[$i*2]['amplitud'].' mm',0,1,'R'); 
            }
            if(!empty($arrays[$i*2]['peso']))
            {
               $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Peso',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,number_format($arrays[$i*2]['peso'],3).' kgs',0,1,'R'); 
            }
           
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(53,4,'',0,0,'L');
            /* Aquí va el lote de genoma --------------------------------------------------------*/
            $pdf->Cell(10,4,'P.C.:',0,0,'L');
            $pdf->Cell(10,4,'',0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(28,4,$alptEmpaque_prov,0,1,'L'); 
            //$pdf->Cell(28,4,'2111342015',0,1,'R'); 

            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Corte',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            $pdf->Cell(28,4,$arrays[$i*2]['alto'].' mm',0,1,'R');
            
            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(53,4,'',0,0,'L');
            $pdf->Cell(10,4,'Piezas aprox',0,0,'L');
            $pdf->SetFont('Arial','B',12); 
            /*if($regPackingList->impresion=="Cristal Gota Gde.Im/Trn.Autoadh.")
            {
              $pdf->Cell(28,4,round($regPackingList->cantidad*1000),0,1,'R'); 
            }
            else
            {*/
              $pdf->Cell(28,4,number_format($arrays[$i*2]['cantidad']*1000),0,1,'R'); 
            //}
            
            $pdf->Ln(2);

            $pdf->SetFont('3of9','',27);  
            $pdf->Cell(53,4,'',0,0,'L');          
            $pdf->Cell(48,5,'*'.$arrays[$i*2]['cdgrollo'].'*',0,1,'L');
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(53,4,'',0,0,'L');       
            $pdf->Cell(40,3,$arrays[$i*2]['cdgrollo'],0,1,'C');

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

         

          

          $pdf->Cell(10,3,'',0,1,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(4,5,'',0,0,'L');
          $pdf->Cell(18,5,'Empaque ',0,0,'L');
          $pdf->SetFont('Arial','B',20);
          $pdf->Cell(20,5,''.$alptEmpaque_noempaque,0,0,'R'); 

          //Ecastilo 27/01/2022
          $pdf->SetFont('Arial','',8);
          $pdf->Cell(26,4,'',0,0,'L');
          $pdf->Cell(26,4,'Grupo Labro/Grupo Ceyla',0,1,'R');


           if(strlen($alptEmpaque_impresion)<=40)
          {
            $pdf->SetFont('Arial','B',10);
          }
          else if(strlen($alptEmpaque_impresion)>40)
          {
            $pdf->SetFont('Arial','B',8);
          }
          $pdf->Cell(4,5,'',0,0,'L');

                    
            $pdf->Cell(94,5,$alptEmpaque_impresion,0,1,'L');
          
    
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L');
          $pdf->Cell(10,4,'P.C.:',0,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(40,4,$alptEmpaque_prov.'',0,1,'R');


          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L');
          $pdf->Cell(10,4,'Rollos',0,0,'L');
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(40,4,$packingListSelect->num_rows.'',0,0,'R');

/*Ecastillo 27/01/2022
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L'); 
          $pdf->Cell(20,4,'Fabricado:',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(20,4,$alptEmpaque_fecha,0,1,'R');
          */

          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L'); 
          $pdf->Cell(28,4,'Piezas aprox',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(22,4,number_format($alptEmpaque_piezas*1000),0,0,'L'); 
/*Ecastillo 27/01/2022
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L'); 
          $pdf->Cell(20,4,'Caducidad:',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(20,4,$alptEmpaque_caduca,0,1,'R');
*/
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L'); 
          $pdf->Cell(20,4,'',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(20,4,'',0,1,'R');
          

          $pdf->SetY(21);
      
          $pdf->Cell(4,4,'',0,0,'L'); 
          $pdf->SetFont('Arial','',10); 
          $pdf->Cell(22,4,'Longitud',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(28,4,number_format($alptEmpaque_longitud,2)." mts",0,0,'R');

          $pdf->SetFont('Arial','',10);
          $pdf->Cell(4,4,'',0,0,'L'); 
          //Aquí iba el lote
          $pdf->Cell(20,4,'',0,0,'L');
          $pdf->SetFont('Arial','B',10); 
          $pdf->Cell(20,4,'',0,1,'R');

          if($alptEmpaque_pbruto>0)
          {
            $pdf->Cell(4,4,'',0,0,'L'); 
            $pdf->SetFont('Arial','',10); 
            $pdf->Cell(28,4,'Peso',0,0,'L');
            $pdf->SetFont('Arial','B',10); 
            $pdf->Cell(22,4,$alptEmpaque_pbruto." kgs",0,1,'R');
        }

          $pdf->Ln(4); 

          $pdf->SetFont('3of9','',26);
          $pdf->Cell(98,6,'*'.$idPaquete.'*',0,1,'C');
          $pdf->SetFont('Arial','',8); 
          $pdf->Cell(50,3,'',0,0,'R'); 
          $pdf->Cell(5,3,$idPaquete,0,1,'C'); 

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

$consul=$MySQLiconn->query("SELECT c.*,(select descripcionImpresion from impresiones where id=c.producto) as productoc,(select logproveedor from impresiones where id=c.producto) as logproveedor,(select haslote from impresiones where id=c.producto) as haslote,date(c.fechamov) as fecha,DATE(DATE_ADD(c.fechamov, INTERVAL 6 MONTH)) as caduca from caja c where c.codigo='$numero'");//Esta consulta tiene longitud,carece de dinamicidad

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
    $pdf->Cell(18,5,'Empaque ',0,0,'L');
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(32,5,''.$regPackingList->referencia,0,1,'R'); 

    //$pdf->Cell(4,4,'',0,0,'L');
   // $pdf->SetFont('Arial','B',10);
   // $pdf->Cell(18,4,'Producto',0,1,'L');
        if(strlen($regPackingList->productoc)<=40)
    {
    $pdf->SetFont('Arial','B',10);
  }
  else if(strlen($regPackingList->productoc)>40)
  {
    $pdf->SetFont('Arial','B',8);
  }
    $pdf->Cell(4,5,'',0,0,'L');
    if(!empty($regPackingList->logproveedor))
    {
      $pdf->Cell(94,5,$regPackingList->productoc,0,1,'L');
    }
    else
    {
      $pdf->Cell(94,5,$regPackingList->productoc,0,1,'L');
    }

    $pdf->Ln(1);

   $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(28,4,'Contenido',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(22,4,$regPackingList->noElementos.' paquetes',0,0,'R');    
    
    /*ECastillo 27/01/2022

    $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Fabricado:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->fecha,0,1,'R'); 
      */
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,'',0,1,'R'); 


    //$pdf->Cell(4,4,'',0,0,'L');
    //$pdf->SetFont('Arial','B',9);
    //$pdf->Cell(18,4,'Peso ',0,0,'L');
    //$pdf->SetFont('Arial','B',11);
    //$pdf->Cell(40,4,number_format($regPackingList->peso,3).' Kgs',0,1,'R');   

    $pdf->Cell(4,4,'',0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(18,4,'Piezas aprox ',0,0,'L');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(32,4,$regPackingList->piezas.' millares',0,0,'R');

/*ECastillo 27/01/2022

    $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'Caducidad:',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,$regPackingList->caduca,0,1,'R'); 
      */
       $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,4,'',0,0,'L'); 
      $pdf->Cell(20,4,'',0,0,'L');
      $pdf->SetFont('Arial','B',10); 
      $pdf->Cell(20,4,'',0,1,'R'); 

    if($regPackingList->peso>0)
    {
       $pdf->SetY(21);
      
      
      
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,6,'',0,0,'L'); 
      $pdf->Cell(15,6,'Peso',0,0,'L');
      $pdf->SetFont('Arial','B',10);  
      $pdf->Cell(12,6,$regPackingList->peso." kgs",0,0,'R');
    }

    if($regPackingList->haslote==1)
    {
     $SECU=$MySQLiconn->query("SELECT e.codigo,(select lote from codigosbarras where codigo=e.codigo) as lote from caja ka inner join ensambleempaques e on e.codEmpaque=ka.codigo WHERE ka.codigo='".$numero."' group by lote limit 1");
     $rowe=$SECU->fetch_object();
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(4,6,'',0,0,'L'); 
      $pdf->Cell(35,6,'Lote:',0,0,'L');
      $pdf->SetFont('Arial','B',9); 
      $pdf->Cell(20,6,$rowe->lote,0,1,'R'); 
      $pdf->Ln(4);
    }
    else
    {
      $pdf->Ln(8);
    }

     
     $pdf->SetFont('3of9','',34);

     $pdf->Cell(98,6,'*'.$numero.'*',0,1,'C');  } 
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
