<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

  <head>
   <title>Entradas| Almacén</title>
   <!-- Latest compiled and minified CSS -->


 </head>
 <?php
 include("../components/barra_latera_almacen.php");
//$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
//$_SESSION['descripcion']=" ";
//$_SESSION['descripcionCil']=" ";
//$_SESSION['descripcionBanda']=" ";
//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
 include "modelo_almacen/entrada.php";

 //include("controlador_almacen/crud_almacen.php");
 include("../Database/SQLConnection.php");
?>
<body>

    <p id="ke"></p>
    <div id="page-wrapper">
      <div class="container-fluid">
          <div class="panel panel-info">

            <div class="panel-heading"><b class="titulo">Enlazar con orden de compra</b>
            </div>
            <div class="panel-body">
             <h4 class="ordenMedio">Movimientos seleccionados</h4>
      <div class="table-responsive col-sm-4" id="tablein" style="border-radius:2px;border-color:gray">
      <table class="table table-hover" border="1" style="border-radius:2px;border-color:gray">
      <td>Código</td><td>Nombre</td><td>Cantidad</td><td>Hora</td><td>Fecha</td>
      <?php
       $arrayProductos = base64_decode($_GET['envio']);
    /* Deshacemos el trabajo hecho por 'serialize' */
      $arrayProductos = unserialize($arrayProductos);
      for($i=0;$i<count($arrayProductos);$i++)
        {?>
         <tr>
          <th><?php echo $arrayProductos[$i]->getCodigo();?></th>
          <th><?php echo $arrayProductos[$i]->getNombre();?></th>
          <th><?php echo $arrayProductos[$i]->getCantidad();?></th>
          <th><?php echo $arrayProductos[$i]->getHora();?></th>
          <th><?php echo $arrayProductos[$i]->getFecha();?></th>
        </tr>
        <?php
      }?>

    </table>
  </div>

      </div>
      <div id="window_in" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h2>Entrada de materia prima</h2>
            </div>
            <div class="modal-body">
              <form method="post" name="formu" id="formu" role="form">
                <div class="panel panel-default">

                  <div class="panel-heading"><b class="titulo">Agregar entrada</b>
                  </div>
                  <div class="panel-body">
                    <div class="col-xs-3">
                      <label for="cmbproducto">Producto</label>
                      <input type="text" name="cdgproducto2">
                    </div>
                    <div class="col-xs-3">
                      <label for="cmbalmacen">Almacén</label>
                      <select id="cmbalmacen" name="cmbalmacen" class="form-control">
                        <option value="materiaprima">Materia prima</option>
                        <option value="otro">Otro</option>
                      </select>
                    </div>
                    <div class="col-xs-3">
                      <label for="precio">Costo</label>
                      <input type="number" name="precio" step="0.01" class="form-control" placeholder="precio">
                    </div>
                    <div class="col-xs-3" id="cmbunidades">
                    </div>
                  </div>
                  <button style="float:right;" class="btn btn-success" name="save_in">Guardar</button>
                </form>

              </div>

              <h4>Entradas de materia prima</h4>
              <div class="table-responsive" id="table_in">
                <table class="table table-hover">
                  <?php
//$SQlin=$mysqli->fetch_object();

                  ?>
                  <th>Código</th><th>Nombre</th><th>Almacén</th><th>Cantidad</th><th>Costo</th><th>Total</th>
                </table>
              </div>
              <div class="modal-footer">

              </div>
            </div>
        </body>
      </html>

      <?php
      ob_end_flush();
    } else {
      echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
      include "../ingresar.php";
    }

    ?>