<?php  
  function conectar()
  { return new mysqli("localhost","root","MSpwd55","proton140612_1924"); }
  
  function sistModulo($cdgmodulo)
  { $link_mysqli = conectar();
    $querySelect = $link_mysqli->query("
      SELECT * FROM sistmodulo
      WHERE cdgmodulo = '".$cdgmodulo."'
      AND sttmodulo >= '1'"); 

    if ($querySelect->num_rows > 0)
    { $regQuery = $querySelect->fetch_object(); 
      $answerModulo = $regQuery->modulo; 
    } else
    { $answerModulo = ''; }

    return $answerModulo; }

  function sistPermiso($cdgmodulo, $cdgusuario)
  { $link_mysqli = conectar();
    $querySelect = $link_mysqli->query("
      SELECT sistpermiso.permiso
        FROM sistpermiso,
             sistperfil,
             sistusuario
      WHERE (sistpermiso.cdgmodulo = '".$cdgmodulo."' AND 
             sistpermiso.cdgperfil = sistperfil.cdgperfil) AND 
            (sistusuario.cdgusuario = '".$cdgusuario."' AND 
             sistperfil.cdgperfil = sistusuario.cdgperfil)");

    if ($querySelect->num_rows > 0)
    { $regQuery = $querySelect->fetch_object(); 
      $answerPermiso = $regQuery->permiso; 
    } else
    { $answerPermiso = ''; }

    return $answerPermiso; }

  function valorFecha($txtDate)
  { $dataYear = substr($txtDate,0,4);
    $dataMonth = str_pad(trim(substr($txtDate,5,2)),2,'0',STR_PAD_LEFT);
    $dataDay = str_pad(trim(substr($txtDate,8,2)),2,'0',STR_PAD_LEFT);
    
    if ((int)$dataMonth == 0 OR (int)$dataDay == 0 OR (int)$dataYear == 0)
    { $answerDate = date("Y-m-d"); }  
    else
    { $answerDate = $dataYear.'-'.$dataMonth.'-'.$dataDay; }

    return $answerDate; }

  function ValidarFecha($dataDate)
  { if ($dataDate == '')
    { $answerDate = date('Y-m-d'); }
    else
    { $dataDates = str_replace("-", "", $dataDate);

      $dataDay = str_pad(substr($dataDates,6,2),2,'0',STR_PAD_LEFT);
      $dataMonth = str_pad(substr($dataDates,4,2),2,'0',STR_PAD_LEFT);
      $dataYear = str_pad(substr($dataDates,0,4),4,'0',STR_PAD_LEFT);

      if (checkdate((int)$dataMonth,(int)$dataDay,(int)$dataYear))
      { $answerDate = $dataYear.'-'.$dataMonth.'-'.$dataDay; }
      else
      { $answerDate = date('Y-m-d'); }
    }

    return $answerDate; }

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
  $png_gear = '<img alt="Productos" src="../img_sistema/gear.png" height="16" border="0"/>'; 
  $png_folder = '<img alt="Expediente" src="../img_sistema/folder.png" height="16" border="0"/>';
  $png_folder_open = '<img alt="Abrir expediente" src="../img_sistema/folder.png" height="16" border="0"/>';
  $png_shopping_cart = '<img alt="Compras" src="../img_sistema/shopping_cart.png" height="16" border="0"/>';
  $png_pnc = '<img alt="Producto No Conforme" src="../img_sistema/pnc.png" height="16" border="0"/>';  
  $png_blue_accept = '<img alt="Retorno" src="../img_sistema/sub_blue_accept.png" height="16" border="0"/>'; 
  

  $png_button_blue_rew = '<img alt="Compras" src="../img_sistema/button_blue_rew.png" height="40" border="0"/>';
  $png_button_blue_ffw = '<img alt="Compras" src="../img_sistema/button_blue_ffw.png" height="40" border="0"/>';


  $png_acrobat = '<img alt="Acrobat Reader" src="../img_sistema/acrobat.png" height="16" border="0"/>';  
  $jpg_excel = '<img alt="Excel" src="../img_sistema/excel.jpg" height="16" border="0"/>';  

  $msg_noread = 'No cuentas con permisos de lectura.';
  $msg_norewrite = 'No cuentas con permisos de lectura/escritura.';
  $msg_nodelete = 'No cuentas con permisos para eliminar registros.';
  
   session_start();
?>
