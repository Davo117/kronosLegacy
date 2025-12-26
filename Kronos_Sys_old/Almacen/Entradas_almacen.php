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
 $_SESSION['arrayProductos']=array();

 include("controlador_almacen/crud_almacen.php");
 include("../Database/SQLConnection.php");

 if(empty($_POST['fechaIng']))
 {
  $fch=date('Y-m-d');
}
else
{
  $fch=$_POST['fechaIng'];
}


?>
<body>
  <?php

  if(isset($_GET['externa']) or isset($_GET['interna']))
  {
    if(isset($_GET['externa']))
    {
      $ciddoc=19;
    }
    else if(isset($_GET['interna']))
    {
      $ciddoc=32;
    }
            //SE trae el ultimo folio del documento "Requisición de compra", en la BD de prueba es 3001,pero en la BD normal es 3000
$SQLlastId=sqlsrv_query($SQLconn,"SELECT top 1 cfolio+1 as nuevoFolio from admdocumentos where  ciddocumentode=".$ciddoc." and cseriedocumento like '%Almacen%'");
$rowLastid=sqlsrv_fetch_array($SQLlastId,SQLSRV_FETCH_ASSOC);
    ?>
    <p id="ke"></p>
    <div id="page-wrapper">
      <div class="container-fluid">

        <form method="POST" name="formulary" id="formulary" role="form">
          <div class="panel panel-info">

            <div class="panel-heading"><b class="titulo">Entradas de almacén</b>
              <button <?php if(isset($_SESSION['arrayProducto']) and count($_SESSION['arrayProducto'])>0){ echo "visible='false'"; } else{ echo "visible='true'";} ?> name="save_folioin" class="btn btn-info" style="float:right;">Enviar</button>
            </div>
            <div class="panel-body">
              <div class="col-xs-3">
                <label for="fechain">Fecha de ingreso</label>
                <input readonly type="date" name="fechain" value="<?php echo $fch;?>" id="fechain" class="form-control">
              </div>
              <div class="col-xs-3">
                <label for="serie">Serie</label>
                <select name="serie" id="serie" class="form-control">
                  <option value="almacen">Almacén</option>
                  <option value="vtas">Ventas</option>
                </select>
              </div>
              <div class="col-xs-3">
                <label for="folio">Folio</label>
                <input  readonly type="number" name="folio" value="<?php echo $rowLastid['nuevoFolio']?>" class="form-control">
              </div>
              <br>
              <hr style="clear:left;clear: right;">
              <br>

              <div class="col-xs-3" style="clear:right;clear: left;">
                <label for="cdgproducto">Código del producto</label>
                <input type="text" name="cdgproducto" id="cdgproducto" style="border-color:red" class="form-control" autofocus>
              </div>
              <br>
              <a style="float:right;" class="btn btn-large btn-primary" data-toggle="confirmation"
              data-btn-ok-label="Continue" data-btn-ok-class="btn-primary"
              data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"
              data-btn-cancel-label="Stoooop!" data-btn-cancel-class="btn-danger"
              data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"
              title="Agregar entrada" href="controlador_almacen/crud_almacen.php?envio=<?php echo $_SESSION['arrayProducto'];?>&doc=<?php echo $ciddoc ?>"  onClick="return confirm('¿Desea continuar?');" name="find"><img width="50" height="50" src="../pictures/Almacen.png"></img></a>
              <hr style="clear: left;clear: right;">
              <br>
              <div class="table-responsive" id="tablein">
                <table class="table table-hover">
                  <td>Código</td><td>Nombre</td><td>Cantidad</td>
                  <tr></tr>
                </table>
              </div>
            </div>
            <div id="getcantidad" class="modal" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title">Ingrese Cantidad</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" style="overflow:hidden;" id="formCantidad">
                    <form id="setProducto" method="POST">
                      <div class="col-xs-3">
                        <label for="cantidad">Cantidad</label>
                        <input  type="number"  name="txtcantidad" id="cantidad" value="" class="form-control">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnaddin"  disabled="true">Agregar ala entrada</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </form>


        <h4 class="ordenMedio">Entradas de inventario</h4>
        <div class="table-responsive">
          <table class="table table-hover">
            <?php
            $SQlo=sqlsrv_query($SQLconn,"SELECT*FROM admdocumentos where CIDDOCUMENTODE=".$ciddoc."");

            ?>
            <th>Opciones</th><th>Fecha</th><th>Serie</th><th>Folio</th><th>Total</th>
            <tr>
              <?php
              while($row=sqlsrv_fetch_array($SQlo, SQLSRV_FETCH_ASSOC))
              {

                ?>
                <td><button class="btn btn-info" id="<?php echo $row['CIDDOCUMENTO'];?>" style="" data-toggle="modal" data-target="#window_in">Agregar producto<?php echo $row['CIDDOCUMENTO'];?></button></td><td><?php echo $row['CFECHA']->format('d/m/Y');?></td><td><?php echo $row['CSERIEDOCUMENTO'];?></td><td><?php echo $row['CFOLIO'];?></td><td><?php echo $row['CTOTALUNIDADES'];?></td>

              </tr>
              <?php
            }
            ?>
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
            <?php
          }
          else
          {
            ?>
             <div id="window_type" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h2>Selecciona el tipo de entrada</h2>
            </div>
            <div class="modal-body">
              <form method="GET" name="formu" id="formtype" role="form">
                <div class="panel panel-default">

                  <div class="panel-heading"><b class="titulo"></b>
                  </div>
                  <div class="panel-body">
                    <div class="col-xs-3">
                       <button style="" class="btn btn-success" name="externa">Crear compra</button>
                      
                    </div>
                    <div class="col-xs-3">
                       <button style="" class="btn btn-success" name="interna">Entrada interna</button>
                    </div>
                  </div>
                
                </form>
            <script type="text/javascript">
              $(document).ready(function()
              {
                $('#window_type').modal();
              });
            </script>
            <?php
          }

          ?>


        </body>
        <script type="text/javascript">
         $(document).ready(function(){

          $("select[name=cmbproducto]").change(function(e)
          {
           consulta = $("select[name=cmbproducto]").val();
           $("#cmbunidades").delay(100).queue(function(n) {      


            $.ajax({
             type: "POST",
             url: "controlador_almacen/crud_almacen.php",
             data: "b="+consulta,
             dataType: "html",
             error: function(){
              alert("error petición ajax");
            },
            success: function(data){
              $("#cmbunidades").html(data);
              n();
            }
          });

          });
         });
        });

         $(document).ready(function(){

          $(function() {
           $(document).on('click', 'button.btn-info', function(event) {
            let id = this.id;
            $.ajax({
              type: "POST",
              url: "controlador_almacen/crud_almacen.php",
              data: "mat_in="+id,
              dataType: "html",
              error: function(){
                alert("error petición ajax");
              },
              success: function(data){                                                      
                $("#window_in").html(data);
                n();
              }
            });
            console.log("Se presionó el Boton con Id :"+ id)
          });
         });
        });
         $(document).ready(function()
         {
           $(function() {
             $(document).on('click', 'button#save_in', function(event) {
              var producto=$('#cdgproducto').val();
              var cantidad=$('#cdgcantidad').val();
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_almacen.php",
                data: "b="+producto,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){                                                      
                  $("#formCantidad").html(data);
                  n();
                }
              });
              console.log("Se presionó el Boton con Id :"+ id)
            });
           });
         });
         $(document).ready(function(){
          $("#formulary").submit(function(e){
           e.preventDefault();
           var producto=$('#cdgproducto').val();

           $.ajax({
            type: "POST",
            url: "controlador_almacen/crud_almacen.php",
            data: "b="+producto,
            dataType: "html",
            error: function(){
              alert("error petición ajax");
            },
            success: function(data){                                                      
              $("#formCantidad").html(data);
              $("#getcantidad").modal();
              $('#cantidad').trigger('focus');
              var name=$('#nameProduct').html();
              if(name!=null)
              {
                $('#btnaddin').prop('disabled',false);
              }

              n();
            }
          });

         });

          $(document).on('keypress', '#cantidad', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13){
            var producto=$('#cdgproducto').val();
            var nombreProducto=$('#nameProduct').html();
            var cantidad=$('#cantidad').val();
            $.ajax({
              type: "POST",
              url: "controlador_almacen/crud_almacen.php",
              data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto,
              dataType: "html",
              error: function(){
                alert("error petición ajax");
              },
              success: function(data){ 
                $('#cdgproducto').val("");
                $('#getcantidad').modal('hide');
                $('#cdgproducto').trigger('focus');                                                  
                $("#tablein").html(data);

              }
            });
          }

        });
        });

      </script>
      <script type="text/javascript">

      </script>
      </html>

      <?php
      ob_end_flush();
    } else {
      echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
      include "../ingresar.php";
    }

    ?>