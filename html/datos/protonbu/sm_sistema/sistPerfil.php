<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '90030';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    $sistPerfil_idperfil = $_POST['text_idperfil'];
    $sistPerfil_perfil = $_POST['text_perfil'];
    
    $link_mysqli = conectar();
    $sistModuloSelect = $link_mysqli->query("
      SELECT * FROM sistmodulo
      WHERE sttmodulo != '9'
      ORDER BY cdgmodulo");
      
    if ($sistModuloSelect->num_rows > 0)
    { $id_modulo = 1;
      while ($regSistModulo = $sistModuloSelect->fetch_object())      
      { $sistPerfil_idmodulo[$id_modulo] = $regSistModulo->idmodulo;
        $sistPerfil_modulo[$id_modulo] = $regSistModulo->modulo;
        $sistPerfil_cdgmodulo[$id_modulo] = $regSistModulo->cdgmodulo; 
        
        $id_modulo++; }
        
      $num_modulos = $sistModuloSelect->num_rows; }
    
    $sistModuloSelect->close;     
    
    if ($_GET['cdgperfil'])
    { if (substr($sistModulo_permiso,0,1) == 'r')
      { $sistPerfil_cdgperfil = $_GET['cdgperfil']; 
    
        $link_mysqli = conectar();
        $sistPerfilSelect = $link_mysqli->query("
          SELECT * FROM sistperfil
          WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
          
        if ($sistPerfilSelect->num_rows > 0)
        { $regSistPerfil = $sistPerfilSelect->fetch_object();
          
          $sistPerfil_idperfil = $regSistPerfil->idperfil;
          $sistPerfil_perfil = $regSistPerfil->perfil;
          $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;
          $sistPerfil_sttperfil = $regSistPerfil->sttperfil;
          
          $link_mysqli = conectar();
          $sistPermisoSelect = $link_mysqli->query("
            SELECT * FROM sistpermiso
            WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
            
          if ($sistPermisoSelect->num_rows > 0)
          { while ($regSistPermiso = $sistPermisoSelect->fetch_object())
            { $sistPerfil_permiso[$regSistPermiso->cdgmodulo] = $regSistPermiso->permiso; }
          }
          
          if ($_GET['proceso'] == 'update')
          { if (substr($sistModulo_permiso,0,2) == 'rw')
            { if ($sistPerfil_sttperfil == '0')
              { $sistPerfil_newsttperfil = '1'; }
            
              if ($sistPerfil_sttperfil == '1')
              { $sistPerfil_newsttperfil = '0'; }
              
              if ($sistPerfil_newsttperfil != '')
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  UPDATE sistperfil
                  SET sttperfil = '".$sistPerfil_newsttperfil."'
                  WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
                
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El perfil '".$sistPerfil_idperfil."' fue actualizado exitosamente en su status."; }
                else
                { $msg_alert = "El perfil '".$sistPerfil_idperfil."' NO fue actualizado en su status."; } 
              } 
              else
              { $msg_alert = 'Este perfil no puede ser afectado en su status.'; }              
            } else
            { $msg_alert = $msg_norewrite; }
          }
          
          if ($_GET['proceso'] == 'delete')
          { if (substr($sistModulo_permiso,0,3) == 'rwx')
            { $link_mysqli = conectar();
              $sistPermisoSelect = $link_mysqli->query("
                SELECT * FROM sistpermiso
                WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
              
              if ($sistPermisoSelect->num_rows > 0)  
              { $msg_alert = "El perfil '".$sistPerfil_idperfil."' NO fue desechado por que no esta vacio."; }
              else
              { $link_mysqli = conectar();
                $link_mysqli->query("
                  DELETE FROM sistperfil                  
                  WHERE (cdgperfil = '".$sistPerfil_cdgperfil."' AND
                    sttperfil = '0')");
                  
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert = "El perfil '".$sistPerfil_cdgperfil."' fue desechado exitosamente."; }
                else
                { $msg_alert = "El perfil '".$sistPerfil_cdgperfil."' NO fue desechado."; }               
              }
              
              $sistPermisoSelect->close;                
            } else
            { $msg_alert = $msg_nodelete; }
          }
        } else
        { $msg_alert = 'El perfil que buscas no pudo ser encontrado.'; }        
      } else
      { $msg_alert = $msg_noread; }        
    }

    if ($_POST['submit_salvar'])
    { if (substr($sistModulo_permiso,0,2) == 'rw')
      { if ($sistPerfil_idperfil != '')
        { if ($sistPerfil_perfil != '')
          { $link_mysqli = conectar();
            $sistPerfilSelect = $link_mysqli->query("
              SELECT * FROM sistperfil
              WHERE idperfil = '".$sistPerfil_idperfil."'");
            
            if ($sistPerfilSelect->num_rows > 0)
            { $regSistPerfil = $sistPerfilSelect->fetch_object();            
              $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;
              
              $link_mysqli = conectar();
              $link_mysqli->query("
                UPDATE sistperfil
                SET perfil = '".$sistPerfil_perfil."'
                WHERE (idperfil = '".$sistPerfil_idperfil."' AND
                  sttperfil = '1')");
              
              if ($link_mysqli->affected_rows > 0)
              { $msg_alert = "El perfil '".$sistPerfil_idperfil."' fue actualizado exitosamente."; }
              else
              { $msg_alert = "El perfil '".$sistPerfil_idperfil."' NO fue actualizado."; }                  
            } else
            { for ($id_indice = 1; $id_indice <= 99; $id_indice++) 
              { $sistPerfil_cdgperfil = str_pad($id_indice,2,'0',STR_PAD_LEFT);
              
                $link_mysqli = conectar();
                $sistPerfilSelect = $link_mysqli->query("
                  SELECT * FROM sistperfil
                  WHERE cdgperfil = '".$sistPerfil_cdgperfil."'");
                  
                if ($sistPerfilSelect->num_rows == 0)
                { $link_mysqli = conectar();
                  $link_mysqli->query("
                    INSERT INTO sistperfil
                      (idperfil, perfil, cdgperfil)
                    VALUES
                      ('".$sistPerfil_idperfil."', '".$sistPerfil_perfil."', '".$sistPerfil_cdgperfil."')");
                      
                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert = "El perfil '".$sistPerfil_idperfil."' fue insertado exitosamente."; }
                  else
                  { $msg_alert = "El perfil '".$sistPerfil_idperfil."' NO fue insertado."; }
                  
                  $id_indice = 100; }
                
                $sistPerfilSelect->close;          
              }                  
            }
            
            // Permisos
            for ($id_modulo = 1; $id_modulo <= $num_modulos; $id_modulo++)
            { $sistPerfil_permiso = $_POST['radio_cdgmodulo'.$sistPerfil_cdgmodulo[$id_modulo]];
              
              if ($sistPerfil_permiso != '')
              { $link_mysqli = conectar();
                $sistPermidoInsert = $link_mysqli->query("
                  INSERT INTO sistpermiso
                    (cdgperfil, cdgmodulo, permiso)
                  VALUES
                    ('".$sistPerfil_cdgperfil."', '".$sistPerfil_cdgmodulo[$id_modulo]."', '".$sistPerfil_permiso."')");
                    
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= '\n Modulo '.$sistPerfil_idmodulo[$id_modulo].' permiso INSERTADO.'; }                    
                else
                { $link_mysqli = conectar();
                  $sistPermisoUpdate = $link_mysqli->query("
                    UPDATE sistpermiso
                    SET permiso = '".$sistPerfil_permiso."'
                    WHERE (cdgperfil = '".$sistPerfil_cdgperfil."' AND
                    cdgmodulo = '".$sistPerfil_cdgmodulo[$id_modulo]."')");

                  if ($link_mysqli->affected_rows > 0)
                  { $msg_alert .= '\n Modulo '.$sistPerfil_idmodulo[$id_modulo].' permiso ACTUALIZADO.'; } 
                }
              } else
              {	$link_mysqli = conectar();
                $sistPermisoDelete = $link_mysqli->query("
                  DELETE FROM sistpermiso
                  WHERE (cdgperfil = '".$sistPerfil_cdgperfil."' AND
                    cdgmodulo = '".$sistPerfil_cdgmodulo[$id_modulo]."')");
                    
                if ($link_mysqli->affected_rows > 0)
                { $msg_alert .= '\n Modulo '.$sistPerfil_idmodulo[$id_modulo].' permiso REMOVIDO.'; }
              }              
            }
            
            //$SelectSistModuloAll = $link_mysqli->query("CALL SelectSistModuloAll()");
            //if ($SelectSistModuloAll->num_rows >= 1)
            //{	while ($regSistModulo = $SelectSistModuloAll->fetch_object())
              //{ $sistModulo_cdgmodulo = $regSistModulo->cdgmodulo;

                //$sistPerfil_cdgpermiso = $_POST[rdo_cdgmodulo.$sistModulo_cdgmodulo];
                if ($sistPerfil_cdgpermiso != '---')
                {  	$link_mysqli = conectar();

                  $InsertSistPermiso = $link_mysqli->query("CALL InsertSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."', '".$sistPerfil_cdgpermiso."')");
                  if ($link_mysqli->affected_rows <= 0)
                  {
                    $link_mysqli = conectar();

                    $UpdateSistPermiso = $link_mysqli->query("CALL UpdateSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."', '".$sistPerfil_cdgpermiso."')");

                    $UpdateSistPermiso->close; }

                  $InsertSistPermiso->close;
                }
                else
                {	$link_mysqli = conectar();

                  $DeleteSistPermiso = $link_mysqli->query("CALL DeleteSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."')");

                  $DeleteSistPermiso->close; }
              //}
            //}

            $SelectSistModuloAll->close;            
            //////////////////
            
          } else
          { $msg_alert = 'Es necesario indicar alguna descripcion o referencia del perfil.';
            $autofocus_perfil = 'autofocus'; }               
        } else
        { $msg_alert = 'Es necesario indicar el nombre del perfil.';
          $autofocus_idperfil = 'autofocus'; }        
      } else
      { $msg_alert = $msg_nowrite; }      
    }    
    
    if ($_POST['checkbox_vertodo'])
    { $vertodo = 'checked';

      $link_mysqli = conectar();
      $sistPerfilSelect = $link_mysqli->query("
        SELECT * FROM sistperfil
        ORDER BY sttperfil DESC,
          idperfil"); }
    else
    { $link_mysqli = conectar();
      $sistPerfilSelect = $link_mysqli->query("
        SELECT * FROM sistperfil
        WHERE sttperfil >= '1'
        ORDER BY idperfil"); }
      
    if ($sistPerfilSelect->num_rows > 0)
    { $id_perfil = 1;
      while ($regSistPerfil = $sistPerfilSelect->fetch_object())      
      { $sistPerfiles_idperfil[$id_perfil] = $regSistPerfil->idperfil;
        $sistPerfiles_perfil[$id_perfil] = $regSistPerfil->perfil;
        $sistPerfiles_cdgperfil[$id_perfil] = $regSistPerfil->cdgperfil; 
        $sistPerfiles_sttperfil[$id_perfil] = $regSistPerfil->sttperfil;
        
        $id_perfil++; }
        
      $num_perfiles = $sistPerfilSelect->num_rows; }
    
    $sistPerfilSelect->close; 
    
    echo '
    <form id="frm_sistPerfil" name="frm_sistPerfil" method="POST" action="sistPerfil.php" onSubmit="return validarNewItem()">
      <table align="center">
        <thead>
          <tr><th>'.$sistModulo_modulo.'</th></tr>
        </thead>
        <tbody>
          <tr><td><label id="lbl_idperfil" for="lbl_idperfil">Perfil</label><br/>
              <input type="text" id="text_idperfil" name="text_idperfil" style="width:120px" value="'.$sistPerfil_idperfil.'" '.$autofocus_idperfil.' required/></td></tr>
        <tr><td><label id="lbl_perfil" for="lbl_perfil">Descripci&oacute;n</label><br/>
              <input type="text" id="text_perfil" name="text_perfil" style="width:240px" value="'.$sistPerfil_perfil.'" '.$autofocus_perfil.' required/></td></tr>
        </tbody>
        <tfoot>
          <tr><td align="right"><input type="submit" id="submit_salvar" name="submit_salvar" value="Salvar" /></td></tr>
        </tfoot>
      </table><br/>
  
      <table align="center">
        <thead>
          <tr><td colspan="2"></td>
            <th colspan="4">Permisos</th></tr>
          <tr><th><label for="label_idmodulo">Modulo</label></th>
            <th><label for="label_idmodulo">Descripci&oacute;n</label></th>
            <th><label for="label_000">---</label></th>
            <th><label for="label_r00">r--</label></th>
            <th><label for="label_rw0">rw-</label></th>
            <th><label for="label_rwx">rwx</label></th></tr>
        </thead>
        <tbody>';
      
      for ($id_modulo = 1; $id_modulo <= $num_modulos; $id_modulo++)      
      { if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$id_modulo]] != '')
        { if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$id_modulo]] == 'r')
					{	$prmR = 'checked="checked"'; }

					if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$id_modulo]] == 'rw')
					{	$prmRW = 'checked="checked"'; }

					if ($sistPerfil_permiso[$sistPerfil_cdgmodulo[$id_modulo]] == 'rwx')
					{	$prmRWX = 'checked="checked"'; }          
        } else
        { $prm = 'checked="checked"'; }
          
        
        echo '
          <tr><td>'.$sistPerfil_idmodulo[$id_modulo].'</td>
            <td>'.$sistPerfil_modulo[$id_modulo].'</td>
            <td align="center"><input type="radio" name="radio_cdgmodulo'.$sistPerfil_cdgmodulo[$id_modulo].'" value="" '.$prm.' title="sin permisos" /></td>
            <td align="center"><input type="radio" name="radio_cdgmodulo'.$sistPerfil_cdgmodulo[$id_modulo].'" value="r" '.$prmR.' title="leer" /></td>
            <td align="center"><input type="radio" name="radio_cdgmodulo'.$sistPerfil_cdgmodulo[$id_modulo].'" value="rw" '.$prmRW.' title="leer y escribir" /></td>
            <td align="center"><input type="radio" name="radio_cdgmodulo'.$sistPerfil_cdgmodulo[$id_modulo].'" value="rwx" '.$prmRWX.' title="control total" /></td></tr>'; 
            
        $prm = '';
        $prmR = '';
        $prmRW = '';
        $prmRWX = ''; }
      
      echo '
        </tbody>
        <tfoot>
          <tr><td colspan="6" align="right"></td></tr>
        </tfoot>
      </table><br/>
    
      <table align="center">
        <thead>
          <tr><th><label for="label_ttlidperfil">Perfil</label></th>
            <th><label for="label_ttlperfil">Descripci&oacute;n</label></th>
            <th colspan="2"><label for="label_ttloperacion">Operaciones</label></th></tr>
        </thead>
        <tbody>
        </tbody>';
      
      for ($id_perfil = 1; $id_perfil <= $num_perfiles; $id_perfil++)
      { echo '
          <tr><td>'.$sistPerfiles_idperfil[$id_perfil].'</td>
            <td>'.$sistPerfiles_perfil[$id_perfil].'</td>';
            
        if ((int)$sistPerfiles_sttperfil[$id_perfil] > 0)      
        { if ((int)$sistPerfiles_sttperfil[$id_perfil] > 1)
          { echo '
            <td align="center"><a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$id_perfil].'">'.$png_search.'</a></td>
            <td align="center">'.$png_power_blue.'</td>'; }
          else
          { echo '
            <td align="center"><a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$id_perfil].'">'.$png_search.'</a></td>
            <td align="center"><a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$id_perfil].'&proceso=update">'.$png_power_blue.'</a></td>'; } 
        } else
        { echo '
            <td align="center"><a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$id_perfil].'&proceso=delete">'.$png_recycle_bin.'</a></td>
            <td align="center"><a href="sistPerfil.php?cdgperfil='.$sistPerfiles_cdgperfil[$id_perfil].'&proceso=update">'.$png_power_black.'</a></td>'; }

        echo '</tr>'; }
        
      echo '
        <tfoot>
          <tr><td colspan="4" align="right"><label for="label_piedefiltro">['.$num_perfiles.'] perfiles encontrados</label></td></tr>
        </tfoot>      
      </table>
    </form>'; 
 /*
	if ($_SESSION[idusuario])
	{	$sistPermiso_cdgpermiso = substr(permisoModulo($conector, $_SESSION[idusuario], $sistModulo_cdgmodulo),0,3);
		$modulo = substr(permisoModulo($conector, $_SESSION[idusuario], $sistModulo_cdgmodulo),3);

		if (substr($sistPermiso_cdgpermiso,0,1) != 'r')
        {  	htmlHTML($modulo);
			html_HTML('No cuentas con permisos de acceso a este modulo.');
			exit; }
	}
	else
	{	htmlHTML($modulo);
		html_HTML('Es necesario tener una sesion iniciada para usar este modulo.');
		exit; }

    //Operaciones con los registros existentes
    if ($_GET[cdgperfil])
    {  	if (substr($sistPermiso_cdgpermiso,0,1) != 'r')
        {  	$aviso = $noRead; }
        else
        {   $link_mysqli = conectar();

			$SelectSistPerfil = $link_mysqli->query("CALL SelectSistPerfilByCdgPerfil('".$_GET[cdgperfil]."')");
			if ($SelectSistPerfil->num_rows >= 1)
            {  	$regSistPerfil = $SelectSistPerfil->fetch_object();

                $sistPerfil_idperfil = $regSistPerfil->idperfil;
                $sistPerfil_perfil = $regSistPerfil->perfil;
                $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;
                $sistPerfil_sttperfil = $regSistPerfil->sttperfil;

				if ($_GET[proceso] == 'update')
				{	if (substr($sistPermiso_cdgpermiso,0,2) != 'rw')
					{  	$aviso = $noWrite; }
					else
					{  	$link_mysqli = conectar();

						$UpdateSistPerfilBySttPerfil = $link_mysqli->query("CALL UpdateSistPerfilBySttPerfil('".$sistPerfil_idperfil."','".$sistPerfil_sttperfil."')");
						if ($link_mysqli->affected_rows <= 0)
						{	$aviso = 'El perfil '.$sistPerfil_idperfil.' NO fue actualizado.'; }
						else
						{	$aviso = 'El perfil '.$sistPerfil_idperfil.' fue ACTUALIZADO satisfactoriamente.'; }

						$UpdateSistPerfilBySttPerfil->close;
					}
				}

				if ($_GET[proceso] == 'delete')
				{	if ($sistPermiso_cdgpermiso != 'rwx')
					{  	$aviso = $noDelete; }
					else
					{  	$link_mysqli = conectar();

						$SelectSistUsuarioByCdgPerfil = $link_mysqli->query("CALL SelectSistUsuarioByCdgPerfil('".$sistPerfil_cdgperfil."')");
						if ($SelectSistUsuarioByCdgPerfil->num_rows <= 0)
						{  	$link_mysqli = conectar();

							$DeleteSistPerfil = $link_mysqli->query("CALL DeleteSistPerfil('".$sistPerfil_idperfil."')");
							if ($link_mysqli->affected_rows <= 0)
							{	$aviso = 'El perfil '.$sistPerfil_idperfil.' NO fue eliminado.'; }
							else
							{	$link_mysqli = conectar();

								$DeleteSistPermisoByCdgPerfil = $link_mysqli->query("CALL DeleteSistPermisoByCdgPerfil('".$sistPerfil_cdgperfil."')");

								$DeleteSistPermisoByCdgPerfil->close;

								$aviso = 'El perfil '.$sistPerfil_idperfil.' fue ELIMINADO satisfactoriamente.'; }

							$DeleteSistPerfil->close;
						}
						else
						{  	$aviso = 'El perfil '.$sistPerfil_idperfil.' NO fue eliminado por que existen usuarios asociados.'; }

						$SelectSistUsuarioByCdgPerfil->close;
					}
				}
			}

			$SelectSistPerfil->close;
		}
    }

    //Operaciones con el formulario
    if ($_POST[btn_salvar])
    {  	if (substr($sistPermiso_cdgpermiso,0,2) != 'rw')
        {  	$aviso = $noWrite; }
        else
        {  	$sistPerfil_idperfil = $_POST[txt_idperfil];
            $sistPerfil_perfil = trim($_POST[txt_perfil]);

            $link_mysqli = conectar();

			$SelectSistPerfil = $link_mysqli->query("CALL SelectSistPerfil('".$sistPerfil_idperfil."')");
			if ($SelectSistPerfil->num_rows <= 0)
			{  	for ($id = 1; $id <= 999; $id++)
				{  	$sistPerfil_cdgperfil = str_pad($id,3,'0',STR_PAD_LEFT);

					if ($id > 999)
					{  	$aviso = 'Los codigos disponibles se han agotado. [999]'; }
					else
					{  	$link_mysqli = conectar();

						$SelectSistPerfilByCdgPerfil = $link_mysqli->query("CALL SelectSistPerfilByCdgPerfil('".$sistPerfil_cdgperfil."')");
						if ($SelectSistPerfilByCdgPerfil->num_rows <= 0)
						{  	$link_mysqli = conectar();

							$InsertSistPerfil = $link_mysqli->query("CALL InsertSistPerfil('".$sistPerfil_idperfil."', '".$sistPerfil_perfil."', '".$sistPerfil_cdgperfil."')");
							if ($link_mysqli->affected_rows <= 0)
							{  	$aviso = 'El nuevo perfil '.$sistPerfil_idperfil.' NO fue insertado.'; }
							else
							{  	$aviso = 'El nuevo perfil '.$sistPerfil_idperfil.' fue INSERTADO satisfactoriamente.'; }

							$InsertSistPerfil->close;

							$id = 999;
						}

						$SelectSistPerfilByCdgPerfil->close;
					}
				}
			}
			else
			{  	$regSistPerfil = $SelectSistPerfil->fetch_object();
                $sistPerfil_cdgperfil = $regSistPerfil->cdgperfil;

				$link_mysqli = conectar();

				$UpdateSistPerfil = $link_mysqli->query("CALL UpdateSistPerfil('".$sistPerfil_idperfil."','".$sistPerfil_perfil."')");
				if ($link_mysqli->affected_rows <= 0)
				{  	$aviso = 'El perfil '.$sistPerfil_idperfil.' NO fue actualizado.'; }
				else
				{  	$aviso = 'El perfil '.$sistPerfil_idperfil.' fue ACTUALIZADO satisfactoriamente.'; }

				$UpdateSistPerfil->close;
			}

			$SelectSistPerfil->close;

			// Permisos por modulo segun perfil
			$link_mysqli = conectar();

			$SelectSistModuloAll = $link_mysqli->query("CALL SelectSistModuloAll()");
			if ($SelectSistModuloAll->num_rows >= 1)
			{	while ($regSistModulo = $SelectSistModuloAll->fetch_object())
				{  	$sistModulo_cdgmodulo = $regSistModulo->cdgmodulo;

					$sistPerfil_cdgpermiso = $_POST[rdo_cdgmodulo.$sistModulo_cdgmodulo];
					if ($sistPerfil_cdgpermiso != '---')
					{  	$link_mysqli = conectar();

						$InsertSistPermiso = $link_mysqli->query("CALL InsertSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."', '".$sistPerfil_cdgpermiso."')");
						if ($link_mysqli->affected_rows <= 0)
						{
							$link_mysqli = conectar();

							$UpdateSistPermiso = $link_mysqli->query("CALL UpdateSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."', '".$sistPerfil_cdgpermiso."')");

							$UpdateSistPermiso->close; }

						$InsertSistPermiso->close;
					}
					else
					{	$link_mysqli = conectar();

						$DeleteSistPermiso = $link_mysqli->query("CALL DeleteSistPermiso('".$sistPerfil_cdgperfil."', '".$sistModulo_cdgmodulo."')");

						$DeleteSistPermiso->close; }
				}
			}

			$SelectSistModuloAll->close;

			$aviso .= ' *Revisa los permisos';
		}
	}
    //Armado de la pagina
    htmlHTML($modulo);

    echo '
		

        <table align="center" width="600">
            <tr>
                <td>
                    <form id="frm_sistPerfil" name="frm_sistPerfil" method="POST" action="sistPerfil.php" onSubmit="return validarNewItem()">
                        <fieldset>
                            <legend>Datos de registro</legend>
                            <table align="center">
                                <tr>
                                    <td><label id="lbl_idperfil" for="lbl_idperfil">idPerfil</label></td>
                                    <td><input type="text" size="12" maxlength="16" id="txt_idperfil" name="txt_idperfil" value="'.$sistPerfil_idperfil.'" onkeypress="return permitir(event, \'ids\')" /></td>
                                </tr>
                                <tr>
                                    <td><label id="lbl_perfil" for="lbl_perfil">Perfil</label></td>
                                    <td><input type="text" size="40" maxlength="40" id="txt_perfil" name="txt_perfil" value="'.$sistPerfil_perfil.'" /></td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="2"><input type="submit" id="btn_salvar" name="btn_salvar" value="Salvar" /></td>
                                </tr>
                            </table><br/>
                            <table align="center">
								<thead>
									<tr>
										<th colspan="2"><label id="lbl_ttlvacio" for="lbl_ttlvacio"></label></td>
										<th align="center" colspan="4"><label id="lbl_ttlpermisos" for="lbl_ttlpermisos">Permisos</label></td>
									</tr>
									<tr align="center">
										<th align="left"><label id="lbl_ttlidmodulo" for="lbl_ttlidmodulo">idModulo</label></td>
										<th align="left"><label id="lbl_ttlmodulo" for="lbl_ttlmodulo">Modulo</label></td>
										<th><label id="lbl_ttl" for="lbl_ttl">---</label></td>
										<th><label id="lbl_ttlR" for="lbl_ttlR">r--</label></td>
										<th><label id="lbl_ttlRW" for="lbl_ttlRW">rw-</label></td>
										<th><label id="lbl_ttlRWX" for="lbl_ttlRWX">rwx</label></td>
									</tr>
                                </thead>
								<tbody>';

	$link_mysqli = conectar();

    $SelectSistModuloAll = $link_mysqli->query("CALL SelectSistModuloAll()");
    if ($SelectSistModuloAll->num_rows >= 1)
    {	while ($regSistModulo = $SelectSistModuloAll->fetch_object())
		{	if ($regSistModulo->sttmodulo == '1')
			{	$prm = 'checked="checked"';
				$prmR = '';
				$prmRW = '';
				$prmRWX = '';

				if ($sistPerfil_cdgperfil != '')
				{   $link_mysqli = conectar();

					$SelectSistPermiso = $link_mysqli->query("CALL SelectSistPermiso('".$sistPerfil_cdgperfil."', '".$regSistModulo->cdgmodulo."')");
					$regSistPermiso = $SelectSistPermiso->fetch_object();

					if ($regSistPermiso->cdgpermiso == 'r--')
					{	$prmR = 'checked="checked"'; }

					if ($regSistPermiso->cdgpermiso == 'rw-')
					{	$prmRW = 'checked="checked"'; }

					if ($regSistPermiso->cdgpermiso == 'rwx')
					{	$prmRWX = 'checked="checked"'; }

					$SelectSistPermiso->close; }

				$radioButton = ''; }
			else
			{	$radioButton = 'disabled'; }

			echo '
									<tr>
										<td>'.$regSistModulo->idmodulo.'</td>
										<td>'.$regSistModulo->modulo.'</td>
										<td align="center"><input type="radio" name="rdo_cdgmodulo'.$regSistModulo->cdgmodulo.'" value="---" '.$prm.' title="sin permisos" '.$radioButton.'/></td>
										<td align="center"><input type="radio" name="rdo_cdgmodulo'.$regSistModulo->cdgmodulo.'" value="r--" '.$prmR.' title="leer" '.$radioButton.'/></td>
										<td align="center"><input type="radio" name="rdo_cdgmodulo'.$regSistModulo->cdgmodulo.'" value="rw-" '.$prmRW.' title="leer y escribir" '.$radioButton.'/></td>
										<td align="center"><input type="radio" name="rdo_cdgmodulo'.$regSistModulo->cdgmodulo.'" value="rwx" '.$prmRWX.' title="control total" '.$radioButton.'/></td>
									</tr>';
		}
	}

	$numreg = $SelectSistModuloAll->num_rows;
	$SelectSistModuloAll->close;

    //
    echo '
								</tbody>
								<tfoot>
									<tr>
										<th colspan="6" align="right"><label for="lbl_ppg">['.$numreg.'] Modulos encontrados</label></th>
									</tr>
								</tfoot>
                            </table>
                        </fieldset>

                        <fieldset>
                            <legend>Registros</legend>
                            <table align="center">
								<thead>
									<tr align="left">
										<th colspan="2"><label id="lbl_ttlidperfil" for="lbl_ttlidperfil">idPerfil</label></th>
										<th colspan="2"><label id="lbl_ttlperfil" for="lbl_ttlperfil">Perfil</label></th>
										<th><label id="lbl_ttlstatus" for="lbl_ttlstatus">Status</label></th>
									</tr>
								</thead>
								<tbody>';

    //Armado de tabla de registros
    $link_mysqli = conectar();

    $SelectSistPerfilAll = $link_mysqli->query("CALL SelectSistPerfilAll()");
    if ($SelectSistPerfilAll->num_rows >= 1)
    {  	while ($regSistPerfil = $SelectSistPerfilAll->fetch_object())
        {  	if ($regSistPerfil->sttperfil >= '1')
			{  	echo '
								<tr>
									<td><a href="sistPerfil.php?cdgperfil='.$regSistPerfil->cdgperfil .'">'.$editar.'</a></td>
									<td>'.$regSistPerfil->idperfil.'</td>
									<td></td>
									<td>'.$regSistPerfil->perfil.'</td>
									<td align="center"><a href="sistPerfil.php?cdgperfil='.$regSistPerfil->cdgperfil.'&proceso=update">'.$stts1.'</a></td>
								</tr>'; }
			else
			{  	echo '
								<tr>
									<td></td>
									<td>'.$regSistPerfil->idperfil.'</td>
									<td><a href="sistPerfil.php?cdgperfil='.$regSistPerfil->cdgperfil.'&proceso=delete">'.$borrar.'</a></td>
									<td>'.$regSistPerfil->perfil.'</td>
									<td align="center"><a href="sistPerfil.php?cdgperfil='.$regSistPerfil->cdgperfil.'&proceso=update">'.$stts0.'</a></td>
								</tr>'; }
        }
    }

	$numreg = $SelectSistPerfilAll->num_rows;
	$SelectSistPerfilAll->close;
    //
    echo '
								</tbody>
								<tfoot>
									<tr>
										<th colspan="5" align="right"><label for="lbl_ppg">['.$numreg.'] Registros encontrados</label></th>
									</tr>
								</tfoot>
                            </table>
                        </fieldset>
                    </form>
                </td>
            </tr>
        </table>'; //*/


    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }

?>

  </body>
</html>
