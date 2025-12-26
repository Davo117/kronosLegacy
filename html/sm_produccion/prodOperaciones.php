<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Histórico de Operaciones</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <!--<a href="ayuda.php"><img id="imagen_ayuda" src="../img_sistema/help_blue.png" border="0"/></a>-->
        <Label><h1>Producción</h1></label>
      </section><?php
  
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();

  $sistModulo_cdgmodulo = '600HO';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }
  }  

  // --> Nombre de los dias
  $diatxt[0] = "Domingo";
  $diatxt[1] = "Lunes";
  $diatxt[2] = "Martes";
  $diatxt[3] = "Miercoles";
  $diatxt[4] = "Jueves";
  $diatxt[5] = "Viernes";
  $diatxt[6] = "Sabado";
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

  if ($_POST['dateDsdFecha'])
  { $prodOperaciones_dsdfecha = $_POST['dateDsdFecha']; }
  else
  { $prodOperaciones_dsdfecha = date(); }

  if ($_POST['dateDsdFecha']) { $prodOperaciones_dsdrngfecha = $_POST['dateDsdFecha']; }
  if ($_POST['dateHstFecha']) { $prodOperaciones_hstrngfecha = $_POST['dateHstFecha']; }

  $prodOperaciones_dsdrngfecha = ValidarFecha($prodOperaciones_dsdrngfecha);
  $prodOperaciones_hstrngfecha = ValidarFecha($prodOperaciones_hstrngfecha);

  if (($_POST['hideDsdFecha'] AND $_POST['hideHstFecha']) AND ($_POST['slctCdgOperacion'] OR $_POST['slctCdgEmpleado'] OR $_POST['slctCdgProducto']))
  { $prodOperaciones_cdgoperacion = $_POST['slctCdgOperacion'];
    $prodOperaciones_cdgempleado = $_POST['slctCdgEmpleado'];
    $prodOperaciones_cdgproducto = $_POST['slctCdgProducto'];

    $prodOperaciones_dsdfecha = $_POST['hideDsdFecha'];
    $prodOperaciones_hstfecha = $_POST['hideHstFecha'];

    $prodOperaciones_idmesprevio = substr($prodOperaciones_dsdfecha,5,2);
    $prodOperaciones_idanoprevio = substr($prodOperaciones_dsdfecha,0,4);

    if ($prodOperaciones_idmesprevio == 12)
    { $prodOperaciones_idano = $prodOperaciones_idanoprevio+1;
      $prodOperaciones_idmes = 1;
    } else
    { $prodOperaciones_idano = $prodOperaciones_idanoprevio;
      $prodOperaciones_idmes = $prodOperaciones_idmesprevio+1; }

    $prodOperaciones_idmessiguiente = substr($prodOperaciones_hstfecha,5,2);
    $prodOperaciones_idanosiguiente = substr($prodOperaciones_hstfecha,0,4);
  } else
  { $prodOperaciones_cdgoperacion = $_GET['cdgoperacion'];
    $prodOperaciones_cdgempleado = $_GET['cdgempleado'];
    $prodOperaciones_cdgproducto = $_GET['cdgproducto'];

    if ($_GET['idano'] AND $_GET['idmes'])
    { $prodOperaciones_idano = $_GET['idano'];
      $prodOperaciones_idmes = $_GET['idmes']; }
    else
    { $prodOperaciones_idano = date("Y");
      $prodOperaciones_idmes = date("n"); }

    if ($prodOperaciones_idmes == 1)


    { $prodOperaciones_idmesprevio = 12;
      $prodOperaciones_idanoprevio = $prodOperaciones_idano-1;

      $prodOperaciones_idmessiguiente = 2;
      $prodOperaciones_idanosiguiente = $prodOperaciones_idano;
    }
    else
    { if ($prodOperaciones_idmes == 12)
      { $prodOperaciones_idmesprevio = 11;
        $prodOperaciones_idanoprevio = $prodOperaciones_idano;

        $prodOperaciones_idmessiguiente = 1;
        $prodOperaciones_idanosiguiente = $prodOperaciones_idano+1;
      }
      else
      { $prodOperaciones_idmesprevio = $prodOperaciones_idmes-1;
        $prodOperaciones_idanoprevio = $prodOperaciones_idano;

        $prodOperaciones_idmessiguiente = $prodOperaciones_idmes+1;
        $prodOperaciones_idanosiguiente = $prodOperaciones_idano;
      }
    }
  }

  $prodOperaciones_diasmesprevio = date("t", mktime(0,0,0,$prodOperaciones_idmesprevio,1,$prodOperaciones_idanoprevio));
  $prodOperaciones_diasmessiguiente = date("t", mktime(0,0,0,$prodOperaciones_idmessiguiente,1,$prodOperaciones_idanosiguiente));
  $prodOperaciones_diasmes = date("t", mktime(0,0,0,$prodOperaciones_idmes,1,$prodOperaciones_idano));
  $prodOperaciones_dia1mes = date("N", mktime(0,0,0,$prodOperaciones_idmes,1,$prodOperaciones_idano));

  if ($prodOperaciones_dia1mes == 7)
  { $prodOperaciones_dia1mes = 0; }

  if ($prodOperaciones_cdgoperacion == '')
  { if ($prodOperaciones_cdgempleado == '')
    { if ($prodOperaciones_cdgproducto == '')
      { // Todas las operaciones
        // Todos los empleados
        // Todos los productos

        //Lotes
        $prodLoteOpeSelect= $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote) AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($regProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdLoteOpe->fchoperacion] += $regProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina) AND
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] += $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo) AND
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] += $regProdRolloOpe->metros; }
        }   
      } else
      { // Todas las operaciones
        // Todos los empleados
        // Un solo producto $prodOperaciones_cdgproducto

        //Lotes
        $prodLoteOpeSelect= $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodlote.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($regProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdLoteOpe->fchoperacion] += $regProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobina.cdgproducto = '".$prodOperaciones_cdgproducto."') AND                 
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] += $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrollo.cdgproducto = '".$prodOperaciones_cdgproducto."') AND                 
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] += $regProdRolloOpe->metros; }
        }
      }
    } else
    { if ($prodOperaciones_cdgproducto == '')
      { // Todas las operaciones
        // Un solo empleado $prodOperaciones_cdgempleado
        // Todos los productos

        //Lotes
        $prodLoteOpeSelect= $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgempleado = '".$prodOperaciones_cdgempleado."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($regProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdLoteOpe->fchoperacion] += $regProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina
                 prodbobinaope.cdgempleado = '".$prodOperaciones_cdgempleado."') AND                 
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] += $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgempleado = '".$prodOperaciones_cdgempleado."') AND                 
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] += $regProdRolloOpe->metros; }
        }
      } else
      { // Todas las operaciones
        // Un solo empleado $prodOperaciones_cdgempleado
        // Un solo producto $prodOperaciones_cdgproducto

        //Lotes
        $prodLoteOpeSelect= $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                (prodlote.cdgproducto = '".$prodOperaciones_cdgproducto."') AND                 
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($regProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdLoteOpe->fchoperacion] += $regProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgempleado = '".$prodOperaciones_cdgempleado."') AND
                (prodbobina.cdgproducto = '".$prodOperaciones_cdgproducto."') AND                 
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] += $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgempleado = '".$prodOperaciones_cdgempleado."') AND
                (prodrollo.cdgproducto = '".$prodOperaciones_cdgproducto."') AND                 
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] += $regProdRolloOpe->metros; }
        }        
      }
    }
  } else
  { if ($prodOperaciones_cdgempleado == '')
    { if ($prodOperaciones_cdgproducto == '')
      { // Una sola operacion $prodOperaciones_cdgoperacion
        // Todos los empleados
        // Todos los productos

        // Lotes
        $prodLoteOpeSelect = $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($resProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$resProdLoteOpe->fchoperacion] = $resProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] = $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] = $regProdRolloOpe->metros; }
        }
      } else
      { // Una sola operacion $prodOperaciones_cdgoperacion
        // Todos los empleados
        // Un solo producto $prodOperaciones_cdgproducto

        // Lotes
        $prodLoteOpeSelect = $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodlote.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($resProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$resProdLoteOpe->fchoperacion] = $resProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodbobina.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] = $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodrollo.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] = $regProdRolloOpe->metros; }
        }
      }
    } else
    { if ($prodOperaciones_cdgproducto == '')
      { // Una sola operacion $prodOperaciones_cdgoperacion
        // Un solo empleado $prodOperaciones_cdgempleado
        // Todos los productos

        // Lotes
        $prodLoteOpeSelect = $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodloteope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($resProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$resProdLoteOpe->fchoperacion] = $resProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodbobinaope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] = $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND 
                 prodrolloope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodrolloope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] = $regProdRolloOpe->metros; }
        }
      } else
      { // Una sola operacion $prodOperaciones_cdgoperacion
        // Un solo empleado $prodOperaciones_cdgempleado
        // Un solo producto $prodOperaciones_cdgproducto

        // Lotes
        $prodLoteOpeSelect = $link->query("
          SELECT prodloteope.fchoperacion,
             SUM(prodloteope.longitud) AS metros
            FROM prodlote,
                 prodloteope
          WHERE (prodlote.cdglote = prodloteope.cdglote AND
                 prodloteope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodloteope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodlote.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodloteope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodloteope.fchoperacion");

        if ($prodLoteOpeSelect->num_rows > 0)
        { while($resProdLoteOpe = $prodLoteOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$resProdLoteOpe->fchoperacion] = $resProdLoteOpe->metros; }
        }

        // Bobinas
        $prodBobinaOpeSelect = $link->query("
          SELECT prodbobinaope.fchoperacion,
             SUM(prodbobinaope.longitud) AS metros
            FROM prodbobina,
                 prodbobinaope
          WHERE (prodbobina.cdgbobina = prodbobinaope.cdgbobina AND
                 prodbobinaope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodbobinaope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodbobina.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodbobinaope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')
        GROUP BY prodbobinaope.fchoperacion");

        if ($prodBobinaOpeSelect->num_rows > 0)
        { while($regProdBobinaOpe = $prodBobinaOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdBobinaOpe->fchoperacion] = $regProdBobinaOpe->metros; }
        }

        // Rollos
        $prodRolloOpeSelect = $link->query("
          SELECT prodrolloope.fchoperacion,
             SUM(prodrolloope.longitud) AS metros
            FROM prodrollo,
                 prodrolloope
          WHERE (prodrollo.cdgrollo = prodrolloope.cdgrollo AND
                 prodrolloope.cdgempleado = '".$prodOperaciones_cdgempleado."' AND
                 prodrolloope.cdgoperacion = '".$prodOperaciones_cdgoperacion."') AND
                (prodrollo.cdgproducto = '".$prodOperaciones_cdgproducto."') AND
         (SUBSTR(prodrolloope.fchoperacion,1,7) BETWEEN '".$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."' AND '".$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."')                 
        GROUP BY prodrolloope.fchoperacion");

        if ($prodRolloOpeSelect->num_rows > 0)
        { while($regProdRolloOpe = $prodRolloOpeSelect->fetch_object())
          { $prodOperaciones_registradas[$regProdRolloOpe->fchoperacion] = $regProdRolloOpe->metros; }
        }        
      }
    }
  }
  
  // Catalogo de operaciones
  $prodOperacionSelect = $link->query("
    SELECT * FROM prodoperacion
     WHERE sttoperacion = '1'
  ORDER BY idoperacion");

  if ($prodOperacionSelect->num_rows > 0)
  { $item = 0;

    while ($regProdOperacion = $prodOperacionSelect->fetch_object())
    { $item++;

      $prodOperacion_operacion[$item] = $regProdOperacion->operacion;
      $prodOperacion_cdgoperacion[$item] = $regProdOperacion->cdgoperacion;

      $prodOperaciones_operacion[$regProdOperacion->cdgoperacion] = $regProdOperacion->operacion; }
  
    $nOperaciones = $item; }

  // Catalogo de empleados
  $rechEmpleadoSelect = $link->query("
    SELECT * FROM rechempleado
     WHERE sttempleado = '1'
  ORDER BY empleado");

  if ($rechEmpleadoSelect->num_rows > 0)
  { $item = 0;

    while ($regRechEmpleado = $rechEmpleadoSelect->fetch_object())
    { $item++;

      $prodOperacion_empleado[$item] = $regRechEmpleado->empleado;
      $prodOperacion_cdgempleado[$item] = $regRechEmpleado->cdgempleado;

      $prodOperaciones_empleados[$regRechEmpleado->cdgempleado] = $regRechEmpleado->empleado; }
  
    $nEmpleados = $item; }

  // Catalogo de productos
  $pdtoImpresionSelect = $link->query("
    SELECT pdtodiseno.diseno,
           pdtoimpresion.impresion,
           pdtoimpresion.cdgimpresion
      FROM pdtodiseno,
           pdtoimpresion
     WHERE pdtodiseno.cdgdiseno = pdtoimpresion.cdgdiseno AND
          (pdtodiseno.sttdiseno = '1' AND pdtoimpresion.sttimpresion = '1')
  ORDER BY pdtoimpresion.impresion");

  if ($pdtoImpresionSelect->num_rows > 0)
  { $item = 0;

    while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object())
    { $item++;

      $prodOperacion_producto[$item] = $regPdtoImpresion->diseno.' | '.$regPdtoImpresion->impresion;
      $prodOperacion_cdgproducto[$item] = $regPdtoImpresion->cdgimpresion;

      $prodOperaciones_productos[$regPdtoImpresion->cdgimpresion] = $regPdtoImpresion->diseno.' | '.$regPdtoImpresion->impresion; }
  }

  $pdtoBandaPSelect = $link->query("
    SELECT pdtobanda.banda,
           pdtobandap.bandap,
           pdtobandap.cdgbandap
      FROM pdtobanda,
           pdtobandap
     WHERE pdtobanda.cdgbanda = pdtobandap.cdgbanda AND
          (pdtobanda.sttbanda = '1' AND pdtobandap.sttbandap = '1')
  ORDER BY pdtobandap.bandap");

  if ($pdtoBandaPSelect->num_rows > 0)
  { while ($regPdtoBandaP = $pdtoBandaPSelect->fetch_object())
    { $item++;

      $prodOperacion_producto[$item] = $regPdtoBandaP->banda.' | '.$regPdtoBandaP->bandap;
      $prodOperacion_cdgproducto[$item] = $regPdtoBandaP->cdgbandap;

      $prodOperaciones_productos[$regPdtoBandaP->cdgbandap] = $regPdtoBandaP->banda.' | '.$regPdtoBandaP->bandap; }
  }

  $nProductos = $item;

  // Catálogo de productos
  $pdtoDisenoSelect = $link->query("
    SELECT * FROM pdtodiseno
     WHERE sttdiseno = '1'
  ORDER BY diseno,
           iddiseno");
  
  if ($pdtoDisenoSelect->num_rows > 0)
  { $item = 0;

    while ($regPdtoDiseno = $pdtoDisenoSelect->fetch_object()) 
    { $item++;

      $pdtoDiseno_iddiseno[$item] = $regPdtoDiseno->iddiseno;
      $pdtoDiseno_diseno[$item] = $regPdtoDiseno->diseno;
      $pdtoDiseno_cdgdiseno[$item] = $regPdtoDiseno->cdgdiseno; 
      
      $pdtoImpresionSelect = $link->query("
        SELECT * FROM pdtoimpresion
         WHERE cdgdiseno = '".$regPdtoDiseno->cdgdiseno."' AND 
               sttimpresion = '1'
      ORDER BY impresion,
               idimpresion");
      
      if ($pdtoDisenoSelect->num_rows > 0)
      { $subItem = 0;

        while ($regPdtoImpresion = $pdtoImpresionSelect->fetch_object()) 
        { $subItem++;

          $pdtoImpresion_idimpresion[$item][$subItem] = $regPdtoImpresion->idimpresion;
          $pdtoImpresion_impresion[$item][$subItem] = $regPdtoImpresion->impresion;
          $pdtoImpresion_cdgimpresion[$item][$subItem] = $regPdtoImpresion->cdgimpresion; }

        $nImpresiones[$item] = $pdtoImpresionSelect->num_rows; }
    } 

    $nDisenos = $pdtoDisenoSelect->num_rows; }

  // Armado de la pagina
  echo '
      <div class="bloque">
        <article class="subbloque">
          <label class="modulo_listado">Histórico de Operaciones</label>
        </article>

        <section class="listado">
          <table align="center">
            <thead>
              <tr align="center">
                <th><a href="prodOperaciones.php?idano='.$prodOperaciones_idanoprevio.'&idmes='.$prodOperaciones_idmesprevio.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'">'.$png_button_blue_rew.'</a></th>
                <th>'.$mestxt[$prodOperaciones_idmesprevio].'<br />'.$prodOperaciones_idanoprevio.'</th>
                <th colspan="4"><h1>'.$mestxt[$prodOperaciones_idmes].' '.$prodOperaciones_idano.'</h1></th>
                <th>'.$mestxt[$prodOperaciones_idmessiguiente].'<br />'.$prodOperaciones_idanosiguiente.'</th>
                <th><a href="prodOperaciones.php?idano='.$prodOperaciones_idanosiguiente.'&idmes='.$prodOperaciones_idmessiguiente.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'">'.$png_button_blue_ffw.'</a></th>
              </tr>
              <tr>';

  for ($dianum = 0; $dianum<=6; $dianum++)
  { echo '
                <th>'.$diatxt[$dianum].'</th>'; }

  echo '
                <th>Semana</th>
              </tr>
            </thead>

            <tbody>';

  $prodOperaciones_iddiamp = (($prodOperaciones_diasmesprevio+1)-($prodOperaciones_dia1mes));
  $prodOperaciones_iddia = 1;
  $prodOperaciones_iddiams = 1;

  for ($prodOperaciones_idsemana = 1; !$prodOperaciones_idsemanas; $prodOperaciones_idsemana++)
  { echo '
              <tr>';

    if ($prodOperaciones_idsemana == 1)
    { for ($dianum = 0; $dianum<=6; $dianum++)
      {  if ($dianum >= $prodOperaciones_dia1mes)
        { if (checkdate($prodOperaciones_idmes, $prodOperaciones_iddia, $prodOperaciones_idano))
          { $prodOperaciones_iddiames = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT);
            $prodOperaciones_idsemana = date("W", mktime(0,0,0,$prodOperaciones_idmes,$prodOperaciones_iddia,$prodOperaciones_idano));

            if ($dianum == 0)
            { $prodOperaciones_inisemana = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT);
              $prodOperaciones_registradas_smn = $prodOperaciones_registradas[$prodOperaciones_iddiames]; }
            else
            { $prodOperaciones_registradas_smn = $prodOperaciones_registradas_smn+$prodOperaciones_registradas[$prodOperaciones_iddiames]; }

            if ($dianum == 6)
            { $prodOperaciones_finsemana = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT); }

            echo '
                <td>
                  <article class="subbloque" style="background-color:#F2F5A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>

                    <tbody>';

            if ($prodOperaciones_registradas[$prodOperaciones_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>'; 
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_iddiames.'&hstfecha='.$prodOperaciones_iddiames.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a></td>
                      </tr>'; }

            echo '
                    </tbody>

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>';

            if (($prodOperaciones_iddia) >= $prodOperaciones_diasmes)
            { $prodOperaciones_idsemanas = 1; }

            $prodOperaciones_iddia++;
          }
        } else
        { if (checkdate($prodOperaciones_idmesprevio, $prodOperaciones_iddiamp, $prodOperaciones_idanoprevio))
          { $prodOperaciones_iddiames = $prodOperaciones_idanoprevio.'-'.str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiamp, 2, '0', STR_PAD_LEFT);
            $prodOperaciones_idsemana = date("W", mktime(0,0,0,$prodOperaciones_idmesprevio,$prodOperaciones_iddiamp,$prodOperaciones_idanoprevio));

            if ($dianum == 0)
            { $prodOperaciones_inisemana = $prodOperaciones_idanoprevio.'-'.str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiamp, 2, '0', STR_PAD_LEFT);
              $prodOperaciones_registradas_smn = $prodOperaciones_registradas[$prodOperaciones_iddiames]; }
            else
            { $prodOperaciones_registradas_smn = $prodOperaciones_registradas_smn+$prodOperaciones_registradas[$prodOperaciones_iddiames]; }

            if ($dianum == 6)
            { $prodOperaciones_finsemana = $prodOperaciones_idanoprevio.'-'.str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiamp, 2, '0', STR_PAD_LEFT); }

            echo '
                <td>
                  <article class="subbloque" style="background-color:#F5D0A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodOperaciones_iddiamp, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>

                    <tbody>';

            if ($prodOperaciones_registradas[$prodOperaciones_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_iddiames.'&hstfecha='.$prodOperaciones_iddiames.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a></td>
                      </tr>'; }

            echo '
                    </tbody>

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>';

            $prodOperaciones_iddiamp++;
          }
        }
      }
    }
    else
    { for ($dianum = 0; $dianum<=6; $dianum++)
      { if (checkdate($prodOperaciones_idmes, $prodOperaciones_iddia, $prodOperaciones_idano))
        { $prodOperaciones_iddiames = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT);
          $prodOperaciones_idsemana = date("W", mktime(0,0,0,$prodOperaciones_idmes,$prodOperaciones_iddia,$prodOperaciones_idano));

          if ($dianum == 0)
          { $prodOperaciones_inisemana = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT);
            $prodOperaciones_registradas_smn = $prodOperaciones_registradas[$prodOperaciones_iddiames]; }
          else
          { $prodOperaciones_registradas_smn = $prodOperaciones_registradas_smn+$prodOperaciones_registradas[$prodOperaciones_iddiames]; }

          if ($dianum == 6)
          { $prodOperaciones_finsemana = $prodOperaciones_idano.'-'.str_pad($prodOperaciones_idmes, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT); }

          echo '
                <td>
                  <article class="subbloque" style="background-color:#F2F5A9">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodOperaciones_iddia, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>

                    <tbody>';

            if ($prodOperaciones_registradas[$prodOperaciones_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_iddiames.'&hstfecha='.$prodOperaciones_iddiames.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a></td>
                      </tr>'; }

            echo '
                    </tbody>

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>';

          if (($prodOperaciones_iddia) >= $prodOperaciones_diasmes)
          { $prodOperaciones_idsemanas = 1; }

          $prodOperaciones_iddia++;
        } else
        { if (checkdate($prodOperaciones_idmessiguiente, $prodOperaciones_iddiams, $prodOperaciones_idanosiguiente))
          { $prodOperaciones_iddiames = $prodOperaciones_idanosiguiente.'-'.str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiams, 2, '0', STR_PAD_LEFT);
            $prodOperaciones_idsemana = date("W", mktime(0,0,0,$prodOperaciones_idmessiguiente,$prodOperaciones_iddiams,$prodOperaciones_idanosiguiente));

            if ($dianum == 0)
            { $prodOperaciones_inisemana = $prodOperaciones_idanosiguiente.'-'.str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiams, 2, '0', STR_PAD_LEFT);
              $prodOperaciones_registradas_smn = $prodOperaciones_registradas[$prodOperaciones_iddiames]; 
            } else
            { $prodOperaciones_registradas_smn = $prodOperaciones_registradas_smn+$prodOperaciones_registradas[$prodOperaciones_iddiames]; }

            if ($dianum == 6)
            { $prodOperaciones_finsemana = $prodOperaciones_idanosiguiente.'-'.str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT).'-'.str_pad($prodOperaciones_iddiams, 2, '0', STR_PAD_LEFT); }

            echo '
                <td>
                  <article class="subbloque" style="background-color:#BEF781">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodOperaciones_iddiams, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>

                    <tbody>';

            if ($prodOperaciones_registradas[$prodOperaciones_iddiames] <= 0)
            { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>';
            } else
            { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_iddiames.'&hstfecha='.$prodOperaciones_iddiames.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a></td>
                      </tr>'; }

            echo '
                    </tbody>

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>';

            $prodOperaciones_iddiams++;
          }
        }
      }
    }

    echo '
                <td>
                  <article class="subbloque">
                  <table width="90px">
                    <thead>
                      <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="subbloque">'.str_pad($prodOperaciones_idsemana, 2, '0', STR_PAD_LEFT).'</th>
                      </tr>
                    </thead>

                    <tbody>';

    if ($prodOperaciones_registradas_smn <= 0)
    { echo '
                      <tr align="center"><td colspan="3">&nbsp;</td></tr>'; 
    } else
    { echo '
                      <tr align="center">
                        <td align="right"><b></b></td>
                        <td></td>
                        <td><a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_inisemana.'&hstfecha='.$prodOperaciones_finsemana.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a></td>
                      </tr>'; }

    echo '
                    </tbody>

                    <tfoot>
                    </tfoot>
                  </table>
                  </article>
                </td>
              </tr>'; }

  echo '
            </tbody>

            <tfoot>
              <tr align="left">
                <td colspan="8">
                  <form id="formProdOperaciones" name="formProdOperaciones" method="POST" action="prodOperaciones.php" >
                    <input type="hidden" name="hideDsdFecha" id="hideDsdFecha" value="'.$prodOperaciones_idanoprevio."-".str_pad($prodOperaciones_idmesprevio, 2, '0', STR_PAD_LEFT)."-01".'">
                    <input type="hidden" name="hideHstFecha" id="hideHstFecha" value="'.$prodOperaciones_idanosiguiente."-".str_pad($prodOperaciones_idmessiguiente, 2, '0', STR_PAD_LEFT)."-31".'">

                    <article>
                      <label><b>Operaciones</b></label><br/>
                      <select id="slctCdgOperacion" name="slctCdgOperacion" onchange="document.formProdOperaciones.submit()">
                        <option value="">-</option>';

    for ($item = 1; $item <= $nOperaciones; $item++) 
    { echo '
                        <option value="'.$prodOperacion_cdgoperacion[$item].'"';

      if ($prodOperaciones_cdgoperacion == $prodOperacion_cdgoperacion[$item])
      { echo ' selected="selected"'; }

      echo '>'.utf8_decode($prodOperacion_operacion[$item]).'</option>'; }

    echo '
                      </select>
                    </article> 

                    <article> 
                      <label><b>Empleados</b></label><br/>
                      <select id="slctCdgEmpleado" name="slctCdgEmpleado" onchange="document.formProdOperaciones.submit()">
                        <option value="">-</option>';

    for ($item = 1; $item <= $nEmpleados; $item++) 
    { echo '
                        <option value="'.$prodOperacion_cdgempleado[$item].'"';

      if ($prodOperaciones_cdgempleado == $prodOperacion_cdgempleado[$item])
      { echo ' selected="selected"'; }

      echo '>'.utf8_decode($prodOperacion_empleado[$item]).'</option>'; }

    echo '
                      </select>
                    </article>

                    <article>
                      <label><b>Productos</b></label><br/>
                      <select id="slctCdgProducto" name="slctCdgProducto" onchange="document.formProdOperaciones.submit()">
                        <option value="">-</option>';
    
    for ($item = 1; $item <= $nProductos; $item++) 
    { echo '
                        <option value="'.$prodOperacion_cdgproducto[$item].'"';

      if ($prodOperaciones_cdgproducto == $prodOperacion_cdgproducto[$item])
      { echo ' selected="selected"'; }

      echo '>'.utf8_decode($prodOperacion_producto[$item]).'</option>'; }

    echo '
                      </select>
                    </article>

                    <article>
                      <label><b>Desde</b></label><br/>
                      <input type="date" id="dateDsdFecha" name="dateDsdFecha" value="'.$prodOperaciones_dsdrngfecha.'" style="width:140px" onchange="document.formProdOperaciones.submit()" />              
                    </article>

                    <article>
                      <label><b>Hasta</b></label><br/>
                      <input type="date" id="dateHstFecha" name="dateHstFecha" value="'.$prodOperaciones_hstrngfecha.'" style="width:140px" onchange="document.formProdOperaciones.submit()" />              
                    </article>

                    <article>
                      <a href="pdf/prodOperacionesPdf.php?dsdfecha='.$prodOperaciones_dsdrngfecha.'&hstfecha='.$prodOperaciones_hstrngfecha.'&cdgoperacion='.$prodOperaciones_cdgoperacion.'&cdgempleado='.$prodOperaciones_cdgempleado.'&cdgproducto='.$prodOperaciones_cdgproducto.'" target="_blank">'.$_gear.'</a>
                    </article>
                  </form>
                </td>
              </tr>
            </tfoot>
          </table>
        <section>
      </div>';

    if ($msg_alert != '')
    { echo '
      <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }          
?>

    </div>
  </body>
</html>
