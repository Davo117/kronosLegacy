<?php require '../datos/mysql.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Infraestructura</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" media="screen" />
  </head>
  <body><?php

  if ($_SESSION['cdgusuario'])
  { if ($_POST['submit'])
    { $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrequipo
         WHERE (idequipo = '".$_POST['textequipo']."' OR cdgequipo = '".$_POST['textequipo']."')");

      if ($querySelect->num_rows > 0)
      { $regQuery = $querySelect->fetch_object();

        $cdgequipo = $regQuery->cdgequipo;

        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
          SELECT * FROM infrsolicitud
           WHERE cdgequipo = '".$cdgequipo."'");
      
        if ($querySelect->num_rows > 0)
        { $regQuery = $querySelect->fetch_object();
          $cdgsolicitud = $regQuery->cdgsolicitud;
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE infrsolicitud
               SET solicitud = '".$_POST['textareasolicitud']."'
             WHERE cdgequipo = '".$cdgequipo."' AND
                   solicitante = '".$_SESSION['cdgusuario']."'");

          if ($link_mysqli->affected_rows == 0)
          { $success = 'Ya existe un solicitud pendiente por recibir de este equipo.'; }
        } else
        { $link_mysqli = conectar();
          $link_mysqli->query("
            INSERT INTO infrsolicitud
              (cdgequipo, solicitante, solicitud, fchsolicitud)
            VALUES
              ('".$cdgequipo."', '".$_SESSION['cdgusuario']."', '".$_POST['textareasolicitud']."', NOW())"); 
        }
      } else
      { $success = 'El equipo indicado no existe, favor de verificarlo.'; }
    }

    if ($_GET['operacion'])
    { switch ($_GET['operacion']) {
        case 'insert':
          echo '
    <div id="formulario">
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('RC-03-PSG-6.3 Solicitud de mantenimiento').'</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="infrsolicitud.php">  
        <label for="labelusuario">Solicitante</label>  
        <input type="text" id="textusuario" name="textusuario" value="'.$_SESSION['usuario'].'" placeholder="Usuario" readonly="readonly"  />  
        <label for="labelid">Equipo</label>  
        <input type="text" id="textequipo" name="textequipo" value="" placeholder="'.utf8_decode('Código o número de serie').'" required="required" autofocus="autofocus" />  
        <label for="labelnota">'.utf8_decode('Descripción de la solicitud').'</label>  
        <textarea id="textareasolicitud" name="textareasolicitud" placeholder="Descripción del problema" required="required"></textarea>
        <input type="submit" value="Registrar" id="submit" name="submit" />
      </form>
    </div>';
        break;
      
        case 'select':
          $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT infrequipo.equipo,
                   infrsolicitud.cdgequipo,
                   infrsolicitud.solicitud 
              FROM infrequipo,
                   infrsolicitud
             WHERE infrequipo.cdgequipo = infrsolicitud.cdgequipo AND 
                   infrsolicitud.cdgequipo = '".$_GET['cdgequipo']."' AND
                   infrsolicitud.solicitante = '".$_SESSION['cdgusuario']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Infraestructuras').'</label><br>
      <label id="operacion">Actualizar registro</label><br>
      <hr>
      <form method="post" action="infrsolicitud.php">  
        <label for="labelequipo"><b>'.$regQuery->equipo.'</b></label><br>
        <img id="logo" src="../img_infra/'.$regQuery->cdgequipo.'.jpg" />
        <br>
        <label for="labelusuario">Solicitante</label>  
        <input type="text" id="textusuario" name="textusuario" value="'.$_SESSION['usuario'].'" placeholder="Usuario" readonly="readonly"  />  
        <label for="labelid">Equipo</label>  
        <input type="text" id="textequipo" name="textequipo" value="'.$regQuery->cdgequipo.'" placeholder="'.utf8_decode('Código o número de serie').'" required="required" autofocus="autofocus" />  
        <label for="labelnota">'.utf8_decode('Descripción de la solicitud').'</label>  
        <textarea id="textareasolicitud" name="textareasolicitud" placeholder="Descripción del problema" required="required">'.$regQuery->solicitud.'</textarea>
        <input type="submit" value="Actualizar" id="submit" name="submit" />
      </form>
    </div>'; }
        break;

        default:
          # code...
        break; }
    } else
    { if ($_GET['cdgequipo'])
      { switch ($_GET['sttsolicitud']) {
          case 'X':
            $link_mysqli = conectar();
            $link_mysqli->query("
              DELETE FROM infrsolicitud
               WHERE cdgequipo = '".$_GET['cdgequipo']."' AND
                     solicitante = '".$_SESSION['cdgusuario']."'");
          break;

          default:
          # code...
          break; }
      }

      echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('solicitudes de mantenimiento').'&nbsp;</label>
      <a href="infrsolicitud.php?operacion=insert"><img src="../img_sistema/email_add.png" width="32" /></a>
      <article>
        <dl>';

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT infrequipo.equipo,
               infrsolicitud.cdgequipo 
          FROM infrequipo,
               infrsolicitud
         WHERE infrequipo.cdgequipo = infrsolicitud.cdgequipo AND
               infrsolicitud.solicitante = '".$_SESSION['cdgusuario']."'
      ORDER BY infrsolicitud.fchsolicitud");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt>
        <a href="infrsolicitud.php?cdgequipo='.$regQuery->cdgequipo.'&operacion=select"><img src="../img_sistema/search.png" width="24" /></a>  
        <a href="infrsolicitud.php?cdgequipo='.$regQuery->cdgequipo.'&sttsolicitud=X"><img src="../img_sistema/recycle_bin.png" width="24" /></a>
        <label>'.$regQuery->equipo.'</label>
      </dt>'; }    
      }

      echo '
        </dl>
      </article>
    </div>'; }
  }
?>

  </body>
</html>
