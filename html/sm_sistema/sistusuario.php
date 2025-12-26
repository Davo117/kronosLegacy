<?php 
  header('Content-Type: text/html; charset=UTF-8'); 
  require '../datos/mysql.php'; 

  $sistModuloPermiso = sistPermiso('800', $_SESSION['cdgusuario']);
  if (substr($sistModuloPermiso,0,1) != 'r')
  { echo '<div align="center"><h1>'.utf8_decode('Módulo restringido a usuarios autorizados').'</h1></div>';
    exit; } 
  
  if ($_POST['submit'])
  { if (substr($sistModuloPermiso,0,2) == 'rw')
    { if ($_POST['selectempleado'])
      { $link = conectar();
        $link->query("
          INSERT INTO sistusuario
            (idusuario, usuario, wordpass, cdgperfil, cdgusuario)
          VALUES
            ('".$_POST['textid']."', '".$_POST['textnombre']."', MD5('NewUser'), '".$_POST['selectperfil']."', '".$_POST['selectempleado']."')"); }

      if ($_POST['hiddencdgempleado'])  
      { $link = conectar();
        $link->query("
          UPDATE sistusuario
             SET idusuario = '".$_POST['textid']."',
                 usuario = '".$_POST['textnombre']."',
                 cdgperfil = '".$_POST['selectperfil']."'
           WHERE cdgusuario = '".$_POST['hiddencdgempleado']."'"); }      
    }
  }

  if ($_POST['upload'])
  { if (substr($sistModuloPermiso,0,2) == 'rw')
    { $link = conectar();
      $querySelectUsuario = $link->query("
        SELECT * FROM sistusuario
         WHERE cdgusuario = '".$_POST['hiddencdgempleado']."'");
      
      if ($querySelectUsuario->num_rows > 0)
      { $regUsuario = $querySelectUsuario->fetch_object();
        $cdgusuario = '../img_sist/'.$regUsuario->cdgusuario.'.jpg';

        if(($_FILES['fileupload']['type'] == "image/pjpeg") or ($_FILES['fileupload']['type'] == "image/jpeg"))
        { if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $cdgusuario))
          { $success = 'Imagen asignada'; }
          else
          { $failure = 'Ocurrio un error.'; }
        } else
        { $failure = 'El formato debe ser JPG.'; }
      }
    }
  }

?><!DOCTYPE html>
<html>
  <head>
    <title>Usuarios</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="../sm_ventas/estilo.css" media="screen" />
  </head>
  <body><?php

  if ($_GET['operacion'])
  { switch ($_GET['operacion']) {
      case 'insert':
        echo '
    <div id="formulario">
      <a href="sistusuario.php"><img id="icono" src="../img_sistema/user.png" width="54" /></a>
      <label id="bloque">Sistema</label><br>
      <label id="modulo">'.utf8_decode('Usuarios').'</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="sistusuario.php">  
        <label for="labelempleado">Empleado</label>  
        <select id="selectempleado" name="selectempleado">
          <option value="">-</option>';

        $link = conectar();
        $querySelectEmpleado = $link->query("
          SELECT cdgempleado, 
                 empleado
            FROM rechempleado
           WHERE sttempleado = '1'
        ORDER BY empleado");

        if ($querySelectEmpleado->num_rows > 0)
        { while ($regEmpleado = $querySelectEmpleado->fetch_object())
          { if ($regEmpleado->cdgempleado == $regUsuario->cdgusuario)
            { echo '
          <option value="'.$regEmpleado->cdgempleado.'" selected="selected">'.$regEmpleado->empleado.'</option>';}
            else
            { echo '
          <option value="'.$regEmpleado->cdgempleado.'">'.$regEmpleado->empleado.'</option>'; } 
          }
        }

        echo '
        </select>
        <label for="labelid">idUsuario</label>  
        <input type="text" id="textid" name="textid" value="" placeholder="Nombre corto" required="required" autofocus="autofocus" />  
        <label for="labelnombre">Alias</label>  
        <input type="text" id="textnombre" name="textnombre" value="" placeholder="Nombre" required="required" />       
        <label for="labelperfil">Perfil</label>  
        <select id="selectperfil" name="selectperfil">
          <option value="">-</option>';

        $link = conectar();
        $querySelectPerfil = $link->query("
          SELECT cdgperfil, 
                 perfil 
            FROM sistperfil
        ORDER BY perfil");

        if ($querySelectPerfil->num_rows > 0)
        { while ($regPerfil = $querySelectPerfil->fetch_object())
          { if ($regPerfil->cdgperfil == $regUsuario->cdgperfil)
            { echo '
          <option value="'.$regPerfil->cdgperfil.'" selected="selected">'.$regPerfil->perfil.'</option>';
            } else
            { echo '
          <option value="'.$regPerfil->cdgperfil.'">'.$regPerfil->perfil.'</option>'; }
          }
        }

        echo '
        </select> 
        <input type="submit" value="Registrar" id="submit" name="submit" />
      </form>
    </div>';
      break;
    
      case 'select':
        $link = conectar();
        $querySelectUsuario = $link->query("
          SELECT * FROM sistusuario
           WHERE cdgusuario = '".$_GET['cdgusuario']."'");

        if ($querySelectUsuario->num_rows > 0)
        { $regUsuario = $querySelectUsuario->fetch_object(); 
          
          echo '
    <div id="formulario">  
      <a href="sistusuario.php"><img id="icono" src="../img_sistema/search.png" width="54" /></a>
      <label id="bloque">Sistema</label><br>
      <label id="modulo">'.utf8_decode('Usuarios').'</label><br>
      <label id="operacion">Actualizar registro</label><br>
      <hr>
      <form method="post" action="sistusuario.php">';
            
          if (file_exists('../img_sist/'.$regUsuario->cdgusuario.'.jpg'))
          { echo'
        <img id="logo" src="../img_sist/'.$regUsuario->cdgusuario.'.jpg" alt="'.$regUsuario->usuario.'" />'; 
          } else
          { echo'
        <img id="logo" src="../img_sistema/user.png" />'; }

          echo '<br>
        <input type="hidden" id="hiddencdgempleado" name="hiddencdgempleado" value="'.$regUsuario->cdgusuario.'" /> 
        <label for="labelid">idUsuario</label>  
        <input type="text" id="textid" name="textid" value="'.$regUsuario->idusuario.'" placeholder="Nombre corto" required="required" />
        <label for="labelnombre">Alias</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regUsuario->usuario.'" placeholder="Nombre" required="required" autofocus="autofocus" />       
        <label for="labelperfil">Perfil</label>  
        <select id="selectperfil" name="selectperfil">
          <option value="">Elige un perfil</option>';

        $link = conectar();
        $querySelectPerfil = $link->query("
          SELECT cdgperfil, 
                 perfil 
            FROM sistperfil
        ORDER BY perfil");

        if ($querySelectPerfil->num_rows > 0)
        { while ($regPerfil = $querySelectPerfil->fetch_object())
          { if ($regPerfil->cdgperfil == $regUsuario->cdgperfil)
            { echo '
          <option value="'.$regPerfil->cdgperfil.'" selected="selected">'.$regPerfil->perfil.'</option>';}
            else
            { echo '
          <option value="'.$regPerfil->cdgperfil.'">'.$regPerfil->perfil.'</option>'; }
          }
        }

        echo '
        </select> 
        <input type="submit" value="Actualizar" id="submit" name="submit" />
      </form>
    </div>'; }
      break;

      case 'photo':
        $link = conectar();
        $querySelectUsuario = $link->query("
          SELECT * FROM sistusuario
           WHERE cdgusuario = '".$_GET['cdgusuario']."'");

        if ($querySelectUsuario->num_rows > 0)
        { $regUsuario = $querySelectUsuario->fetch_object(); 
          
          echo '
    <div id="formulario">
      <a href="sistusuario.php"><img id="icono" src="../img_sistema/camera.png" width="54" /></a>
      <label id="bloque">Sistema</label><br>
      <label id="modulo">'.utf8_decode('Usuarios').'</label><br>
      <label id="operacion">Asignar imagen</label>
      <hr>
      <form enctype="multipart/form-data" method="post" action="sistusuario.php">
        <label for="labelid">idUsuario</label>  
        <input type="text" id="textid" name="textid" value="'.$regUsuario->idusuario.'" readonly="readonly" />  
        <label for="labelnombre">Alias</label>  
        <input type="text" id="textnombre" name="textnombre" value="'.$regUsuario->usuario.'"  readonly="readonly" />
        <input type="hidden" id="hiddencdgempleado" name="hiddencdgempleado" value="'.$regUsuario->cdgusuario.'" />    
        <label for="labelimagen">Elegir imagen</label>
        <input type="file" id="fileupload" name="fileupload" accept="image/jpeg" />
        <input type="submit" value="Subir imagen" id="upload" name="upload" />
      </form>
    </div>'; }
      break;

      default:
        # code...
      break; }
  } else
  { if ($_GET['cdgusuario'])
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { switch ($_GET['sttusuario']) {
          case '0':
            $link = conectar();
            $link->query("
              UPDATE sistusuario
                 SET sttusuario = '1'
               WHERE cdgusuario = '".$_GET['cdgusuario']."'");
            break;
          
          case '1':
            $link = conectar();
            $link->query("
              UPDATE sistusuario
                 SET sttusuario = '0'
               WHERE cdgusuario = '".$_GET['cdgusuario']."'");
            break;

          case 'O':
            if (substr($sistModuloPermiso,0,3) == 'rwx')
            { $link = conectar();
              $link->query("
                DELETE FROM sistusuario
                 WHERE cdgusuario = '".$_GET['cdgusuario']."' AND
                       sttusuario = '0'"); }
            break;

          default:
            # code...
            break; }
        }
    }

    echo '
    <div id="listado">  
      <a href="sistusuario.php?operacion=insert"><img id="icono" src="../img_sistema/user.png" width="54" /></a>
      <label id="bloque">Sistema</label><br>
      <label id="modulo">'.utf8_decode('Usuarios del sistema').'&nbsp;</label>      
      <article>
        <dl>';

    $link = conectar();
    $querySelectUsuario = $link->query("
      SELECT * FROM sistusuario
       WHERE sttusuario = '1'
    ORDER BY usuario");

    if ($querySelectUsuario->num_rows > 0)
    { echo '
        <dt><hr></dt>';
      while ($regUsuario = $querySelectUsuario->fetch_object())
      { echo '
          <dt><a href="sistusuario.php?operacion=select&cdgusuario='.$regUsuario->cdgusuario.'"><img src="../img_sistema/search.png" width="24" /></a>
            <a href="sistusuario.php?operacion=photo&cdgusuario='.$regUsuario->cdgusuario.'"><img src="../img_sistema/camera.png" width="24" /></a>
            <a href="sistusuario.php?cdgusuario='.$regUsuario->cdgusuario.'&sttusuario='.$regUsuario->sttusuario.'"><img src="../img_sistema/power_blue.png" width="24" /></a>
            <label>'.$regUsuario->usuario.'</label></dt>'; }    
    }

    $link = conectar();
    $querySelectUsuario = $link->query("
      SELECT * FROM sistusuario
       WHERE sttusuario = '0'
    ORDER BY usuario");

    if ($querySelectUsuario->num_rows > 0)
    { echo '
          <dt><hr></dt>';
      while ($regUsuario = $querySelectUsuario->fetch_object())
      { echo '
          <dt align="right">
            <label>'.$regUsuario->usuario.'</label>            
            <a href="sistusuario.php?cdgusuario='.$regUsuario->cdgusuario.'&sttusuario='.$regUsuario->sttusuario.'"><img src="../img_sistema/power_black.png" width="24" /></a>
            <a href="sistusuario.php?cdgusuario='.$regUsuario->cdgusuario.'&sttusuario=O"><img src="../img_sistema/recycle_bin.png" width="24" /></a>
          </dt>'; }    
    }
    
    echo '
        </dl>
      </article>
    </div>'; }
?>

  </body>
</html>