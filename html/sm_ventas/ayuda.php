<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php
  include '../datos/mysql.php';
  $link = conectar();

  m3nu_ventas();

  $sistModulo_cdgmodulo = '30000';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

    if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { ma1n(); }
    else 
    { echo '
      <div id="loginform">
        <form id="login" action="/sm_ventas/ayuda.php" method="post">';

      log1n();

      echo '
        </form>
      </div>
    </div>
  </body>
</html>'; 
      exit; }

    if (substr($sistModulo_permiso,0,1) == 'r')
    { echo '
    <h2><a name="Clientes">Clientes</a></h2>
    <p>En este módulo se almacenan los datos necesarios de los clientes que se liberan por el área de ventas para manejo por parte del área de logística, ingresando la siguiente información:</p>
    
    <ul>
      <li>Identificador/RFC</li>
      <li>Nombre/Razón social</li>
      <li>Domicilio/Calle y número</li>
      <li>Colonia</li>
      <li>Ciudad</li>
      <li>Código postal</li>
      <li>Teléfono</li>
    </ul>

    <p>Este módulo es el primero de cuatro pasos para dar de alta un cliente en el sistema, ya que aquí solo se ingresan datos de contacto. Los siguientes pasos se realiza en los módulos Contactos por cliente, Sucursales y Contactos por sucursal.</p>

    <h2><a name="ContactosCliente">Contactos por cliente</a></h2>
    <p>En este módulo se almacenan los datos necesarios de los contactos de cada cliente, con la finalidad de proporcionar en un mismo sitio la información necesaria para mantener una comunicación estrecha con nuestros clientes. La información almacenada es la siguiente:</p>

    <ul>
      <li>Cliente</li>
      <li>Identificador</li>
      <li>Nombre</li>
      <li>Puesto</li>
      <li>Teléfono</li>
      <li>Móvil</li>
      <li>eM@il</li>
    </ul>

    <p>Este es el segundo de cuatro pasos para registrar un cliente aunque no es obligatorio.</p>

    <h2><a name="Sucursales">Sucursales</a></h2>
    <p>En este módulo se almacenan las sucursales de cada cliente, cada cliente debe contener al menos una sucursal a donde les será enviado el productos solicitado. La información requerida es la siguiente:</p>

    <ul>
      <li>Cliente</li>
      <li>Identificador/Código</li>
      <li>Nombre/Sucursal</li>
      <li>Domicilio/Calle y número</li>
      <li>Colonia</li>
      <li>Ciudad</li>
      <li>Código postal</li>
      <li>Teléfono</li>
      <li>Transporte</li>
    </ul>

    <p>Este es el tercero de cuatro pasos para registrar un cliente, y el segundo más importante ya que las sucursales representan los destinos y sin ellos no es posible realizar embarques.</p>

    <h2><a name="ContactosSucursal">Contactos por Sucursal</a></h2>
    <p>En este módulo se almacenan los datos necesarios de los contactos de cada sucursal de cada cliente, con la finalidad de proporcionar en un mismo sitio la información necesaria para mantener una comunicación estrecha con las sucursales de nuestros clientes. La información almacenada es la siguiente:</p>

    <ul>
      <li>Cliente</li>
      <li>Identificador/Código</li>
      <li>Nombre</li>
      <li>Puesto</li>
      <li>Teléfono</li>
      <li>Móvil</li>
      <li>eM@il</li>
    </ul>

    <p>Este es el segundo de cuatro pasos para registrar un cliente aunque no es obligatorio.</p>

    <h2><a name="OC">Orden de compra</a></h2>
    <p>En este módulo se registran las distintas ordenes de compra de nuestros clientes, ingresando la siguiente información:</p>

    <ul>
      <li>Sucursal</li>
      <li>Folio de la O.C.</li>
      <li>Fecha documento</li>
      <li>Fecha recepción</li>
    </ul>

    <p>Este es el primer paso para ingresar he informar a al área de producción acerca de las necesidades de nuestros clientes.</p>

    <h2>Requerimientos de producto por orden de compra</h2>
    <p>En este módulo es donde se ingresa el detalle de lo requerido por nuestros clientes, ingresando los siguientes datos:</p>

    <ul>
      <li>Orden de compra</li>
      <li>Producto/Cliente</li>
      <li>Cantidad</li>
      <li>Referencia</li>
    </ul>

    <p>En el dato <em>Producto</em> se indica el producto como lo conoce el cliente, ya que un producto para el cliente puede representar varios dentro de la organización de acuerdo con la temporada del año o años.</p>

    <h2>Confirmaciones de producto por orden de compra</h2>
    <p>En este módulo se confirma y detalla el producto a producir, además de la presentación y fecha en que lo solicita el cliente.</p>

    <ul>
      <li>Orden de compra</li>
      <li>Producto/Empresa</li>
      <li>Empaque/Presentación</li>
      <li>Cantidad</li>
      <li>Referencia</li>
      <li>Fecha embarque</li>
      <li>Fecha entrega</li>
    </ul>

    <p>Se solicita fecha embarque y fecha entrega para estimar con que anticipación debe salir el producto de la planta para cumplir el tiempo con las necesidades de nuestros clientes, apoyando el proceso de logística ya que en ocasiones los destinos son alcanzados en plazos mayores a 24 horas.</p>

    <h2>Embarque</h2>
    <p>En este módulo se generan los embarques o listas de salida, para lo cual es necesaria la siguiente información:</p>

    <ul>
      <li>Sucursal</li>
      <li>Producto/Empresa</li>
      <li>Empaque/Presentación</li>
      <li>Transporte</li>
      <li>Referencia</li>
      <li>Fecha embarque</li>
      <li>Observaciones</li>
    </ul>

    <p>Los embarques solicitan la sucursal, el producto y la presentación con la finalidad de validar al momento de surtir cada una de las <em>Confirmaciones de producto por orden de compra</em>. Desde este módulo se imprimen las listas de salida y las etiquetas de identificación y rastreo para cada uno de los empaques del embarque.</p>

    <h2>Surtido de confirmaciones</h2>
    <p>En este módulo se vinculan los embarques a las <em>Confirmaciones de producto por orden de compra</em> validando que coincida la sucursal, el producto y la presentación con la finalidad de evitar errores en los envíos. Mostrando una lista desplegable con los embarque compatibles.</p>

    <h2>Armado de embarques</h2>
    <p>En este módulo se filtran los empaque compatibles con el embarque, tomando en cuenta el producto y la presentación. De los cuales se seleccionan los necesarios para hasta cubrir la cantidad deseada.</p>'; }

  } else
  { echo '
    <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
  </body>
</html>