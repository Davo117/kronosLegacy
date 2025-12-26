  <?php
  ob_end_clean();

  include('../../datos/mysql.php');
  require('../../fpdf/fpdf.php');

  class PDF extends FPDF
  { function Header()
    { if (file_exists('../../img_sistema/logo.jpg')==true)
      { $this->Image('../../img_sistema/logo.jpg',15,10,0,12); }

      $this->SetFillColor(255,153,0);

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Inspección de producto no conforme'),0,1,'L');
  
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('RC-01-POT-7.5'),0,1,'L');

      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('1.0'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Fecha de revisión'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Abril 21, 2014'),0,1,'L');
      
      $this->SetFont('Arial','B',8);
      $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
      $this->Cell(0.5,4,'',0,0,'R',true);
      $this->SetFont('Arial','I',8);
      $this->Cell(75,4,utf8_decode('Inspector de calidad'),0,1,'L');
      
      $this->Ln(4.15); }
    
    // Pie de página
    function Footer()
    { $this->SetFont('arial','',8);
      $this->SetY(-7.5);
      $this->Cell(0,6,utf8_decode('Grupo Labro | Sistema de Gestión de Calidad, página '.$this->PageNo().' de {nb}'),0,1,'R'); }
  }

  // Productos
  $link_mysqli = conectar();
  $querySelect = $link_mysqli->query("
    SELECT pdtoimpresion.impresion AS producto,
           pdtoimpresion.cdgimpresion AS cdgproducto,
           pdtodiseno.alto
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno");
  
  if ($querySelect->num_rows > 0)
  { while ($regQuery = $querySelect->fetch_object())
    { $prodPNC_CPProducto[$regQuery->cdgproducto] = $regQuery->producto;
      $prodPNC_CPAlto[$regQuery->cdgproducto] = $regQuery->alto;}
  }
  
  // Proceso|Operación
  $link_mysqli = conectar();
  $query1Select = $link_mysqli->query("
    SELECT prodproceso.proceso,
           prodoperacion.operacion,
           prodoperacion.cdgoperacion
    FROM prodproceso, 
         prodoperacion
    WHERE prodproceso.cdgproceso = prodoperacion.cdgproceso AND
          prodproceso.cdgproceso = '".$_GET['cdgproceso']."'");

  if ($query1Select->num_rows > 0)
  { $nOperaciones = $query1Select->num_rows;

    $nOperacion = 0;
    while ($regQuery1 = $query1Select->fetch_object())
    { $nOperacion++;

      $prodPNC_Proceso = $regQuery1->proceso;
      $prodPNC_Operacion[$regQuery1->cdgoperacion] = $regQuery1->operacion;
      $prodPNC_Operaciones[$nOperacion] = $regQuery1->cdgoperacion;

      $link_mysqli = conectar();
      $query2Select = $link_mysqli->query("
        SELECT rechempleado.idempleado,
               rechempleado.empleado,
               rechempleado.cdgempleado
        FROM prodrolloope,
             rechempleado
        WHERE prodrolloope.cdgempleado = rechempleado.cdgempleado AND
              prodrolloope.cdgoperacion = '".$prodPNC_Operaciones[$nOperacion]."' AND
             (prodrolloope.fchoperacion BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['dsdfecha']."')
        GROUP BY rechempleado.cdgempleado");

      if ($query2Select->num_rows > 0)
      { $nEmpleados = $query2Select->num_rows;

        $nEmpleado = 0;
        while ($regQuery2 = $query2Select->fetch_object())
        { $nEmpleado++;

          $prodPNC_Empleado[$regQuery2->cdgempleado] = $regQuery2->empleado;
          $prodPNC_Empleados[$nEmpleado] = $regQuery2->cdgempleado;
          
          $link_mysqli = conectar(); 
          $query5Select = $link_mysqli->query("
            SELECT prodrolloope.cdgempleado,
                   prodrollo.cdgproducto,
               SUM(prodrolloope.longitud) as longitud 
            FROM prodrollo,
                 prodrolloope
            WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                  prodrolloope.cdgoperacion = '".$prodPNC_Operaciones[$nOperacion]."' AND
                  prodrolloope.cdgempleado = '".$prodPNC_Empleados[$nEmpleado]."' AND
                 (prodrolloope.fchoperacion BETWEEN '".$_GET['dsdfecha']."' AND '".$_GET['dsdfecha']."')
            GROUP BY prodrolloope.cdgempleado,
                     prodrollo.cdgproducto");
          
          if ($query5Select->num_rows > 0)
          { $prodPNC_ProductosEmpleado[$nEmpleado] = $query5Select->num_rows;
            
            $nProducto = 0;
            while ($regQuery5 = $query5Select->fetch_object())
            { $nProducto++;
              
              $prodPNC_RProducto[$nEmpleado][$nProducto] = $regQuery5->cdgproducto;
              $prodPNC_RLongitud[$nEmpleado][$nProducto] = $regQuery5->longitud;
            }
          }
        }
      }
    }
  } 

  $pdf = new PDF('P','mm','letter');
  $pdf->AliasNbPages();
  $pdf->SetDisplayMode(real, continuous);
  $pdf->AddFont('3of9','','free3of9.php');

  if ($nOperaciones > 0)
  { for ($nOperacion = 1; $nOperacion <= $nOperaciones; $nOperacion++)
    { $pdf->AddPage();
      $pdf->SetFillColor(255,153,0);
      
      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(45,4.15,utf8_decode('Proceso'),0,0,'R');
      $pdf->Cell(0.5,4.15,'',0,0,'R',true);
      $pdf->SetFont('arial','B',8);
      $pdf->Cell(45,4,utf8_decode($prodPNC_Proceso),0,1,'L');
    
      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(45,4.15,utf8_decode('Operación'),0,0,'R');
      $pdf->Cell(0.5,4.15,'',0,0,'R',true);
      $pdf->SetFont('arial','B',8);
      $pdf->Cell(45,4,utf8_decode($prodPNC_Operacion[$prodPNC_Operaciones[$nOperacion]]),0,1,'L');
    
      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(45,4.15,utf8_decode('Fecha de inspección'),0,0,'R');
      $pdf->Cell(0.5,4.15,'',0,0,'R',true);
      
      $pdf->SetFont('arial','B',8);
      if ($_GET['dsdfecha'] == $_GET['hstfecha'])
      { $pdf->Cell(45,4,utf8_decode($_GET['dsdfecha']),0,1,'L');
      } else 
      { $pdf->Cell(45,4,utf8_decode('Del '.$_GET['dsdfecha'].' al '.$_GET['hstfecha']),0,1,'L'); }
      
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(70,4.15,utf8_decode('Empleados'),0,0,'L');
      $pdf->Cell(60,4.15,utf8_decode('Productos'),0,0,'L');
      $pdf->Cell(20,4.15,utf8_decode('Recibido'),0,0,'L');
      $pdf->Cell(20,4.15,utf8_decode('Entregado'),0,1,'L');
      
      for ($nEmpleado = 1; $nEmpleado <= $nEmpleados; $nEmpleado++)
      { $pdf->SetFont('3of9','',20);
        $pdf->Cell(20,8.3,'*'.$prodPNC_Empleados[$nEmpleado].'*',0,0,'C');
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(40,8.3,utf8_decode($prodPNC_Empleado[$prodPNC_Empleados[$nEmpleado]]),0,1,'L');
        
        $pdf->SetY($pdf->GetY()-8.3);
        for ($nProducto = 1; $nProducto <= $prodPNC_ProductosEmpleado[$nEmpleado]; $nProducto++)
        { $pdf->SetFont('Arial','I',8);
          $pdf->SetX(80);
          $pdf->Cell(60,4.15,utf8_decode($prodPNC_CPProducto[$prodPNC_RProducto[$nEmpleado][$nProducto]]),0,0,'L');
          $pdf->Cell(20,4.15,number_format(($prodPNC_RLongitud[$nEmpleado][$nProducto]/$prodPNC_CPAlto[$prodPNC_RProducto[$nEmpleado][$nProducto]]),3,'.',''),0,1,'R'); }
          
        $pdf->SetFont('Arial','B',8);
        $pdf->SetX(80);
        $pdf->Cell(60,4.15,utf8_decode('Total'),0,1,'R');
      }
    }
  } else
  { $pdf->SetFont('Arial','I',8);
    $pdf->Cell(10,4.5,'Se encontro información para mostrar.',0,1,'L'); }

  $pdf->Output('RC-01-POT-7.5.pdf', 'D');
?>
