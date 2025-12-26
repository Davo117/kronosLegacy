
<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

  <head>
   <title>Salidas | Almacén</title>
   <!-- Latest compiled and minified CSS -->
   </head>
 <?php
  include("../components/barra_latera_almacen.php");
  include("../Database/SQLConnection.php");
//$_SESSION['codigoPermiso']='20001';
//include ("funciones/permisos.php");
//$_SESSION['descripcion']=" ";
//$_SESSION['descripcionCil']=" ";
//$_SESSION['descripcionBanda']=" ";
//include("Controlador_Disenio/bitacoras/bitacoraDisenio.php");
  include "modelo_almacen/salida.php";
  $_SESSION['arrayProductos'] = array();

  include("controlador_almacen/crud_salidas.php");
  include("../Database/conexionphp.php");
  if(empty($_POST['fechaOut']))
  {
  $fch=date('Y-m-d');
  }
  else
  {
    $fch=$_POST['fechaOut'];
  }
?>
<body>
  <?php
            //SE trae el ultimo folio del documento "Requisición de compra", en la BD de prueba es 3001,pero en la BD normal es 3000
//$SQLlastId=sqlsrv_query($SQLconn,"SELECT top 1 cfolio+1 as nuevoFolio from admdocumentos where  ciddocumentode=".$ciddoc." and cseriedocumento like '%Almacen%'");
//$rowLastid=sqlsrv_fetch_array($SQLlastId,SQLSRV_FETCH_ASSOC);
  $MYS=$mysqli->query("SELECT MAX(id)+1 as folio FROM obelisco.salidas order by id desc");
  $rowLastid=$MYS->fetch_array();
  $folio=$rowLastid['folio'];
  if(is_null($folio))
  {
    $folio=1;
  }
  ?>
  <p id="ke"></p>
  <div id="page-wrapper">
    <div class="container-fluid">

      <form method="POST" name="formulary" id="formulary" role="form">
        <div class="panel panel-success">

          <div class="panel-heading" style="overflow:hidden;"><b class="titulo">Salidas de almacén</b>
            <div class="col-xs-2 col-md-2 col-lg-2 input-xs" style="float:right;">
             <select class="form-control" name="tipo" onchange="document.formulary.submit()">
              <option value="0">--</option>
              <option value="1" <?php if(isset($_POST['tipo']) and $_POST['tipo']==1 or !isset($_POST['tipo'])){ echo "selected";}?>>Producto individual</option>
              <option value="2" <?php if(isset($_POST['tipo']) and $_POST['tipo']==2){ echo "selected";}?>>Cupón de explosión</option>
            </select>
          </div>
        </div>  
        <div class="panel-body">
          <div class="col-xs-3">
            <label for="fechain">Fecha de salida</label>
            <input readonly type="date" name="fechain" value="<?php echo $fch;?>" id="fechain" class="form-control">
          </div>
          <div class="col-xs-3">
            <label for="folio">Folio</label>
            <input  readonly type="number" name="folio" value="<?php echo $folio?>" class="form-control">
          </div>
          <div class="col-xs-3" id="contenedorreal">
            <?php
            if(isset($_POST['tipo']) and $_POST['tipo']==1 or !isset($_POST['tipo']))
            {
              ?>
              <label for="cdgproducto">Producto</label><br>
              <input type="text" name="cdgproducto" placeholder="Código" id="cdgproducto" autofocus="true" class="form-control">
              <?php
            }
            else
              {?>
                <label for="cdgcupon">Cupón</label>
                <input type="text" name="cdgcupon" placeholder="Código" id="cdgcupon" autofocus="true" class="form-control">
                <?php
              }
              ?>
            </div>
            
          </div>

            <div class="table-responsive" id="tableOut">
              <div class="container-fluid">
                <h4 class="ordenMedio">Movimientos actuales</h4>
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Nombre</th>
                      <th>Cantidad</th>
                      <th>Costo</th>
                      <th>Hora</th>
                      <th>Fecha</th>
                      <th>Solicita</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

        <div id="getcantidad" class="modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" style="text-align: center">Ingrese cantidad a salir</h3>
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
              <div>
              <h4 style="text-align:center; color: #239B56; font-size:14px">Al finalizar presiona ENTER para agregar este producto</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>

        </div>
        <a style="float:right;" class="btn btn-large btn-success" 
        title="Agregar salida" href="controlador_almacen/crud_salidas.php?envio=1&folio=<?php echo $folio.'&fechain='.$fch;?>"  onClick="return confirm('¿Desea continuar?');" id="send" name="find"><img width="30" height="30" src="../pictures/cerrarSesion.png"></img>/ F4</a>
      </div>

    </form>

    <div id="page-wrapper">
      <div class="container-fluid">
        <h4 class="ordenMedio">Salida de inventario</h4>
        <div class="table-responsive">
          <table class="table table-hover">
            <?php
              $lstIn=$mysqli->query("SELECT * FROM obelisco.salidas order by fecha desc");
            ?>
            <thead class="thead-light">
              <th>Opciones</th>
              <th>Fecha</th>
              <th>Serie</th>
              <th>Total</th>
            </thead>
            <tbody>
              <?php
              while($row=$lstIn->fetch_array())
              {
                ?>
                <tr>
                  <td>
                    <a class="btn" target="_blank" title="Generar reporte PDF" href="alm_doc/CO-FR07.php?salida=<?php echo $row['id'];?>" style="">
                      <img src="../pictures/icono-PDF.png" class="img-responsive" width="30" height="30">
                    </a>
                  </td>
                  <td><?php echo $row['fecha'];?></td>
                  <td><?php echo "Almacén";?></td>
                  <td><?php echo number_format($row['costoTotal'],2);?></td>
                </tr>
              </tbody>
              <?php
              }
              ?>
          </table>
        </div>
      </div>
    </div>

        <div id="window_in" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2>Salida de materia prima</h2>
              </div>
              <div class="modal-body">
                <form method="post" name="formu" id="formu" role="form">
                  <div class="panel panel-default">

                    <div class="panel-heading"><b class="titulo">Agregar salida</b>
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

                <h4>Salida de materia prima</h4>
                <div class="table-responsive" id="table_in">
                  <table class="table table-hover">
                    <th>Código</th><th>Nombre</th><th>Almacén</th><th>Cantidad</th><th>Costo</th><th>Total</th>
                  </table>
                </div>
                <div class="modal-footer">

                </div>
              </div>
            </body>
            
            <script type="text/javascript">
             $(document).ready(function(){
              $("#cdgproducto").prop('autofocus','true');


              $("select[name=cmbproducto]").change(function(e)
              {
               consulta = $("select[name=cmbproducto]").val();
               $("#cmbunidades").delay(100).queue(function(n) {      


                $.ajax({
                 type: "POST",
                 url: "controlador_almacen/crud_salidas.php",
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
              $(document).on('keydown', '#cdgproducto', function(e) {
                console.log("oki");
                var code = (e.keyCode ? e.keyCode : e.which);

                if(code==13){

                }
              });
            });

             $(document).ready(function(){

              $(function() {
               $(document).on('click', 'button.btn-info', function(event) {
                let id = this.id;
                $.ajax({
                  type: "POST",
                  url: "controlador_almacen/crud_salidas.php",
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
            //Método para eliminar elementos de la salida antes de guardarla
            $(document).ready(function(){
              $(function() {
                $(document).on('click', 'a.btn-borrar', function(event){
                  var id = this.id;
                  $.ajax({
                    type: "POST",
                    url: "controlador_almacen/crud_salidas.php",
                    data: "eliminar=" + id,
                    dataType: "html",
                    error: function(){
                      alert("No se eliminó el elemento")
                    },
                    success: function(data){
                      $("#tableOut").html(data);
                    }
                  });
                }); 
              });
            });
            
             $(document).ready(function()
             {
               $(function() {
                 $(document).on('click', 'button#save_in', function(event) {
                  var producto=$('#cdgproducto').val();
                  var cantidad=$('#cdgcantidad').val();
                  var hascode=$('#hascode').val();
                  $.ajax({
                    type: "POST",
                    url: "controlador_almacen/crud_salidas.php",
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
                });
               });
             });
             $(document).ready(function(){
              $(document).on('keypress', '#cdgproducto', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
            if(code==13){
               var producto=$('#cdgproducto').val();
               $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_salidas.php",
                data: "b="+producto,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){
                  console.log(data.substr(6,27));
                  if(data.substr(6,27) == '  <div class="modal-body" s'){
                    $("#formCantidad").html(data);
                    $("#getcantidad").modal();
                    $('#operador').trigger('focus');
                    $('#cantidad').trigger('focus');
                  }else{
                     $("#tableOut").html(data);
                    $('#cdgproducto').val("");
                    $('#cdgproducto').trigger('focus');
                    
                    
                    var name=$('#nameProduct').html();
                    if(name!=null)
                    {
                      $('#btnaddin').prop('disabled',false);
                    }
                    else
                    {
                      $('#btnaddin').prop('disabled',true);
                    }

                  }                                      
                }
              });
             }

             });          
              $("#formulary").submit(function(e){
               e.preventDefault();
             });
          // Método para guardar la salida (Cantidad y producto)
          $(document).on('keypress', '#cantidad', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13){
            if($('#hasprice').val()==1)
            {
              $('#precio').focus();
            }
            else
            {
              $('#operador').focus();
            }
            
          }

        });
$(document).on('keypress', '#operador', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13){
            
              var operador=$('#operador').val();
              var producto=$('#idproducto').val();
              var nombreProducto=$('#nombreproducto').val();
              var cantidad=$('#cantidad').val();
              var cantidad2=$('#cantidad2').val();
              var codigoB=$("#codigoB").val();
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_salidas.php",
                data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&ope="+operador+"&cB="+codigoB+"&u2="+cantidad2,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){ 
                  $('#cdgproducto').selectpicker('val', '');
                  $('#getcantidad').modal('hide');                   
                  $("#tableOut").html(data);
                  $("#cdgproducto").val("");
                  $("#cdgproducto").trigger("focus");
                } 
              });
            
            
          }

        });

          
          //Método para guardar la salida CON PRECIO
          $(document).on('keypress', '#precio', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13){
             if($('#hascode').val()==1)
             {
              $('#unidades').focus();
            }
            else
            {
              var producto=$('#cdgproducto').val();
              var nombreProducto=$('#nameProduct').html();
              var cantidad=$('#cantidad').val();
              var cantidad2=$('#cantidad2').val();
              var hascode=$('#hascode').val();
              var precio=$('#precio').val();
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_salidas.php",
                data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&h="+hascode+"&pr="+precio+"&ope="+operador+"&u2"+cantidad2,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){ 
                  $('#cdgproducto').selectpicker('val', '');
                  $('#getcantidad').modal('hide');                   
                  $("#tableOut").html(data);
                } 
              });
            }
          }

        });
          $(document).keydown(function(tecla){ 
            if (tecla.keyCode == 115) { 
              document.getElementById("send").click()
            }
          });
            //Método para guardar la salida CON PRECIO Y UNIDADES
            $(document).on('keypress', '#unidades', function(e) {

             var code = (e.keyCode ? e.keyCode : e.which);
             if(code==13){
              var producto=$('#cdgproducto').val();
              var nombreProducto=$('#nameProduct').html();
              var cantidad=$('#cantidad').val();
              var cantidad2=$('#cantidad2').val();
              var hascode=$('#hascode').val();
              var precio=$('#precio').val();
              var unidades=$('#unidades').val();
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_salidas.php",
                data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&h="+hascode+"&pr="+precio+"&un="+unidades+"&ope="+operador+"&u2="+cantidad2,
                dataType: "html",
                error: function(){
                  alert("error petición ajax");
                },
                success: function(data){ 
                  $('#cdgproducto').selectpicker('val', '');
                  $('#getcantidad').modal('hide');                   
                  $("#tableOut").html(data);

                } 
              });


            }

          });

          });

        </script>
        </html>
        <?php
        ob_end_flush();
      } else {
        echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
        include "../ingresar.php";
      }

      ?>