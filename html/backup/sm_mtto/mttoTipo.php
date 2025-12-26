<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body>
<?php
  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '80000';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
    
    $mttoIdTipo = $_POST['textIdTipo'];
    $mttoTipo = $_POST['textTipo'];


    if ($_POST['submitSalvar'])
    { $link_mysqli = conectar();
      $querySelectById = $link_mysqli->query("
        SELECT * FROM mttotipo
        WHERE idtipo = '".$mttoIdTipo."'");

      if ($querySelectById->num_rows > 0)
      { $answer = 'Registro encontrado.';

        $link_mysqli->query("
          UPDATE mttotipo
          SET tipo = '".$mttoTipo."'
          WHERE idtipo = '".$mttoIdTipo."' AND
                stttipo = '1'");

        if ($link_mysqli->affected_rows > 0)
        { $answer = 'Registro actualizado.'; }
        else
        { $answer = 'El registro NO fue actualizado.'; }
      } else
      { $answer = 'Registro NO encontrado.';

        for ($cdg=1; $cdg<=99; $cdg++)
        { $mttoCdgTipo = str_pad($cdg,2,'0',STR_PAD_LEFT);

          $querySelectByCdg = $link_mysqli->query("
            SELECT * FROM mttotipo
            WHERE cdgtipo = '".$mttoCdgTipo."'");

          if ($querySelectByCdg->num_rows > 0)
          {  
            // Ya existe uno con este nombre
          }
          else
          { $link_mysqli->query("
              INSERT INTO mttotipo
                (idtipo, tipo, cdgtipo)
              VALUES
                ('".$mttoIdTipo."', '".$mttoTipo."', '".$mttoCdgTipo."')");

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
    <form name="formTipo" id="formTipo" method="POST" action="mttoTipo.php">
      <section>
        <article>
          <dl>
            <dt><label for="labelIdTipo"><b>id</b>entificador</label><br>
              <input type="text" id="textIdTipo" name="textIdTipo" value="'.$mttoIdTipo.'" placeholder="MT" required="required" />
              <input type="submit" id="submitSalvar" name="submitSalvar" value="Salvar" /></dt>
            <dt><label for="labelTipo">Infraestructura</label><br>
              <input type="text" id="textTipo" name="textTipo" value="'.$mttoTipo.'" placeholder="Mantenimiento" required="required" /></dt>
          </dl>
        </article>
      </section>
    </form>';

    $link_mysqli = conectar();
    $querySelect = $link_mysqli->query("
      SELECT * FROM mttotipo
    ORDER BY stttipo DESC, 
             idtipo");

    if ($querySelect->num_rows > 0)
    { $nTipo = $querySelect->num_rows;

      while ($regQuery = $querySelect->fetch_object())
      { $id++;
        
      }
    } else
    {}

  } else
  { echo '<div align="center" style="align-text:justify"><h1>Este m&oacute;dulo esta reservado para usuarios registrados en el sistema.</h1></div>'; }
?>
  </body>
</html>