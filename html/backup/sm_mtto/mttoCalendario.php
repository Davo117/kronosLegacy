<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';

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