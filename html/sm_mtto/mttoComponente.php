<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';

  $mttoCdgCalendario = $_POST['slcCdgCalendario'];
  $mttoIdComponente = $_POST['txtIdComponente'];
  $mttoComponente = $_POST['txtComponente'];

  $answer = 'Nada aun';

  if ($_POST['btnSalvar'])
  { $link_mysqli = conectar();

    $mttoCalenarioSelectById = $link_mysqli->query("
      SELECT * FROM mttocomponente
      WHERE cdgcalendario = '".$mttoIdCalendario."' AND
            idComponente = '".$mttoIdComponente."'");

    if ($mttoCalenarioSelectById->num_rows > 0)
    { $answer = 'Registro encontrado.';

      $link_mysqli->query("
        UPDATE mttocomponente
        SET componente = '".$mttoComponente."'
        WHERE cdgcalendario = '".$mttoIdCalendario."' AND
              idcomponente = '".$mttoIdComponente."'");

      if ($link_mysqli->affected_rows > 0)
      { $answer = 'Registro actualizado.'; }
      else
      { $answer = 'El registro NO fue actualizado.'; }
    } else
    { $answer = 'Registro NO encontrado.';

      for ($cdg=1; $cdg<=9999; $cdg++)
      { $mttoCdgComponente = $mttoCdgCalendario.str_pad($cdg,4,'0',STR_PAD_LEFT);

	    $mttoCalenarioSelectByCdg = $link_mysqli->query("
	      SELECT * FROM mttocomponente
	      WHERE cdgcomponente = '".$mttoCdgComponente."'");

	    if ($mttoCalenarioSelectByCdg->num_rows > 0)
	    { /*Sigue buscando*/ }
	    else
	    { $link_mysqli->query("
    	    INSERT INTO mttocomponente
    	      (cdgcalendario, idcomponente, componente, cdgcomponente)
    	    VALUES
    	      ('".$mttoCdgCalendario."', '".$mttoIdComponente."', '".$mttoComponente."', '".$mttoCdgComponente."')");

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
  	  <form id="frmComponente" method="POST" action="mttoComponente.php">
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
          <label for="lblIdComponente"><b>id</b>Componente</label>
  	      <input type="text" id="txtIdComponente" name="txtIdComponente" value="'.$mttoIdComponente.'" required="required" /></article>
        <article id="nombre">
          <label for="lblComponente"><i>Componente</i></label>
  	      <input type="text" id="txtComponente" name="txtComponente" value="'.$mttoComponente.'" required="required" /></article>
  	    <article>
    	    <input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /></article>
      </form>
    </section>
  </body>';

  if ($answer != '')
  { echo $answer; }
?>
</html>