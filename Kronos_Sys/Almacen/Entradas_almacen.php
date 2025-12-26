<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

  <head>
   <title>Entradas | Almacén</title>
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
    include("../Database/conexionphp.php");

    if(empty($_POST['fechaIn']))
    {
      $fch=date('Y-m-d');
    }
    else
    {
      $fch=$_POST['fechaIn'];
    }
?>

<body>
  <?php

      $MYS=$mysqli->query("SELECT MAX(id) + 1 as folio FROM obelisco.entradas order by id desc");
      $rowLastid=$MYS->fetch_array();
      $folio=$rowLastid['folio'];
      if(is_null($folio))
      {
        $folio=1;
      }
      else
      {

      }
    ?>

      <div id="page-wrapper">
      <div class="container-fluid">
        <form method="POST" name="formulary" id="formulary" role="form">
          <div class="panel panel-info">
            <div class="panel-heading"><b class="titulo">Entradas de almacén</b>
              
            </div>
            <div class="panel-body">
              <div class="col-xs-2">
                <label for="fechaEn">Fecha de ingreso</label>
                <input type="date" name="fechaIn" value="<?php echo $fch;?>" id="fechaIn" class="form-control">
              </div>
              <div class="col-xs-2">
                <label for="folio">Folio</label>
                <input   type="text" name="folio" value="<?php echo $folio?>" class="form-control">
              </div>
              <div class="col-xs-4 col-lg-2 col-md-4">
                <label for="Proveedor">Proveedor</label><br>
                <select required name="cdgproveedor" autofocus="false" onchange="document.formulary.submit()" id="cdgproveedor"  class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="--Selecciona el proveedor">
                  <?php $resultado = sqlsrv_query($SQLconn, "SELECT CRAZONSOCIAL as proveedor, CIDCLIENTEPROVEEDOR as codigo FROM ADMCLIENTES WHERE CESTATUS=1 and CTIPOCLIENTE=3"); ?>
                  <option value="">--</option>
                  <?php
                      while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
                          if($_POST['cdgproveedor']==$row['codigo']){ ?>
                            <option selected data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
                  <?php
                          }
                          else{
                  ?> 
                              <option data-tokens="<?php echo $row['proveedor'].$row['codigo'];?>" value="<?php echo $row['codigo'];?>"><?php echo $row['proveedor'];?></option>
                  <?php
                          }
                        }   
                  ?>
                  </select>
                </div>
            <div class="col-xs-2 col-lg-2 col-md-3" id="contenedorreal">
              <label for="cdgproducto">Producto</label><br>
              <input type="text" class="form-control" id="cdgproducto" name="cdgproducto" placeholder="Escanee el código">
            </div>              
          </div>

           
            <div class="table-responsive" id="tablein">
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
                      <th>Lote</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
              
            <div id="getcantidad" class="modal" tabindex="-1" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</BUtton>
                    <h2 class="modal-title" style="text-align: center">Datos del producto a ingresar</h2>
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
            <a style="float:right;" class="btn btn-success" 
              title="Agregar entrada"  href="controlador_almacen/crud_almacen.php?envio=1&proveedor=<?php echo $_POST['cdgproveedor'].'&folio='.$folio.'&fechaIn='.$fch;?>"  onClick="return confirm('¿Desea continuar?');" id="send" name="find"> Guardar Entrada / F4</a>
          </div>
        </form>
      </div>
    </div>
<?php
?>
    <div id="page-wrapper">
      <div class="container-fluid">
        <h4 class="ordenMedio">Entradas de inventario</h4>
        <div class="table-responsive">
          <table class="table table-hover">
            <?php
            //$SQlo=sqlsrv_query($SQLconn,"SELECT*FROM admdocumentos where CIDDOCUMENTODE=".$ciddoc."");
            $lstIn=$mysqli->query("SELECT * FROM obelisco.entradas order by id desc");

            ?>
            <thead class="thead-light">
            <tr> 
              <th>Opciones</th>
              <th>Fecha</th>
              <th>Serie</th>
              <th>Proveedor</th>
              <th>Total</th>
            </tr>  
            </thead>
            <tbody>
              <?php
              while($row=$lstIn->fetch_array())
              {
                $SQlo=sqlsrv_query($SQLconn,"SELECT CRAZONSOCIAL FROM ADMCLIENTES where CIDCLIENTEPROVEEDOR='".$row['proveedor']."'");
                $rowS=sqlsrv_fetch_array($SQlo,SQLSRV_FETCH_ASSOC);
              ?>
              <tr>
                <td><button class="btn glyphicon glyphicon-paperclip btn-detalle" id="<?php echo $row['id'];?>" title="Ver productos"></td>
                <td><?php echo $row['fecha'];?></td>
                <td><?php echo "Almacén";?></td>
                <td><?php echo $rowS['CRAZONSOCIAL'];?></td>
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

    
    <div id="window_in" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title" style="text-align: center">Detalle entrada a almacén</h2>
          </div>
          <div class="modal-body" id="detalleIn">
          </div>
          <div class="modal-footer">  
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>  
          </div>  
        </div>
      </div> 
    </div> 
</body>

        <script type="text/javascript">
         $(document).ready(function(){

          // Intercalar entre focus de los input
          if($("#cdgproveedor").val()!='')
          {
           $("#cdgproducto").prop('autofocus','true');
          }
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
        
        //Método para mostrar detalles de entrada seleccionada
        $(document).ready(function(){
          $(function() {
            $(document).on('click', 'button.btn-detalle', function(event){
              var id = this.id;
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_almacen.php",
                data: "show_mov=" + id,
                dataType: "html",
                error: function(){
                  alert("Error cargando detalle")
                },
                success: function(data){
                  $("#detalleIn").html(data);
                  $("#window_in").modal("show");
                }
              });
            }); 
          });
        });
        
        //Método para eliminar elementos de la entrada antes de guardarla
        $(document).ready(function(){
          $(function() {
            $(document).on('click', 'a.btn-borrar', function(event){
              var id = this.id;
              $.ajax({
                type: "POST",
                url: "controlador_almacen/crud_almacen.php",
                data: "eliminar=" + id,
                dataType: "html",
                error: function(){
                  alert("No se eliminó el elemento")
                },
                success: function(data){
                  $("#tablein").html(data);
                }
              });
            }); 
          });
        });

         $(document).ready(function(){
           $(function() {
             $(document).on('click', 'button#save-in', function(event) {
              var producto=$('#cdgproducto').val();
              var cantidad=$('#cdgcantidad').val();

              var hascode=$('#hascode').val();
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
                }
              });
            });
           });
         });

        $(document).ready(function(){
          $(document).on('keypress', '#cdgproducto', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code==13){
              
              var proveedor = $('#cdgproveedor').val(); 
              var producto = $('#cdgproducto').val();     
              var codTmp = producto.split("");
              var proveedorCB = codTmp[3]+codTmp[4]+codTmp[5];
              var betohben = parseInt(proveedor);
              console.log(proveedorCB);
              console.log(proveedor)
              if (betohben == proveedorCB) {
                $.ajax({
                  type: "POST",
                  url: "controlador_almacen/crud_almacen.php",
                  data: "b=" + producto,
                  dataType: "html",
                  error: function(){
                    alert("error petición ajax");
                  },
                  success: function(data){
                    if(data.substr(6,30) == '       <div class="modal-body"'){
                      $("#formCantidad").html(data);
                      $("#getcantidad").modal();
                      $('#cantidad').trigger('focus');
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
                    else{
                      $("#tablein").html(data);
                      $('#cdgproducto').val("");
                      $('#cdgproducto').trigger('focus');
                    }                                       
                  }
                });
              }else{
                alert("El código de barras no corresponde a este proveedor.");
              }  
            }
          });    

                $("#formulary").submit(function(e){
                e.preventDefault();
              });
                // Método para guardar la entrada (Cantidad y producto)
                $(document).on('keypress', '#cantidad', function(e) {

                var code = (e.keyCode ? e.keyCode : e.which);
                if(code==13){
                  if($('#hasprice').val()==0)
                  {
                    $('#precio').focus();
                  }
                  
                  else if($('#haslote').val()==1)
                  {
                    $('#lote').focus();
                  }

                  else
                  {
                  var producto=$('#idprodu').val();
                  var nombreProducto=$('#nameProduct').html();
                  var cantidad=$('#cantidad').val();
                  var cantidad2=$('#cantidad2').val();
                  var codiguin=$('#codigoB').val();
                  var hascode=$('#hascode').val();
                  $.ajax({
                    type: "POST",
                    url: "controlador_almacen/crud_almacen.php",
                    data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&h="+hascode+"&cB="+codiguin+"&u2="+cantidad2,
                    dataType: "html",
                    error: function(){
                      alert("error petición ajax");
                    },
                    success: function(data){ 
                      $('#cdgproducto').selectpicker('val', '');
                      $('#getcantidad').modal('hide');                   
                      $("#tablein").html(data);
                      $('#cdgproducto').val("");
                    } 
                  });
                  } 
                }
              });
              $(document).on('keypress', '#precio', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if(code==13){
                  $('#lote').focus();
                }
                });



          //Método para guardar la entrada CON PRECIO
          $(document).on('keypress', '#lote', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13)
           {
            var producto=$('#idprodu').val();
            var nombreProducto=$('#nameProduct').html();
            var codiguin=$('#codigoB').val();
            var cantidad=$('#cantidad').val();
            var cantidad2=$('#cantidad2').val();
            var hascode=$('#hascode').val();
            var precio=$('#precio').val();
            var lote = $('#lote').val();
            var fechaIn = $('#fechaIn').val();
            $.ajax({
              type: "POST",
              url: "controlador_almacen/crud_almacen.php",
              data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&h="+hascode+"&pr="+precio+"&cB="+codiguin+"&nlote="+lote+"&fechaIn="+fechaIn+"&u2="+cantidad2,
              dataType: "html",
              error: function(){
                alert("error petición ajax");
              },
              success: function(data){ 
                $('#cdgproducto').selectpicker('val', '');
                $('#getcantidad').modal('hide');                   
                $("#tablein").html(data);
                $('#cdgproducto').val("");
              } 
              }); 
          }

        });
           $(document).keydown(function(tecla){ 
            if (tecla.keyCode == 115) { 
                document.getElementById("send").click()
            }
        });
            //Método para guardar la entrada CON PRECIO Y UNIDADES
          $(document).on('keypress', '#unidades', function(e) {

           var code = (e.keyCode ? e.keyCode : e.which);
           if(code==13){
            var producto=$('#idprodu').val();
            var nombreProducto=$('#nameProduct').html();
             var codiguin=$('#codigoB').val();
            var cantidad=$('#cantidad').val();
            var cantidad2=$('#cantidad2').val();
            var hascode=$('#hascode').val();
            var precio=$('#precio').val();
            var unidades=$('#unidades').val();
            $.ajax({
              type: "POST",
              url: "controlador_almacen/crud_almacen.php",
              data: "c="+cantidad+"&p="+producto+"&n="+nombreProducto+"&h="+hascode+"&pr="+precio+"&un="+unidades+"&cB="+codiguin+"&u2="+cantidad2,
              dataType: "html",
              error: function(){
                alert("error petición ajax");
              },
              success: function(data){ 
                $('#cdgproducto').selectpicker('val', '');
                $('#getcantidad').modal('hide');                   
                $("#tablein").html(data);
                $('#cdgproducto').val("");
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