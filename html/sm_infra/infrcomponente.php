<?php require '../datos/mysql.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Infraestructura</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" media="screen" />
  </head>
  <body><?php
  
  $sistModuloPermiso = sistPermiso('820', $_SESSION['cdgusuario']);
  if (substr($sistModuloPermiso,0,1) == 'r')
  { if ($_POST['submit'])
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { $cdgtipo = $_POST['hiddencdgtipo'];

        $link_mysqli = conectar();
        $querySelect = $link_mysqli->query("
          SELECT * FROM infrcomponente
           WHERE cdgtipo = '".$cdgtipo."' AND
                 idcomponente = '".$_POST['textid']."'");
        
        if ($querySelect->num_rows > 0)
        { $regQuery = $querySelect->fetch_object();
          $cdgcomponente = $regQuery->cdgcomponente;
          
          $link_mysqli = conectar();
          $link_mysqli->query("
            UPDATE infrcomponente
               SET componente = '".$_POST['textnombre']."',
                   nota = '".$_POST['textareanota']."'
             WHERE cdgcomponente = '".$cdgcomponente."' AND
                   sttcomponente = '1'");
        } else
        { for ($cdg = 90001; $cdg <= 99999; $cdg++)
          { $link_mysqli = conectar();
            $link_mysqli->query("
              INSERT INTO infrcomponente
                (cdgtipo, idcomponente, componente, nota, cdgcomponente)
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
        SELECT * FROM infrcomponente
         WHERE cdgtipo = '".$_POST['hiddencdgtipo']."' AND
               idcomponente = '".$_POST['textid']."'");
      
      if ($querySelect->num_rows > 0)
      { $regQuery = $querySelect->fetch_object();
        $cdgcomponente = '../img_infra/'.$regQuery->cdgcomponente.'.jpg';

        if(($_FILES['fileupload']['type'] == "image/pjpeg") or ($_FILES['fileupload']['type'] == "image/jpeg"))
        { if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $cdgcomponente))
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
      <label id="modulo">RC-04-PSG-6.3 Componentes</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="infrcomponente.php">  
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
            SELECT * FROM infrcomponente
             WHERE cdgcomponente = '".$_GET['cdgcomponente']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">RC-04-PSG-6.3 Componentes</label><br>
      <label id="operacion">Actualizar registro</label><br>
      <hr>
      <form method="post" action="infrcomponente.php">  
        <img id="logo" src="../img_infra/'.$regQuery->cdgcomponente.'.jpg" alt="'.$regQuery->componente.'" />
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idcomponente.'" placeholder="Nombre corto" required="required" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->componente.'" placeholder="Nombre" required="required" autofocus="autofocus" />       
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
            SELECT * FROM infrcomponente
             WHERE cdgcomponente = '".$_GET['cdgcomponente']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 
            
            echo '
    <div id="formulario">  
      <label id="bloque">PSG-6.3 Infraestructura</label><br>
      <label id="modulo">Catálogo de componentes</label><br>
      <label id="operacion">Asignar imagen</label><br>
      <hr>
      <form enctype="multipart/form-data" method="post" action="infrcomponente.php">  
        <label for="labelid">Identificador</label>  
        <input type="text" id="textid" name="textid" value="'.$regQuery->idcomponente.'" readonly="readonly" />  
        <label for="labelnombre">Nombre</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regQuery->componente.'"  readonly="readonly" />       
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
    { if ($_GET['cdgcomponente'])
      { if (substr($sistModuloPermiso,0,1) == 'r')
        { $link_mysqli = conectar();
          $querySelect = $link_mysqli->query("
            SELECT * FROM infrcomponente
             WHERE cdgcomponente = '".$_GET['cdgcomponente']."'");

          if ($querySelect->num_rows > 0)
          { $regQuery = $querySelect->fetch_object(); 

            $cdgtipo = $regQuery->cdgtipo; }
        }

        if (substr($sistModuloPermiso,0,2) == 'rw')
        { switch ($_GET['sttcomponente']) {
          case '0':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrcomponente
                 SET sttcomponente = '1'
               WHERE cdgcomponente = '".$_GET['cdgcomponente']."'");
            break;
          
          case '1':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrcomponente
                 SET sttcomponente = '0'
               WHERE cdgcomponente = '".$_GET['cdgcomponente']."'");
            break;

          case '9':
            $link_mysqli = conectar();
            $link_mysqli->query("
              UPDATE infrcomponente
                 SET sttcomponente = '9'
               WHERE cdgcomponente = '".$_GET['cdgcomponente']."' AND
                     sttcomponente = '1'");
            break;

          case 'X':
            $link_mysqli->query("
              DELETE FROM infrcomponente
               WHERE cdgcomponente = '".$_GET['cdgcomponente']."' AND
                     sttcomponente = '0'");
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
      <label id="modulo">'.utf8_decode('Catálogo de componentes').'&nbsp;</label>
      <a href="infrcomponente.php?operacion=insert&cdgtipo='.$cdgtipo.'"><img src="../img_sistema/puzzle.png" width="32" /></a>
      <article>
        <dl>';

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrcomponente
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttcomponente = '1'
      ORDER BY idcomponente");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt><label>'.$regQuery->componente.'</label><br>
        <a href="infrcomponente.php?operacion=select&cdgcomponente='.$regQuery->cdgcomponente.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrcomponente.php?operacion=photo&cdgcomponente='.$regQuery->cdgcomponente.'"><img src="../img_sistema/camera.png" width="24" /></a>
        <a href="infrcomponente.php?cdgcomponente='.$regQuery->cdgcomponente.'&sttcomponente=9"><img src="../img_sistema/folder_download.png" width="24" /></a>
        <a href="infrcomponente.php?cdgcomponente='.$regQuery->cdgcomponente.'&sttcomponente='.$regQuery->sttcomponente.'"><img src="../img_sistema/power_blue.png" width="24" /></a>
        <label>(<b>'.$regQuery->idcomponente.'</b>)</label>
      </dt>'; }    
      }

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrcomponente
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttcomponente = '9'
      ORDER BY idcomponente");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt><label>'.$regQuery->componente.'</label><br>
        <a href="infrcomponente.php?operacion=select&cdgcomponente='.$regQuery->cdgcomponente.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrcomponente.php?cdgcomponente='.$regQuery->cdgcomponente.'&sttcomponente=0"><img src="../img_sistema/folder_upload.png" width="24" /></a>
        <label>(<b>'.$regQuery->idcomponente.'</b>)</label>
      </dt>'; }    
      }

      $link_mysqli = conectar();
      $querySelect = $link_mysqli->query("
        SELECT * FROM infrcomponente
         WHERE cdgtipo = '".$cdgtipo."' AND
               sttcomponente = '0'
      ORDER BY idcomponente");

      if ($querySelect->num_rows > 0)
      { echo '
        <dt><hr></dt>';
        while ($regQuery = $querySelect->fetch_object())
        { echo '
      <dt align="right">
        <label>'.$regQuery->componente.'</label><br>
        <label>(<b>'.$regQuery->idcomponente.'</b>)</label>
        <a href="infrcomponente.php?operacion=select&cdgcomponente='.$regQuery->cdgcomponente.'"><img src="../img_sistema/search.png" width="24" /></a>
        <a href="infrcomponente.php?cdgcomponente='.$regQuery->cdgcomponente.'&sttcomponente='.$regQuery->sttcomponente.'"><img src="../img_sistema/power_black.png" width="24" /></a>
        <a href="infrcomponente.php?cdgcomponente='.$regQuery->cdgcomponente.'&sttcomponente=X"><img src="../img_sistema/recycle_bin.png" width="24" /></a>
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
