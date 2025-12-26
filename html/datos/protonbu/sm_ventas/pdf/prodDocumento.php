<?php
  include '../../datos/mysql.php';

  if ($_GET['cdgembarque'])
  { $link_mysqli = conectar();
    $vntsEmbarqueSelect = $link_mysqli->query("
      SELECT vntsembarque.cdgembarque,
        vntsembarque.referencia,
        vntsembarque.fchembarque,
        vntssucursal.sucursal
      FROM vntsembarque,
        vntssucursal
      WHERE vntsembarque.cdgembarque = '".$_GET['cdgembarque']."' AND
        vntsembarque.cdgsucursal = vntssucursal.cdgcsucursal");

    if ($vntsEmbarqueSelect->num_rows > 0)
    { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object();

      $_SESSION['cdgembarque'] = $regVntsEmbarque->cdgembarque;
      $_SESSION['referencia'] = $regVntsEmbarque->referencia;
      $_SESSION['fchembarque'] = $regVntsEmbarque->fchembarque;
      $_SESSION['sucursal'] = $regVntsEmbarque->sucursal;


    }
  } else
  { documentoB('Es necesario acceder a los embarques desde el modulo de embarques.'); }


/*





    $link_mysqli = conectar();
    $prodDocumentoSelect = $link_mysqli->query("
      SELECT * FROM proddocumento
      WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");

    if ($prodDocumentoSelect->num_rows > 0)
    { $regProdDocumento = $prodDocumentoSelect->fetch_object();

      $_SESSION['prodDocumento_iddocumento'] = $regProdDocumento->iddocumento;
      $_SESSION['prodDocumento_fchdocumento'] = $regProdDocumento->fchdocumento;
      $_SESSION['prodDocumento_documento'] = $regProdDocumento->documento;
      $_SESSION['prodDocumento_cdgempleado'] = $regProdDocumento->cdgempleado;
      $_SESSION['prodDocumento_cdgdocumento'] = $regProdDocumento->cdgdocumento;
      $_SESSION['prodDocumento_sttdocumento'] = $regProdDocumento->sttdocumento;      

      $link_mysqli = conectar();
      $prodSubLoteSelect = $link_mysqli->query("
        SELECT * FROM prodbobina
        WHERE cdgdocumento = '".$prodDocumento_cdgdocumento."'");

      if ($prodSubLoteSelect->num_rows > 0)
      { $link_mysqli = conectar();
        $pdtoMezclaSelect = $link_mysqli->query("
          SELECT prodlote.cdgmezcla,
            pdtoproyecto.proyecto,
            pdtoimpresion.impresion,
            pdtoimpresion.corte,
            pdtomezcla.mezcla
          FROM prodlote,
            prodbobina,
            pdtoproyecto,
            pdtoimpresion,
            pdtomezcla
          WHERE (prodlote.cdglote = prodbobina.cdglote AND
            prodbobina.cdgdocumento = '".$prodDocumento_cdgdocumento."' AND
            prodbobina.sttbobina = '".$prodDocumento_sttbobina."') AND
           (prodlote.cdgmezcla = pdtomezcla.cdgmezcla AND
            pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion AND
            pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)
          GROUP BY prodlote.cdgmezcla,
            pdtoproyecto.proyecto,
            pdtoimpresion.impresion,
            pdtomezcla.mezcla");

        if ($pdtoMezclaSelect->num_rows > 0)
        { include '../../fpdf/fpdf.php';

          class PDF extends FPDF
          { function Header()
            { if ($_SESSION['usuario'] == '')
              { $_SESSION['usuario'] = 'Invitado'; }

              if (file_exists('../../img_sistema/logo.jpg')==true)
              { $this->Image('../../img_sistema/logo.jpg',10,7,0,10); }

              $this->SetY(5);
              $this->SetFont('arial','B',8);
              $this->Cell(0,4,'Usuario: '.$_SESSION['usuario'],0,0,'R');
              $this->Ln();
              $this->Cell(0,4,'Documento de transferencia de bobinas '.$_SESSION['prodDocumento_iddocumento'].' ('.$_SESSION['prodDocumento_cdgdocumento'].')',0,1,'R');
              
              if ($_SESSION['prodDocumento_sttdocumento'] == '1') 
              { $info_firma = 'Documento en transito'; }
              else
              { $link_mysqli = conectar();
				$rechEmpleadoSelect = $link_mysqli->query("
                  SELECT * FROM rechempleado
                  WHERE cdgempleado = '".$_SESSION['prodDocumento_cdgempleado']."'");
                  
                if ($rechEmpleadoSelect->num_rows > 0)
				{ $regRechEmpleado = $rechEmpleadoSelect->fetch_object();
				
				  $info_firma = 'Documento recibido y liberado por '.$regRechEmpleado->empleado; }
				else
				{ $info_firma = 'Documento recibido y liberado'; }
			  }	  			
              
              $this->Cell(0,4,$info_firma.', fecha de documento '.$_SESSION['prodDocumento_fchdocumento'],0,1,'R');
              $this->Ln(2);

              $this->SetFillColor(180,180,180);
              $this->SetFont('arial','B',8);

              $this->SetFont('arial','B',8);
              $this->Cell(24,4,'  Lote',0,0,'L',true);
              $this->Cell(40,4,'No. Lote',0,0,'L',true);
              $this->Cell(40,4,'',0,0,'L',true);
              $this->Cell(20,4,'Bobina',0,0,'L',true);
              $this->Cell(22,4,'Longitud (mts)',0,0,'L',true);
              $this->Cell(22,4,'Amplitud (mm)',0,0,'L',true);
              $this->Cell(27,4,'Peso (kgs)',0,0,'L',true);
              $this->Ln(); }
          }

          $pdf=new PDF('P','mm','letter');
          $pdf->AliasNbPages();
          $pdf->SetDisplayMode(real, continuous);
          $pdf->AddPage();

          while ($regPdtoMezcla = $pdtoMezclaSelect->fetch_object())
          { $link_mysqli = conectar();
            $prodSubLoteSelect = $link_mysqli->query("
              SELECT proglote.tarima,
                proglote.idlote,
                proglote.lote,
                prodlote.noop,
                prodbobina.bobina,
                pdtoproyecto.proyecto,
                pdtoimpresion.impresion,
                pdtomezcla.mezcla,
                pdtomezcla.idmezcla,
                prodbobina.amplitud,
                prodbobina.longitud,
                prodbobina.peso,
                prodbobina.cdgbobina
              FROM prodbobina,
                prodlote,
                proglote,
                pdtomezcla,
                pdtoimpresion,
                pdtoproyecto
              WHERE (proglote.cdglote = prodlote.cdglote
                AND prodlote.cdglote = prodbobina.cdglote)
              AND (prodlote.cdgmezcla = pdtomezcla.cdgmezcla
                AND pdtomezcla.cdgimpresion = pdtoimpresion.cdgimpresion
                AND pdtoimpresion.cdgproyecto = pdtoproyecto.cdgproyecto)              
              AND prodbobina.cdgdocumento = '".$prodDocumento_cdgdocumento."'
              AND prodlote.cdgmezcla = '".$regPdtoMezcla->cdgmezcla."'
              AND prodbobina.sttbobina = '".$prodDocumento_sttbobina."'
              ORDER BY pdtoproyecto.proyecto,
                pdtoimpresion.impresion,
                pdtomezcla.mezcla,
                prodlote.noop,
                prodbobina.bobina");

            $id_bobina = 1;
            $prodDocumento_longitud = 0;
            $prodDocumento_peso = 0;
            while ($regProdSubLote = $prodSubLoteSelect->fetch_object())
            { $pdf->SetFont('arial','',5);
              $pdf->Cell(4,4,$id_bobina,0,0,'R');
              $pdf->SetFont('arial','',8);
              $pdf->Cell(20,4,$regProdSubLote->tarima.'/'.$regProdSubLote->idlote,0,0,'L');
              $pdf->Cell(40,4,$regProdSubLote->lote,0,0,'R');
              $pdf->Cell(40,4,'',0,0,'L');
              $pdf->Cell(20,4,$regProdSubLote->noop.'-'.$regProdSubLote->bobina,0,0,'R');
              $pdf->Cell(22,4,number_format($regProdSubLote->longitud,2),0,0,'R');
              $pdf->Cell(22,4,$regProdSubLote->amplitud,0,0,'R');
              $pdf->Cell(22,4,number_format($regProdSubLote->peso,3),0,0,'R');
              $pdf->Cell(5,4,'',1,0,'R');
              $pdf->Ln();

              $prodDocumento_longitud += $regProdSubLote->longitud;
              $prodDocumento_peso += $regProdSubLote->peso;

              $id_bobina++; }

              $pdf->SetFont('arial','B',8);
              $pdf->Cell(104,4,'',0,0,'R');
              $pdf->Cell(86,4,number_format($prodDocumento_longitud,2).' metros   '.number_format($prodDocumento_longitud/$regPdtoMezcla->corte,3).' millares   '.number_format($prodDocumento_peso,3).' kilos',1,0,'R');
              $pdf->Cell(5,4,'',1,1,'R',true);

            $pdf->Cell(195,4,'Proyecto: '.$regPdtoMezcla->proyecto.'    Impresion: '.$regPdtoMezcla->impresion.'    Mezcla: '.$regPdtoMezcla->mezcla.'   ['.$prodSubLoteSelect->num_rows.'] Bobinas',0,1,'R');
            $pdf->Ln(2); }

          $prodSubLoteSelect->close;

          $pdf->Output('Documento de transferencia de bobinas '.$_SESSION['prodDocumento_iddocumento'].'.pdf', 'I');
        } else
        { documentoB('El documento que buscas contiene problemas de referencia.'); }

        $pdtoMezclaSelect->close;
      } else
      { documentoB('El documento que buscas esta vacio.'); }

      $prodSubLoteSelect->close;
    } else
    { documentoB('El documento que buscas no existe.'); }

    $prodDocumentoSelect->close;
  } else
  { documentoB('El necesario acceder a los documentos desde el modulo de documentos.'); } //*/
?>
