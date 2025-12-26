<html>
<head>
</head>
<body>
  <?php
  error_reporting(0);
  include("../../Database/SQLConnection.php");


  header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=CO-FR04 Plan de comprasss.xls"); 
   
    $SPR_1=sqlsrv_query($SQLconn,"SELECT  d.CIDDOCUMENTO as idReq,d.crazonsocial as proveedor ,d.CFOLIO as Req ,datediff(day,d.cfecha,getdate()) as diasTranscurridos,CONVERT(date,d.CFECHA) as fechaSolicitud,d.CFECHAEXTRA as fechaProgramada,CIMPORTEEXTRA1 as numeroReprogramacion,CTEXTOEXTRA3 as solicitante,COBSERVACIONES as observaciones  from admdocumentos d where d.CIDDOCUMENTOde=38 and CIDCONCEPTODOCUMENTO=3000");

 


  echo '
  <table border="1">
  <tr style="background-color:#94C2F1;height:80px;">
  <th style="text-align:center">Req.</th>
  <th style="text-align:center">No.Orden compra</th>        
  <th style="text-align:center">Proveedor</th>
  <th style="text-align:center">'.utf8_decode('Descripción de materiales').'</th>
  <th style="text-align:center">Fecha de solicitud</th>
  <th style="text-align:center">'.utf8_decode('Días transcurridos').'</th>
  <th style="text-align:center">Fecha de entrega programada</th>
  <th style="text-align:center">'.utf8_decode('No. Reprogramación').'</th>
  <th style="text-align:center">'.utf8_decode('Fecha de recepción').'</th>
  <th style="text-align:center">'.utf8_decode('Días transcurridos ala entrega').'</th>
  <th style="text-align:center">Estatus</th>
  <th style="text-align:center">Factura (as)</th>
  <th style="text-align:center">Observaciones</th>
  <th style="text-align:center">Solicitante</th>
  </tr>';
  while($row= sqlsrv_fetch_array($SPR_1, SQLSRV_FETCH_ASSOC))
   {
    $estatus=0;
     $SQrd=sqlsrv_query($SQLconn,"SELECT CFOLIO as ordenCompra,CIDDOCUMENTO as idCompra,CCANCELADO from admdocumentos where CIDDOCUMENTOORIGEN=".$row['idReq']." and CIDDOCUMENTOde=17");
     $rowC=sqlsrv_fetch_array($SQrd, SQLSRV_FETCH_ASSOC);

//Valido si el requerimiento o la orden de compra están vacios,esto para el estatus
     $queryValidacion=sqlsrv_query($SQLconn,"SELECT CIDMOVIMIENTO,CIDPRODUCTO,CIDMOVTOORIGEN,CIDDOCUMENTO  from admmovimientos where CIDDOCUMENTO=".$rowC['idCompra']." or CIDDOCUMENTO=".$row['idReq']."");
     while($rowVal=sqlsrv_fetch_array($queryValidacion,SQLSRV_FETCH_ASSOC))
     {
      //Si el estatus es menor a 1 y encunetra en la consulta un dato que tenga el id de la requisición,quiere decir que si existe la requisición
      if($rowVal['CIDDOCUMENTO']==$row['idReq'] and $estatus<1)
      {
        $estatus=1;
      }
      // Valida si existe la compra en alguno de los registros de la consulta y coloca el estatus 2
      if($rowVal['CIDDOCUMENTO']==$rowC['idCompra'] and $estatus<2)
      {
        $estatus=2;
        $SQLOrigen=sqlsrv_query($SQLconn,"SELECT CIDMOVIMIENTO,CIDPRODUCTO,CIDMOVTOORIGEN,CIDDOCUMENTO from admmovimientos where CIDMOVTOORIGEN=".$rowVal['CIDMOVIMIENTO']."");
        $arrow=sqlsrv_fetch_array($SQLOrigen, SQLSRV_FETCH_ASSOC);
        $compra=$arrow['CIDDOCUMENTO'];
        if(!empty($compra))
        {
          $estatus=3;
          $SQLCompraC=sqlsrv_query($SQLconn,"SELECT CRAZONSOCIAL,CFOLIO as factura,CONVERT(date,cfecha) as fechaRecepcion,CCANCELADO,COBSERVACIONES as observaciones from admdocumentos where ciddocumento=".$compra."");
          $rowFactura=sqlsrv_fetch_array($SQLCompraC,SQLSRV_FETCH_ASSOC);
          $factura=$rowFactura['factura'];
          $fechaRecepcion=$rowFactura['fechaRecepcion'];

        }
      }
     }
      if(empty($fechaRecepcion))
          {
            $fechaRecepcion="";
          }

     

     ?>
    <tr>
      <td style="text-align:center"><?php echo $row['Req'];?></td>
      <td style="text-align:center"><?php echo $rowC['ordenCompra'];?></td>
      <td><?php echo $row['proveedor'];?></td>
      <td>
      <?php
      $SQL=sqlsrv_query($SQLconn,"SELECT m.CIDPRODUCTO,m.COBSERVAMOV as CNOMBREPRODUCTO from admmovimientos m
  INNER JOIN admProductos p on m.CIDPRODUCTO=p.CIDPRODUCTO WHERE m.CIDDOCUMENTO=".$rowC['idCompra']."");
      while($raw= sqlsrv_fetch_array($SQL, SQLSRV_FETCH_ASSOC))
      {
        if(!empty($raw['CNOMBREPRODUCTO']))
        {
        ?>

        <b><?php echo $raw['CNOMBREPRODUCTO'];?></b>
        <?php
      }
      }
      ?>
      </td>
      <td style="text-align:center"><?php echo $row['fechaSolicitud']->format('d/m/Y');?></td>
      <td style="text-align:center"><?php echo $row['diasTranscurridos'] ?></td>
      <td style="text-align:center"><?php echo $row['fechaProgramada']->format('d/m/Y');?></td>
      <td style="text-align:center;"><?php echo $row['numeroReprogramacion'];?></td>
      <td style="text-align:center;"><?php 
     
     if($fechaRecepcion!="")
     {
       $fechin=$fechaRecepcion->format('d/m/Y');
      if($fechin!='30/12/1899' and !empty($fechin))
      {
         $datetime1 = new DateTime($row['fechaProgramada']->format('Y-m-d'));
      $datetime2 = new DateTime($fechaRecepcion->format('Y/m/d'));
      $interval = $datetime1->diff($datetime2);
     
         echo $fechaRecepcion->format('d/m/Y');

      }
     }
     
     ?></td>
      <td style="text-align:center;"><?php if(!empty($interval)){ echo $interval->format('%R%a');}?></td>
      <td style="  <?php
     /*   if(!empty($row['CAFECTADO']))
        {
          echo "background-color:yellow";
        }
       if(!empty($row['CIMPRESO']))
        {
          echo "Impreso";
        }
         if(!empty($row['CCANCELADO']))
        {
          echo "Cancelado";
        }
         if(!empty($row['CDEVUELTO']))
        {
          echo "Devuelto";
        }
        ?>">
        <?php
        if(!empty($row['CAFECTADO']))
        {
          echo "Programado";
        }
       if(!empty($row['CIMPRESO']))
        {
          echo "Impreso";
        }
         if(!empty($row['CCANCELADO']))
        {
          echo "Cancelado";
        }
         if(!empty($row['CDEVUELTO']))
        {
          echo "Devuelto";
        }*/
         if($estatus==3)
    {
         echo "background-color:#49D62C";
    }
    else if($estatus==1)
    {
        echo "background-color:yellow";
    }
    else if(!empty($row['CCANCELADO']) or !empty($rowFactura['CCANCELADO']))
        {
           echo "background-color:white";
        }
        ?>">
        <?php
              if($estatus==3)
    {
        echo "Recibido";
    }
    else if($estatus==2)
    {
        echo "Programado";
    }
    else if(!empty($row['CCANCELADO']) or !empty($rowFactura['CCANCELADO']))
        {
          echo "Cancelado";
        }
        ?>
      </td>
      <td><?php 
           $SQLFac=sqlsrv_query($SQLconn,"SELECT CRAZONSOCIAL,CFOLIO as factura,CONVERT(date,cfecha) as fechaRecepcion,CCANCELADO,COBSERVACIONES as observaciones from admdocumentos where ciddocumento=".$compra."");
          if($SQLFac)
          {
            while($rowFact=sqlsrv_fetch_array($SQLFac,SQLSRV_FETCH_ASSOC))
          {
            echo $rowFact['factura']."<br>";
          }
          }?></td>
      <td><?php if(!empty($rowFactura['observaciones'])){ echo $rowFactura['observaciones'];}?></td>
      <td><?php echo $row['solicitante'];?></td>
      <td></td>

    </tr>

    <?php

   
  }

  echo '</table>';