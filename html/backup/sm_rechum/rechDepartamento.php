<!DOCTYPE html>
<html>
  <head>
    <style>
 
    </style>
  </head>
  <body><br />
<?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '10020';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_POST['txt_iddepartamento']) { $rechDepartamento_iddepartamento = $_POST['txt_iddepartamento']; }
    if ($_POST['txt_departamento']) { $rechDepartamento_departamento = $_POST['txt_departamento']; }
    if ($_GET['cdgdepartamento']) { $rechDepartamento_cdgdepartamento = $_GET['cdgdepartamento']; }

    if ($_GET['cdgdepartamento'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $rechDepartamentoSelect = $link_mysqli->query("
          SELECT * FROM rechdepartamento
          WHERE cdgdepartamento = '".$rechDepartamento_cdgdepartamento."'");

        if ($rechDepartamentoSelect->num_rows > 0)
        { $regRechDepartamento = $rechDepartamentoSelect->fetch_object();

          $rechDepartamento_iddepartamento = $regRechDepartamento->iddepartamento;
          $rechDepartamento_departamento = $regRechDepartamento->departamento;
          $rechDepartamento_cdgdepartamento = $regRechDepartamento->cdgdepartamento;
          $rechDepartamento_sttdepartamento = $regRechDepartamento->sttdepartamento; }

        if ($_GET['departamento'] == 'update')
        { if (substr($sistModulo_permiso,0,2) == 'rw')
          { if ($rechDepartamento_sttdepartamento == '1')
            { $rechDepartamento_newsttdepartamento = '0'; }

            if ($rechDepartamento_sttdepartamento == '0')
            { $rechDepartamento_newsttdepartamento = '1'; }

            if ($rechDepartamento_newsttdepartamento != '')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE rechdepartamento
                SET sttdepartamento = '".$rechDepartamento_newsttdepartamento."'
                WHERE cdgdepartamento = '".$rechDepartamento_cdgdepartamento."'");

                if ($link_mysqli->affected_rows > 0)
                { $answer = 'El registro Fue actualizado en su Status.'; }
                else
                { $answer = 'El registro NO fue actualizado es su Status.'; }
            } else
            { $answer = 'El registro no puede ser actualizado.'; }
          } else
          { $answer = $msg_norewrite; }
        }

        if ($_GET['departamento'] == 'delete')
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $link_mysqli = conectar();
            $rechPuestoSelect = $link_mysqli->query("
              SELECT * FROM rechpuesto
              WHERE cdgdepartamento = '".$rechDepartamento_cdgdepartamento."'");

            if ($rechPuestoSelect->num_rows > 0)
            { $answer = 'El registro tiene informacion ligada, NO fue eliminado'; }
            else
            { $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM rechdepartamento
                WHERE cdgdepartamento = '".$rechDepartamento_cdgdepartamento."' AND
                      sttdepartamento = '0'");

              if ($link_mysqli->affected_rows > 0)
              { $answer = 'El registro Fue eliminado con exito.'; }
            }
          } else
          { $answer = 'Sin permisos especiales'; }
        }
      } else
      { $answer = 'Sin permisos de lectura'; }
    }

    if ($_POST["btn_salvar"])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $link_mysqli = conectar();
        $rechDepartamentoSelect = $link_mysqli->query("
          SELECT * FROM rechdepartamento
          WHERE iddepartamento = '".$rechDepartamento_iddepartamento."'");

        if ($rechDepartamentoSelect->num_rows > 0)
        { $regRecHDepartamento = $rechDepartamentoSelect->fetch_object();

          $link_mysqli = conectar();
          $link_mysqli->query("
          UPDATE rechdepartamento
          SET departamento = '".$rechDepartamento_departamento."'
          WHERE cdgdepartamento = '".$regRecHDepartamento->cdgdepartamento."' AND
                sttdepartamento = '1'");

          if ($link_mysqli->affected_rows > 0)
          { $answer = 'El registro fue actualizado con exito.'; }
          else
          { $answer = 'El registro NO fue actualizado.'; }
        } else
        { for ($cdgDepartamento=1;$cdgDepartamento<=1000;$cdgDepartamento++)
          { $rechDepartamento_cdgdepartamento = str_pad($cdgDepartamento,3,'0',STR_PAD_LEFT);

            if ($cdgDepartamento <= 999)
            { $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO rechdepartamento
                  (iddepartamento, departamento, cdgdepartamento)
                VALUES
                  ('".$rechDepartamento_iddepartamento."', '".$rechDepartamento_departamento."', '".$rechDepartamento_cdgdepartamento."')");

              if ($link_mysqli->affected_rows > 0)
              { $answer = 'El nuevo registro fue insertado con exito.';
                $cdgDepartamento = 1000; }
            } else
            { $answer = 'No fue posible insertar un nuevo registro, el tope ha sido alcanzado.'; }
          }
        }
      } else
      { $answer = 'Sin permisos de escritura'; }
    }

    if ($_POST['chk_vertodo'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $vertodo = 'checked';

        $link_mysqli = conectar();
        $rechDepartamentoSelect = $link_mysqli->query("
        SELECT * FROM rechdepartamento
        WHERE sttdepartamento != '9'
        ORDER BY sttdepartamento DESC,
           departamento");
      } else
      { $link_mysqli = conectar();
        $rechDepartamentoSelect = $link_mysqli->query("
        SELECT * FROM rechdepartamento
        WHERE sttdepartamento = '1'
        ORDER BY sttdepartamento DESC,
              departamento"); }
    } else
    { $link_mysqli = conectar();
      $rechDepartamentoSelect = $link_mysqli->query("
        SELECT * FROM rechdepartamento
        WHERE sttdepartamento = '1'
        ORDER BY sttdepartamento DESC,
             departamento"); }

    if ($rechDepartamentoSelect->num_rows > 0)
    { $item = 1;
      while($regRecHDepartamento = $rechDepartamentoSelect->fetch_object())
      { $rechDepartamentos_iddepartamento[$item] = $regRecHDepartamento->iddepartamento;
        $rechDepartamentos_departamento[$item] = $regRecHDepartamento->departamento;
        $rechDepartamentos_cdgdepartamento[$item] = $regRecHDepartamento->cdgdepartamento;
        $rechDepartamentos_sttdepartamento[$item] = $regRecHDepartamento->sttdepartamento;

        $item++; }

      $items = $rechDepartamentoSelect->num_rows; }

    echo '
    <section id="stylized" class=”myform”>
      <section id="formulario">
        <form id="frm_rechDepartamento" name="frm_rechDepartamento" method="POST" action="rechDepartamento.php">
          <h1>'.$sistModulo_modulo.'</h1>
          <p>'.utf8_decode('Módulo para registro de departamentos').'</p>
          
          <label for="lbl_iddepartamento">ID
          <span class="small">Identificador</spam></label>
          <input type="text" id="txt_iddepartamento" name="txt_iddepartamento" maxlenght="16" value="'.$rechDepartamento_iddepartamento.'" title="Identificador de departamento" autofocus required/>
          
          
          <label for="lbl_departamento">Departamento
          <span class="small">Nombre</spam></label></label>
          <input type="text" id="txt_departamento" name="txt_departamento" maxlenght="48" value="'.$rechDepartamento_departamento.'" title="Descripcion del departamento" required/>
          
          <button type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" />
          <div class="spacer"></div>

          <input type="checkbox" name="chk_vertodo" id="chk_vertodo" onclick="document.frm_rechDepartamento.submit()" '.$vertodo.'>
          <label id="lbl_vertodo" for="lbl_vertodo">ver todo</label>
        </form>
      </section>';

    if ($items>0)
    { echo '
      <section id="filtro">
        <table align="center">
          <thead>
            <tr align="left">
              <th>ID</th>
              <th>Departamento</th>
              <th colspan="2">Operaciones</th></tr>
          </thead>
          <tbody>';

      for ($item=1;$item<=$items;$item++)
      { echo '
            <tr align="center">
              <td align="left">'.$rechDepartamentos_iddepartamento[$item].'</td>
              <td align="left">'.$rechDepartamentos_departamento[$item].'</td>';

        if ((int)$rechDepartamentos_sttdepartamento[$item] > 0)
        { echo '
              <td><a href="rechDepartamento.php?cdgdepartamento='.$rechDepartamentos_cdgdepartamento[$item].'">'.$png_search.'</a></td>
              <td><a href="rechDepartamento.php?cdgdepartamento='.$rechDepartamentos_cdgdepartamento[$item].'&departamento=update">'.$png_power_blue.'</a></td></tr>'; 
        } else
        { echo '
              <td><a href="rechDepartamento.php?cdgdepartamento='.$rechDepartamentos_cdgdepartamento[$item].'&departamento=delete">'.$png_recycle_bin.'</a></td>
              <td><a href="rechDepartamento.php?cdgdepartamento='.$rechDepartamentos_cdgdepartamento[$item].'&departamento=update">'.$png_power_black.'</a></td></tr>'; }
      }
    echo '
          </tbody>
          <tfoot>
            <tr align="right"><th colspan="4"><label id="lbl_numregistros" for="lbl_numregistros">'.$items.' registro(s)</label></th></tr>
          </tfoot>
        </table>
      </section>'; }

    echo '
    </section>';

    if ($answer != '')
    { echo '

    <script type="text/javascript"> alert("'.$answer.'"); </script>'; }
  } else
  { echo '
    <div id="answer" align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
  </body>
</html>