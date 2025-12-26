<?php require '../datos/mysql.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Infraestructura</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" media="screen" />
  </head>
  <body><?php
  
  $sistModuloPermiso = sistPermiso('810', $_SESSION['cdgusuario']);
  if (substr($sistModuloPermiso,0,1) == 'r')
  { if ($_POST['submit'])
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { $cdgtipo = $_POST['hiddencdgtipo'];

        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
          SELECT * FROM infrequipo
           WHERE cdgtipo = '".$cdgtipo."' AND
                 idequipo = '".$_POST['textid']."'");
        
        if ($querySelect->num_rows > 0)
        { $regQuery = $querySelect->fetch_object();
          $cdgequipo = $regQuery->cdgequipo;
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE infrequipo
               SET equipo = '".$_POST['textnombre']."',
                   nota = '".$_POST['textareanota']."'
             WHERE cdgequipo = '".$cdgequipo."' AND
                   sttequipo = '1'");
        } else
        { for ($cdg = 10001; $cdg <= 19999; $cdg++)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO infrequipo
                (cdgtipo, idequipo, equipo, nota, cdgequipo)
              VALUES
                ('".$cdgtipo."', '".$_POST['textid']."', '".$_POST['textnombre']."', '".$_POST['textareanota']."', '".$cdgtipo.$cdg."')");

            if ($link_mysqli->affected_rows > 0) 
            { break; }  
          }
        }
      }
    }

    if ($_POST['upload'])
    { $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrequipo
         WHERE cdgtipo = '".$_POST['hiddencdgtipo']."' AND
               idequipo = '".$_POST['textid']."'");
      
      if ($querySelect->num_rows > 0)
      { $regQuery = $querySelect->fetch_object();
        $cdgequipo = '../img_infra/'.$regQuery->cdgequipo.'.jpg';

        if(($_FILES['fileupload']['type'] == "image/pjpeg") or ($_FILES['fileupload']['type'] == "image/jpeg"))
        { if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $cdgequipo))
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
      <label id="modulo">RC-01-PSG-6.3 Equipos</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="infrequipo.php">  
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="" placeholder="Nombre corto" required="required" autofocus="autofocus" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="" placeholder="Nombre" required="required" />       
        <label for="labelnota">Nota</label>  
        <textarea id="textareanota" name="textareanota" placeholder="Escribe algun dato importante" required="required"></textarea>
        <input type="hidden" id="hiddencdgtipo" name="hiddencdgtipo" value="'.$_GET['cdgtipo'].'" />
        <input type="submit" value="Registrar" id="submit" name="submit" />
      </form>
    </div>';
        break;
      
        case 'select':
          $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrequipo
             WHERE cdgequipo = '".$_GET['cdgequipo']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">RC-01-PSG-6.3 Equipos</label><br>
      <label id="operacion">Actualizar registro</label><br>
      <hr>
      <form method="post" action="infrequipo.php">  
        <img id="logo" src="../img_infra/'.$regQuery->cdgequipo.'.jpg" alt="'.$regQuery->equipo.'" />
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idequipo.'" placeholder="Nombre corto" required="required" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->equipo.'" placeholder="Nombre" required="required" autofocus="autofocus" />
        <label for="labelnota">Nota</label>  
        <textarea id="textareanota" name="textareanota" placeholder="Escribe algun dato importante" required="required">'.$regQuery->nota.'</textarea>
        <input type="hidden" id="hiddencdgtipo" name="hiddencdgtipo" value="'.$regQuery->cdgtipo.'" />
        <input type="submit" value="Actualizar" id="submit" name="submit" />
      </form>
    </div>'; }
        break;

        case 'photo':
          $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrequipo
             WHERE cdgequipo = '".$_GET['cdgequipo']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">Catálogo de equipos</label><br>
      <label id="operacion">Asignar imagen</label><br>
      <hr>
      <form enctype="multipart/form-data" method="post" action="infrequipo.php">  
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idequipo.'" readonly="readonly" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->equipo.'"  readonly="readonly" />       
        <label for="labelimagen">Elegir imagen</label>
        <input type="file" id="fileupload" name="fileupload" />
        <input type="hidden" id="hiddencdgtipo" name="hiddencdgtipo" value="'.$regQuery->cdgtipo.'" />
        <input type="submit" value="Subir imagen" id="upload" name="upload" />
      </form>
    </div>'; }
        break;

        default:
          # code...
        break; }
    } else
    { if ($_GET['cdgequipo'])
      { if (substr($sistModuloPermiso,0,1) == 'r')
        { $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrequipo
             WHERE cdgequipo = '".$_GET['cdgequipo']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 

            $cdgtipo = $regQuery->cdgtipo; }
        }

        if (substr($sistModuloPermiso,0,2) == 'rw')
        { switch ($_GET['sttequipo']) {
          case '0':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrequipo
                 SET sttequipo = '1'
               WHERE cdgequipo = '".$_GET['cdgequipo']."'");
            break;
          
          case '1':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrequipo
                 SET sttequipo = '0'
               WHERE cdgequipo = '".$_GET['cdgequipo']."'");
            break;

          case '9':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrequipo
                 SET sttequipo = '9'
               WHERE cdgequipo = '".$_GET['cdgequipo']."' AND
                     sttequipo = '1'");
            break;

          case 'X':
            $link_mysqli->query("
              DELETE FROM infrequipo
               WHERE cdgequipo = '".$_GET['cdgequipo']."' AND
                     sttequipo = '0'");
            break;             
          default:
            # code...
            break; }
        }       
      } else
      { if ($_POST['hiddencdgtipo']) { $cdgtipo = $_POST['hiddencdgtipo']; }
        if ($_GET['cdgtipo']) { $cdgtipo = $_GET['cdgtipo']; }
      }
      
      echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">'.utf8_decode('Catálogo de equipos').'&nbsp;</label>
      <a href="infrequipo.php?operacion=insert&cdgtipo='.$cdgtipo.'"><img src="../img_sistema/folder_add.png" width="32" /></a>
      <article>
        <dl>';

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrequipo
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttequipo = '1'
      ORDER BY idequipo");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt><label>'.$regQuery->equipo.'</label><br>
        <a href="infrequipo.php?operacion=select&cdgequipo='.$regQuery->cdgequipo.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrequipo.php?operacion=photo&cdgequipo='.$regQuery->cdgequipo.'"><img src="../img_sistema/camera.png" width="24" /></a>
        <img src="../img_sistema/puzzle.png" width="24" />
        <a href="infrequipo.php?cdgequipo='.$regQuery->cdgequipo.'&sttequipo=9"><img src="../img_sistema/folder_user.png" width="24" /></a>
        <a href="infrequipo.php?cdgequipo='.$regQuery->cdgequipo.'&sttequipo='.$regQuery->sttequipo.'"><img src="../img_sistema/power_blue.png" width="24" /></a>
        <label>(<b>'.$regQuery->idequipo.'</b>)</label>
      </dt>'; }    
      }

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrequipo
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttequipo = '9'
      ORDER BY idequipo");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt><label>'.$regQuery->equipo.'</label><br>
        <a href="infrequipo.php?operacion=select&cdgequipo='.$regQuery->cdgequipo.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrequipo.php?cdgequipo='.$regQuery->cdgequipo.'&sttequipo=0"><img src="../img_sistema/folder.png" width="24" /></a>
        <label>(<b>'.$regQuery->idequipo.'</b>)</label>
      </dt>'; }    
      }

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrequipo
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttequipo = '0'
      ORDER BY idequipo");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt align="right">
        <label>'.$regQuery->equipo.'</label><br>
        <label>(<b>'.$regQuery->idequipo.'</b>)</label>
        <a href="infrequipo.php?operacion=select&cdgequipo='.$regQuery->cdgequipo.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrequipo.php?cdgequipo='.$regQuery->cdgequipo.'&sttequipo='.$regQuery->sttequipo.'"><img src="../img_sistema/power_black.png" width="24" /></a>
        <a href="infrequipo.php?cdgequipo='.$regQuery->cdgequipo.'&sttequipo=X"><img src="../img_sistema/recycle_bin.png" width="24" /></a>
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
