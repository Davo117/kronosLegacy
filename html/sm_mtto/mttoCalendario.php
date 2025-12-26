<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';
/*
 $link_mysqli = conectar();
  $queryDiseno = $link_mysqli->query("
    SELECT * FROM pdtodiseno
  ORDER BY cdgdiseno");

  if ($queryDiseno->num_rows > 0)
  { echo '<h1>Here we go.</h1>'; 
    
    while ($regDiseno = $queryDiseno->fetch_object())
    { $nDiseno++;

      $alpaso = $regDiseno->alpaso;

      $queryImpresion = $link_mysqli->query("
        SELECT * FROM pdtoimpresion 
         WHERE cdgdiseno = '".$regDiseno->cdgdiseno."'
      ORDER BY cdgimpresion");

      if ($queryImpresion->num_rows > 0)
      { while ($regImpresion = $queryImpresion->fetch_object())
        { $nImpresion++; 

          $queryLote = $link_mysqli->query("
            SELECT * FROM prodlote
             WHERE sttlote = '9' AND
                   cdgproducto = '".$regImpresion->cdgimpresion."'");

          if ($alpaso == 0) { $alpaso = $regImpresion->alpaso; }

          if ($queryLote->num_rows > 0)
          { while ($regLote = $queryLote->fetch_object())
            { $nLote++; 

              $querySelectLoteOpe = $link_mysqli->query("
                SELECT * FROM prodloteope
                 WHERE cdglote = '".$regLote->cdglote."' AND
                       cdgoperacion = '30001'");

              if ($querySelectLoteOpe->num_rows > 0)
              { $regLoteOpe = $querySelectLoteOpe->fetch_object();

                for ($nbobina=1; $nbobina<=$alpaso; $nbobina++)
                { $cdgbobina = substr($regLote->cdglote,0,8).$nbobina.'000';

                  $link_mysqli->query("
                    INSERT INTO prodbobinaope
                      (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, cdgdefecto, fchoperacion, fchmovimiento)
                    VALUES
                      ('".$cdgbobina."', '30001', '".$regLoteOpe->cdgempleado."', '".$regLoteOpe->cdgmaquina."', '".$regLote->longitud."', '".($regLote->peso/$alpaso)."', 'UP', '".$regLote->fchmovimiento."', NOW())");

                  if ($link_mysqli->affected_rows > 0)
                  { $nInsertB++; }
                }
              } else
              { /*
                $link_mysqli->query("
                  INSERT INTO prodloteope
                    (cdglote, cdgoperacion, cdgempleado, cdgmaquina, longin, peso, cdgdefecto, fchoperacion, fchmovimiento)
                  VALUES
                    ('".$regLote->cdglote."', '30001', '9999', '99999', '".$regLote->longitud."', '".$regLote->peso."', 'OP', '".$regLote->fchmovimiento."', NOW())");

                if ($link_mysqli->affected_rows > 0)
                { $nInsert++; 
                  

                  for ($nbobina=1; $nbobina<=$alpaso; $nbobina++)
                  { $cdgbobina = substr($regLote->cdglote,0,8).$nbobina.'000';

                    $link_mysqli->query("
                      INSERT INTO prodbobinaope
                        (cdgbobina, cdgoperacion, cdgempleado, cdgmaquina, longitud, peso, cdgdefecto, fchoperacion, fchmovimiento)
                      VALUES
                        ('".$cdgbobina."', '30001', '9999', '99999', '".$regLote->longitud."', '".($regLote->peso/$alpaso)."', 'OP', '".$regLote->fchmovimiento."', NOW())");

                    if ($link_mysqli->affected_rows > 0)
                    { $nInsertA++; }
                  }
                } 
              }
            }
          }

        }
      }
    }

    echo $nDiseno.' '.utf8_decode(' Diseños').'<br>';
    echo $nImpresion.' '.utf8_decode(' Impresiones').'<br>';
    echo $nLote.' '.utf8_decode(' Lotes refilados').'<br>';
    echo $nInsert.' '.utf8_decode(' Lotes refilados (Insert)').'<br>';
    echo $nInsertA.' '.utf8_decode(' Bobinas refiladas (InsertA)').'<br>';
    echo $nInsertB.' '.utf8_decode(' Bobinas refiladas (InsertB)').'<br>';
  } //*/
   

  $mttoIdCalendario = $_POST['txtIdCalendario'];
  $mttoCalendario = $_POST['txtCalendario'];

  $answer = 'Nada aun';

  if ($_POST['btnSalvar'])
  { $link_mysqli = conectar();

    $mttoCalenarioSelectById = $link_mysqli->query("
      SELECT * FROM mttocalendario
      WHERE idcalendario = '".$mttoIdCalendario."'");

    if ($mttoCalenarioSelectById->num_rows > 0)
    { $answer = 'Registro encontrado.';

      $link_mysqli->query("
        UPDATE mttocalendario
        SET calendario = '".$mttoCalendario."'
        WHERE idcalendario = '".$mttoIdCalendario."' AND
              sttcalendario = '1'");

      if ($link_mysqli->affected_rows > 0)
      { $answer = 'Registro actualizado.'; }
      else
      { $answer = 'El registro NO fue actualizado.'; }
    } else
    { $answer = 'Registro NO encontrado.';

      for ($cdg=1; $cdg<=99; $cdg++)
      { $mttoCdgCalendario = str_pad($cdg,2,'0',STR_PAD_LEFT);

  	    $mttoCalenarioSelectByCdg = $link_mysqli->query("
  	      SELECT * FROM mttocalendario
  	      WHERE cdgcalendario = '".$mttoCdgCalendario."'");

  	    if ($mttoCalenarioSelectByCdg->num_rows > 0)
  	    { /*Sigue buscando*/ }
  	    else
  	    { $link_mysqli->query("
      	    INSERT INTO mttocalendario
      	      (idcalendario, calendario, cdgcalendario)
      	    VALUES
      	      ('".$mttoIdCalendario."', '".$mttoCalendario."', '".$mttoCdgCalendario."')");

  	      if ($link_mysqli->affected_rows > 0)
  	      { $answer = 'Registro insertado.'; }
  	      else
  	      { $answer = 'El registro NO fue insertado.'; }	  

  	      break;
        }
      }
    }
  }

  echo '
  <body>
  	<section>
  	  <form id="frmCalendario" method="POST" action="mttoCalendario.php">
        <article id="id">
          <label for="lblIdCalendario"><b>id</b>Calendario</label>
  	      <input type="text" id="txtIdCalendario" name="txtIdCalendario" value="'.$mttoIdCalendario.'" required="required" /></article>
        <article id="nombre">
          <label for="lblCalendario"><i>Calendario</i></label>
  	      <input type="text" id="txtCalendario" name="txtCalendario" value="'.$mttoCalendario.'" required="required" /></article>
  	    <article>
    	    <input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /></article>
      </form>
    </section>
  </body>';

  if ($answer != '')
  { echo $answer; }
?>
</html>