<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
<?php
  include '../datos/mysql.php';

  $mttoCdgCalendario = $_POST['slcCdgCalendario'];
  $mttoCdgComponente = $_POST['slcCdgComponente'];
  $mttoCdgCaracteristica = $_POST['slcCdgCaracteristica'];
  $mttoDescripcion = $_POST['txtDescripcion'];

  $answer = 'Nada aun';

  if ($_POST['btnSalvar'])
  { /*$link_mysqli = conectar();

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
	    { //Sigue buscando
	    }
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
    } //*/
  }

  echo '
  <body>
  	<section>
  	  <form id="frmEstacionCarac" name="frmEstacionCarac" method="POST" action="mttoEstacionCarac.php">
        <article id="id">
          <label for="lblCdgCalendario"><b>Calendario</b></label>
          <select id="slcCdgCalendario" name="slcCdgCalendario" onchange="document.frmEstacionCarac.submit()">
            <option value="">Elije un Calendario</opcion>';

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
          <label for="lblEstación">Estación</label>
         <select id="slcCdgEstacion" name="slcCdgEstacion">
            <option value="">Elije una estación</opcion>';

  $link_mysqli = conectar();
  $mttoEstacionSelect = $link_mysqli->query("
  	SELECT estacion,
  	       cdgestacion
  	FROM mttoestacion
  	WHERE cdgcalendario = '".$mttoCdgCalendario."'"); 

  if ($mttoEstacionSelect->num_rows > 0)
  { while ($regEstacion = $mttoEstacionSelect->fetch_object())
    { echo '<option value="'.$regEstacion->cdgestacion.'"';

      if ($regEstacion->cdgestacion == $mttoCdgEstacion)
      { echo ' selected="selected">'; }
      else
      { echo '>'; }      

      echo $regEstacion->estacion.'</option>'; //*/
    }
  } 

  echo '
          </select>
        </article>
        <article id="id">
          <label for="lblCaracteristica">Caracteristica</label>
         <select id="slcCdgCaracteristica" name="slcCdgCaracteristica">
            <option value="">Elije una caracteristica</opcion>';

  $link_mysqli = conectar();
  $mttoCaracteristicaSelect = $link_mysqli->query("
  	SELECT caracteristica,
  	       cdgcaracteristica
  	FROM mttocaracteristica
  	WHERE cdgcalendario = '".$mttoCdgCalendario."' OR cdgcalendario = '00'"); 

  if ($mttoCaracteristicaSelect->num_rows > 0)
  { while ($regCaracteristica = $mttoCaracteristicaSelect->fetch_object())
    { echo '<option value="'.$regCaracteristica->cdgcaracteristica.'"';

      if ($regCaracteristica->cdgcaracteristica == $mttoCdgCaracteristica)
      { echo ' selected="selected">'; }
      else
      { echo '>'; }      

      echo $regCaracteristica->caracteristica.'</option>'; //*/
    }
  } 

  echo '
          </select>
        </article>
        <article id="nombre">
          <label for="lblDescripcion"><i>Descipción</i></label>
  	      <input type="text" id="txtDescripcion" name="txtDescripcion" value="'.$mttoComponente.'" required="required" /></article>
  	    <article>
    	    <input type="submit" id="btnSalvar" name="btnSalvar" value="Salvar" /></article>
      </form>
    </section>
  </body>';

  if ($answer != '')
  { echo $answer; }
?>
</html>