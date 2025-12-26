<?php 
include '../../fpdf/fpdf.php';
include '../Controlador_produccion/db_produccion.php';
error_reporting(0);
     global $prodOperaciones_operacion;
    $prodOperaciones_operacion=$_GET['proceso'];
    global $prodOperaciones_empleado;
    $prodOperaciones_empleado=$_GET['empleado'];
   global $prodOperaciones_producto;
   $prodOperaciones_producto=$_GET['producto'];
    global $procesoActual;
    $procesoActual=$prodOperaciones_operacion;
    global $fecha;
   $fecha=$_GET['fch'];

      if(!empty($fecha))
{
  if(!empty($_GET['hstfch']))
  {
     global $consul;
    $consul="DATE(fecha) between '".$fecha."' and '".$_GET['hstfch']."'";
   global  $consulTime;
   $consulTime="DATE(`pro".$procesoActual."`.fecha) between '".$fecha."' and '".$_GET['hstfch']."' ";
  }
  else
  {
     global $consul;
    $consul="DATE(fecha)='".$fecha."'";
   global  $consulTime;
   $consulTime="DATE(`pro".$procesoActual."`.fecha)='".$fecha."'";
  }
   
}
else
{

  global $consul;
  $consul="DATE(fecha)=curdate()";
  global $consulTime;
  $consulTime="DATE(`pro".$procesoActual."`.fecha)=curdate()";
}
if(!empty($prodOperaciones_producto))
{
  global $consultProd;
  $consultProd="and producto='".$prodOperaciones_producto."'";
  global $consultProdTime;
  $consultProdTime="and merma.producto='".$prodOperaciones_producto."'";
  $pr=$MySQLiconn->query("SELECT millaresPorPaquete as mpp from impresiones where descripcionImpresion='".$prodOperaciones_producto."'");
  $gu=$pr->fetch_array();
  global $mpp;
  $mpp=$gu['mpp']*1000;
}
else
{
  global $mpp;
  $mpp=500;
  global $consultProd;
  $consultProd="";
  global $consultProdTime;
  $consultProdTime="";
}
if(!empty($prodOperaciones_empleado))
{
  global $consultEmp;
  $consultEmp="and operador='".$prodOperaciones_empleado."'";
  global $consultEmpTime;
  $consultEmpTime="and `pro$procesoActual`.operador='".$prodOperaciones_empleado."'";

}
else
{
  global $consultEmp;
  $consultEmp="";
  global $consultEmpTime;
  $consultEmpTime="";
}

class PDF extends FPDF
  { function Header()
    {
  global $prodOperaciones_operacion;
    $prodOperaciones_operacion=$_GET['proceso'];
    if(empty($prodOperaciones_operacion))
    {
      $prodOperaciones_operacion="Todos los procesos";
    }
    global $prodOperaciones_empleado;
    $prodOperaciones_empleado=$_GET['empleado'];
    if(empty($prodOperaciones_empleado))
    {
      $prodOperaciones_empleado="Todos los empleados";
    }
    $opdor=explode('|',$prodOperaciones_empleado);
   $opdor=$opdor[0];
   global $prodOperaciones_producto;
   $prodOperaciones_producto=$_GET['producto'];
   if(empty($prodOperaciones_producto))
   {
    $prodOperaciones_producto="Todos los productos";
   }
    global $procesoActual;
    $procesoActual=$prodOperaciones_operacion;
    global $fecha;
   $fecha=$_GET['fch'];
   
      if (file_exists('../../pictures/logo-labro.jpg')==true)
      { $this->Image('../../pictures/logo-labro.jpg',10,7,0,10); }      

       $this->SetFillColor(224,7,8);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Histórico de Operaciones'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,'Operación',0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($prodOperaciones_operacion),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Empleado'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($opdor),0,1,'L');      

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Producto'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($prodOperaciones_producto),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Rango de fechas'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode($_GET['fch'].' | '.$_GET['hstfch']),0,1,'L');      
      $this->Cell(75,4,"",0,1,'L');      

      $this->Ln(4.15); }
  }

  $pdf=new PDF('P','mm','letter');
  $pdf->AliasNbPages();  
  $pdf->AddPage();
  $pdf->SetFillColor(255,153,0);
  
  // 20011 Embosado (BS)
  // 30011 Refilado (BS)
  // 30015 Laminado (BS)
  // 40011 Sliteo (BS)

  // 20001 Impresión (SS)
  // 30001 Refilado (SS)
  // 40001 Fusión (SS)
  // 40006 Revisión (SS)
  // 50001 Corte (SS)

 // if ($prodOperaciones_cdgoperacion == '')
  //{ if ($prodOperaciones_cdgempleado == '')
    //{ if ($prodOperaciones_cdgproducto == '')
      //{ // Todas las operaciones
        // Todos los empleados
        // Todos los productos     

        // *** Rollos
  if(empty($_GET['proceso']))
  {
    $tipo=$_GET['tipo'];
    $tipos=$tipo;
    $MySQLiconn->query("SET @p0='".$tipo."'");
    $TOP=$MySQLiconn->query("call getJuegoProcesosByType(@p0)");
    $procesos=array();

     $idsProcesos=array();
    for($i=0;$arrProcess=$TOP->fetch_object();$i++)
    {

      $procesos[$i]=$arrProcess->descripcionproceso;
      $idsProcesos[$i]=$arrProcess->id;
    }
    $MySQLiconn->next_result();
    for($i=0;$i<count($procesos);$i++)
    {
          $tipos=$_GET['tipo'];
      $SQL=$MySQLiconn->query("SELECT descripcionproceso,(SELECT nombreParametro from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionproceso='".$procesos[$i]."')) as referencial from juegoprocesos where id='".$idsProcesos[$i]."'");
$row=$SQL->fetch_object();
$procesoEnCurso=$row->descripcionproceso;
$dato=$row->referencial;
/*echo "Proces".$procesoActual."--";
echo "Ref".$dato."--";*/
      $S=$MySQLiconn->query("SELECT DATE(fecha) as fecha,(SELECT divisiones from procesos where descripcionProceso='".$procesoEnCurso."') as divisiones FROM `pro".$procesoEnCurso."` where $consul $consultProd $consultEmp  group by DATE(fecha)");
while($rem=$S->fetch_object())
{
 
        
          $divisiones=$rem->divisiones;
          $fecha=$_GET['fch'];

      if(!empty($fecha))
{
  if(!empty($_GET['hstfch']))
  {
     $consul;
    $consul="DATE(fecha) between '".$fecha."' and '".$_GET['hstfch']."'";
   $consulTime;
   $consulTime="DATE(`pro".$procesoEnCurso."`.fecha) between '".$fecha."' and '".$_GET['hstfch']."' ";
  }
  else
  {
     $consul;
    $consul="DATE(fecha)='".$fecha."'";
   $consulTime;
   $consulTime="DATE(`pro".$procesoEnCurso."`.fecha)='".$fecha."'";
  }
   
}
else
{
  $consul;
  $consul="DATE(fecha)=curdate()";
  $consulTime;
  $consulTime="DATE(`pro".$procesoEnCurso."`.fecha)=curdate()";
}
if(!empty($_GET['empleado']))
{
  $consultEmp;
  $consultEmp="and operador='".$prodOperaciones_empleado."'";
  $consultEmpTime;
  $consultEmpTime="and `pro$procesoEnCurso`.operador='".$prodOperaciones_empleado."'";

}
else
{
   $consultEmp;
  $consultEmp="";
   $consultEmpTime;
  $consultEmpTime="";
}
          if($divisiones==1)
          {
              $Sim=$MySQLiconn->query("SELECT `pro".$procesoEnCurso."`.operador,`pro".$procesoEnCurso."`.producto,`pro".$procesoEnCurso."`.noop,DATE(`pro".$procesoEnCurso."`.fecha),merma.banderas,merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoEnCurso."`.fecha) as hora from `pro".$procesoEnCurso."` inner join merma on merma.codigo=`pro".$procesoEnCurso."`.$dato where  DATE(`pro".$procesoEnCurso."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=1 group by TIME(`pro".$procesoEnCurso."`.fecha)");
              //echo "SELECT `pro".$procesoEnCurso."`.operador,`pro".$procesoEnCurso."`.producto,`pro".$procesoEnCurso."`.noop,DATE(`pro".$procesoEnCurso."`.fecha),merma.banderas,merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoEnCurso."`.fecha) as hora from `pro".$procesoEnCurso."` inner join merma on merma.codigo=`pro".$procesoEnCurso."`.$dato where  DATE(`pro".$procesoEnCurso."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=1 group by TIME(`pro".$procesoEnCurso."`.fecha)";

          }
          else
          {
              $Sim=$MySQLiconn->query("SELECT `pro".$procesoEnCurso."`.operador,`pro".$procesoEnCurso."`.producto,`pro".$procesoEnCurso."`.noop,merma.banderas,DATE(`pro".$procesoEnCurso."`.fecha),merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoEnCurso."`.fecha) as hora from `pro".$procesoEnCurso."` inner join merma on merma.codigo=`pro".$procesoEnCurso."`.$dato where  DATE(`pro".$procesoEnCurso."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=0 group by TIME(`pro".$procesoEnCurso."`.fecha )");
              //echo "SELECT `pro".$procesoEnCurso."`.operador,`pro".$procesoEnCurso."`.producto,`pro".$procesoEnCurso."`.noop,merma.banderas,DATE(`pro".$procesoEnCurso."`.fecha),merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoEnCurso."`.fecha) as hora from `pro".$procesoEnCurso."` inner join merma on merma.codigo=`pro".$procesoEnCurso."`.$dato where  DATE(`pro".$procesoEnCurso."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=0 group by TIME(`pro".$procesoEnCurso."`.fecha )";
               
          }
          
           if($Sim->num_rows>0)
          {

       $pdf->SetFont('arial','',12);
            $pdf->Cell(23,4,$rem->fecha,0,1,'L');

            $pdf->SetFont('arial','B',8);
           // $pdf->Cell(23,5,utf8_decode("Operación | '.'$prodOperaciones_operaciones['20011']"),0,1,'L');
                $pdf->Cell(23,5,"Operación: ".strtoupper($procesoEnCurso)." (".$_GET['tipo'].")",0,1,'L');
            $pdf->Cell(5,4,'',0,0,'R');
            $pdf->Cell(23,4,utf8_decode('NoOP'),1,0,'C',true);
            $pdf->Cell(13,4,utf8_decode('In'),1,0,'C',true);
            $pdf->Cell(13,4,utf8_decode('Out'),1,0,'C',true);
            $pdf->Cell(12,4,utf8_decode('Scrap'),1,0,'C',true);
            $pdf->Cell(50,4,utf8_decode('Empleado'),1,0,'C',true);
            $pdf->Cell(80,4,utf8_decode('Producto'),1,1,'C',true);

            $prodOperacion_longin = 0;
            $prodOperacion_longout = 0;

            $item = 0;

            while ($regProdLoteOpe = $Sim->fetch_object())
            { 
               $nombre=explode('|',$regProdLoteOpe->operador);
  $codigo=$nombre[1];
  $nombre=$nombre[0];
  $item++;

              $pdf->SetFont('arial','I',5);
              if($item%2>0)
              {
                $pdf->SetFillColor(243,243,243);
              }
              else
              {
                $pdf->SetFillColor(223,223,223);
              }
              $pdf->Cell(5,4,$item,0,0,'R');

              $pdf->SetFont('arial','',8);
              $pdf->Cell(23,4,utf8_decode($regProdLoteOpe->noop),1,0,'C',true);
              $pdf->Cell(13,4,number_format($regProdLoteOpe->longIn,2,'.',','),1,0,'R',true);
              $pdf->Cell(13,4,number_format($regProdLoteOpe->longOut,2,'.',','),1,0,'R',true);
              $pdf->Cell(12,4,number_format((100-((($regProdLoteOpe->longOut*100)/$regProdLoteOpe->longIn))),2,'.',',').'%',1,0,'R',true);      
             // $pdf->Cell(60,4,utf8_decode("$prodOperacion_empleados[$regProdLoteOpe->cdgempleado]"),1,0,'L');
              $pdf->Cell(50,4,utf8_decode($nombre),1,0,'L',true);
              //$pdf->Cell(60,4,utf8_decode("$prodOperacion_productos[$regProdLoteOpe->cdgproducto]"),1,1,'L');
              $pdf->Cell(80,4,$regProdLoteOpe->producto,1,1,'L',true);
            
            $prodOperacion_longin += $regProdLoteOpe->longIn;
              $prodOperacion_longout += $regProdLoteOpe->longOut;

           // $prodOperaciones_longin['20011'] += $regProdLoteOpe->longIn;
             // $prodOperaciones_longout['20011'] += $regProdLoteOpe->longOut; }
}
            $pdf->SetFont('arial','I',8);
            $pdf->Cell(28,4,utf8_decode('Metros'),0,0,'R');
            $pdf->SetFont('arial','B',8);
            $pdf->Cell(13,4,number_format($prodOperacion_longin,2,'.',','),1,0,'R');
            $pdf->Cell(13,4,number_format($prodOperacion_longout,2,'.',','),1,0,'R');
            $pdf->Cell(12,4,number_format((100-((($prodOperacion_longout*100)/$prodOperacion_longin))),2,'.',',').'%',1,1,'R');
          }
           $pdf->SetFillColor(255,153,0);
            $pdf->Ln(2); }
          }


  }
  else
  {


  $SQL=$MySQLiconn->query("SELECT nombreParametro as referencial from juegoparametros where numParametro='C' and identificadorJuego=(SELECT packParametros from procesos where descripcionProceso='".$_GET['proceso']."')");
$row=$SQL->fetch_object();
$procesoActual=$_GET['proceso'];
$procesoEnCurso=$procesoActual;
$dato=$row->referencial;
    $tipos=$_GET['tipo'];
          if($tipos=="Termoencogible")
          {
            $tipos="Termoencogible";
          }

       $S=$MySQLiconn->query("SELECT DATE(fecha) as fecha,(SELECT divisiones from procesos where descripcionProceso='".$procesoActual."') as divisiones FROM `pro".$procesoActual."` where $consul $consultProd $consultEmp  group by DATE(fecha)");
while($rem=$S->fetch_object())
{  
 
          $divisiones=$rem->divisiones;
          if($divisiones==1)
          {
          
              $Sim=$MySQLiconn->query("SELECT `pro".$procesoActual."`.operador,`pro".$procesoActual."`.producto,`pro".$procesoActual."`.noop,DATE(`pro".$procesoActual."`.fecha),merma.banderas,merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoActual."`.fecha) as hora from `pro".$procesoActual."` inner join merma on merma.codigo=`pro".$procesoActual."`.$dato where  DATE(`pro".$procesoActual."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=1 group by TIME(`pro".$procesoActual."`.fecha)");
          }
          else
          {

              $Sim=$MySQLiconn->query("SELECT `pro".$procesoActual."`.operador,`pro".$procesoActual."`.producto,`pro".$procesoActual."`.noop,merma.banderas,DATE(`pro".$procesoActual."`.fecha),merma.unidadesIn,merma.unidadesOut,merma.longIn,merma.longOut,TIME(`pro".$procesoActual."`.fecha) as hora from `pro".$procesoActual."` inner join merma on merma.codigo=`pro".$procesoActual."`.$dato where  DATE(`pro".$procesoActual."`.fecha)='".$rem->fecha."' $consultProdTime $consultEmpTime  and rollo_padre=0 group by TIME(`pro".$procesoActual."`.fecha )");
          }
          
           if($Sim->num_rows>0)
          {
       $pdf->SetFont('arial','',12);
            $pdf->Cell(23,4,$rem->fecha,0,1,'L');

            $pdf->SetFont('arial','B',8);
           // $pdf->Cell(23,5,utf8_decode("Operación | '.'$prodOperaciones_operaciones['20011']"),0,1,'L');
             $pdf->Cell(23,5,"Operacion: ".strtoupper($prodOperaciones_operacion)." (".$_GET['tipo'].")",0,1,'L');
            $pdf->Cell(5,4,'',0,0,'R');
            $pdf->Cell(23,4,utf8_decode('NoOP'),1,0,'C',true);
            $pdf->Cell(13,4,utf8_decode('In'),1,0,'C',true);
            $pdf->Cell(13,4,utf8_decode('Out'),1,0,'C',true);
            $pdf->Cell(12,4,utf8_decode('Scrap'),1,0,'C',true);
            if(empty($_GET['empleado']))
            {

            $pdf->Cell(50,4,utf8_decode('Empleado'),1,0,'C',true);
            }
            $pdf->Cell(80,4,utf8_decode('Producto'),1,1,'C',true);

            $prodOperacion_longin = 0;
            $prodOperacion_longout = 0;

            $item = 0;

            while ($regProdLoteOpe = $Sim->fetch_object())
            { $item++;

              $pdf->SetFont('arial','I',5);
              if($item%2>0)
              {
                $pdf->SetFillColor(243,243,243);
              }
              else
              {
                $pdf->SetFillColor(223,223,223);
              }
              $pdf->Cell(5,4,$item,0,0,'R');

              $pdf->SetFont('arial','',8);
              $pdf->Cell(23,4,utf8_decode($regProdLoteOpe->noop),1,0,'C',true);
              $pdf->Cell(13,4,number_format($regProdLoteOpe->longIn,2,'.',','),1,0,'R',true);
              $pdf->Cell(13,4,number_format($regProdLoteOpe->longOut,2,'.',','),1,0,'R',true);
              $pdf->Cell(12,4,number_format((100-((($regProdLoteOpe->longOut*100)/$regProdLoteOpe->longIn))),2,'.',',').'%',1,0,'R',true);      
             // $pdf->Cell(60,4,utf8_decode("$prodOperacion_empleados[$regProdLoteOpe->cdgempleado]"),1,0,'L');
              if(empty($_GET['empleado']))
              {
                 $opdor=explode('|',$regProdLoteOpe->operador);
              $pdf->Cell(50,4,utf8_decode($opdor[0]),1,0,'L',true);
              }
             
              //$pdf->Cell(60,4,utf8_decode("$prodOperacion_productos[$regProdLoteOpe->cdgproducto]"),1,1,'L');
              $pdf->Cell(80,4,$regProdLoteOpe->producto,1,1,'L',true);
            
            $prodOperacion_longin += $regProdLoteOpe->longIn;
              $prodOperacion_longout += $regProdLoteOpe->longOut;

           // $prodOperaciones_longin['20011'] += $regProdLoteOpe->longIn;
             // $prodOperaciones_longout['20011'] += $regProdLoteOpe->longOut; }
}
            $pdf->SetFont('arial','I',8);
            $pdf->Cell(28,4,utf8_decode('Metros'),0,0,'R');
            $pdf->SetFont('arial','B',8);
            $pdf->Cell(13,4,number_format($prodOperacion_longin,2,'.',','),1,0,'R');
            $pdf->Cell(13,4,number_format($prodOperacion_longout,2,'.',','),1,0,'R');
            try{
            $pdf->Cell(12,4,number_format((100-((($prodOperacion_longout*100)/$prodOperacion_longin))),2,'.',',').'%',1,1,'R');
          }
          catch(Exception $e)
          {
            echo $e->getMessage();
          }
        }
             
  $pdf->SetFillColor(255,153,0);
          
            $pdf->Ln(2); }
          }
        
             $pdf->Output();  