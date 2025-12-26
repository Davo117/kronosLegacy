<!DOCTYPE html>
<html>
 <head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
 </head>
 <body><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '10590';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);  

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);
  //Armado de modulo
  //Catalogo de periodos, generación manual de los periodos de pre-nomina

    $rechPeriodo_iniperiodo = $_POST['date_iniperiodo'];
    $rechPeriodo_numdias = $_POST['text_numdias'];
    $rechPeriodo_numperiodos = $_POST['text_numperiodos'];

    if ($rechPeriodo_iniperiodo == '')
    { $rechPeriodo_iniperiodo = date('Y-m-d'); }
    else
    { $fchgenerada = str_replace("-", "", $rechPeriodo_iniperiodo);

      $dia = str_pad(substr($fchgenerada,6,2),2,'0',STR_PAD_LEFT);
      $mes = str_pad(substr($fchgenerada,4,2),2,'0',STR_PAD_LEFT);
      $ano = str_pad(substr($fchgenerada,0,4),4,'0',STR_PAD_LEFT);

      if (checkdate((int)$mes,(int)$dia,(int)$ano))
      { $rechPeriodo_iniperiodo = $ano.'-'.$mes.'-'.$dia; }
      else
      { $rechPeriodo_iniperiodo = date('Y-m-d'); }
    }  

    if ($rechPeriodo_numdias == '')
    { $rechPeriodo_numdias = 7; }

    if ($rechPeriodo_numperiodos == '')
    { $rechPeriodo_numperiodos = 1; }

    if ($_POST['submit_generar'])
    { $iniperiodo = new DateTime($rechPeriodo_iniperiodo);
  	  $finperiodo = new DateTime($rechPeriodo_iniperiodo);

      if ($rechPeriodo_numperiodos > 0)
  	  { $ininewperiodo = 24*$rechPeriodo_numdias;
  	    $finnewperiodo = $ininewperiodo;

  	    for ($id_periodo=1; $id_periodo<=$rechPeriodo_numperiodos; $id_periodo++)
  	    { $iniperiodo->setTime(0, 0, 0);
  	  	  if ($id_periodo == 1)
  	  	  { $finperiodo->setTime($finnewperiodo, 0, -1); }
  	      else
  	      { $finperiodo->setTime($finnewperiodo+24, 0, -1); }
  	  	
  		    $link_mysqli = conectar();
  		    $link_mysqli->query("
  	  	  	INSERT INTO rechperiodo
  		    	  (cdgperiodo, iniperiodo, finperiodo)
  		    	VALUES
  		  	    ('".$finperiodo->format('yW')."', '".$iniperiodo->format('Y-m-d H:i:s')."', '".$finperiodo->format('Y-m-d H:i:s')."')");

  		  //echo $iniperiodo->format('Y-m-d H:i:s').' / '.$finperiodo->format('Y-m-d H:i:s').' Semana '.$finperiodo->format('yWw').'<br/>'; 

  		    $iniperiodo->setTime($ininewperiodo, 0, 0); 
  		  
  		  }
  	  }
    }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { if ($_POST['checkbox_vertodos'])
      { $vertodo = 'checked';

        $link_mysqli = conectar();
        $rechPeriodoSelect = $link_mysqli->query("
          SELECT * FROM rechperiodo
          ORDER BY cdgperiodo"); }
      else
      { // Buscar coincidencias
        $link_mysqli = conectar();
        $rechPeriodoSelect = $link_mysqli->query("
          SELECT * FROM rechperiodo
          WHERE sttperiodo = '1'
          ORDER BY cdgperiodo"); }

      if ($rechPeriodoSelect->num_rows > 0)
      { $id_periodo = 1;
        while ($regRechPeriodo = $rechPeriodoSelect->fetch_object())
        { $rechPeriodos_cdgperiodo[$id_periodo] = $regRechPeriodo->cdgperiodo;
          $rechPeriodos_iniperiodo[$id_periodo] = $regRechPeriodo->iniperiodo;
          $rechPeriodos_finperiodo[$id_periodo] = $regRechPeriodo->finperiodo;
          $rechPeriodos_sttperiodo[$id_periodo] = $regRechPeriodo->sttperiodo;

          $id_periodo++; }

        $num_periodos = $rechPeriodoSelect->num_rows; }
    }        

    echo '
  <form id="form_rechperiodo" name="form_rechperiodo" method="POST" action="rechPeriodo.php">
	 <table align="center">
	  <thead>
	   <tr><th colspan="2">'.$sistModulo_modulo.'</th></tr>
	  </thead>
	  <tbody>
	   <tr><td colspan="2"><label for="label_iniperiodo"><strong>Inicio del periodo</strong></label><br/>
	      <input type="date" name="date_iniperiodo" id="date_iniperiodo" value="'.$rechPeriodo_iniperiodo.'" title="Inicio del periodo" onchange="document.form_rechum.submit()" required /></td></tr>
	   <tr><td><label for="label_numdias"><strong>D&iacute;as</strong></label><br/>
	      <input type="number" name="text_numdias" id="text_numdias" style="width:50px" value="'.$rechPeriodo_numdias.'" title="Cantidad de dias en el periodo" required /></td>
	     <td><label for="label_numperiodos"><strong>Periodos</strong></label><br/>
	      <input type="number" name="text_numperiodos" id="text_numperiodos" style="width:60px" value="'.$rechPeriodo_numperiodos.'" title="Cantidad de periodos a generar" required /></td></tr>
	  </tbody>
	  <tfoot>
	   <tr><td colspan="2" align="right"><input type="submit" name="submit_generar" id="submit_generar" value="Generar periodo(s)"/></td></tr>
	  </tfoot>
	 </table>


   <table align="center">
    <thead>
     <tr><td colspan="3"></td>
      <th colspan="3"><input type="checkbox" name="checkbox_vertodos" id="checkbox_vertodos" onclick="document.form_rechperiodo.submit()" '.$vertodo.'>
       <label for="lbl_vertodo">ver todo</label></th></tr>
     <tr><th>idPeriodo</th><th>Inicio</th><th>Fin</th></tr>
    </thead>
    <tbody>';

    if ($num_periodos > 0)
    { for ($id_periodo = 1; $id_periodo<=$num_periodos; $id_periodo++)
      { echo '
     <tr align="center">
      <td><em>'.$rechPeriodos_cdgperiodo[$id_periodo].'</em></td>
      <td>'.$rechPeriodos_iniperiodo[$id_periodo].'</td>
      <td>'.$rechPeriodos_finperiodo[$id_periodo].'</td>
      <td></td>
      <td>'.$id_periodo.'</td>
      <td></td></tr>'; }
    }

    echo '
    </tbody>
    <tfoot>
     <tr><th colspan="6" align="right"><label for="lbl_ppgdatos">['.$num_periodos.'] Registros encontrados</label></th></tr>
    </tfoot>
   </table>
  </form>'; 
  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }

  ?>
  </body>
</html>