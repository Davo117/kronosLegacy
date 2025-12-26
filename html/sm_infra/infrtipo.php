<?php require '../datos/mysql.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Infraestructura</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" media="screen" />
  </head>
  <body><?php
  
  $sistModuloPermiso = sistPermiso('800', $_SESSION['cdgusuario']);
  if (substr($sistModuloPermiso,0,1) == 'r')
  { if ($_POST['submit'])
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
          SELECT * FROM infrtipo
           WHERE idtipo = '".$_POST['textid']."' AND
                 propietario = '".$_SESSION['cdgusuario']."'");
        
        if ($querySelect->num_rows > 0)
        { $regQuery = $querySelect->fetch_object();
          $cdgtipo = $regQuery->cdgtipo;
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE infrtipo
               SET tipo = '".$_POST['textnombre']."',
                   nota = '".$_POST['textareanota']."'
             WHERE cdgtipo = '".$cdgtipo."'");
        } else
        { for ($cdg = 1; $cdg <= 99; $cdg++)
          { $cdgtipo = str_pad($cdg,2,'0',STR_PAD_LEFT);
            
            $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO infrtipo
                (idtipo, tipo, nota, propietario, cdgtipo)
              VALUES
                ('".$_POST['textid']."', '".$_POST['textnombre']."', '".$_POST['textareanota']."', '".$_SESSION['cdgusuario']."', '".$cdgtipo."')");

            if ($link_mysqli->affected_rows > 0) 
            { break; }     
          }
        }
      }
    }

    if ($_POST['upload'])
    { $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrtipo
         WHERE idtipo = '".$_POST['textid']."' AND
               propietario = '".$_SESSION['cdgusuario']."'");
      
      if ($querySelect->num_rows > 0)
      { $regQuery = $querySelect->fetch_object();
        $cdgtipo = '../img_infra/'.$regQuery->cdgtipo.'.jpg';

        if(($_FILES['fileupload']['type'] == "image/pjpeg") or ($_FILES['fileupload']['type'] == "image/jpeg"))
        { if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $cdgtipo))
          { $success = 'Imagen asignada'; }
          else
          { $failure = 'Ocurrio un error.'; }
        } else
        { $failure = 'El formato debe ser JPG.'; }
      }
    }

    if ($_GET['operacion'])
    { switch ($_GET['operacion']) {
        case 'insert':
          echo '
    <div id="formulario">
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Infraestructuras').'</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="infrtipo.php">  
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="" placeholder="Nombre corto" required="required" autofocus="autofocus" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="" placeholder="Nombre" required="required" />       
        <label for="labelnota">Nota</label>  
        <textarea id="textareanota" name="textareanota" placeholder="Escribe algun dato importante" required="required"></textarea>
        <input type="submit" value="Registrar" id="submit" name="submit" />
      </form>
    </div>';
        break;
      
        case 'select':
          $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrtipo
             WHERE cdgtipo = '".$_GET['cdgtipo']."' AND
                   propietario = '".$_SESSION['cdgusuario']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Infraestructuras').'</label><br>
      <label id="operacion">Actualizar registro</label><br>
      <hr>
      <form method="post" action="infrtipo.php">  
        <img id="logo" src="../img_infra/'.$regQuery->cdgtipo.'.jpg" alt="'.$regQuery->tipo.'" />
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idtipo.'" placeholder="Nombre corto" required="required" />
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->tipo.'" placeholder="Nombre" required="required" autofocus="autofocus" />       
        <label for="labelnota">Nota</label>  
        <textarea id="textareanota" name="textareanota" placeholder="Escribe algun dato importante" required="required">'.$regQuery->nota.'</textarea>
        <input type="submit" value="Actualizar" id="submit" name="submit" />
      </form>
    </div>'; }
        break;

        case 'photo':
          $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrtipo
             WHERE cdgtipo = '".$_GET['cdgtipo']."' AND
                   propietario = '".$_SESSION['cdgusuario']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Infraestructuras').'</label><br>
      <label id="operacion">Asignar imagen</label><br>
      <hr>
      <form enctype="multipart/form-data" method="post" action="infrtipo.php">  
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idtipo.'" readonly="readonly" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->tipo.'"  readonly="readonly" />       
        <label for="labelimagen">Elegir imagen</label>
        <input type="file" id="fileupload" name="fileupload" />
        <input type="submit" value="Subir imagen" id="upload" name="upload" />
      </form>
    </div>'; }
        break;

        default:
          # code...
        break; }
    } else
    { if ($_GET['cdgtipo'])
      { if (substr($sistModuloPermiso,0,2) == 'rw')
        { switch ($_GET['stttipo']) {
          case '0':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrtipo
                 SET stttipo = '1'
               WHERE cdgtipo = '".$_GET['cdgtipo']."' AND
                     propietario = '".$_SESSION['cdgusuario']."'");
            break;
          
          case '1':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrtipo
                 SET stttipo = '0'
               WHERE cdgtipo = '".$_GET['cdgtipo']."' AND
                     propietario = '".$_SESSION['cdgusuario']."'");
            break;

          case 'X':
            $link_mysqli = conectar();
            $querySelect = $link_mysqli->query("
              SELECT * FROM infrequipo
               WHERE cdgtipo = '".$_GET['cdgtipo']."'");

            if ($querySelect->num_rows == 0)
            { $link_mysqli->query("
              DELETE FROM infrtipo
               WHERE cdgtipo = '".$_GET['cdgtipo']."'  AND
                     propietario = '".$_SESSION['cdgusuario']."' AND
                     stttipo = '0' "); }
            break;             
          default:
            # code...
            break; }
        }
      }

      echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Tipos de infraestructura').'&nbsp;</label>
      <a href="infrtipo.php?operacion=insert"><img src="../img_sistema/filter.png" width="32" /></a>
      <article>
        <dl>';

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrtipo
         WHERE propietario = '".$_SESSION['cdgusuario']."' AND
               stttipo = '1'
      ORDER BY idtipo");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt><label>'.$regQuery->tipo.'</label></dt>
      <dd>
        <a href="infrtipo.php?operacion=select&cdgtipo='.$regQuery->cdgtipo.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrtipo.php?operacion=photo&cdgtipo='.$regQuery->cdgtipo.'"><img src="../img_sistema/camera.png" width="24" /></a>
        <a href="infrequipo.php?cdgtipo='.$regQuery->cdgtipo.'"><img src="../img_sistema/folder.png" width="24" /></a>
        <a href="infrcomponente.php?cdgtipo='.$regQuery->cdgtipo.'"><img src="../img_sistema/puzzle.png" width="24" /></a>
        <a href="infrpreventivo.php?cdgtipo='.$regQuery->cdgtipo.'"><img src="../img_sistema/tools.png" width="24" /></a>
        <a href="infrtipo.php?cdgtipo='.$regQuery->cdgtipo.'&stttipo='.$regQuery->stttipo.'"><img src="../img_sistema/power_blue.png" width="24" /></a>
      </dd>'; }    
      }

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrtipo
         WHERE propietario = '".$_SESSION['cdgusuario']."' AND
               stttipo = '0'
      ORDER BY idtipo");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt align="right">
        <label>'.$regQuery->tipo.'</label>
        <a href="infrtipo.php?cdgtipo='.$regQuery->cdgtipo.'&stttipo='.$regQuery->stttipo.'"><img src="../img_sistema/power_black.png" width="24" /></a>
        <a href="infrtipo.php?cdgtipo='.$regQuery->cdgtipo.'&stttipo=X"><img src="../img_sistema/recycle_bin.png" width="24" /></a>
      </dt>'; }    
      }
      echo '
        </dl>
      </article>';

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT infrequipo.equipo,
               infrsolicitud.cdgequipo,
               infrsolicitud.solicitud,
               infrsolicitud.fchsolicitud
          FROM infrsolicitud,
               infrequipo,
               infrtipo
         WHERE infrsolicitud.cdgequipo = infrequipo.cdgequipo AND
               infrequipo.cdgtipo = infrtipo.cdgtipo AND
               infrtipo.propietario = '".$_SESSION['cdgusuario']."'");

      if ($querySelect->num_rows > 0)
      { echo '
      <article>
        <label><b>Solicitudes de mantenimiento</b></label>
        <hr>';

        while ($regQuery = $querySelect->fetch_object())
        { echo '
        <dt><label>'.$regQuery->equipo.'</label></dt>
        <dd><label><i>'.$regQuery->fchsolicitud.'</i></label></dd>'; }
      }
      
        echo '
      </article>
    </div>'; }
  }
?>

  </body>
</html>
