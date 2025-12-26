<!DOCTYPE html>
<html>
  <head> 		  
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="screen" />     
  </head> 
  <body><?php
  
  include '../datos/mysql.php';
   
  // --> Nombre de los dias
	$diatxt[1] = "Lunes";
	$diatxt[2] = "Martes";
	$diatxt[3] = "Miercoles";
	$diatxt[4] = "Jueves";
	$diatxt[5] = "Viernes";
	$diatxt[6] = "Sabado";	  	
	$diatxt[7] = "Domingo";
  // <-- Nombre de los dias

  // --> Nombre de los meses
	$mestxt[1] = "Enero";	
	$mestxt[2] = "Febrero";
	$mestxt[3] = "Marzo";
	$mestxt[4] = "Abril";
	$mestxt[5] = "Mayo";
	$mestxt[6] = "Junio";
	$mestxt[7] = "Julio";
	$mestxt[8] = "Agosto";
	$mestxt[9] = "Septiembre";
	$mestxt[10] = "Octubre";
	$mestxt[11] = "Noviembre";
	$mestxt[12] = "Diciembre";  
  // <-- Nombre de los meses

  if ($_POST['date_dsdfecha'])
  { $prodCalendario_dsdfecha = $_POST['date_dsdfecha']; }
  else
  { $prodCalendario_dsdfecha = date(); }

  if ($_POST['date_dsdfecha']) { $prodCalendario_dsdrngfecha = $_POST['date_dsdfecha']; }
  if ($_POST['date_hstfecha']) { $prodCalendario_hstrngfecha = $_POST['date_hstfecha']; }

  $prodCalendario_dsdrngfecha = ValidarFecha($prodCalendario_dsdrngfecha);
  $prodCalendario_hstrngfecha = ValidarFecha($prodCalendario_hstrngfecha);  
  
  if (($_POST['hide_dsdfecha'] AND $_POST['hide_hstfecha']) AND ($_POST['slc_cdgproceso'] OR $_POST['slc_cdgempleado'] OR $_POST['slc_cdgproducto']))
  { $prodCalendario_cdgproceso = $_POST['slc_cdgproceso'];
    $prodCalendario_cdgempleado = $_POST['slc_cdgempleado'];
    $prodCalendario_cdgproducto = $_POST['slc_cdgproducto'];

    $prodCalendario_dsdfecha = $_POST['hide_dsdfecha'];
    $prodCalendario_hstfecha = $_POST['hide_hstfecha'];

    $prodCalendario_idmesprevio = substr($prodCalendario_dsdfecha,5,2); 
    $prodCalendario_idanoprevio = substr($prodCalendario_dsdfecha,0,4); 

    if ($prodCalendario_idmesprevio == 12)
    { $prodCalendario_idano = $prodCalendario_idanoprevio+1;
      $prodCalendario_idmes = 1; 
    } else
    { $prodCalendario_idano = $prodCalendario_idanoprevio;
      $prodCalendario_idmes = $prodCalendario_idmesprevio+1; } 

    $prodCalendario_idmessiguiente = substr($prodCalendario_hstfecha,5,2);
    $prodCalendario_idanosiguiente = substr($prodCalendario_hstfecha,0,4);
  } else
  { $prodCalendario_cdgproceso = $_GET['cdgproceso'];
    $prodCalendario_cdgempleado = $_GET['cdgempleado'];
    $prodCalendario_cdgproducto = $_GET['cdgproducto'];

    if ($_GET['idano'] AND $_GET['idmes'])
    { $prodCalendario_idano = $_GET['idano'];
      $prodCalendario_idmes = $_GET['idmes']; }
    else
    { $prodCalendario_idano = date("Y"); 
      $prodCalendario_idmes = date("n"); }
      
    if ($prodCalendario_idmes == 1)


    { $prodCalendario_idmesprevio = 12; 
      $prodCalendario_idanoprevio = $prodCalendario_idano-1; 

      $prodCalendario_idmessiguiente = 2;
      $prodCalendario_idanosiguiente = $prodCalendario_idano;
    }
    else
    { if ($prodCalendario_idmes == 12)
      { $prodCalendario_idmesprevio = 11; 
        $prodCalendario_idanoprevio = $prodCalendario_idano; 

        $prodCalendario_idmessiguiente = 1;
        $prodCalendario_idanosiguiente = $prodCalendario_idano+1;
      }
      else
      { $prodCalendario_idmesprevio = $prodCalendario_idmes-1; 
        $prodCalendario_idanoprevio = $prodCalendario_idano; 

        $prodCalendario_idmessiguiente = $prodCalendario_idmes+1;
        $prodCalendario_idanosiguiente = $prodCalendario_idano;
      }
    }
  }

	$prodCalendario_diasmesprevio = date("t", mktime(0,0,0,$prodCalendario_idmesprevio,1,$prodCalendario_idanoprevio));
	$prodCalendario_diasmessiguiente = date("t", mktime(0,0,0,$prodCalendario_idmessiguiente,1,$prodCalendario_idanosiguiente));
	$prodCalendario_diasmes = date("t", mktime(0,0,0,$prodCalendario_idmes,1,$prodCalendario_idano));
	$prodCalendario_dia1mes = date("N", mktime(0,0,0,$prodCalendario_idmes,1,$prodCalendario_idano));

  if ($prodCalendario_cdgproceso != '')
  { // Proceso Activo
    if ($prodCalendario_cdgempleado != '')
    { // Empleado Activo
      if ($prodCalendario_cdgproducto != '')
      { // Producto Activo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodloteope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodlote.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobinaope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodbobina.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrolloope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodrollo.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
      } else
      { // Producto Nulo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodloteope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobinaope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrolloope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
      }
    } else
    { // Empleado Nulo
      if ($prodCalendario_cdgproducto != '')
      { // Producto Activo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodlote.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodbobina.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
            prodrollo.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }        
      } else
      { // Producto Nulo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE pprodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodoperacion.cdgproceso = '".$prodCalendario_cdgproceso."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
      }          
    }
  } else
  { // Proceso Nulo
    if ($prodCalendario_cdgempleado != '')
    { // Empleado Activo
      if ($prodCalendario_cdgproducto != '')
      { // Producto Activo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodloteope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodlote.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobinaope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodbobina.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrolloope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
            prodrollo.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }      
      } else
      { // Producto Nulo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodloteope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobinaope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrolloope.cdgempleado = '".$prodCalendario_cdgempleado."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        } 
      }
    } else
    { // Empleado Nulo
      if ($_SESSION['prodcalendario_producto'] != '')
      { // Producto Activo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,        
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodlote.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobina.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrollo.cdgproducto = '".$prodCalendario_cdgproducto."' AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31') AND
            prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        } 
      } else
      { // Producto Nulo
        $link_mysqli = conectar();
        $prodCalendarioSelectLote= $link_mysqli->query("
          SELECT SUBSTR(prodloteope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodlote.longitud) AS metros
          FROM prodlote,
            prodloteope,
            prodoperacion
          WHERE prodlote.cdglote = prodloteope.cdglote AND
            prodloteope.cdgoperacion = prodoperacion.cdgoperacion AND
           (SUBSTR(prodloteope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31')            
          GROUP BY SUBSTR(prodloteope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectLote->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectLote->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
        
        $link_mysqli = conectar();
        $prodCalendarioSelectBobina = $link_mysqli->query("
          SELECT SUBSTR(prodbobinaope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodbobina.longitud) AS metros
          FROM prodbobina,
            prodbobinaope,
            prodoperacion
          WHERE prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
            prodbobinaope.cdgoperacion = prodoperacion.cdgoperacion AND
           (SUBSTR(prodbobinaope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31')
          GROUP BY SUBSTR(prodbobinaope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectBobina->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectBobina->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }

        $link_mysqli = conectar();
        $prodCalendarioSelectRollo = $link_mysqli->query("
          SELECT SUBSTR(prodrolloope.fchmovimiento,1,10) AS fchmovimiento,
            SUM(prodrollo.longitud) AS metros
          FROM prodrollo,
            prodrolloope,
            prodoperacion
          WHERE prodrollo.cdgrollo = prodrolloope.cdgrollo AND
            prodrolloope.cdgoperacion = prodoperacion.cdgoperacion AND
           (SUBSTR(prodrolloope.fchmovimiento,1,10) BETWEEN '".$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01' AND '".$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31')
          GROUP BY SUBSTR(prodrolloope.fchmovimiento,1,10),
            prodoperacion.cdgproceso");

        if ($prodCalendarioSelectRollo->num_rows > 0)
        { while($regProdCalendario = $prodCalendarioSelectRollo->fetch_object())
          { $prodFchCalendario[$regProdCalendario->fchmovimiento] = $regProdCalendario->metros; }
        }
      }          
    }
  }    

  $link_mysqli = conectar(); 
  $pdtoProyectoSelect = $link_mysqli->query("
    SELECT * FROM pdtodiseno
    WHERE sttdiseno = '1'
    ORDER BY diseno");

  if ($pdtoProyectoSelect->num_rows > 0)
  { $id_proyecto = 1;
    while ($regPdtoProyecto = $pdtoProyectoSelect->fetch_object()) 
    { $pdtoProyecto_idproyecto[$id_proyecto] = $regPdtoProyecto->iddiseno;
      $pdtoProyecto_proyecto[$id_proyecto] = $regPdtoProyecto->diseno;
      $pdtoProyecto_cdgproyecto[$id_proyecto] = $regPdtoProyecto->cdgdiseno; 
      
      $link_mysqli = conectar(); 
      $pdtoImpresionSelect = $link_mysqli->query("
        SELECT * FROM pdtoimpresion
        WHERE cdgdiseno = '".$regPdtoProyecto->cdgdiseno."' AND 
          sttimpresion = '1'
        ORDER BY impresion,
          idimpresion");
      
      if ($pdtoProyectoSelect->num_rows > 0)
      { $id_impresion = 1;
        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
        { $pdtoImpresion_idimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->impresion;
          $pdtoImpresion_ancho[$id_proyecto][$id_impresion] = $regPdtoImpresion->ancho;
          $pdtoImpresion_alpaso[$id_proyecto][$id_impresion] = $regPdtoImpresion->alpaso;
          $pdtoImpresion_ceja[$id_proyecto][$id_impresion] = $regPdtoImpresion->ceja;
          $pdtoImpresion_tolerancia[$id_proyecto][$id_impresion] = $regPdtoImpresion->tolerancia;      
          $pdtoImpresion_corte[$id_proyecto][$id_impresion] = $regPdtoImpresion->corte;
          $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion] = $regPdtoImpresion->cdgimpresion;  

          $id_impresion++; }

        $numimpresiones[$id_proyecto] = $pdtoImpresionSelect->num_rows; }

      $id_proyecto++; } 

    $numproyectos = $pdtoProyectoSelect->num_rows; }    
  
  // Armado de la pagina
  echo '
    <br/>
    <table align="center">
      <thead>
        <tr align="center">
          <th><a href="prodCalendario.php?idano='.$prodCalendario_idanoprevio.'&idmes='.$prodCalendario_idmesprevio.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'">'.$png_button_blue_rew.'</a></th>
          <th>'.$mestxt[$prodCalendario_idmesprevio].'<br />'.$prodCalendario_idanoprevio.'</th>
          <th colspan="4"><h1>'.$mestxt[$prodCalendario_idmes].' '.$prodCalendario_idano.'</h1></th>
          <th>'.$mestxt[$prodCalendario_idmessiguiente].'<br />'.$prodCalendario_idanosiguiente.'</th>
          <th><a href="prodCalendario.php?idano='.$prodCalendario_idanosiguiente.'&idmes='.$prodCalendario_idmessiguiente.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'">'.$png_button_blue_ffw.'</a></th>
        </tr>
        <tr>';

	for ($dianum = 1; $dianum<=7; $dianum++)
	{	echo '	  
          <th>'.$diatxt[$dianum].'</th>'; }

	echo '	  
          <th>Semana</th>
        </tr>
      </thead>
      
      <tbody>';
    
	$prodCalendario_iddiamp = (($prodCalendario_diasmesprevio+1)-($prodCalendario_dia1mes-1));
	$prodCalendario_iddia = 1;
	$prodCalendario_iddiams = 1;

	for ($prodCalendario_idsemana = 1; !$prodCalendario_idsemanas; $prodCalendario_idsemana++)
	{	echo '
        <tr>';
			
		if ($prodCalendario_idsemana == 1)  
		{ for ($dianum = 1; $dianum<=7; $dianum++)
			{	if ($dianum >= $prodCalendario_dia1mes)
				{ if (checkdate($prodCalendario_idmes, $prodCalendario_iddia, $prodCalendario_idano))
					{ $prodCalendario_iddiames = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT);  
						$prodCalendario_idsemana = date("W", mktime(0,0,0,$prodCalendario_idmes,$prodCalendario_iddia,$prodCalendario_idano)); 
            
            if ($dianum == 1) 
            { $prodCalendario_inisemana = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT); 
              $prodFchCalendario_smn = $prodFchCalendario[$prodCalendario_iddiames]; } 
            else
            { $prodFchCalendario_smn = $prodFchCalendario_smn+$prodFchCalendario[$prodCalendario_iddiames]; }
						
            if ($dianum == 7) 
            { $prodCalendario_finsemana = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT); }  
				  
						echo '
          <td style="background:orange;"> 
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:orange;">'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalendario[$prodCalendario_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_iddiames.'&hstfecha='.$prodCalendario_iddiames.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
						if (($prodCalendario_iddia) >= $prodCalendario_diasmes) 
            { $prodCalendario_idsemanas = 1; }	           
				 
						$prodCalendario_iddia++; 
					}
				}
				else
				{ if (checkdate($prodCalendario_idmesprevio, $prodCalendario_iddiamp, $prodCalendario_idanoprevio))
					{ $prodCalendario_iddiames = $prodCalendario_idanoprevio.'-'.str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiamp, 2, '0', STR_PAD_LEFT);
						$prodCalendario_idsemana = date("W", mktime(0,0,0,$prodCalendario_idmesprevio,$prodCalendario_iddiamp,$prodCalendario_idanoprevio)); 
            
            if ($dianum == 1) 
            { $prodCalendario_inisemana = $prodCalendario_idanoprevio.'-'.str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiamp, 2, '0', STR_PAD_LEFT);  
              $prodFchCalendario_smn = $prodFchCalendario[$prodCalendario_iddiames]; } 
            else
            { $prodFchCalendario_smn = $prodFchCalendario_smn+$prodFchCalendario[$prodCalendario_iddiames]; }
              
						if ($dianum == 7) 
            { $prodCalendario_finsemana = $prodCalendario_idanoprevio.'-'.str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiamp, 2, '0', STR_PAD_LEFT); } 
								
						echo '
          <td style="background:OrangeRed;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:OrangeRed;">'.str_pad($prodCalendario_iddiamp, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalendario[$prodCalendario_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_iddiames.'&hstfecha='.$prodCalendario_iddiames.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
						$prodCalendario_iddiamp++; 
					}  
				}
      }
		}
		else
		{ for ($dianum = 1; $dianum<=7; $dianum++)
			{	if (checkdate($prodCalendario_idmes, $prodCalendario_iddia, $prodCalendario_idano))
				{ $prodCalendario_iddiames = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT);
					$prodCalendario_idsemana = date("W", mktime(0,0,0,$prodCalendario_idmes,$prodCalendario_iddia,$prodCalendario_idano)); 
          
          if ($dianum == 1) 
          { $prodCalendario_inisemana = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT);  
            $prodFchCalendario_smn = $prodFchCalendario[$prodCalendario_iddiames]; } 
          else
          { $prodFchCalendario_smn = $prodFchCalendario_smn+$prodFchCalendario[$prodCalendario_iddiames]; }  
					
          if ($dianum == 7) 
          { $prodCalendario_finsemana = $prodCalendario_idano.'-'.str_pad($prodCalendario_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT); }  
				  
					echo '
          <td style="background:orange;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:orange;">'.str_pad($prodCalendario_iddia, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalendario[$prodCalendario_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_iddiames.'&hstfecha='.$prodCalendario_iddiames.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>
            </table>
          </td>'; 
			   
					if (($prodCalendario_iddia) >= $prodCalendario_diasmes) 
          { $prodCalendario_idsemanas = 1; }	           
				 
					$prodCalendario_iddia++; 
				} 				
				else
				{ if (checkdate($prodCalendario_idmessiguiente, $prodCalendario_iddiams, $prodCalendario_idanosiguiente))
					{ $prodCalendario_iddiames = $prodCalendario_idanosiguiente.'-'.str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiams, 2, '0', STR_PAD_LEFT);
						$prodCalendario_idsemana = date("W", mktime(0,0,0,$prodCalendario_idmessiguiente,$prodCalendario_iddiams,$prodCalendario_idanosiguiente)); 

            if ($dianum == 1) 
            { $prodCalendario_inisemana = $prodCalendario_idanosiguiente.'-'.str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiams, 2, '0', STR_PAD_LEFT);  
              $prodFchCalendario_smn = $prodFchCalendario[$prodCalendario_iddiames]; } 
            else
            { $prodFchCalendario_smn = $prodFchCalendario_smn+$prodFchCalendario[$prodCalendario_iddiames]; }
              
						if ($dianum == 7) 
            { $prodCalendario_finsemana = $prodCalendario_idanosiguiente.'-'.str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodCalendario_iddiams, 2, '0', STR_PAD_LEFT); } 				
				
						echo '
          <td style="background:OliveDrab;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:OliveDrab;">'.str_pad($prodCalendario_iddiams, 2, '0', STR_PAD_LEFT).'</th>
                </tr>
              </thead>
              
              <tbody>';
                  
            if ($prodFchCalendario[$prodCalendario_iddiames] <= 0)
            { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
            else
            { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_iddiames.'&hstfecha='.$prodCalendario_iddiames.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
            echo '	                  
              </tbody>
              
              <tfoot>
              </tfoot>              
            </table>
          </td>'; 
			   
						$prodCalendario_iddiams++; 
					}  
				}	
			}
		}
    
		echo '	  
          <td style="background:Gray;">
            <table>
              <thead>
                <tr>
                  <th colspan="2" width="50">&nbsp;</th>
                  <th style="background:Gray;">'.str_pad($prodCalendario_idsemana, 2, '0', STR_PAD_LEFT).'</th>
                </tr>				    
              </thead>
              
              <tbody>';
                  
    if ($prodFchCalendario_smn <= 0)
    { echo '
                <tr align="center">
                  <td colspan="2">&nbsp;</td>
                  <td></td>
                </tr>'; }
    else
    { echo '
                <tr align="center">
                  <td align="right"><b></b></td>
                  <td></td>
                  <td><a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_inisemana.'&hstfecha='.$prodCalendario_finsemana.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a></td>                     
                </tr>'; }              
                
    echo '	                  
              </tbody> 

              <tfoot>
              </tfoot>
            </table>
          </td>
        </tr>';
	}		  
  
	echo '	
      </tbody>';

  echo '
      <tfoot>
        <form id="form_produccion" name="form_produccion" method="POST" action="prodCalendario.php" onSubmit="return validarNewItem()">
          <input type="hidden" name="hide_dsdfecha" id="hide_dsdfecha" value="'.$prodCalendario_idanoprevio."-".str_pad($prodCalendario_idmesprevio, 2, '0', STR_PAD_LEFT)."-01".'">
          <input type="hidden" name="hide_hstfecha" id="hide_hstfecha" value="'.$prodCalendario_idanosiguiente."-".str_pad($prodCalendario_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31".'">

                <tr><td align="left" colspan="2"><strong>Procesos</strong><br/>
                    <select id="slc_cdgproceso" name="slc_cdgproceso" style="width:120px;" onchange="document.form_produccion.submit()">
                      <option value="" selected="selected">- selecciona -</option>';

    $link_mysqli = conectar();
    $prodProcesoSelect = $link_mysqli->query("
      SELECT * FROM prodproceso
      WHERE sttproceso = '1'
      ORDER by idproceso");

    while ($regProdProceso = $prodProcesoSelect->fetch_object())
    { echo '
                    <option value="'.$regProdProceso->cdgproceso.'"';

      if ($prodCalendario_cdgproceso == $regProdProceso->cdgproceso) 
      { echo ' selected="selected"'; }

      echo '>'.$regProdProceso->proceso.'</option>'; }

    echo '
                    </select></td>
                  <td align="left" colspan="2"><strong>Empleados</strong><br/>
                    <select id="slc_cdgempleado" name="slc_cdgempleado" style="width:120px;" onchange="document.form_produccion.submit()">
                      <option value="" selected="selected">- selecciona -</option>';

    $link_mysqli = conectar();
    $rechEmpleadoSelect = $link_mysqli->query("
      SELECT * FROM rechempleado
      WHERE sttempleado = '1'
      ORDER by empleado");

    while ($regRechEmpleado = $rechEmpleadoSelect->fetch_object())
    { echo '
                    <option value="'.$regRechEmpleado->cdgempleado.'"';

      if ($prodCalendario_cdgempleado == $regRechEmpleado->cdgempleado) 
      { echo ' selected="selected"'; }

      echo '>'.$regRechEmpleado->empleado.'</option>'; }

    echo '
                    </select></td>
          <td align="left" colspan="2"><strong>Productos</strong><br/>
            <select id="slc_cdgproducto" name="slc_cdgproducto" onchange="document.form_produccion.submit()" style="width:120px;">
              <option value="">Selecciona una opcion</option>';
    
    for ($id_proyecto = 1; $id_proyecto <= $numproyectos; $id_proyecto++) 
    { echo '
              <optgroup label="'.$pdtoProyecto_proyecto[$id_proyecto].'">';

      for ($id_impresion = 1; $id_impresion <= $numimpresiones[$id_proyecto]; $id_impresion++) 
      { echo '
              <option value="'.$pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion].'"';
            
        if ($prodCalendario_cdgproducto == $pdtoImpresion_cdgimpresion[$id_proyecto][$id_impresion]) 
        { echo ' selected="selected"'; }

        echo '>'.$pdtoImpresion_impresion[$id_proyecto][$id_impresion].'</option>'; }

      echo '
              </optgroup>'; }
    
    echo '
            </select></td>
          <td align="center" colspan="4"><strong>Rango de fechas</strong> <a href="pdf/prodCalendarioPdf.php?dsdfecha='.$prodCalendario_dsdrngfecha.'&hstfecha='.$prodCalendario_hstrngfecha.'&cdgproceso='.$prodCalendario_cdgproceso.'&cdgempleado='.$prodCalendario_cdgempleado.'&cdgproducto='.$prodCalendario_cdgproducto.'" target="_blank">'.$png_acrobat.'</a><br/>
            <label for="label_dsdfecha"><strong>Desde</strong></label>
            <input type="date" id="date_dsdfecha" name="date_dsdfecha" value="'.$prodCalendario_dsdrngfecha.'" onchange="document.form_produccion.submit()" style="width:140px;" /><br/>
            <label for="label_hstfecha"><strong>Hasta</strong></label>
            <input type="date" id="date_hstfecha" name="date_hstfecha" value="'.$prodCalendario_hstrngfecha.'" onchange="document.form_produccion.submit()" style="width:140px;" /></td></tr>  
        </form>    
      </tfoot>
    </table>';
    
?>

  </body>	
</html>