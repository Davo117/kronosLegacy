<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Devoluciones</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '40210';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); 
    } else
    { echo '
      <div id="loginform">
        <form id="login" action="vntsdev.php" method="POST">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
     
      exit; }
  } else 
  { echo '  
      <section id="UserActive">
        <a href="vntsdev.php?mode=logout">';
        if (file_exists('../img_sist/'.$_SESSION['cdgusuario'].'.jpg'))
        { echo'<img id="icono" src="../img_sist/'.$_SESSION['cdgusuario'].'.jpg"  />'; 
        } else
        { echo'<img id="icono" src="../img_sistema/user.png" width="52" />'; }

      echo '</a>
        <label><i>'.$_SESSION['usuario'].'</i>&nbsp;</label>       
      </section>'; }

  $sistModuloPermiso = sistPermiso('2300', $_SESSION['cdgusuario']);
  if (substr($sistModuloPermiso,0,1) != 'r')
  { echo '
      <div id="formulario">        
        <label id="bloque">Ventas</label><br>
        <label id="modulo">'.utf8_decode('Devoluciones').'</label><hr>
        <div align="center"><h2>'.utf8_decode('Módulo reservado para usuarios autorizados').'</h2></div>
      </div>
    </div>
  </body>
</html>';
    exit; }

  // Remover una imagen
  if ($_GET['delimage'])
  { if (substr($sistModuloPermiso,0,3) == 'rwx')
    { unlink("../img_vnts/".$_GET['delimage'].".jpg"); }

    $_GET['cdgdev'] = SUBSTR($_GET['delimage'],0,7);
    $_GET['operacion'] = 'photo';
  }

  // Subir una imagen 
  if ($_POST[upload])
  { if (substr($sistModuloPermiso,0,2) == 'rw')
    { //mkdir('../img_vnts', 0777);

      for ($item=1; $item<=9; $item++)
      { $imagen = '../img_vnts/'.$_POST['hideCdgDev'].$item.'.jpg';
        
        if (!file_exists($imagen))
        { if(($_FILES[fileupload][type] == "image/pjpeg") or ($_FILES[fileupload][type] == "image/jpeg"))
          { if (move_uploaded_file($_FILES[fileupload][tmp_name], $imagen))
            { break; }
          }
        } 
      }
    }

    $_GET['cdgdev'] = $_POST['hideCdgDev'];
    $_GET['operacion'] = 'photo';
  }  

  // Salvar una nueva devolución
  if ($_POST['bttnSalvar'])
  { if ($_POST['rdioTipoCodigo'] == '3')
    { // Buscar como paquete
      $prodPaqueteSelect = $link->query("
        SELECT * FROM prodpaquete
         WHERE cdgpaquete = '".$_POST['textCodigo']."'");

      if ($prodPaqueteSelect->num_rows > 0)
      { $regProdPaquete = $prodPaqueteSelect->fetch_object(); 
        $vntsDev_cdgempaque = $regProdPaquete->cdgempaque; 

        $alptEmpaqueSelect = $link->query("
          SELECT * FROM alptempaque
           WHERE cdgempaque = '".$vntsDev_cdgempaque."'");

        if ($alptEmpaqueSelect->num_rows > 0)
        { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object();

          $vntsDev_cdgembarque = $regAlptEmpaque->cdgembarque;
          $vntsDev_cdgproducto = $regAlptEmpaque->cdgproducto;
          $vntsDev_tpoempaque = $regAlptEmpaque->tpoempaque; }
      }      
    }

    if ($_POST['rdioTipoCodigo'] == '2')
    { // Buscar como rollo
      $prodRolloSelect = $link->query("
        SELECT * FROM prodrollo
         WHERE cdgrollo = '".$_POST['textCodigo']."'");

      if ($prodRolloSelect->num_rows > 0)
      { $regProdRollo = $prodRolloSelect->fetch_object(); 
        $vntsDev_cdgempaque = $regProdRollo->cdgempaque;

        $alptEmpaqueSelect = $link->query("
          SELECT * FROM alptempaque
           WHERE cdgempaque = '".$vntsDev_cdgempaque."'");

        if ($alptEmpaqueSelect->num_rows > 0)
        { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object(); 

          $vntsDev_cdgembarque = $regAlptEmpaque->cdgembarque; 
          $vntsDev_cdgproducto = $regAlptEmpaque->cdgproducto; 
          $vntsDev_tpoempaque = $regAlptEmpaque->tpoempaque; }
      }      
    }

    if ($_POST['rdioTipoCodigo'] == '1')
    { // Buscar como empaque
      $alptEmpaqueSelect = $link->query("
        SELECT * FROM alptempaque
         WHERE cdgempaque = '".$_POST['textCodigo']."'");

      if ($alptEmpaqueSelect->num_rows > 0)
      { $regAlptEmpaque = $alptEmpaqueSelect->fetch_object(); 

        $vntsDev_cdgembarque = $regAlptEmpaque->cdgembarque; 
        $vntsDev_cdgproducto = $regAlptEmpaque->cdgproducto; 
        $vntsDev_tpoempaque = $regAlptEmpaque->tpoempaque; }
    }

    if ($_POST['rdioTipoCodigo'] == '0')
    { echo '<h1>Embarque</h1><br/>';
      // Buscar como embarque
      $vntsEmbarqueSelect = $link->query("
        SELECT * FROM vntsembarque
         WHERE cdgembarque = '".$_POST['textCodigo']."'");

      if ($vntsEmbarqueSelect->num_rows > 0)
      { $regVntsEmbarque = $vntsEmbarqueSelect->fetch_object(); 

        $vntsDev_cdgembarque = $regVntsEmbarque->cdgembarque; 
        $vntsDev_cdgproducto = $regVntsEmbarque->cdgproducto; 
        $vntsDev_tpoempaque = $regVntsEmbarque->cdgempaque; }      
    }

    if ($vntsDev_cdgembarque != '')
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { for ($id = 1; $id <= 999; $id++)
        { $vntsDev_cdgdev = SUBSTR($_POST['dateFchDevolucion'],2,2).SUBSTR($_POST['dateFchDevolucion'],5,2).str_pad($id,3,'0',STR_PAD_LEFT); 

          $link->query("
            INSERT INTO vntsdev
              (cdgembarque, fchcaptura, fchdocumento, cdgproducto, tpoempaque, cdgdev)
            VALUES
              ('".$vntsDev_cdgembarque."', NOW(), '".$_POST['dateFchDevolucion']."', '".$vntsDev_cdgproducto."', '".$vntsDev_tpoempaque."', '".$vntsDev_cdgdev."')"); 

          if ($link->affected_rows > 0)
          { break; } 
        }
      }
    }

    if ($_POST['hideCdgDev'])
    { $link->query("
        UPDATE vntsdev
           SET observacion = '".$_POST['textObsevacion']."',
               cdgcontacto = '".$_POST['slctContacto']."'
         WHERE cdgdev = '".$_POST['hideCdgDev']."' AND
               sttdev = '1'"); }
  }

  if ($_GET['operacion'])
  { switch ($_GET['operacion']) {
      case 'newitem':
        # code...
        echo '

      <div class="bloque">
        <form method="POST" action="vntsdev.php">  
          <article class="subbloque">
            <label class="modulo_listado">Devoluciones</label>
          </article>
        
          <section class="subbloque">        
            <article>
              <label>Fecha de la devolución</label><br/>
              <input type="date" id="dateFchDevolucion" name="dateFchDevolucion" value="" required />
            </article>

            <article>
              <label>Código</label><br/>
              <input type="text" id="textCodigo" name="textCodigo" value="" placeholder="Código" required />
            </article>

            <article>
              <label>Tipo de código</label><br/>
              <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="0"> <b>Embarque</b>
              <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="1"> <b>Empaque</b>
              <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="2"> <b>Rollo</b>
              <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="3"> <b>Paquete</b>
            </article>
            
            <article>
              <br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form> 
      </div>';

/*
    <div id="formulario">
      <a href="vntsdev.php"><img id="icono" src="../img_sistema/barcode.png" width="54" /></a>
      <label id="bloque">Ventas</label><br>
      <label id="modulo">'.utf8_decode('Devoluciones').'</label><br>
      <label id="operacion">Nuevo registro</label><br>
      <hr>
      <form method="post" action="vntsdev.php">  
        <label>'.utf8_decode('Fecha documento').'</label><br>
        <input type="date" id="dateFchDevolucion" name="dateFchDevolucion" value="" required="required" /><br>
        <label for="labelcode">'.utf8_decode('Código').'</label><br>
        <input type="text" id="textCodigo" name="textCodigo" value="" style="width:160px;text-align:center;font-size:20px;font-weight:bold" placeholder="CODIGO" required="required" /><br>
        <label for="labelorigen">'.utf8_decode('Tipo de código').'</label><br>
        <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="0"> <b>'.utf8_decode('Envío | Embarque').'</b><br>
        <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="1"> <b>'.utf8_decode('Empaque').'</b><br>
        <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="2"> <b>'.utf8_decode('Rollo').'</b><br>
        <input type="radio" name="rdioTipoCodigo" id="rdioTipoCodigo" value="3"> <b>'.utf8_decode('Paquete').'</b><br>
        <input type="submit" value="Salvar" id="bttnSalvar" name="bttnSalvar" />
      </form>
    </div>
*/      
        break;

      case 'view':
        # code...
        $queryselectdevolucion = $link->query("
        SELECT pdtoimpresion.impresion,
               pdtoempaque.empaque,
               vntsdev.cdgembarque,
               vntsdev.fchrecepcion,
               vntsdev.fchdocumento,
               vntsdev.observacion,
               vntsdev.cdgcontacto,
               vntsdev.cdgdev,
               vntsdev.sttdev,
               vntssucursal.sucursal,
               vntssucursal.cdgsucursal,
               vntsoc.oc
          FROM pdtoimpresion,
               pdtoempaque,
               vntsdev,
               vntsembarque,
               vntssucursal,
               vntsoc,
               vntsoclote
         WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
                pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
               (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
                vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
               (vntsembarque.cdglote = vntsoclote.cdglote AND
                vntsoclote.cdgoc = vntsoc.cdgoc) AND
                vntsdev.cdgdev = '".$_GET['cdgdev']."'
       ORDER BY vntsdev.cdgdev");

        if ($queryselectdevolucion->num_rows > 0)
        { $regdevolucion = $queryselectdevolucion->fetch_object();

          echo '
      <div class="bloque">
        <form method="post" action="vntsdev.php">
          <input type="hidden" id="hideCdgDev" name="hideCdgDev" value="'.$regdevolucion->cdgdev.'" readonly="readonly" />

          <article class="subbloque">
            <label class="modulo_listado">Devoluciones</label>
          </article>
        
          <section class="subbloque">        
            <article>
              <label><b>Folio</b></label><br/>
              <label>'.$regdevolucion->cdgdev.'</label>
            </article>

            <article>
              <label><b>Fecha documento</b></label><br/>
              <label>'.$regdevolucion->fchdocumento.'</label>
            </article><br/>

            <article>
              <label><b>Embarque</b></label><br/>
              <label>'.$regdevolucion->cdgembarque.'</label>
            </article>

            <article>
              <label><b><a href="../sm_ventas/vntsSucursal.php?cdgsucursal='.$regdevolucion->cdgsucursal.'">Sucursal</a></b></label><br/>
              <label>'.$regdevolucion->sucursal.'</label>
            </article><br/>

            <article>
              <label><b>O.C.</b></label><br/>
              <label>'.$regdevolucion->oc.'</label>
            </article>

            <article>
              <label><b>Producto</b></label><br/>
              <label>'.$regdevolucion->impresion.'</label>
            </article>

            <article>
              <label><b>Presentación</b></label><br/>
              <label>'.$regdevolucion->empaque.'</label>
            </article><br/><br/>

            <article>
              <label><b>Referencia</b></label><br/>
              <input type="text" id="textObsevacion" name="textObsevacion" value="'.$regdevolucion->observacion.'" placeholder="Causa" required />
            </article>

            <article>
              <label><b>Quien reporta</b></label><br/>
              <select id="slctContacto" name="slctContacto" required>
                <option value="">-</option>';

          $vntsContactoSelect = $link->query("
            SELECT * FROM vntscontacto
             WHERE cdgcliente = '".$regdevolucion->cdgsucursal."'");

          if ($vntsContactoSelect->num_rows > 0)
          { while ($regcontacto = $vntsContactoSelect->fetch_object())
            { if ($regcontacto->cdgcontacto == $regdevolucion->cdgcontacto)
              { echo '
                <option value="'.$regcontacto->cdgcontacto.'" selected="selected" >'.$regcontacto->contacto.' - '.$regcontacto->puesto.'</option>';}
              else
              { echo '
                <option value="'.$regcontacto->cdgcontacto.'">'.$regcontacto->contacto.' | '.$regcontacto->puesto.'</option>'; } 
            }
          }

          echo '
              </select>              
            </article>

            <article>
              <br/>
              <input type="submit" id="bttnSalvar" name="bttnSalvar" value="Salvar" />
            </article>
          </section>
        </form> 
      </div>'; }
        break;
      
      case 'photo':
       # code...
        $vntsDevSelect = $link->query("
        SELECT pdtoimpresion.impresion,
               pdtoempaque.empaque,
               vntsdev.cdgembarque,
               vntsdev.fchrecepcion,
               vntsdev.fchdocumento,
               vntsdev.observacion,
               vntsdev.cdgdev,
               vntsdev.sttdev,
               vntssucursal.sucursal,
               vntsoc.oc
          FROM pdtoimpresion,
               pdtoempaque,
               vntsdev,
               vntsembarque,
               vntssucursal,
               vntsoc,
               vntsoclote
         WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
                pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
               (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
                vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
               (vntsembarque.cdglote = vntsoclote.cdglote AND
                vntsoclote.cdgoc = vntsoc.cdgoc) AND
                vntsdev.cdgdev = '".$_GET['cdgdev']."'
       ORDER BY vntsdev.cdgdev");

        if ($vntsDevSelect->num_rows > 0)
        { $regVntsDev = $vntsDevSelect->fetch_object();

          echo '
    <div id="formulario">
      <a href="vntsdev.php"><img id="icono" src="../img_sistema/camera.png" width="54" /></a>
      <label id="bloque">Ventas</label><br>
      <label id="modulo">'.utf8_decode('Devoluciones').'</label><br>
      <label id="operacion">Asignar imagenes</label><br>
      <hr>
      <form enctype="multipart/form-data" method="post" action="vntsdev.php">        
        <label><b>Folio</b> </label>
        <label style="font-size: 24px">'.$regVntsDev->cdgdev.'</label>
        <input type="hidden" id="hideCdgDev" name="hideCdgDev" value="'.$regVntsDev->cdgdev.'" readonly="readonly" /><br>        
        <label><b>Fecha documento</b> '.$regVntsDev->fchdocumento.'</label><br>
        <label><b>'.utf8_decode('Fecha recepción').'</b> '.$regVntsDev->fchrecepcion.'</label><br>
        <label><b>Embarque</b> '.$regVntsDev->cdgembarque.'</label><br>
        <label><b>Sucursal</b> '.$regVntsDev->sucursal.'</label><br>
        <label><b>O.C.</b> '.$regVntsDev->oc.'</label><br>
        <label><b>Producto</b> '.$regVntsDev->impresion.'</label><br>
        <label><b>'.utf8_decode('Presentación').'</b> '.$regVntsDev->empaque.'</label></br>
        <label><b>Observaciones</b><br><i>'.$regVntsDev->observacion.'</i></label><br>
        <label><b>Elegir imagenes</b></label><br>
        <input type="file" id="fileupload" name="fileupload" accept="image/jpeg" /><br>
        <input type="submit" value="Subir imagen" id="upload" name="upload" />';

          for ($item=1; $item<=9; $item++)
          { if (file_exists('../img_vnts/'.$regVntsDev->cdgdev.$item.'.jpg'))
            { if ($regVntsDev->sttdev == 1)
              { if (substr($sistModuloPermiso,0,3) == 'rwx')
                { echo '
        <a href="vntsdev.php?proceso=photo&cdgdev='.$regVntsDev->cdgdev.'&delimage='.$regVntsDev->cdgdev.$item.'">'.$_recycle_bin.'</a><br>'; }
              }
              
              echo'
        <img id="logo" src="../img_vnts/'.$regVntsDev->cdgdev.$item.'.jpg" /><br>';
            } 
          }

          echo '        
      </form>
    </div>'; }     
        break;

      case 'openbox':
        # code...
        if ($_GET['cdgempaque'])
        { $link->query("
            UPDATE alptempaque
               SET cdgdev = '".$_GET['cdgdev']."'
            WHERE cdgempaque = '".$_GET['cdgempaque']."'");

          if ($link->affected_rows > 0)          
          { 
          } else
          { $link->query("
              UPDATE alptempaque
                 SET cdgdev = ''
              WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                    cdgdev = '".$_GET['cdgdev']."'"); 


            if ($_GET['tpoempaque'] == 'C')
            { $link->query("
                UPDATE alptempaquep
                   SET cdgdev = '',
                       dev = 0,
                       cdgpaquete = SUBSTR(cdgpaquete,1,12)
                WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                      cdgdev = '".$_GET['cdgdev']."'"); }

            if ($_GET['tpoempaque'] == 'Q')
            { $link->query("
                UPDATE alptempaquer
                   SET cdgdev = '',
                       dev = 0,
                       cdgrollo = SUBSTR(cdgrollo,1,12)
                WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                      cdgdev = '".$_GET['cdgdev']."'"); }
          }
        }

        if ($_GET['tpoempaque'] == 'C')
        { $imgempaque = 'src="../img_sistema/box.png"';          
          $imgempaquedev = 'src="../img_sistema/box_open.png"';

          $queryselectempaque = $link->query("
            SELECT alptempaque.cdgempaque,
                   alptempaque.tpoempaque,
                   alptempaque.noempaque,
               SUM(alptempaquep.cantidad) AS cantidad
              FROM vntsdev,
                   alptempaque,
                   alptempaquep
             WHERE vntsdev.cdgdev = '".$_GET['cdgdev']."' AND
                   vntsdev.cdgembarque = alptempaque.cdgembarque AND
                   alptempaque.cdgempaque = alptempaquep.cdgempaque AND
                   alptempaque.cdgdev = ''
          GROUP BY alptempaque.cdgempaque"); 

          $queryselectempaqued = $link->query("
            SELECT alptempaque.cdgempaque,
                   alptempaque.tpoempaque,
                   alptempaque.noempaque,
               SUM(alptempaquep.dev) AS cantidad
              FROM vntsdev,
                   alptempaque,
                   alptempaquep
             WHERE vntsdev.cdgdev = '".$_GET['cdgdev']."' AND
                   vntsdev.cdgembarque = alptempaque.cdgembarque AND
                   alptempaque.cdgempaque = alptempaquep.cdgempaque AND
                   alptempaque.cdgdev = vntsdev.cdgdev
          GROUP BY alptempaque.cdgempaque"); } 

        if ($_GET['tpoempaque'] == 'Q')
        { $imgempaque = 'src="../img_sistema/database_active.png"';
          $imgempaquedev = 'src="../img_sistema/database_inactive.png"';

          $queryselectempaque = $link->query("
            SELECT alptempaque.cdgempaque,
                   alptempaque.tpoempaque,
                   alptempaque.noempaque,
               SUM(alptempaquer.cantidad) AS cantidad
              FROM vntsdev,
                   alptempaque,
                   alptempaquer
             WHERE vntsdev.cdgdev = '".$_GET['cdgdev']."' AND
                   vntsdev.cdgembarque = alptempaque.cdgembarque AND
                   alptempaque.cdgempaque = alptempaquer.cdgempaque AND
                   alptempaque.cdgdev = ''
          GROUP BY alptempaque.cdgempaque"); 

          $queryselectempaqued = $link->query("
            SELECT alptempaque.cdgempaque,
                   alptempaque.tpoempaque,
                   alptempaque.noempaque,
               SUM(alptempaquer.dev) AS cantidad
              FROM vntsdev,
                   alptempaque,
                   alptempaquer
             WHERE vntsdev.cdgdev = '".$_GET['cdgdev']."' AND
                   vntsdev.cdgembarque = alptempaque.cdgembarque AND
                   alptempaque.cdgempaque = alptempaquer.cdgempaque AND
                   alptempaque.cdgdev = vntsdev.cdgdev
          GROUP BY alptempaque.cdgempaque"); }

        echo '
    <div id="listado">
      <a href="vntsdev.php"><img id="icono" '.$imgempaque.' width="54" /></a>
      <label id="bloque">Ventas</label><br>
      <label id="modulo">'.utf8_decode('Devoluciones').'</label><br>
      <label id="operacion">'.utf8_decode('Empaques de la devolución').'</label><br>      
      <form method="post" action="vntsdev.php"> ';          

        if ($queryselectempaque->num_rows > 0)
        { echo '<hr>';
          while ($regempaque = $queryselectempaque->fetch_object())
          { echo '          
          <article class="empaque">            
            <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=openbox&cdgempaque='.$regempaque->cdgempaque.'">
              <img id="icono" '.$imgempaque.' width="32" /></a>
            <label style="font-size: 32px"><b>'.$regempaque->tpoempaque.'</b>'.$regempaque->noempaque.'</label><br>            
            <label><b>Piezas</b> '.$regempaque->cantidad.' <i>mlls</i></label>
          </article>'; }
        }

        if ($queryselectempaqued->num_rows > 0)
        { echo '<hr>';
          while ($regempaque = $queryselectempaqued->fetch_object())
          { echo '          
          <article class="empaque">            
            <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=openbox&cdgempaque='.$regempaque->cdgempaque.'">
              <img id="icono" '.$imgempaquedev.' width="32" /></a>
            <label style="font-size: 32px"><b>'.$regempaque->tpoempaque.'</b>'.$regempaque->noempaque.'</label><br>
            <label><b>Piezas</b> 
              <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=detail&cdgempaque='.$regempaque->cdgempaque.'">'.$regempaque->cantidad.'</a> <i>mlls</i></label>
          </article>'; }
        }        

        echo '
      </form>
    </div>';
        break;

      case 'detail':
        # code...

        if ($_GET['tpoempaque'] == 'C')
        { $imgempaque = 'src="../img_sistema/box_open.png"';
          
          if ($_GET[apply])
          { $queryselectcontenido = $link->query("
              SELECT alptempaquep.cantidad,
                     alptempaquep.cdgpaquete AS barcode
                FROM alptempaquep
               WHERE alptempaquep.cdgempaque = '".$_GET['cdgempaque']."' AND
                     cdgdev = ''");

            if ($queryselectcontenido->num_rows > 0)
            { while ($regcontenido = $queryselectcontenido->fetch_object())
              { $link->query("
                  UPDATE alptempaquep
                     SET cdgdev = '".$_GET['cdgdev']."',
                         dev = '".$_GET[$regcontenido->barcode.'dev']."',
                         cdgpaquete = CONCAT(cdgpaquete, ' ".$_GET['cdgdev']."')
                   WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                         cdgpaquete = '".$regcontenido->barcode."' AND
                         cantidad > '".$_GET[$regcontenido->barcode.'dev']."' AND                        
                         cdgdev = ''"); }
            }
          }

          if ($_GET[applyall])
          { $link->query("
              UPDATE alptempaquep
                 SET cdgdev = '".$_GET['cdgdev']."',
                     dev = cantidad,
                     cdgpaquete = CONCAT(cdgpaquete, ' ".$_GET['cdgdev']."')
               WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                     cdgdev = ''"); }

          if ($_GET[barcode])
          { $link->query("
              UPDATE alptempaquep
                 SET cdgdev = '".$_GET['cdgdev']."',
                     dev = cantidad,
                     cdgpaquete = CONCAT(cdgpaquete, ' ".$_GET['cdgdev']."')
               WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                     cdgpaquete = '".$_GET[barcode]."' AND
                     cdgdev = ''");

            if ($link->affected_rows > 0)
            { 
            } else
            { $link->query("
                UPDATE alptempaquep
                   SET cdgdev = '',
                       dev = 0,
                       cdgpaquete = SUBSTR(cdgpaquete,1,12)
                 WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                       cdgpaquete = '".$_GET[barcode]."' AND
                       cdgdev = '".$_GET['cdgdev']."'"); }
          }

          $queryselectcontenido = $link->query("
            SELECT alptempaquep.cantidad,
                   alptempaquep.cdgpaquete AS barcode
              FROM alptempaquep
             WHERE alptempaquep.cdgempaque = '".$_GET['cdgempaque']."' AND
                   cdgdev = ''");

          $queryselectcontenidod = $link->query("
            SELECT alptempaquep.dev,
                   alptempaquep.cdgpaquete AS barcode
              FROM alptempaquep
             WHERE alptempaquep.cdgempaque = '".$_GET['cdgempaque']."' AND
                   cdgdev = '".$_GET['cdgdev']."'");
        }

        if ($_GET['tpoempaque'] == 'Q')
        { $imgempaque = 'src="../img_sistema/database_active.png"';

          if ($_GET[apply])
          { $queryselectcontenido = $link->query("
              SELECT alptempaquer.cantidad,
                     alptempaquer.cdgrollo AS barcode
                FROM alptempaquer
               WHERE alptempaquer.cdgempaque = '".$_GET['cdgempaque']."' AND
                     cdgdev = ''");

            if ($queryselectcontenido->num_rows > 0)
            { while ($regcontenido = $queryselectcontenido->fetch_object())
              { $link->query("
                  UPDATE alptempaquer
                     SET cdgdev = '".$_GET['cdgdev']."',
                         dev = '".$_GET[$regcontenido->barcode.'dev']."',
                         cdgrollo = CONCAT(cdgrollo, ' ".$_GET['cdgdev']."')
                   WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                         cdgrollo = '".$regcontenido->barcode."' AND
                         cantidad > '".$_GET[$regcontenido->barcode.'dev']."' AND                        
                         cdgdev = ''"); }
            }
          }

          if ($_GET[applyall])
          { $link->query("
              UPDATE alptempaquer
                 SET cdgdev = '".$_GET['cdgdev']."',
                     dev = cantidad,
                     cdgrollo = CONCAT(cdgrollo, ' ".$_GET['cdgdev']."')
              WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                    cdgdev = ''"); }

          if ($_GET[barcode])
          { $link->query("
              UPDATE alptempaquer
                 SET cdgdev = '".$_GET['cdgdev']."',
                     dev = cantidad,
                     cdgrollo = CONCAT(cdgrollo, ' ".$_GET['cdgdev']."')
              WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                    cdgrollo = '".$_GET[barcode]."' AND
                    cdgdev = ''");

            if ($link->affected_rows > 0)
            { 
            } else
            { $link->query("
                UPDATE alptempaquer
                   SET cdgdev = '',
                       dev = 0,
                       cdgrollo = SUBSTR(cdgrollo,1,12)
                WHERE cdgempaque = '".$_GET['cdgempaque']."' AND
                      cdgrollo = '".$_GET[barcode]."' AND
                      cdgdev = '".$_GET['cdgdev']."'"); }
          }

          $queryselectcontenido = $link->query("
            SELECT alptempaquer.nocontrol,
                   alptempaquer.cantidad,
                   alptempaquer.cdgrollo AS barcode
              FROM alptempaquer
             WHERE alptempaquer.cdgempaque = '".$_GET['cdgempaque']."' AND
                   cdgdev = ''");

          $queryselectcontenidod = $link->query("
            SELECT alptempaquer.nocontrol,
                   alptempaquer.dev,
                   alptempaquer.cdgrollo AS barcode
              FROM alptempaquer
             WHERE alptempaquer.cdgempaque = '".$_GET['cdgempaque']."' AND
                   cdgdev = '".$_GET['cdgdev']."'");
        }

        echo '
    <div id="listado">
      <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=openbox">
        <img id="icono" '.$imgempaque.' width="54" /></a>
      <label id="bloque">Ventas</label><br>
      <label id="modulo">'.utf8_decode('Devoluciones').'</label><br>
      <label id="operacion">'.utf8_decode('Detalle de la devolución').'</label><br>      
      <form method="GET" action="vntsdev.php">        
        <input type="hidden" id="cdgdev" name="cdgdev" value="'.$_GET['cdgdev'].'" />
        <input type="hidden" id="tpoempaque" name="tpoempaque" value="'.$_GET['tpoempaque'].'" />
        <input type="hidden" id="cdgempaque" name="cdgempaque" value="'.$_GET['cdgempaque'].'" />
        <input type="hidden" id="operacion" name="operacion" value="detail" />';

      if ($queryselectcontenido->num_rows > 0)
      { echo '<hr>';
        while ($regcontenido = $queryselectcontenido->fetch_object())
        { echo '
        <article class="empaque">
          <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=detail&cdgempaque='.$_GET['cdgempaque'].'&barcode='.$regcontenido->barcode.'">
            <img id="icono" src="../img_sistema/barcode.png" width="32" /></a>
          <input type="text" style="width:90px; text-align:right" id="'.$regcontenido->barcode.'dev" name="'.$regcontenido->barcode.'dev" value='.$regcontenido->cantidad.' /><br>
          <label style="font-size: 200%">'.$regcontenido->nocontrol.'</label><br>
          <label>'.$regcontenido->barcode.'</label><br>          
          <label style="font-size: 120%"><b>Piezas</b> '.$regcontenido->cantidad.' <i>Mlls</i></label>
        </article>'; }
      } 

      if ($queryselectcontenidod->num_rows > 0)
      { echo '<hr>';
        while ($regcontenido = $queryselectcontenidod->fetch_object())
        { echo '
        <article class="empaque">
          <a href="vntsdev.php?cdgdev='.$_GET['cdgdev'].'&tpoempaque='.$_GET['tpoempaque'].'&operacion=detail&cdgempaque='.$_GET['cdgempaque'].'&barcode='.$regcontenido->barcode.'">
            <img id="icono" src="../img_sistema/warning_blue.png" width="32" /></a>
          <label style="font-size: 200%">'.$regcontenido->nocontrol.'</label><br>
          <label>'.SUBSTR($regcontenido->barcode,1,12).'</label><br>
          <label style="font-size: 150%"><b>Piezas</b> '.$regcontenido->dev.' <i>Mlls</i></label>
        </article>'; }
      }

        echo '
        <hr>
        <input type="submit" value="Aplicar modificaciones" id="apply" name="apply" />
        <input type="submit" value="Aplicar al 100%" id="applyall" name="applyall" />
      </form>
    </div>';

        break;

      default:
        # code...
        break; }
  } else
  { if ($_GET['cdgdev'])
    { if (substr($sistModuloPermiso,0,2) == 'rw')
      { switch ($_GET['sttdev']) {
          case '0':            
            if (substr($sistModuloPermiso,0,2) == 'rw')
            { $link->query("
                UPDATE vntsdev
                   SET sttdev = '1'
                 WHERE cdgdev = '".$_GET['cdgdev']."'"); }

            break;

          case '1':
            if (substr($sistModuloPermiso,0,2) == 'rw')
            { $link->query("
                UPDATE vntsdev
                   SET sttdev = '0'
                 WHERE cdgdev = '".$_GET['cdgdev']."'"); }

            break;

          case '8':
            if (substr($sistModuloPermiso,0,3) == 'rwx')
            { $link->query("
                UPDATE vntsdev
                   SET sttdev = '8'
                 WHERE cdgdev = '".$_GET['cdgdev']."' AND
                       observacion != '' AND 
                       cdgcontacto != ''");

              if ($link->affected_rows > 0)
              { if ($_GET['tpoempaque'] == 'C')
                { $alptEmpaquePSelect = $link->query("
                    SELECT SUBSTR(cdgpaquete,1,12) AS cdgpaquete 
                      FROM alptempaquep
                     WHERE SUBSTR(cdgpaquete,14,7) = '".$_GET['cdgdev']."'");

                  if ($alptEmpaquePSelect->num_rows > 0)
                  { while ($regAlptEmpaqueP = $alptEmpaquePSelect->fetch_object())
                    { $link->query("
                      UPDATE prodpaquete
                         SET obs = CONCAT('".date('Y-m-d H:i:s')."\nDevolución ".$_GET['cdgdev']."\n', obs),
                             cdgtemporal = cdgempaque,
                             cdgempaque = '',
                             sttpaquete = '1'
                       WHERE cdgpaquete = '".$regAlptEmpaqueP->cdgpaquete."'"); 

                      $link->query("
                      UPDATE prodpaqueteope
                         SET cdgoperacion = CONCAT(cdgoperacion, ' ".$_GET['cdgdev']."'),
                       WHERE cdgpaquete = '".$regAlptEmpaqueR->cdgpaquete."' AND
                             cdgoperacion != '50001'"); }
                  }
                }

                if ($_GET['tpoempaque'] == 'Q')
                { $alptEmpaqueRSelect = $link->query("
                    SELECT SUBSTR(cdgrollo,1,12) AS cdgrollo 
                      FROM alptempaquer
                     WHERE SUBSTR(cdgrollo,14,7) = '".$_GET['cdgdev']."'");

                  if ($alptEmpaqueRSelect->num_rows > 0)
                  { while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
                    { $link->query("
                      UPDATE prodrollo
                         SET obs = CONCAT('".date('Y-m-d H:i:s')."\nDevolución ".$_GET['cdgdev']."\n', obs),
                             cdgtemporal = cdgempaque,
                             cdgempaque = '',
                             sttrollo = '1'
                       WHERE cdgrollo = '".$regAlptEmpaqueR->cdgrollo."'"); 

                      $link->query("
                      UPDATE prodrolloope
                         SET cdgoperacion = CONCAT(cdgoperacion, ' ".$_GET['cdgdev']."'),
                       WHERE cdgrollo = '".$regAlptEmpaqueR->cdgrollo."' AND
                             cdgoperacion != '40001'"); }
                  }
                }
              }
            }

            break;            

          case 'O':
            if (substr($sistModuloPermiso,0,3) == 'rwx')
            { $link->query("
                DELETE FROM vntsdev
                 WHERE cdgdev = '".$_GET['cdgdev']."' AND
                       sttdev = '0'"); 

              if ($link->affected_rows > 0)
              { $link->query("
                  UPDATE alptempaque
                     SET cdgdev = ''
                   WHERE cdgdev = '".$_GET['cdgdev']."'");

                $link->query("
                  UPDATE alptempaquep
                     SET cdgdev = '',
                         dev = 0,
                         cdgpaquete = SUBSTR(cdgpaquete,1,12)
                   WHERE cdgdev = '".$_GET['cdgdev']."'"); 

                $link->query("
                  UPDATE alptempaquer
                     SET cdgdev = '',
                         dev = 0,
                         prodrollo = SUBSTR(prodrollo,1,12)
                   WHERE cdgdev = '".$_GET['cdgdev']."'"); }
              
              $mask = "../img_vnts/".$_GET['cdgdev']."*.jpg";
              array_map("unlink", glob( $mask ) ); }
            break;

          case 'U':
            if (substr($sistModuloPermiso,0,3) == 'rwx')
            { $vntsDevSelect = $link->query("
                SELECT * FROM vntsdev
                 WHERE cdgdev = '".$_GET['cdgdev']."'");

              if ($vntsDevSelect->num_rows > 0)
              { $vntsDevCancelacion = true;

                $regVntsDev = $vntsDevSelect->fetch_object();

                if ($regVntsDev->tpoempaque == 'C')
                { // Cancelar la devolución de paquetes
                  $alptEmpaquePSelect = $link->query("
                    SELECT SUBSTR(cdgpaquete,1,12) AS cdgpaquete 
                      FROM alptempaquep
                     WHERE SUBSTR(cdgpaquete,14,7) = '".$_GET['cdgdev']."'");

                  if ($alptEmpaquePSelect->num_rows > 0)
                  { $item = 0;
                    while ($regAlptEmpaqueP = $alptEmpaquePSelect->fetch_object())
                    { $item++;

                      $vntsCancelarDev_cdgpaquete[$item] = $regAlptEmpaqueP->cdgpaquete; }
                  }

                  $nPaquetesCD = $item;

                  for ($item = 1; $item <= $nPaquetesCD; $item++)
                  { // Cancelar la devolución de paquetes
                    $link->query("
                      UPDATE prodpaquete
                         SET obs = CONCAT('".date('Y-m-d H:i:s')."\Devolución CANCELADA ".$_GET['cdgdev']."\n', obs),
                             cdgempaque = cdgtemporal,
                             sttpaquete = '9'
                       WHERE cdgpaquete = '".$vntsCancelarDev_cdgpaquete[$item]."' AND 
                             sttpaquete = '1'"); 

                    if ($link->affected_rows > 0)
                    { $link->query("
                        UPDATE prodpaqueteope
                           SET cdgoperacion = SUBSTR(cdgoperacion,1,5),
                         WHERE cdgpaquete = '".$vntsCancelarDev_cdgpaquete[$item]."' AND
                               cdgoperacion != '50001'"); 
                    } else
                    { $vntsDevCancelacion = false; }
                  }
                }

                if ($regVntsDev->tpoempaque == 'Q')
                { // Cancelar la devolución de rollos
                  $alptEmpaqueRSelect = $link->query("
                    SELECT SUBSTR(cdgrollo,1,12) AS cdgrollo 
                      FROM alptempaquer
                     WHERE SUBSTR(cdgrollo,14,7) = '".$_GET['cdgdev']."'");

                  if ($alptEmpaqueRSelect->num_rows > 0)
                  { $item = 0;
                    while ($regAlptEmpaqueR = $alptEmpaqueRSelect->fetch_object())
                    { $item++;

                      $vntsCancelarDev_cdgrollo[$item] = $regAlptEmpaqueR->cdgrollo; }
                  }

                  $nRolloCD = $item;

                  for ($item = 1; $item <= $nRolloCD; $item++)
                  { // Cancelar la devolución de paquetes
                    $link->query("
                      UPDATE prodrollo
                         SET obs = CONCAT('".date('Y-m-d H:i:s')."\nDevolución CANCELADA ".$_GET['cdgdev']."\n', obs),
                             cdgempaque = cdgtemporal,
                             sttrollo = '9'
                       WHERE cdgrollo = '".$vntsCancelarDev_cdgrollo[$item]."' AND
                             sttrollo = '1'"); 

                    if ($link->affected_rows > 0)
                    { $link->query("
                        UPDATE prodrolloope
                         SET cdgoperacion = SUBSTR(cdgoperacion,1,5),
                       WHERE cdgrollo = '".$vntsCancelarDev_cdgrollo[$item]."' AND
                             cdgoperacion != '40001'"); 
                    } else
                    { $vntsDevCancelacion = false; }
                  } 

                }                
              }

              if ($vntsDevCancelacion == true)
              { $link->query("
                  UPDATE vntsdev
                     SET sttdev = '1'
                   WHERE cdgdev = '".$_GET['cdgdev']."' AND
                         sttdev = '8'"); }
            }
            break;

          default:
            # code...
            break; }
      }
    }
/*
    echo '
    <div id="listado">
      <a href="vntsdev.php?operacion=newitem"><img id="icono" src="../img_sistema/barcode.png" width="54" /></a>
      <label id="bloque">Ventas</label><br>
      <label id="modulo">'.utf8_decode('Devoluciones').'&nbsp;</label>';

    $queryselectdevolucion = $link->query("
      SELECT vntsdev.cdgembarque,
             vntsdev.tpoempaque,
             pdtoimpresion.impresion,
             pdtoempaque.empaque,
             pdtoempaque.cdgempaque,
             pdtoempaque.cdgempaque,
             vntsdev.fchdocumento,
             vntsdev.cdgdev,
             vntsdev.sttdev,
             vntssucursal.sucursal,
             vntsoc.oc,
             vntsembarque.cantidad
        FROM pdtoimpresion,
             pdtoempaque,
             vntsdev,
             vntsembarque,
             vntssucursal,
             vntsoc,
             vntsoclote
       WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
              pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
             (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
              vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
             (vntsembarque.cdglote = vntsoclote.cdglote AND
              vntsoclote.cdgoc = vntsoc.cdgoc) AND
              vntsdev.sttdev = '1'
     ORDER BY vntsdev.cdgdev");

    if ($queryselectdevolucion->num_rows > 0)
    { echo '<hr>'; 
      
      while ($regdevolucion = $queryselectdevolucion->fetch_object())
      { if ($regdevolucion->tpoempaque == 'C') 
        { $imgempaque = 'src="../img_sistema/box.png"'; 
          //Detalle de la devolución 
          $queryselectcantidaddev = $link->query("
            SELECT SUM(alptempaquep.dev) AS dev
              FROM alptempaquep
             WHERE alptempaquep.cdgdev = '".$regdevolucion->cdgdev."'"); }
        
        if ($regdevolucion->tpoempaque == 'Q') 
        { $imgempaque = 'src="../img_sistema/database_active.png"'; 
          // Detalle de la devolución 
          $queryselectcantidaddev = $link->query("
            SELECT SUM(alptempaquer.dev) AS dev
              FROM alptempaquer
             WHERE alptempaquer.cdgdev = '".$regdevolucion->cdgdev."'"); }
        
        echo '
      <article class="devolucion">
        <a href="vntsdev.php?operacion=view&cdgdev='.$regdevolucion->cdgdev.'"><img id="icono" '.$imgempaque.' width="32" /></a>
        <label><b>'.utf8_decode('Folio').'</b> 
          <a href="vntsdev.php?cdgdev='.$regdevolucion->cdgdev.'&sttdev='.$regdevolucion->sttdev.'">'.$regdevolucion->cdgdev.'
            <img src="../img_sistema/power_blue.png" width="16" /></a></label><br>
        <label><b>'.utf8_decode('Fecha documento').'</b> '.$regdevolucion->fchdocumento.'</label><br>
        <label><b>Embarque</b> '.$regdevolucion->cdgembarque.'</label><br>
        <label><b>Sucursal</b> '.$regdevolucion->sucursal.'</label><br>
        <label><b>O.C.</b> '.$regdevolucion->oc.'</label><br>            
        <label><b>Producto</b> 
          <a href="vntsdev.php?operacion=photo&cdgdev='.$regdevolucion->cdgdev.'">'.$regdevolucion->impresion.' 
            <img src="../img_sistema/camera.png" width="16" /></a></label><br>
        <label><b>'.utf8_decode('Presentación').'</b> 
          <a href="vntsdev.php?operacion=openbox&cdgdev='.$regdevolucion->cdgdev.'&tpoempaque='.$regdevolucion->cdgempaque.'">'.$regdevolucion->empaque.' 
            <img src="../img_sistema/barcode.png" width="16" /></a></label><br>
        <label><b>'.utf8_decode('Cantidad').'</b> '.$regdevolucion->cantidad.' <i>Mlls</i></label><br>';

        if ($queryselectcantidaddev->num_rows > 0)
        { $regcantidaddev = $queryselectcantidaddev->fetch_object(); 

          if ($regcantidaddev->dev > 0)
          { echo '
        <label><b>'.utf8_decode('Devolución').'</b> 
          <a href="vntsdev.php?cdgdev='.$regdevolucion->cdgdev.'&tpoempaque='.$regdevolucion->tpoempaque.'&sttdev=8">'.number_format($regcantidaddev->dev,3,'.','').' <i>Mlls</i> 
            <img src="../img_sistema/gear.png" width="16" /></a></label>'; }
        }

        echo'
      </article>'; }
    }

    $queryselectdevolucion = $link->query("
      SELECT vntsdev.cdgembarque,
             vntsdev.tpoempaque,
             pdtoimpresion.impresion,
             vntssucursal.sucursal,
             pdtoempaque.empaque,
             pdtoempaque.cdgempaque,
             pdtoempaque.cdgempaque,
             vntsdev.fchdocumento,
             vntsdev.cdgdev,
             vntsdev.sttdev,
             vntssucursal.sucursal,
             vntsoc.oc,
             vntsembarque.cantidad
        FROM pdtoimpresion,
             pdtoempaque,
             vntsdev,
             vntsembarque,
             vntssucursal,
             vntsoc,
             vntsoclote
       WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
              pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
             (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
              vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
             (vntsembarque.cdglote = vntsoclote.cdglote AND
              vntsoclote.cdgoc = vntsoc.cdgoc) AND
              vntsdev.sttdev = '8'
     ORDER BY vntsdev.cdgdev");

    if ($queryselectdevolucion->num_rows > 0)
    { echo '<hr>'; 
      
      while ($regdevolucion = $queryselectdevolucion->fetch_object())
      { echo '
      <article class="devolucion">
        <a href="pdf/vntsdev.php?cdgdev='.$regdevolucion->cdgdev.'"><img id="icono" src="../img_sistema/gear.png" width="32" /></a>
        <label><b>'.utf8_decode('Folio').'</b> '.$regdevolucion->cdgdev.'</label><br>
        <label><b>'.utf8_decode('Fecha documento').'</b> '.$regdevolucion->fchdocumento.'</label><br>
        <label><b>Producto</b> '.$regdevolucion->impresion.'</label>';

        echo'
      </article>'; }
    }    

    $queryselectdevolucion = $link->query("
      SELECT vntsdev.cdgembarque,
             pdtoimpresion.impresion,
             pdtoempaque.empaque,
             vntsdev.fchdocumento,
             vntsdev.cdgdev,
             vntsdev.sttdev,
             vntssucursal.sucursal,
             vntsoc.oc,
             vntsembarque.cantidad
        FROM pdtoimpresion,
             pdtoempaque,
             vntsdev,
             vntsembarque,
             vntssucursal,
             vntsoc,
             vntsoclote
       WHERE (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
              pdtoempaque.cdgempaque = vntsdev.tpoempaque) AND
             (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
              pdtoimpresion.cdgimpresion = vntsdev.cdgproducto AND
              vntsembarque.cdgsucursal = vntssucursal.cdgsucursal) AND
             (vntsembarque.cdglote = vntsoclote.cdglote AND
              vntsoclote.cdgoc = vntsoc.cdgoc) AND
              vntsdev.sttdev = '0'
     ORDER BY vntsdev.cdgdev");

    if ($queryselectdevolucion->num_rows > 0)
    { echo '<hr>';
      while ($regdevolucion = $queryselectdevolucion->fetch_object())
      { echo '
      <article class="devolucion">';

        if (substr($sistModuloPermiso,0,3) == 'rwx')
        { echo '
        <a href="vntsdev.php?cdgdev='.$regdevolucion->cdgdev.'&sttdev=O">
          <img id="icono" src="../img_sistema/recycle_bin.png" width="32" /></a>'; }

        echo '          
        <label><b>'.utf8_decode('Folio').'</b> 
          <a href="vntsdev.php?cdgdev='.$regdevolucion->cdgdev.'&sttdev='.$regdevolucion->sttdev.'">'.$regdevolucion->cdgdev.'
            <img src="../img_sistema/power_black.png" width="16" /></a></label><br>
        <label><b>'.utf8_decode('Fecha documento').'</b> '.$regdevolucion->fchdocumento.'</label><br>
        <label><b>Producto</b> '.$regdevolucion->impresion.'</label>';

        echo '
      </article>'; }
    }

      echo '
    </div>';
//*/
    

    // Listado de devoluciones
    $vntsDevSelect = $link->query("
      SELECT vntsdev.fchdocumento,
             vntsdev.cdgdev,             
             vntsdev.sttdev,
             vntsembarque.cdgembarque,
             vntsembarque.cdgsucursal,
             vntsembarque.cdgproducto,
             vntsembarque.cdgempaque,
             vntsembarque.cantidad,
             vntsoc.oc,
             pdtoimpresion.impresion,
             vntssucursal.sucursal             
        FROM vntsdev,
             vntsembarque,
             vntsoc,
             vntsoclote,
             pdtoimpresion,
             vntssucursal
      WHERE (vntsdev.cdgembarque = vntsembarque.cdgembarque AND
             vntsembarque.cdglote = vntsoclote.cdglote AND
             vntsoc.cdgoc = vntsoclote.cdgoc) AND
            (pdtoimpresion.cdgimpresion = vntsdev.cdgproducto) AND
            (vntsembarque.cdgsucursal = vntssucursal.cdgsucursal)
    ORDER BY vntsdev.sttdev,
             vntsdev.cdgdev");

    $item = 0;
    if ($vntsDevSelect->num_rows > 0)
    { while ($regVntsDev = $vntsDevSelect->fetch_object())
      { $item++;
        
        $vntsDevs_fchdocumento[$item] = $regVntsDev->fchdocumento;
        $vntsDevs_cdgdev[$item] = $regVntsDev->cdgdev;
        $vntsDevs_sttdev[$item] = $regVntsDev->sttdev;        
        $vntsDevs_cdgembarque[$item] = $regVntsDev->cdgembarque;
        $vntsDevs_cdgsucursal[$item] = $regVntsDev->sucursal;
        $vntsDevs_cdgproducto[$item] = $regVntsDev->impresion;
        $vntsDevs_tpoempaque[$item] = $regVntsDev->cdgempaque;
        $vntsDevs_cantidad[$item] = $regVntsDev->cantidad;
        $vntsDevs_oc[$item] = $regVntsDev->oc; }
    }

    $nDevoluciones = $item;

    if ($nDevoluciones > 0)
    { echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Histórico de Devoluciones</label>
        </article>
        <label>[<b>'.$nDevoluciones.'</b>] Encontrada(s)</label>
        <label><a href="vntsdev.php?operacion=newitem"><b>Agregar una nuevo registro</b></a></label>';

      for ($item = 1; $item <= $nDevoluciones; $item++)
      { echo '
        <section class="listado">
          <article style="vertical-align:top">';

        if ($vntsDevs_sttdev[$item] == '8')
        { echo '
          <a href="pdf/vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'">'.$_acrobat.'</a>
          <a href="vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'&sttdev=U">'.$_lock.'</a>'; }

        if ($vntsDevs_sttdev[$item] == '1')
        { echo '
          <a href="vntsdev.php?operacion=view&cdgdev='.$vntsDevs_cdgdev[$item].'">'.$_search.'</a>          
          <a href="vntsdev.php?operacion=view&cdgdev='.$vntsDevs_cdgdev[$item].'">'.$_forder_open.'</a>
          <a href="vntsdev.php?operacion=openbox&cdgdev='.$vntsDevs_cdgdev[$item].'&tpoempaque='.$vntsDevs_tpoempaque[$item].'">'.$_barcode.'</a>
          <a href="vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'&sttdev='.$vntsDevs_sttdev[$item].'">'.$_power_blue.'</a>'; }

        // <a href="vntsdev.php?operacion=photo&cdgdev='.$vntsDevs_cdgdev[$item].'">'.$_camera.'</a>

        if ($vntsDevs_sttdev[$item] == '0')
        { echo '
          <a href="vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'&sttdev=O">'.$_recycle_bin.'</a>
          <a href="vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'&sttdev='.$vntsDevs_sttdev[$item].'">'.$_power_black.'</a>'; }

        echo '
          </article>

          <article>
            <article style="text-align:right">
              <label>Folio</label><br/>
              <label>Fecha</label><br/>
              <label>&nbsp;</label>
            </article>

            <article>
              <label><b><i>'.$vntsDevs_cdgdev[$item].'</a></i></b></label><br/>
              <label><b>'.$vntsDevs_fchdocumento[$item].'</b></label><br/>
              <label>&nbsp;</label>
            </article>
          </article>

          <article>
            <article style="text-align:right">
              <label>Embarque</label><br/>
              <label>O.C.</label><br/>
              <label>&nbsp;</label>
            </article>

            <article>
              <label><b>'.$vntsDevs_cdgembarque[$item].'</b></label><br/>
              <label><b>'.$vntsDevs_oc[$item].'</b></label><br/>
              <label>&nbsp;</label>
            </article>            
          </article>

          <article>
            <article style="text-align:right">
              <label>Producto</label><br/>
              <label>Sucursal</label>';

        if ($vntsDevs_sttdev[$item] == '1')
        { echo '<br/>
            <a href="vntsdev.php?cdgdev='.$vntsDevs_cdgdev[$item].'&tpoempaque='.$vntsDevs_tpoempaque[$item].'&sttdev=8"><b><i>Aplicar Devolución</i></b></a>'; 
        } else
        { echo '<br/>
              <label>&nbsp;</label>'; }

        echo '
            </article>

            <article>
              <label><b>'.$vntsDevs_cdgproducto[$item].'</b></label><br/>
              <label><b>'.$vntsDevs_cdgsucursal[$item].'</b></label><br/>
              <label>&nbsp;</label>
            </article>
          </article>        
        </section>';

      }

      echo '
      </div>';
    }    
  }
 ?>
    </div>
  </body>
</html>