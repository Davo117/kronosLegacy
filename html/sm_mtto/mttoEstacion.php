<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';

  $mttoCdgCalendario = $_POST['slcCdgCalendario'];
  $mttoIdEstacion = $_POST['txtIdEstacion'];
  $mttoEstacion = $_POST['txtEstacion'];

  $answer = 'Nada aun';

  if ($_POST['btnSalvar'])
  { $link_mysqli = conectar();

    $mttoCalenarioSelectById = $link_mysqli->query("
      SELECT * FROM mttoestacion
      WHERE cdgcalendario = '".$mttoIdCalendario."' AND
            idEstacion = '".$mttoIdEstacion."'");

    if ($mttoCalenarioSelectById->num_rows > 0)
    { $answer = 'Registro encontrado.';

      $link_mysqli->query("
        UPDATE mttoestacion
        SET estacion = '".$mttoEstacion."'
        WHERE cdgcalendario = '".$mttoIdCalendario."' AND
              idestacion = '".$mttoIdEstacion."'");

      if ($link_mysqli->affected_rows > 0)
      { $answer = 'Registro actualizado.'; }
      else
      { $answer = 'El registro NO fue actualizado.'; }
    } else
    { $answer = 'Registro NO encontrado.';

      for ($cdg=1; $cdg<=9999; $cdg++)
      { $mttoCdgEstacion = $mttoCdgCalendario.str_pad($cdg,4,'0',STR_PAD_LEFT);

	    $mttoCalenarioSelectByCdg = $link_mysqli->query("
	      SELECT * FROM mttoestacion
	      WHERE cdgestacion = '".$mttoCdgEstacion."'");

	    if ($mttoCalenarioSelectByCdg->num_rows > 0)
	    { /*Sigue buscando*/ }
	    else
	    { $link_mysqli->query("
    	    INSERT INTO mttoestacion
    	      (cdgcalendario, idestacion, estacion, cdgestacion)
    	    VALUES
    	      ('".$mttoCdgCalendario."', '".$mttoIdEstacion."', '".$mttoEstacion."', '".$mttoCdgEstacion."')");

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
  	  <form id="frmEstacion" method="POST" action="mttoEstacion.php">
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
          <label for="lblIdEstacion"><b>id</b>Estacion</label>
  	      <input type="text" id="txtIdEstacion" name="txtIdEstacion" value="'.$mttoIdEstacion.'" required="required" /></article>
        <article id="nombre">
          <label for="lblEstacion"><i>Estacion</i></label>
  	      <input type="text" id="txtEstacion" name="txtEstacion" value="'.$mttoEstacion.'" required="required" /></article>
  	    <article>
    	    <input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /></article>
      </form>
    </section>
  </body>';

  if ($answer != '')
  { echo $answer; }
?>
</html>