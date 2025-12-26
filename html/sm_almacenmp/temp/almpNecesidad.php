<!DOCTYPE html>
<html>
  <head>
    <meta name="descripcion" content="Necesidades de materia prima">
    <meta charset="UTF-8">
  </head>
  <body><?php
  include '../datos/mysql.php';
  
  $link_mysqli = conectar();
  $querySelect = $link_mysqli->query("
    SELECT pdtodiseno.diseno,
           pdtoimpresion.impresion,
           vntsoclote.cdgproducto,
           SUM(vntsoclote.cantidad-vntsoclote.surtido) AS millares
    FROM pdtodiseno,
         pdtoimpresion,
         vntsoclote
    WHERE pdtodiseno.sttdiseno = '1' AND
         (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          pdtoimpresion.sttimpresion = '1') AND
         (pdtoimpresion.cdgimpresion = vntsoclote.cdgproducto AND
          vntsoclote.sttlote = '1')
    GROUP BY vntsoclote.cdgproducto");
  
  if ($querySelect->num_rows > 0)
  { $Productos = $querySelect->num_rows;
  
    $noProducto = 0;
    while ($regQuery = $querySelect->fetch_object())
    { $noProducto++;
      
      $almpDiseno[$noProducto] = $regQuery->diseno;
      $almpProducto[$noProducto] = $regQuery->impresion;
      $almpMillares[$noProducto] = $regQuery->millares;
      $almpCdgProducto[$noProducto] = $regQuery->cdgproducto;


      // Inventario en proceso
      $link_mysqli = conectar();
      $query2Select = $link_mysqli->query("
    SELECT ((SUM(prodlote.longitud)/pdtodiseno.alto)*(pdtodiseno.alpaso)) AS millar
      FROM prodlote,
           pdtodiseno,
           pdtoimpresion
     WHERE prodlote.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1') AND
           prodlote.cdgproducto = '".$almpCdgProducto[$noProducto]."' AND
           prodlote.sttlote != '9'");

      if ($query2Select->num_rows > 0)
      { $regQuery2 = $query2Select->fetch_object();

        $almpMillares[$noProducto] = $almpMillares[$noProducto]-$regQuery2->millar; }

      $link_mysqli = conectar();
      $query2Select = $link_mysqli->query("
    SELECT (SUM(prodbobina.longitud)/pdtodiseno.alto) AS millar
      FROM prodbobina,
           pdtoimpresion,
           pdtodiseno
     WHERE prodbobina.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1') AND
           prodbobina.cdgproducto = '".$almpCdgProducto[$noProducto]."' AND
           prodbobina.sttlote BETWEEN '1' AND '8'");

      if ($query2Select->num_rows > 0)
      { $regQuery2 = $query2Select->fetch_object();

        $almpMillares[$noProducto] = $almpMillares[$noProducto]-$regQuery2->millar; }        

      $link_mysqli = conectar();
      $query2Select = $link_mysqli->query("
    SELECT (SUM(prodrollo.longitud)/pdtodiseno.alto) AS millar
      FROM prodrollo,
           pdtodiseno,
           pdtoimpresion
     WHERE prodrollo.cdgproducto = pdtoimpresion.cdgimpresion AND
          (pdtoimpresion.cdgdiseno = pdtodiseno.cdgdiseno AND
           pdtodiseno.sttdiseno = '1' AND
           pdtoimpresion.sttimpresion = '1')  
           prodrollo.cdgproducto = '".$almpCdgProducto[$noProducto]."' AND
          (prodrollo.sttlote = '1' OR prodrollo.sttlote BETWEEN '6' AND '8')");

      if ($query2Select->num_rows > 0)
      { $regQuery2 = $query2Select->fetch_object();

        $almpMillares[$noProducto] = $almpMillares[$noProducto]-$regQuery2->millar; }            
      //.................................
      
      $query9Select = $link_mysqli->query("
        SELECT pdtoimpresiontnt.cdgimpresion,
               pdtopantone.pantone,
               pdtoimpresiontnt.cdgtinta,
               pdtoimpresiontnt.consumo
          FROM pdtoimpresiontnt,
               pdtopantone
         WHERE pdtoimpresiontnt.cdgtinta = pdtopantone.idpantone AND
               pdtoimpresiontnt.cdgimpresion = '".$almpCdgProducto[$noProducto]."'
      ORDER BY pdtoimpresiontnt.notinta");
      
      if ($query9Select->num_rows > 0)
      { $Tintas[$noProducto] = $query9Select->num_rows;
        
        $noTinta = 0;
        while ($regQuery9 = $query9Select->fetch_object())
        { $noTinta++;
          
          $almpCdgTinta[$noProducto][$noTinta] = $regQuery9->cdgtinta;
          $almpTinta[$noProducto][$noTinta] = $regQuery9->pantone;
          $almpConsumo[$noProducto][$noTinta] = $regQuery9->consumo; }
      }
    }
  }
  
  $querySelect = $link_mysqli->query("
   SELECT pdtoimpresiontnt.cdgtinta,
          pdtopantone.pantone
     FROM pdtodiseno,
          pdtoimpresion,
          pdtoimpresiontnt,
          pdtopantone,
          vntsoclote
    WHERE pdtodiseno.sttdiseno = '1' AND
         (pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          pdtoimpresion.sttimpresion = '1' AND
          pdtoimpresion.cdgimpresion = pdtoimpresiontnt.cdgimpresion AND
          pdtoimpresiontnt.cdgtinta = pdtopantone.idpantone) AND
         (pdtoimpresion.cdgimpresion = vntsoclote.cdgproducto AND
          vntsoclote.sttlote = '1')
 GROUP BY pdtoimpresiontnt.cdgtinta,
          pdtopantone.pantone
 ORDER BY pdtopantone.pagina,
          pdtopantone.pantone");
  
  if ($querySelect->num_rows > 0)
  { $Pantones = $querySelect->num_rows;
  
    $noPantone = 0;
    while ($regQuery = $querySelect->fetch_object())
    { $noPantone++;
      
      $almpPantone[$noPantone] = $regQuery->pantone;
      $almpIdPantone[$noPantone] = $regQuery->cdgtinta; }
  }
  
  
  echo '
    <section>';
  for ($noProducto = 1; $noProducto <= $Productos; $noProducto++)
  { if ($almpMillares[$noProducto] > 0)
    { echo '
      <article>Diseño: '.$almpDiseno[$noProducto].'<br>
    Producto: '.$almpProducto[$noProducto].'<br>
    Necesidad: '.number_format($almpMillares[$noProducto],3,'.','');
    
      if ($Tintas[$noProducto] > 0)
      { echo '<dl>';
        
        for ($noTinta = 1; $noTinta <= $Tintas[$noProducto]; $noTinta++)
        { $almpNecedidades[$almpCdgTinta[$noProducto][$noTinta]] += ($almpMillares[$noProducto]*$almpConsumo[$noProducto][$noTinta]);
        
          echo '<dt>
                  <label for="lbl_pantonecolor" style="background-color:#'.$almpCdgTinta[$noProducto][$noTinta].'">&nbsp&nbsp&nbsp&nbsp</label>
                  <label for="lbl_necesidad">'.($almpMillares[$noProducto]*$almpConsumo[$noProducto][$noTinta]).'</label></dt>';
        }
        
        echo '</dl>';
      }
      
      echo '
      </article>';
    }
  }
  
  echo '
    </section>';
  
  for ($noPantone = 1; $noPantone <= $Pantones; $noPantone++)
  { echo $almpPantone[$noPantone].'<br>
      <label for="lbl_pantonecolor" style="background-color:#'.$almpIdPantone[$noPantone].'">&nbsp&nbsp&nbsp&nbsp</label>
      <label for="lbl_necesidad">'.$almpNecedidades[$almpIdPantone[$noPantone]].'</label><br>';
  }//*/
?>
  </body>
</html>