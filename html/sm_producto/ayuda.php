<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Ayuda</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_producto();  

  $sistModulo_cdgmodulo = '20000';
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
        <form id="login" action="ayuda.php" method="post">';

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
    <h2><a name="Disenos">Diseños</a></h2>
    <p>En este módulo se administran los diseños que se liberan por el área de ventas para su fabricación, ingresando la siguiente información:</p>
    
    <ul>
      <li>Código</li>
      <li>Descripción</li>
      <li>Consumos<br/>
        <input type="checkbox"/>Predeterminados</li>
    </ul>

    <p>Este módulo es el primero de dos pasos para dar de alta un producto en el sistema, al activar <input type="checkbox" checked disabled />Predeterminados se cargaran los consumos predeterminados que pueden ser editados en el módulo <a href=#Consumos>Consumos</a>. El siguiente paso se realiza en el módulo Impresión.</p>

    <h2><a name="Impresiones">Impresiones</a></h2>
    <p>En este módulo se administran las diferentes impresiones que se realizarán con un mismo diseño, asignando los Pantone por cada una de las capas deseadas de tinta, ingresando la siguiente información:</p>
    
    <ul>
      <li>Diseño</li>
      <li>Código</li>
      <li>Descripción</li>
      <li>Código cliente</li>
      <li>Ancho pelicula</li>
      <li>Altura de etiqueta</li>      
      <li>Sustrato</li>
      <li>Número de tintas</li>
      <li>Ancho de etiqueta</li>
      <li>Espacio para fusión</li>
      <li>Banda de seguridad</li>
      <li>Millares por rollo</li>
      <li>% +/- millares por rollo</li>
      <li>Millares por paquete</li>
      <li>Asignación de Pantone por capa de tinta
        <ul>
          <li>Pantone</li>
          <li>Disolvente</li>
          <li>Consumo</li>
        </ul>
      </li>
    </ul>
    
    <p>Como podemos notar, cada impresión es entonces una combinación de colores.</p>

    <h2><a name="Consumos">Consumos</a></h2>
    <p>En este módulo se administran las diferentes elementos necesarios a lo largo del proceso indicando su consumo por unidad base del producto (millar), ingresando la siguiente información:</p>
    
    <ul>
      <li>Diseño</li>
      <li>Subproceso</li>
        <ul>
          <li>Impresión</li>
          <li>Refilado</li>
          <li>Fusión</li>
          <li>Revisión</li>
          <li>Corte</li>
          <li>Empaque Corte (Caja)</li>
          <li>Empaque rollo (Queso)</li>
        </ul>
      <li>Elemento</li>
      <li>Consumo</li>
    </ul>
    
    <p>Como podemos notar, un mismo elemento pueder ser ingresado más de una vez siempre y cuando sea empleado en un subproceso diferente. Existe un diseño llamado "Predeterminado" el cual funje como plantilla de consumos y asi poder agilizar la carga de los mismos.</p>

    <h2><a name="Juegos">Juego de cilíndros</a></h2>
    <p>En este módulo de administran los juegos de cilindros empleados para la impresión y se monitorea su desgaste, tomando en cuenta información de producción como son los metros impresos contra los giros garantizados por el proveedor. Estimando la cantidad de producto que los cilindros podrán procesar de acuerdo con las dimensiones del diseño antes de verse afectada la impresión, Ingresando la siguiente información:</p>

    <ul>
      <li>Impresión</li>
      <li>Identificador</li>
      <li>Proveedor</li>      
      <li>Fecha de recepción</li>
      <li>Diámetro</li>
      <li>Tabla</li>
      <li>Registro</li>
      <li>Repeticiones al paso</li>
      <li>Repeticiones al giro</li>      
      <li>Giros garantizados</li>
      <li>Parámetros de operación</li>
        <ul>
          <li>Viscosidad en segundos y su tolerancia +/-</li>
          <li>Temperatura de hornos en grados Celsius y su tolerancia +/-</li>
          <li>Velocidad en metros por minuto y su tolerancia +/-</li>
          <li>Presión en gomas en psi y su tolerancia +/-</li>
          <li>Presión en cilindros en psi y su tolerancia +/-</li>
          <li>Presión en rasquetas en psi y su tolerancia +/-</li>
        </ul>
    </ul>

    <p>Esta información se muestra en el formato PRO-FR07 del SGC, para su monitoreo y aplicación al realizar la operación de impresión.</p>

    <h2><a name="Bandas">Bandas de seguridad</a></h2>

    <p>En este módulo se administran las bandas de seguridad holográficas, este producto es un complemento de seguridad que proporciona autenticidad al sello de seguridad (termoencogible) convirtiéndolo en sello con seguridad holográfica, para lo cual se recolectan los siguientes datos:</p>

    <ul>
      <li>Identificador o código de la banda</li>
      <li>Nombre de la banda</li>
      <li>Anchura</li>
    </ul>

    <h2><a name="BandaP">Bandas de seguridad por proceso</a></h2>

    <p>En este módulo se administran las bandas de seguridad de acuerdo al proceso del que se originarán, actualmente esta puede iniciar con material virgen o pre-embosado. Es por ello que se recolectan los siguientes datos:</p>


    <ul>
      <li>Banda de seguridad</li>
      <li>Identificador o código de la banda por proceso</li>
      <li>Nombre de la banda por proceso</li>
      <li>Anchura para laminado</li>
      <li>Sustrato <input type="checkbox"/> pre-embosado</li>
      <li>Sustrato o materia prima</li>
    </ul>

    <p>De acuerdo con la indicación "Sustrato pre-embosado" el sistema validara en los módulos donde se capture el "Número de Orden de Producción".</p>

    <h3>Sustrato <input type="checkbox" disabled/> pre-embosado</h3>
    <p>En este punto, la secuencia del proceso es: 
      <ol>
        <li>Programar el embosado</li>
        <li>Registrar el embosado</li>
        <li>Registrar el laminado</li>
        <li>Registrar el sliteo</li>
      </ol>
    </p>

    <h3>Sustrato <input type="checkbox" checked disabled/> pre-embosado</h3>
    <p>En este punto, la secuencia del proceso es: 
      <ol>
        <li>Programar el refilado</li>
        <li>Registrar el refilado</li>
        <li>Registrar el laminado</li>
        <li>Registrar el sliteo</li>
      </ol>
    </p>

    <h2><a name="Productos">Productos cliente</a></h2>
    
    <p>En este módulo se administran los productos del cliente, los cuales se ligan a los productos realizados en la empresa. Esto con la finalidad de vincular la información de nuestros clientes con la interna, recolectando los siguientes datos:

    <ul>
      <li>Identificador o código del producto</li>
      <li>Nombre del producto</li>
    </ul>

    <p>La creación de este módulo atiende a la necesidad de cargar las órdenes de compra originales del cliente, en donde un producto del cliente, puede ser cubierto con más de uno de parte nuestra. Una vez que se recibe confirmación de la presentación del producto, esta información es actualizada.</p>

    <p>Con esta información el área de ventas podrá controlar las órdenes de compra o pedidos con la información del cliente y a su vez mantener la información necesaria para producción con el detalle del producto específico a producir.</p>'; }

  } else
  { echo '
    <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
  </body>
</html>