<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';
  
  $sistModulo_cdgmodulo = '10010';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);    

    $rechEmpleado_idempleado = $_POST['txt_idempleado'];
    $rechEmpleado_empleado = $_POST['txt_empleado'];  

    if ($_GET['cdgempleado']) 
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $link_mysqli = conectar();
        $rechEmpleadoSelect = $link_mysqli->query("
          SELECT * FROM rechempleado 
          WHERE cdgempleado = '".$_GET['cdgempleado']."'");

        if ($rechEmpleadoSelect->num_rows > 0) 
        { $regRecHEmpleado = $rechEmpleadoSelect->fetch_object();

          $rechEmpleado_idempleado = $regRecHEmpleado->idempleado;
          $rechEmpleado_empleado = $regRecHEmpleado->empleado;
          $rechEmpleado_cdgempleado = $regRecHEmpleado->cdgempleado;
          $rechEmpleado_sttempleado = $regRecHEmpleado->sttempleado; }

        if ($_GET['empleado'] == 'update') 
        { if (substr($sistModulo_permiso,0,2) == 'rw')
          { if ($rechEmpleado_sttempleado == '1') 
            { $rechEmpleado_newsttempleado = '0'; }
          
            if ($rechEmpleado_sttempleado == '0') 
            { $rechEmpleado_newsttempleado = '1'; }

            if ($rechEmpleado_newsttempleado != '')
            { $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE rechempleado
                SET sttempleado = '".$rechEmpleado_newsttempleado."'
                WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

                if ($link_mysqli->affected_rows > 0) 
                { $msg_alert = 'El empleado \"'.$rechEmpleado_empleado.'\" fue actualizado en su status.';
                  if ($rechEmpleado_newsttempleado == '1')
                  { $link_mysqli = conectar();
                    $link_mysqli->query("
                      UPDATE rechempleado
                      SET acceso = MD5('pwd')
                      WHERE cdgempleado = '".$rechEmpleado_cdgempleado."' AND
                       (idusuario != '' AND acceso = '')"); 

                    $msg_alert .= "Restore"; }
                }
                else 
                { $msg_alert = 'El empleado \"'.$rechEmpleado_empleado.'\" NO fue afectado.'; } 
            }      
          } else
          { $msg_alert = $msg_norewrite; }
        }        

        if ($_GET['empleado'] == 'delete') 
        { if (substr($sistModulo_permiso,0,3) == 'rwx')
          { $link_mysqli = conectar();
            $prodLoteOpe = $link_mysqli->query("
              SELECT * FROM prodloteope
              WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'");

            if ($prodLoteOpe->num_rows > 0) 
            { $msg_alert = 'El empleado \"'.$rechEmpleado_empleado.'\" tiene informacion ligada en produccion, NO fue eliminado'; }
            else 
            { $link_mysqli = conectar();
              $link_mysqli->query("
                DELETE FROM rechempleado
                WHERE cdgempleado = '".$rechEmpleado_cdgempleado."'
                AND sttempleado = '0'");

              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert = 'El empleado \"'.$rechEmpleado_empleado.'\" fue eliminado con exito.'; }
              else 
              { $msg_alert = 'El empleado \"'.$rechEmpleado_empleado.'\" NO fue eliminado, ya que no se encontro en la base de datos.'; }
            }

            $prodLoteOpe->close; 
          } else
          { $msg_alert = $msg_nodelete; }  
        }        
      } else
      { $msg_alert = $msg_noread; }
    }

  
    if ($_POST['btn_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { $link_mysqli = conectar();  	
        $rechEmpleadoSelect = $link_mysqli->query("
          SELECT * FROM rechempleado
          WHERE idempleado = '".$rechEmpleado_idempleado."'");

        if ($rechEmpleadoSelect->num_rows > 0)
        { $regRecHEmpleado = $rechEmpleadoSelect->fetch_object();

          $link_mysqli = conectar();
          $link_mysqli->query("
        	  UPDATE rechempleado
        	  SET empleado = '".$rechEmpleado_empleado."'
        	  WHERE cdgempleado = '".$regRecHEmpleado->cdgempleado."'
        	  AND sttempleado = '1'"); 

          if ($link_mysqli->affected_rows > 0) 
          { $msg_alert = 'El empleado \"'.$rechEmpleado_idempleado.'\" fue actualizado de \"'.$regRecHEmpleado->empleado.'\" a \"'.$rechEmpleado_empleado.'\".'; }
          else 
          { $msg_alert = 'El empleado \"'.$rechEmpleado_idempleado.'\" NO fue actualizado.'; }
        } 
        else 
        { for ($cdgempleado = 1; $cdgempleado <= 10000; $cdgempleado++) 
          { $rechEmpleado_cdgempleado = str_pad($cdgempleado,4,'0',STR_PAD_LEFT);

            if ($cdgempleado > 9999) 
            { $msg_alert = 'No fue posible insertar un nuevo empleado, el tope ha sido alcanzado.'; } 
            else 
            { $link_mysqli = conectar();
              $link_mysqli->query("
                INSERT INTO rechempleado
                  (idempleado, empleado, cdgempleado)
                VALUES
                  ('".$rechEmpleado_idempleado."', '".$rechEmpleado_empleado."', '".$rechEmpleado_cdgempleado."')");
            
              if ($link_mysqli->affected_rows > 0) 
              { $msg_alert = 'El empleado \"'.$rechEmpleado_idempleado.'\" fue insertado con exito.'; 
                $cdgempleado = 10000; }      
            }
          }
        }
      } else
      { $msg_alert = $msg_norewrite; }
    }


      if ($_POST['chk_vertodo'])
      { if (substr($sistModulo_permiso,0,1) == 'r')
        { $vertodo = 'checked'; 

          $link_mysqli = conectar();
          $rechEmpleadoSelect = $link_mysqli->query("
            SELECT * FROM rechempleado 
            WHERE sttempleado != '9'
            ORDER BY sttempleado DESC,
              empleado"); 
        } else
        { $link_mysqli = conectar();
          $rechEmpleadoSelect = $link_mysqli->query("
            SELECT * FROM rechempleado
            WHERE sttempleado = '1'
            ORDER BY sttempleado DESC,
              empleado"); }
      } else 
      { $link_mysqli = conectar();
        $rechEmpleadoSelect = $link_mysqli->query("
          SELECT * FROM rechempleado
          WHERE sttempleado = '1'
          ORDER BY sttempleado DESC,
            empleado"); }

      if ($rechEmpleadoSelect->num_rows > 0) 
      { $id_empleado = 1;
        while($regRecHEmpleado = $rechEmpleadoSelect->fetch_object()) 
        { $rechEmpleados_idempleado[$id_empleado] = $regRecHEmpleado->idempleado; 
          $rechEmpleados_empleado[$id_empleado] = $regRecHEmpleado->empleado; 
          $rechEmpleados_cdgempleado[$id_empleado] = $regRecHEmpleado->cdgempleado; 
          $rechEmpleados_sttempleado[$id_empleado] = $regRecHEmpleado->sttempleado; 

          $id_empleado++; }

        $numempleados = $rechEmpleadoSelect->num_rows; }

      $rechEmpleadoSelect->close; 

    echo '
    <form id="frm_rechempleado" name="frm_rechempleado" method="POST" action="rechEmpleado.php">
      <table align="center">
        <thead>
          <tr align="left"><th colspan="2">'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label for="lbl_idempleado">Empleado</label><br/>
              <input type="text" id="txt_idempleado" name="txt_idempleado" style="width:80px" maxlenght="24" value="'.$rechEmpleado_idempleado.'" title="Identificador de empleado" autofocus required/></td>
            <td><label for="lbl_empleado">Nombre</label><br/>
              <input type="text" id="txt_empleado" name="txt_empleado" style="width:320px" maxlenght="60" value="'.$rechEmpleado_empleado.'" title="Descripcion del empleado" required/></td></tr>
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="2"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></th></tr>
        </tfoot>
      </table><br/>
      
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="4"><input type="checkbox" name="chk_vertodo" id="chk_vertodo" onclick="document.frm_rechempleado.submit()" '.$vertodo.'>
              <label for="lbl_vertodo">Ver todo</label></th></tr>
          <tr align="left">
            <th>Empleado</th>
            <th>Nombre</th>
            <th colspan="2">Operaciones</th></tr>
        </thead>
        <tbody>';

    if ($numempleados > 0) 
    { for ($id_empleado = 1; $id_empleado <= $numempleados; $id_empleado++) 
      { echo '
          <tr align="center">
            <td align="left">'.$rechEmpleados_idempleado[$id_empleado].'</td>
            <td align="left">'.$rechEmpleados_empleado[$id_empleado].'</td>';

        if ((int)$rechEmpleados_sttempleado[$id_empleado] > 0) 
        { echo '
            <td><a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$id_empleado].'">'.$png_search.'</a></td>
            <td><a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$id_empleado].'&empleado=update">'.$png_power_blue.'</a></td></tr>'; }
        else 
        { echo '
            <td><a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$id_empleado].'&empleado=delete">'.$png_recycle_bin.'</a></td>            
            <td><a href="rechEmpleado.php?cdgempleado='.$rechEmpleados_cdgempleado[$id_empleado].'&empleado=update">'.$png_power_black.'</a></td></tr>'; }
      }

      unset($rechEmpleados_idempleado);
      unset($rechEmpleados_empleado);
      unset($rechEmpleados_cdgempleado);
      unset($rechEmpleados_sttempleado); }    

    echo '
        </tbody>
        <tfoot>
          <tr align="right"><th colspan="4"><label for="lbl_numregistros">['.$numempleados.'] Registros encontrados</label></th></tr>
        </tfoot>
      </table>
    </form>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }    

?>

  </body>
</html>
