<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Ayuda</title>
    <link rel="stylesheet" type="text/css" href="../css/2014.css" />
  </head>
  <body>
    <div id="contenedor">
      <section>
        <Label><h1>Banda de Seguridad</h1></label>
      </section>
<?php

  include '../datos/mysql.php';
  $link = conectar();

  m3nu_produccion();  

  $sistModulo_cdgmodulo = '20000';
  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { if ($_GET['mode']=='logout') { cl0s3(); }

    if ($_POST['textusername'] AND $_POST['textpassword']) 
    { val1dat3($_POST['textusername'], $_POST['textpassword']); }

    if ($_SESSION['cdgusuario'])
    { $sistModulo_permiso = sistPermiso($sistModulo_cdgmodulo, $_SESSION['cdgusuario']);

      ma1n(); }

    echo '
    <h3>Introducción</h3>
    <p>La <i>Banda de Seguridad</i> es un elemento complementario del <i>Sello de Seguridad Holográfico</i>, por medio de este sistema informático se administra su producción con la finalidad de garantizar el abastesimiento y asi cumplir con las solicitudes de nuestros clientes.</p>

    <p>La <i>Banda de Seguridad</i> puede provenir de dos puntos, embosarlo en nuestras instalaciones o adquirirlo previemente embosado. De acuerdo al origen se programa para su procesamiento, unificandose en las últimas dos faces (laminado y sliteo).</p>';

    echo '
    <h3><a name="progembosado">Programación del Embosado</a></h3>
    <p>La programación del embosado tiene como objetivo iniciar con la trazabilidad de la <i>Banda de Seguridad</i> cuando esta es embosada en las instalaciones de la empresa, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b><i>Banda de seguridad</i></b><br/>Debe elegirse la banda que se desea embosar.</li>
      <li><b>Máquina</b><br/>Debe indicarse en que máquina debe realizarse la operación.</li>
      <li><b>Fecha programación</b><br/>Fecha para la cual debe realizarse la operación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se filtran los lotes de materia prima compatibles con la <i>Banda de Seguridad</i> que se desea embosar.</p>
    <p>Lo siguiente es elegir los lotes que se desean programar y salvar los cambios, una vez que el sistema termina de procesar la información los lotes aparecerán con un Número de Orden de Producción (<em>NoOP</em>) y los agrupará por <i>Banda de Seguridad</i>, Maquina y Fecha programación.</p>
    <p>Al inicio de cada grupo es posible generar las nuevas etiquetas de identificación para los lotes programados y el formato <em><b>PRO-FRXX</b> Embosado de <i>Banda de Seguridad</i></em>.</p>';

    echo '
    <h3><a name="embosado">Registro del Embosado</a></h3>
    <p>Una vez realizado el embosado es necesario registrar la operación en el sistema para continuar con la trazabilidad, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b>Empleado</b><br/>Número de empleado, el cual puede ubicarse en las credenciales de identificación.</li>
      <li><b>Máquina</b><br/>Código de máquina, el cual puede ubicarse a un costado de cada una de ellas.</li>
      <li><b>Lote</b><br/>Código de barras o Número de Orden de Producción, los cuales pueden ubicarse en la etiqueta de identificación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se mostrará la información del lote ingresado.</p>
    <p>Lo siguientes es indicar la cantidad de metros en máquina, la amplitud del material, el peso del lote y la cantidad de banderas colocadas al concluir proceso.</p>';

    echo '
    <h3><a name="progrefilado">Programación del Refilado</a></h3>
    <p>La programación del refilado tiene como objetivo iniciar con la trazabilidad de la <i>Banda de Seguridad</i> cuando esta se adquiere previamente embosada, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b><i>Banda de seguridad</i></b><br/>Debe elegirse la banda que se desea embosar.</li>
      <li><b>Máquina</b><br/>Debe indicarse en que máquina debe realizarse la operación.</li>
      <li><b>Fecha programación</b><br/>Fecha para la cual debe realizarse la operación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se filtran los lotes de materia prima compatibles con la <i>Banda de Seguridad</i> que se desea refilar.</p>
    <p>Lo siguiente es elegir los lotes que se desean programar y salvar los cambios, una vez que el sistema termina de procesar la información los lotes aparecerán con un Número de Orden de Producción (<em>NoOP</em>) y los agrupará por <i>Banda de Seguridad</i>, Maquina y Fecha programación.</p>
    <p>Al inicio de cada grupo es posible generar las nuevas etiquetas de identificación para los lotes programados y el formato <em><b>PRO-FRXX</b> Refilado de <i>Banda de Seguridad</i></em>.</p>';

    echo '
    <h3><a name="refilado">Registro del Refilado</a></h3>
    <p>Una vez realizado el refilado es necesario registrar la operación en el sistema para continuar con la trazabilidad, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b>Empleado</b><br/>Número de empleado, el cual puede ubicarse en las credenciales de identificación.</li>
      <li><b>Máquina</b><br/>Código de máquina, el cual puede ubicarse a un costado de cada una de ellas.</li>
      <li><b>Lote</b><br/>Código de barras o Número de Orden de Producción, los cuales pueden ubicarse en la etiqueta de identificación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se mostrará la información del lote ingresado.</p>
    <p>Lo siguientes es indicar por cada una de las bobinas resultantes la cantidad de metros en máquina, la amplitud del material, el peso y la cantidad de banderas colocadas al concluir proceso.</p>';

    echo '
    <h3><a name="laminado">Registro del Laminado</a></h3>
    <p>Una vez realizado el laminado es necesario registrar la operación en el sistema para continuar con la trazabilidad, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b>Empleado</b><br/>Número de empleado, el cual puede ubicarse en las credenciales de identificación.</li>
      <li><b>Máquina</b><br/>Código de máquina, el cual puede ubicarse a un costado de cada una de ellas.</li>
      <li><b>Lote/Bobina</b><br/>Código de barras o Número de Orden de Producción, los cuales pueden ubicarse en la etiqueta de identificación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se mostrará la información del lote/bobina ingresado.</p>
    <p>Lo siguientes es indicar la cantidad de metros en máquina, la amplitud del material, el peso y la cantidad de banderas colocadas al concluir proceso.</p>';    

    echo '
    <h3><a name="sliteo">Registro del Sliteo</a></h3>
    <p>Una vez realizado el sliteo es necesario registrar la operación en el sistema para continuar con la trazabilidad, este módulo requiere se indique la siguiente información:</p>
    
    <ul>
      <li><b>Empleado</b><br/>Número de empleado, el cual puede ubicarse en las credenciales de identificación.</li>
      <li><b>Máquina</b><br/>Código de máquina, el cual puede ubicarse a un costado de cada una de ellas.</li>
      <li><b>Lote/Bobina</b><br/>Código de barras o Número de Orden de Producción, los cuales pueden ubicarse en la etiqueta de identificación.</li>
    </ul>

    <p>Una vez que se definen los datos previamente mencionados, en pantalla se mostrará la información del lote/bobina ingresado.</p>
    <p>Lo siguientes es indicar por cada una de los discos resultantes la cantidad de metros en máquina, la amplitud del material, el peso y la cantidad de banderas colocadas al concluir proceso.</p>';    
  } else
  { echo '
    <div align="center"><h1>Módulo no encontrado o bloqueado.</h1></div>'; }
?>
  </body>
</html>
