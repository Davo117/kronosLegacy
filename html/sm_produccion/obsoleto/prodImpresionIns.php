<!DOCTYPE html>
<html>
  <head>
    <title>Producci&oacute;n Impresi&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css" media="all">
  </head>
  <body><br/><?php

  include '../datos/mysql.php';

  $sistModulo_cdgmodulo = '60020';

  $sistModulo_modulo = sistModulo($sistModulo_cdgmodulo);

  if ($sistModulo_modulo != '')
  { echo '
    <table align="center">
      <thead>
        <tr>
          <th>'.utf8_decode('Registros para inspección').'</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>
          <form id="form_inspimpresion" name="form_inspimpresion" method="post" action="pdf/RC-02-POT-7.5.1.php"/>
            <label for="label_ttllote">'.utf8_decode('Lote para inspección en Impresión').'</label><br/>
            <input type="text" style="width:120px" id="txt_lote" name="txt_lote" value="'.$prodImpresion_cdglote.'" required/>
            <input type="submit" id="button_buscar" name="button_buscar" value="Buscar" />
          </form></td></tr>
        <tr><td>
          <form id="form_insprefilado" name="form_insprefilado" method="post" action="pdf/RC-03-POT-7.5.1.php"/>
            <label for="label_ttllote">'.utf8_decode('Lote para inspección en Refilado').'</label><br/>
            <input type="text" style="width:120px" id="txt_lote" name="txt_lote" value="'.$prodImpresion_cdglote.'" required/>
            <input type="submit" id="button_buscar" name="button_buscar" value="Buscar" />
          </form></td></tr>
      </tbody>
      <tfoot>
        <tr></th></tr>
      </tfoot>
    </table>';

    if ($msg_alert != '')
    { echo '
    <script type="text/javascript"> alert("'.$msg_alert.'"); </script>'; }

  } else
  { echo '
    <br/><div align="center"><h1>M&oacute;dulo no encontrado o bloqueado.</h1></div>'; }       
  ?>
  </body>
</html>
