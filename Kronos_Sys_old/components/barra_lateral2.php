<?php
ob_start();
 $buf=getcwd();
 $modulo=explode('/', $buf);
 $modulo=$modulo[6];
 $nombreSistema="Kronos_Sys";
  $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
    <link rel="manifest" href="../pictures/manifest.json">
    <link rel="mask-icon" href="../pictures/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#000">
         <script type="text/javascript" src="../css/teclasN.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script type="text/javascript" src="../bootstrap/Popper.js"></script>
    <script src="../bootstrap/bootstrap-confirmation.js"></script>
    <style type="text/css">  
 @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
@media(min-width:768px) {
    body {
        margin-top: 50px;
    }
    /*html, body, #wrapper, #page-wrapper {height: 100%; overflow: hidden;}*/
}

    </style>
      <script src="../css/jquery-2.0.1.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../css/bootstrap-select-1.12.4/js/bootstrap-select.js"></script>


<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="../css/bootstrap-select-1.12.4/js/i18n/defaults-*.js"></script>
 
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Cairo|Leckerli+One|Eater|Rock+Salt|Russo+One" rel="stylesheet">
    <script type="text/javascript">

        window.alert = function(){};
        var defaultCSS = document.getElementById('bootstrap-css');
        function changeCSS(css){
            if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
            else $('head > link').filter(':first').replaceWith(defaultCSS); 
        }
        $( document ).ready(function() {
          var iframe_height = parseInt($('html').height()); 
          window.parent.postMessage( iframe_height, 'https://bootsnipp.com');
        });
    </script>
</head>
<body>
<div id="wrapper" >
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top bg-secondary" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header" >
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../Menu.php">
                <img src="../pictures/logo-labro.png" alt="Grupo Labro" width="160" height="50">
            </a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav" >
                    
            <li class="dropdown">
                <p style="height:30px" class="navbar-text pull-right "><?php echo $_SESSION['nombre'].",".$_SESSION['nombrerol'];?>/ <a href="../Controler/logout.php">Cerrar sesión</a></p>
                
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="#" data-toggle="collapse" class="menu_opcion" data-target="#submenu-1"  <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Personal'){ echo "Style='background-color:#E8EDFF'";} ?>><img src="../pictures/photo.png">Recursos humanos<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-1" class="collapse">
                        <li><a href="../Personal"><i class="fa fa-angle-double-right"></i>Personal</a></li>
                        <li><a href="../Personal/Departamento.php"><i class="fa fa-angle-double-right"></i>Departamentos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" class="menu_opcion" data-target="#submenu-2" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Producto'){ echo "Style='background-color:#E8EDFF'";} ?>><img src="../pictures/design.png">Producto<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-2" class="collapse">
                          <li><a href="../Producto/Producto_Disenio.php"><i class="fa fa-angle-double-right"></i>Diseño</a></li>
                        <li><a href="../Producto/Producto_Impresiones.php"><i class="fa fa-angle-double-right"></i>Impresión</a></li>
                        <li><a href="../Producto/Producto_Consumos.php"><i class="fa fa-angle-double-right"></i>Consumos</a></li>
                        <li><a href="../Producto/Producto_JuegosCilindros.php"><i class="fa fa-angle-double-right"></i>Juegos de cilindros</a></li>
                        <li><a href="../Producto/Producto_Cireles.php"><i class="fa fa-angle-double-right"></i>Juegos de cireles</a></li>
                        <li><a href="../Producto/Producto_Suaje.php"><i class="fa fa-angle-double-right"></i>Suaje</a></li>
                        <li><a href="../Producto/Producto_ProductosCliente.php"><i class="fa fa-angle-double-right"></i>Productos cliente</a></li>
                        <li><a href="../Producto/Producto_BandasSeguridad.php"><i class="fa fa-angle-double-right"></i>Banda de seguridad</a></li>
                        <?php
                        if($_SESSION["perfil"]=="PF0")
                        {?>
                              <li><a href="../Cotizador/Cotizador.php"><i class="fa fa-angle-double-right"></i>Cotizador</a></li>
                              <?php
                        }?>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-3" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Materia_prima'){ echo "Style='background-color:#E8EDFF'";} ?>>
                   <img src="../pictures/shopping_cart.png">Materia prima<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-3" class="collapse">
                        <li><a href="../Materia_prima/MateriaPrima_Bloques.php"><i class="fa fa-angle-double-right"></i>Bloques</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_Lotes.php"><i class="fa fa-angle-double-right"></i>Bobinas</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_Sustrato.php"><i class="fa fa-angle-double-right"></i>Sustrato</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_Elementos.php"><i class="fa fa-angle-double-right"></i>Elementos de consumo</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_Pantone.php"><i class="fa fa-angle-double-right"></i>Pantone</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_UnidadesMedida.php"><i class="fa fa-angle-double-right"></i>Unidades de medida</a></li>
                        <li><a href="../Materia_prima/MateriaPrima_Explosion.php"><i class="fa fa-angle-double-right"></i>Explosión de materiales</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-4" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Logistica'){ echo "Style='background-color:#E8EDFF'";} ?>>
                        <img src="../pictures/exchange.png">Logística<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-4" class="collapse">
                        <li><a href="../Logistica/Logistica_Clientes.php"><i class="fa fa-angle-double-right"></i>Clientes</a></li>
                        <li><a href="../Logistica/Logistica_Sucursales.php"><i class="fa fa-angle-double-right"></i>Sucursales</a></li>
                        <li><a href="../Logistica/Logistica_ContactoClientes.php"><i class="fa fa-angle-double-right"></i>Contacto cliente</a></li>
                        <li><a href="../Logistica/Logistica_ContactoSucursales.php"><i class="fa fa-angle-double-right"></i>Contacto sucursal</a></li>
                        <li><a href="../Logistica/Logistica_OrdenesCompra.php"><i class="fa fa-angle-double-right"></i>Ordenes de compra</a></li>
                        <li><a href="../Logistica/Logistica_RequerimientosOrden.php"><i class="fa fa-angle-double-right"></i>Requerimientos de orden compra</a></li>
                        <li><a href="../Logistica/Logistica_ConfirmacionesOrden.php"><i class="fa fa-angle-double-right"></i>Confirmaciones de orden compra</a></li>
                        <li><a href="../Logistica/Logistica_Embarques.php"><i class="fa fa-angle-double-right"></i>Embarque</a></li>
                        <li><a href="../Logistica/Logistica_Devoluciones.php"><i class="fa fa-angle-double-right"></i>Devoluciones</a></li>
                    </ul>
                </li>
                <li>
                     <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-5" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Produccion'){ echo "Style='background-color:#E8EDFF'";} ?>>
                        <img src="../pictures/gear.png">Producción<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                     <ul id="submenu-5" class="collapse">
                        <li><a href="../Produccion/Produccion_control.php"><i class="fa fa-angle-double-right"></i>Tablero de control</a></li>
                        <li><a href="../Produccion/Produccion_Registro.php"><i class="fa fa-angle-double-right"></i>Registro de operaciones</a></li>
                        <li><a href="../Produccion/Produccion_Reporte.php"><i class="fa fa-angle-double-right"></i>Tablero de reportes</a></li>
                        <li><a href="../Produccion/Produccion_programacion.php"><i class="fa fa-angle-double-right"></i>Programación</a></li>
                        <li><a href="../Produccion/bajaBS.php"><i class="fa fa-angle-double-right"></i>Baja de BS</a></li>
                        <li><a href="../Produccion/Produccion_Maquinas.php"><i class="fa fa-angle-double-right"></i>Máquinas</a></li>
                        <li><a href="../Produccion/PlanEntregas.php"><i class="fa fa-angle-double-right"></i>Plan de entregas</a></li>
                    </ul>
                </li>
                <li>
                     <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-6" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Calidad'){ echo "Style='background-color:#E8EDFF'";} ?>>
                          <img src="../pictures/calidad.png">Buscador<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-6" class="collapse">
                         <li><a href="../Calidad/Calidad_seguimiento.php"><i class="fa fa-angle-double-right"></i>Seguimiento</a></li>
                        <li><a href="../Calidad/Buscador_Calidad.php"><i class="fa fa-angle-double-right"></i>Localizador</a></li>
                        <li><a href="../Calidad/Buscador_Mapa.php"><i class="fa fa-angle-double-right"></i>Mapa de procesos</a></li>
                    </ul>
                </li>
                <li>
                     <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-7" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Sistema'){ echo "Style='background-color:#E8EDFF'";} ?>>
                         <img src="../pictures/windows.png">Sistema<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-7" class="collapse">
                       <li><a href="../Sistema/newpass.php"><i class="fa fa-angle-double-right"></i>Cambio de contraseña</a></li>
                        <li><a href="../Sistema/newUser.php"><i class="fa fa-angle-double-right"></i>Agregar usuarios</a></li>
                        <li><a href="../Sistema/perfiles.php"><i class="fa fa-angle-double-right"></i>Perfiles</a></li>
                        <li><a href="../Sistema/Estado.php"><i class="fa fa-angle-double-right"></i>Mapa</a></li>
                        <li><a href="../Sistema/bitacora.php"><i class="fa fa-angle-double-right"></i>Bitácora</a></li>
                        <li><a href="../Miscelaneos/Misce_Productos.php"><i class="fa fa-angle-double-right"></i>Misceláneos</a></li>
                    </ul>
                </li>
                  <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-8" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Sistema'){ echo "Style='background-color:#E8EDFF'";} ?>>
                         <img src="../pictures/search.png" width="40">Reporteador<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-8" class="collapse">
                       <li><a href="../Cotizador/Reporteador.php?doc=CO-FR04"><i class="fa fa-angle-double-right"></i>CO-FR04 Plan de compras</a></li>
                    </ul>
                </li>
               <?php /* <li>
                     <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-4" class="menu_opcion" <?php if($buf=='/var/www/html/Saturno v0.6/'.$nombreSistema.'/Calidad'){ echo "Style='background-color:#E8EDFF'";} ?>>
                         <img src="../pictures/folder_search.png">Buscador<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-4" class="collapse">
                        <li><i class="fa fa-angle-double-right"></i>Plata</a></li>
                        <li><i class="fa fa-angle-double-right"></i>Dorado</a></li>
                        <li><i class="fa fa-angle-double-right"></i>Gris</a></li>
                    </ul>
                    */?>
                </li>
            </ul>
        </div>

        <!-- /.navbar-collapse -->
    </nav>

        <!-- /.container-fluid -->
    <!-- /#page-wrapper -->
    <script type="text/javascript">
    $(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $(".side-nav .collapse").on("hide.bs.collapse", function() {                   
        $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
    });
    $('.side-nav .collapse').on("show.bs.collapse", function() {                        
        $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");        
    });
})
window.onload=function(){
var pos=window.name || 0;
window.scrollTo(0,pos);
}
window.onunload=function(){
window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
}    
    
    </script>
</body>
</html>
<?php
ob_end_flush();
?>