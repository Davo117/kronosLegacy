<?php
  include '../../fpdf/fpdf.php';
  include '../../datos/mysql.php';
  $link = conectar();

  if ($_GET['cdgembarque'])
  { $_SESSION['cdgembarque'] = $_GET['cdgembarque'];

    $vntsEmbarqueSelect = $link->query("
      SELECT vntsembarque.cdgembarque,
             vntsembarque.referencia,
             pdtoimpresion.impresion,
             vntsembarque.cdgempaque,
             vntsembarque.fchembarque,
             vntsembarque.cdgsucursal,
             vntsembarque.transporte
        FROM vntsembarque,
             pdtoimpresion
       WHERE vntsembarque.cdgembarque = '".$_SESSION['cdgembarque']."' AND
             vntsembarque.cdgproducto = pdtoimpresion.cdgimpresion");

    if ($vntsEmbarqueSelect->num_rows > 0)
    { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

      $vntsEmbarque_cdgembarque = $regVntsEmbarque->cdgembarque;
      $vntsEmbarque_referencia = $regVntsEmbarque->referencia;
      $vntsEmbarque_producto = $regVntsEmbarque->impresion;
      $vntsEmbarque_cdgempaque = $regVntsEmbarque->cdgempaque;
      $vntsEmbarque_fchembarque = $regVntsEmbarque->fchembarque;
      
      $vntsEmbarque_transporte = $regVntsEmbarque->transporte;
      $vntsEmbarque_cdgsucursal = $regVntsEmbarque->cdgsucursal;

      $vntsSucursalSelect = $link->query("
        SELECT vntscliente.cliente,
               vntssucursal.sucursal, 
               vntssucursal.domicilio,
               vntssucursal.colonia,
               vntssucursal.cdgpostal,
               vntssucursal.telefono
          FROM vntscliente,
               vntssucursal
         WHERE vntscliente.cdgcliente = vntssucursal.cdgcliente AND
               vntssucursal.cdgsucursal = '".$vntsEmbarque_cdgsucursal."'");

      if ($vntsSucursalSelect->num_rows > 0)
      { $regVntsSucursal = $vntsSucursalSelect->fetch_object();

        $vntsEmbarque_cliente = $regVntsSucursal->cliente;
        $vntsEmbarque_sucursal = $regVntsSucursal->sucursal;
        $vntsEmbarque_domicilio = $regVntsSucursal->domicilio;
        $vntsEmbarque_colonia = $regVntsSucursal->colonia;
        $vntsEmbarque_cdgpostal = $regVntsSucursal->cdgpostal;
        $vntsEmbarque_telefono = $regVntsSucursal->telefono; }

      if ($vntsEmbarque_cdgempaque == 'C')
      { $vntsEmbarque_codigodoc = 'EM-FR01';
        $vntsEmbarque_nombredoc = 'Lista de embarque Caja';
        $vntsEmbarque_versiondoc = '3.0';
        $vntsEmbarque_revisiondoc = 'Junio 08, 2015';

        $alptEmpaqueSelect = $link->query("
          SELECT alptempaque.cdgempaque,
                 alptempaque.tpoempaque,
                 alptempaque.noempaque,
                 alptempaque.peso,
             SUM(alptempaquep.cantidad) AS cantidad,
             SUM(alptempaquep.dev) AS devuelto
            FROM alptempaque,
                 alptempaquep,
                 prodpaquete
           WHERE alptempaque.cdgembarque = '".$_GET['cdgembarque']."' AND
                 alptempaque.cdgempaque = alptempaquep.cdgempaque AND
          SUBSTR(alptempaquep.cdgpaquete,1,12) = prodpaquete.cdgpaquete
        GROUP BY alptempaque.cdgempaque,
                 alptempaque.tpoempaque,
                 alptempaque.noempaque,
                 alptempaque.peso
        ORDER BY alptempaque.noempaque");
        
        if ($alptEmpaqueSelect->num_rows > 0)
        { while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
          { $item++;
            $alptEmpaque_cdgempaque[$item] = $regAlptEmpaque->cdgempaque;
            $alptEmpaque_tpoempaque[$item] = $regAlptEmpaque->tpoempaque;
            $alptEmpaque_noempaque[$item] = $regAlptEmpaque->noempaque;
            $alptEmpaque_pesobruto[$item] = $regAlptEmpaque->peso;
            $alptEmpaque_cantidad[$item] = number_format($regAlptEmpaque->cantidad,3);
            $alptEmpaque_devuelto[$item] = number_format($regAlptEmpaque->devuelto,3);
     
            $alptEmpaque_sumapeso += $regAlptEmpaque->peso;
            $alptEmpaque_sumacantidad += number_format($regAlptEmpaque->cantidad,3);
            $alptEmpaque_sumadevuelto += number_format($regAlptEmpaque->devuelto,3); }

          $nEmpaques = $alptEmpaqueSelect->num_rows; }
      } else
      { $vntsEmbarque_codigodoc = 'EM-FR02';
        $vntsEmbarque_nombredoc = 'Lista de embarque Rollos';
        $vntsEmbarque_versiondoc = '4.0';
        $vntsEmbarque_revisiondoc = 'Junio 08, 2015';

        $alptEmpaqueSelect = $link->query("
           SELECT alptempaque.cdgempaque,
                  alptempaque.tpoempaque,
                  alptempaque.noempaque,
                  alptempaque.peso AS pesobruto,
              SUM(alptempaquer.cantidad) AS cantidad
             FROM alptempaque,
                  alptempaquer
            WHERE alptempaque.cdgembarque = '".$_GET['cdgembarque']."' AND
                  alptempaque.cdgempaque = alptempaquer.cdgempaque
         GROUP BY alptempaque.cdgempaque,
                  alptempaque.tpoempaque,
                  alptempaque.noempaque
         ORDER BY alptempaque.noempaque");

      if ($alptEmpaqueSelect->num_rows > 0)
      { while ($regAlptEmpaque = $alptEmpaqueSelect->fetch_object())
        { $item++;
          
          $alptEmpaque_cdgempaque[$item] = $regAlptEmpaque->cdgempaque;
          $alptEmpaque_tpoempaque[$item] = $regAlptEmpaque->tpoempaque;
          $alptEmpaque_noempaque[$item] = $regAlptEmpaque->noempaque;

          $alptEmpaque_sumapeso[$item] = $regAlptEmpaque->pesobruto;
          $alptEmpaque_sumacantidad[$item] = $regAlptEmpaque->cantidad;

          $alptEmpaque_cantidadsuma += $regAlptEmpaque->cantidad;
          $alptEmpaque_pesosuma += $regAlptEmpaque->pesobruto;

          $alptEmpaqueRSelect = $link->query("
            SELECT alptempaquer.nocontrol, 
                   alptempaquer.cdgrollo,
                  (alptempaquer.cantidad) AS cantidad,
                  (alptempaquer.dev) AS devuelto
              FROM alptempaquer
             WHERE alptempaquer.cdgempaque = '".$alptEmpaque_cdgempaque[$item]."'
          ORDER BY alptempaquer.nocontrol");

          $subItem = 0;
          while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
          { $subItem++;

            $alptEmpaque_nocontrol[$item][$subItem] = $regAlptEmpaqueR->nocontrol;
            $alptEmpaque_cantidad[$item][$subItem] = number_format($regAlptEmpaqueR->cantidad,3);
            $alptEmpaque_devuelto[$item][$subItem] = number_format($regAlptEmpaqueR->devuelto,3);

            $alptEmpaque_sumadevuelto += number_format($regAlptEmpaqueR->devuelto,3); }       

          $nRollos[$item] = $alptEmpaqueRSelect->num_rows; }

        $nEmpaques = $alptEmpaqueSelect->num_rows; }
      }
              
      // Generación del archivo
      class PDF extends FPDF
      { function Header()
        { global $vntsEmbarque_codigodoc;
          global $vntsEmbarque_nombredoc;
          global $vntsEmbarque_versiondoc;
          global $vntsEmbarque_revisiondoc;

          global $vntsEmbarque_cdgembarque;
          global $vntsEmbarque_referencia;
          global $vntsEmbarque_cdgempaque;
          global $vntsEmbarque_producto;
          global $vntsEmbarque_fchembarque;
          global $vntsEmbarque_cliente;
          global $vntsEmbarque_sucursal;
          global $vntsEmbarque_domicilio;
          global $vntsEmbarque_colonia;
          global $vntsEmbarque_cdgpostal;
          global $vntsEmbarque_telefono;
          global $vntsEmbarque_transporte;

          global $alptEmpaque_sumadevuelto;

          $this->SetFont('Arial','B',8);
          $this->SetFillColor(255,153,0);

          if (file_exists('../../img_sistema/logonew.jpg')==true)
          { $this->Image('../../img_sistema/logonew.jpg',10,0,0,32); }

          $this->SetFont('Arial','B',8);
          $this->Cell(125,4,utf8_decode('Documento'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode($vntsEmbarque_nombredoc),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(125,4,utf8_decode('Código'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode($vntsEmbarque_codigodoc),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(125,4,utf8_decode('Versión'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode($vntsEmbarque_versiondoc),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(125,4,utf8_decode('Revisión'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode($vntsEmbarque_revisiondoc),0,1,'L');

          $this->SetFont('Arial','B',8);
          $this->Cell(125,4,utf8_decode('Responsable'),0,0,'R');
          $this->Cell(0.5,4,'',0,0,'R',true);
          $this->SetFont('Arial','I',8);
          $this->Cell(75,4,utf8_decode('Coordinador de Logística y Atención al Cliente'),0,1,'L'); 

          $this->Ln(4); }
      }

      $pdf = new PDF('P','mm','letter');
      $pdf->AliasNbPages();
      $pdf->SetDisplayMode(real, continuous);
      $pdf->AddPage();

      $pdf->SetFillColor(180,180,180);

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Cliente'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_cliente),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Embarque'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_cdgembarque),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Sucursal'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_sucursal),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Fecha'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_fchembarque),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Domicilio'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_domicilio),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Producto'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_producto),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Colonia'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_colonia),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Transporte'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_transporte),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Código postal'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_cdgpostal),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Referencia'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_referencia),0,1,'L');


      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(40,4,utf8_decode('Teléfono'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode($vntsEmbarque_telefono),0,0,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(50,4,utf8_decode('Fecha de recibido'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode('__________________'),0,1,'L');

      $pdf->SetFont('Arial','I',8);
      $pdf->Cell(130.5,4,utf8_decode('Nombre y firma'),0,0,'R');
      $pdf->Cell(0.5,4,'',0,0,'R',true);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(40,4,utf8_decode('______________________________________'),0,1,'L');
      
      $pdf->Ln(4);

      $pdf->Cell(4,4,'',0,0,'L');
      $pdf->Cell(24,4,'Empaque',0,0,'C',true);
      $pdf->Cell(24,4,'Millares',0,0,'C',true);
      $pdf->Cell(24,4,'Peso bruto',0,0,'C',true);

      if ($vntsEmbarque_cdgempaque == 'Q')
      { $pdf->Cell(24,4,'Bobina',0,0,'C',true);
        $pdf->Cell(24,4,'Millares',0,0,'C',true); }

      $pdf->SetFillColor(255,153,0);
      if ($alptEmpaque_sumadevuelto > 0)
      { $pdf->SetFillColor(255,153,0); 
        $pdf->Cell(24,4,'Devuelto',0,1,'C',true); }
      else
      { $pdf->Ln(); } 

      $pdf->SetFillColor(255,153,0);

      if ($vntsEmbarque_cdgempaque == 'C')
      { for ($item = 1; $item <= $nEmpaques; $item++)
        { $pdf->SetFont('arial','',6);
          $pdf->Cell(4,5,$item,0,0,'R');
          
          $pdf->SetFont('arial','B',10);
          $pdf->Cell(24,5,$alptEmpaque_tpoempaque[$item].$alptEmpaque_noempaque[$item],1,0,'R');

          $pdf->SetFont('arial','',10);
          $pdf->Cell(24,5,number_format($alptEmpaque_cantidad[$item],3),1,0,'R');
          $pdf->Cell(24,5,number_format($alptEmpaque_pesobruto[$item],3),1,0,'R'); 

          if ($alptEmpaque_devuelto[$item] > 0)
          { $pdf->Cell(24,5,$alptEmpaque_devuelto[$item],0,1,'R'); }
          else
          { $pdf->Ln(); }
        }

        $pdf->SetFont('arial','B',12);
        $pdf->Cell(4,6,'',0,0,'R');
        $pdf->Cell(24,6,'',0,0,'R');
        $pdf->Cell(24,6,number_format($alptEmpaque_sumacantidad,3),1,0,'R');
        $pdf->Cell(24,6,number_format($alptEmpaque_sumapeso,3),1,0,'R');
      } else
      { for ($item = 1; $item <= $nEmpaques; $item++)
        { $pdf->SetFont('arial','',6);
          $pdf->Cell(4,($nRollos[$item]*5),$item,0,0,'R');
          
          $pdf->SetFont('arial','B',20);
          $pdf->Cell(24,($nRollos[$item]*5),$alptEmpaque_tpoempaque[$item].$alptEmpaque_noempaque[$item],1,0,'R');

          $pdf->SetFont('arial','',10);
          $pdf->Cell(24,($nRollos[$item]*5),number_format($alptEmpaque_sumacantidad[$item],3),1,0,'R');
          $pdf->Cell(24,($nRollos[$item]*5),number_format($alptEmpaque_sumapeso[$item],3),1,0,'R');

          $posicionX = $pdf->GetX();
          for ($subItem = 1; $subItem <= $nRollos[$item]; $subItem++)
          { $pdf->SetFont('arial','B',9);
            if ($pdf->GetX() == $posicionX)
            { $pdf->Cell(24,5,'R'.$alptEmpaque_nocontrol[$item][$subItem],1,0,'R'); 
            } else 
            { $pdf->Cell(($posicionX-10),4,'',0,0,'R');
              $pdf->Cell(24,5,'R'.$alptEmpaque_nocontrol[$item][$subItem],1,0,'R'); }

            $pdf->SetFont('arial','',9);
            if ($alptEmpaque_devuelto[$item][$subItem] > 0)
            { $pdf->Cell(24,5,number_format($alptEmpaque_cantidad[$item][$subItem],3),1,0,'R');
              $pdf->Cell(24,5,number_format($alptEmpaque_devuelto[$item][$subItem],3),0,1,'R'); }
            else
            { $pdf->Cell(24,5,number_format($alptEmpaque_cantidad[$item][$subItem],3),1,1,'R'); }
          } 
        } 

        $pdf->SetFont('arial','B',12);
        $pdf->Cell(4,6,'',0,0,'R');
        $pdf->Cell(24,6,'',0,0,'R');
        $pdf->Cell(24,6,number_format($alptEmpaque_cantidadsuma,3),1,0,'R');
        $pdf->Cell(24,6,number_format($alptEmpaque_pesosuma,3),1,0,'R');
        $pdf->Cell(48,6,'',0,0,'R'); 
      }

      if ($alptEmpaque_sumadevuelto > 0)
      { $pdf->Cell(24,6,number_format($alptEmpaque_sumadevuelto,3),1,1,'R',true); }
      else
      { $pdf->Ln(); }

      $pdf->Output('Lista de embarque '.$vntsEmbarque_cdgembarque.' '.$vntsEmbarque_sucursal.'.pdf', 'D');
    }
  } else
  { echo '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Lista de embarque</title>
    <link rel="stylesheet" type="text/css" href="/css/2014.css">
  </head>
  <body>
    <div id="contenedor"><h1>Lista de embarque no encontrada );</h1></div>
  </body>
</html>'; }
?>