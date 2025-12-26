<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';

  $mttoCdgCalendario = $_POST['slcCdgCalendario'];
  $mttoIdCaracteristica = $_POST['txtIdCaracteristica'];
  $mttoCaracteristica = $_POST['txtCaracteristica'];

  $answer = 'Nada aun';

  if ($_POST['btnSalvar'])
  { $link_mysqli = conectar();

    $mttoCalenarioSelectById = $link_mysqli->query("
      SELECT * FROM mttocaracteristica
      WHERE cdgcalendario = '".$mttoIdCalendario."' AND
            idcaracteristica = '".$mttoIdCaracteristica."'");

    if ($mttoCalenarioSelectById->num_rows > 0)
    { $answer = 'Registro encontrado.';

      $link_mysqli->query("
        UPDATE mttocaracteristica
        SET caracteristica = '".$mttoCaracteristica."'
        WHERE cdgcalendario = '".$mttoIdCalendario."' AND
              idcaracteristica = '".$mttoIdCaracteristica."'");

      if ($link_mysqli->affected_rows > 0)
      { $answer = 'Registro actualizado.'; }
      else
      { $answer = 'El registro NO fue actualizado.'; }
    } else
    { $answer = 'Registro NO encontrado.';

      for ($cdg=1; $cdg<=999; $cdg++)
      { $mttoCdgCaracteristica = $mttoCdgCalendario.str_pad($cdg,3,'0',STR_PAD_LEFT);

	    $mttoCalenarioSelectByCdg = $link_mysqli->query("
	      SELECT * FROM mttocaracteristica
	      WHERE cdgcaracteristica = '".$mttoCdgCaracteristica."'");

	    if ($mttoCalenarioSelectByCdg->num_rows > 0)
	    { /*Sigue buscando*/ }
	    else
	    { $link_mysqli->query("
    	    INSERT INTO mttocaracteristica
    	      (cdgcalendario, idcaracteristica, caracteristica, cdgcaracteristica)
    	    VALUES
    	      ('".$mttoCdgCalendario."', '".$mttoIdCaracteristica."', '".$mttoCaracteristica."', '".$mttoCdgCaracteristica."')");

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
  	  <form id="frmCaracteristica" method="POST" action="mttoCaracteristica.php">
        <article id="id">
          <label for="lblCdgCalendario"><b>Calendario</b></label>
          <select id="slcCdgCalendario" name="slcCdgCalendario">
            <option value="00">Todos</opcion>';

  $link_mysqli = conectar();
  $mttoCalenarioSelect = $link_mysqli->query("
  	SELECT calendario,
  	       cdgcalendario
  	FROM mttocalendario
  	WHERE sttcalendario >= '1'
  	ORDER BY idcalendario"); 

  if ($mttoCalenarioSelect->num_rows > 0)
  { while ($regCalendario = $mttoCalenarioSelect->fetch_object())
    { echo '<option value="'.$regCalendario->cdgcalendario.'"';

      if ($regCalendario->cdgcalendario == $mttoCdgCalendario)
      { echo ' selected="selected">'; }
      else
      { echo '>'; }      

      echo $regCalendario->calendario.'</option>'; //*/
    }
  } 

  echo '
          </select>
        </article>
        <article id="id">
          <label for="lblIdCaracteristica"><b>id</b>Caracteristica</label>
  	      <input type="text" id="txtIdCaracteristica" name="txtIdCaracteristica" value="'.$mttoIdCaracteristica.'" required="required" /></article>
        <article id="nombre">
          <label for="lblCaracteristica"><i>Caracteristica</i></label>
  	      <input type="text" id="txtCaracteristica" name="txtCaracteristica" value="'.$mttoCaracteristica.'" required="required" /></article>
  	    <article>
    	    <input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /></article>
      </form>
    </section>
  </body>';

  if ($answer != '')
  { echo $answer; }
?>
</html>