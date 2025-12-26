<?php  

  function conectar()
  { return new mysqli("localhost","root","MSpwd55","proton1113"); }

  function documentoA($mensaje)
  { echo '
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body>
    <br/><div align="center"><h1>'.$mensaje.'</h1></div>
  </body>
</html>'; }

  function documentoB($mensaje)
  { echo '
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../../css/global.css" media="all">
  </head>
  <body>
    <br/><div align="center"><h1>'.$mensaje.'</h1></div>
  </body>
</html>'; }
  
  function sistModulo($cdgmodulo)
  { $link_mysqli = conectar();
    $sistModuloSelect = $link_mysqli->query("
      SELECT * FROM sistmodulo
      WHERE cdgmodulo = '".$cdgmodulo."'
      AND sttmodulo >= '1'"); 

    if ($sistModuloSelect->num_rows > 0)
    { $regSistModulo = $sistModuloSelect->fetch_object(); 

      $sistModulo_modulo = $regSistModulo->modulo; }
    else
    { $sistModulo_modulo = $regSistModulo->cdgmodulo; }

    return $sistModulo_modulo; }

  function sistPermiso($cdgmodulo, $cdgusuario)
  { $link_mysqli = conectar();
    $sistPermisoSelect = $link_mysqli->query("
      SELECT sistpermiso.permiso
      FROM sistpermiso,
        sistperfil,
        rechempleado
      WHERE (sistpermiso.cdgmodulo = '".$cdgmodulo."' AND 
        sistpermiso.cdgperfil = sistperfil.cdgperfil) AND 
       (rechempleado.cdgempleado = '".$cdgusuario."' AND 
        sistperfil.cdgperfil = rechempleado.cdgperfil)");

    if ($sistPermisoSelect->num_rows > 0)
    { $regSistPermiso = $sistPermisoSelect->fetch_object(); 

      $sistModulo_permiso = $regSistPermiso->permiso; }
    else
    { $sistModulo_permiso = ''; }

    return $sistModulo_permiso; }

  function valorFecha($txt_fch)
  { $fch_ano = substr($txt_fch,0,4);
    $fch_mes = str_pad(trim(substr($txt_fch,5,2)),2,'0',STR_PAD_LEFT);
    $fch_dia = str_pad(trim(substr($txt_fch,8,2)),2,'0',STR_PAD_LEFT);
    
    if ((int)$fch_mes == 0 OR (int)$fch_dia == 0 OR (int)$fch_ano == 0)
    { $newfch = date("Y-m-d"); }  
    else
    { $newfch = $fch_ano.'-'.$fch_mes.'-'.$fch_dia; }

    return $newfch; }

  function ValidarFecha($valorfecha)
  { if ($valorfecha == '')
    { $fechavalor = date('Y-m-d'); }
    else
    { $fchgenerada = str_replace("-", "", $valorfecha);

      $dia = str_pad(substr($fchgenerada,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchgenerada,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchgenerada,0,4),4,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $fechavalor = $ano.'-'.$mes.'-'.$dia; }
      else
      { $fechavalor = date('Y-m-d'); }
    }

    return $fechavalor; }

  $png_search = '<img alt="Buscar" src="../img_sistema/search.png" height="16" width="16" border="0"/>';  
  $png_recycle_bin = '<img alt="Suprimir" src="../img_sistema/recycle_bin.png" height="16" width="16" border="0" />';
  $png_power_black = '<img alt="Habilitar" src="../img_sistema/power_black.png" height="16" width="16" border="0" />';
  $png_power_blue = '<img alt="Deshabilitar" src="../img_sistema/power_blue.png" height="16" width="16" border="0" />';  
  $png_file_download = '<img alt="Descargar" src="../img_sistema/file_download.png" height="16" width="16" border="0" />';  
  $png_camera = '<img alt="Imagen" src="../img_sistema/camera.png" height="16" width="16" border="0" />';
  $png_calendar = '<img alt="Programaciones" src="../img_sistema/calendar.png" height="16" width="16" border="0" />';
  $png_link = '<img alt="" src="../img_sistema/link.png" height="16" width="16" border="0" />';
  $png_puzzle = '<img alt="" src="../img_sistema/puzzle.png" height="16" width="16" border="0" />';
  $png_chip = '<img alt="" src="../img_sistema/chip.png" height="16" width="16" border="0" />';
  $png_button_blue_repeat = '<img alt="" src="../img_sistema/button_blue_repeat.png" height="16" width="16" border="0" />';
  $png_box_open = '<img alt="" src="../img_sistema/box_open.png" height="16" width="16" border="0" />';
  $png_barcode = '<img alt="Codigo de barras" src="../img_sistema/barcode.png" height="16" border="0"/>';  
  $png_delivery = '<img alt="Acrobat Reader" src="../img_sistema/delivery.png" height="16" border="0"/>'; 
  $png_user_group = '<img alt="Contactos" src="../img_sistema/user_group.png" height="16" border="0"/>'; 
  $png_tag = '<img alt="Productos" src="../img_sistema/tag.png" height="16" border="0"/>'; 
  $png_folder_open = '<img alt="Sucursales" src="../img_sistema/folder_open.png" height="16" border="0"/>';
  $png_shopping_cart = '<img alt="Compras" src="../img_sistema/shopping_cart.png" height="16" border="0"/>';

  $png_button_blue_rew = '<img alt="Compras" src="../img_sistema/button_blue_rew.png" height="40" border="0"/>';
  $png_button_blue_ffw = '<img alt="Compras" src="../img_sistema/button_blue_ffw.png" height="40" border="0"/>';


  $png_acrobat = '<img alt="Acrobat Reader" src="../img_sistema/acrobat.png" height="16" border="0"/>';  
  $jpg_excel = '<img alt="Excel" src="../img_sistema/excel.jpg" height="16" border="0"/>';  

  $msg_noread = 'No cuentas con permisos de lectura.';
  $msg_norewrite = 'No cuentas con permisos de lectura/escritura.';
  $msg_nodelete = 'No cuentas con permisos para eliminar registros.';
  
   session_start();
?>
