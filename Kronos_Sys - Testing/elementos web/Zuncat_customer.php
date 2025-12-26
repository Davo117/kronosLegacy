<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">

    <title>ZunCat- Personalización</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <style type="text/css">  
 @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
@media(min-width:768px) {
    body {
        margin-top: 50px;
    }
    /*html, body, #wrapper, #page-wrapper {height: 100%; overflow: hidden;}*/
}
    </style>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
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
    <div id="throbber" style="display:none; min-height:120px;"></div>
<div id="noty-holder"></div>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">
                <img src="" alt="ZunCat">
            </a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li><a href="#" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="soy un logo we"><i class="   fa fa-gamepad"></i>
                </a>
            </li>            
            <li class="dropdown">
                <p class="titulo">Personalización</p>
                
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-bug"></i> Modelo<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-1" class="collapse">
                        <li><a href="Zuncat_customer.php?formini=Fear&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Fear</a></li>
                        <li><a href="Zuncat_customer.php?formini=Sunnyes&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Sunnyes</a></li>
                        <li><a href="Zuncat_customer.php?formini=Big Jou&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Big Jou</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-paint-brush"></i> Color de lente<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-2" class="collapse">
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=red&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Rojo<br><img class="textura" src="texturas/glass_red.jpg"></a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=green&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Verde<br><img class="textura" src="texturas/glass_green.jpg"></a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=pink&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Rosa<br><img class="textura" src="texturas/glass_pink.jpg"></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-3">
                    <i class="fa fa-simplybuilt"></i> Armazón<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-3" class="collapse">
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=black&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Negro<br><img class="textura" src="texturas/armazon_black.jpg"></a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=purple&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Morado<br><img class="textura" src="texturas/armazon_purple.jpg"></a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=blue&terminal=<?php if(isset($_GET['terminal'])){ echo $_GET['terminal'];}else{}?>"><i class="fa fa-angle-double-right"></i>Azul<br><img class="textura" src="texturas/armazon_blue.jpg"></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-4">
                        <i class="fa fa-tint"></i> Terminales<i class="fa fa-fw fa-angle-down pull-right"></i></a>

                     <ul id="submenu-4" class="collapse">
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=EEEEEE"><i class="fa fa-angle-double-right"></i>Plata</a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=CBE702"><i class="fa fa-angle-double-right"></i>Dorado</a></li>
                        <li><a href="Zuncat_customer.php?formini=<?php if(isset($_GET['formini'])){ echo $_GET['formini'];}else{}?>&colorsini=<?php if(isset($_GET['colorsini'])){ echo $_GET['colorsini'];}else{}?>&armazon=<?php if(isset($_GET['armazon'])){ echo $_GET['armazon'];}else{}?>&terminal=9E9E9E"><i class="fa fa-angle-double-right"></i>Gris</a></li>
                    </ul>
                </li>
                <li>
                    <a href="faq"><i class="fa fa-fw fa fa-question-circle"></i> MENU 5</a>
                </li>
            </ul>
        </div>

        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
                <?php /*<div class="tit_modelo" 
                 style="background-color:black;float:left;<?php 
                 if(isset($_GET['formini'])) 
                 {

                    if($_GET['formini']=='Fear')
                    {
                        echo "font-family: 'Rock Salt', cursive;";
                    }
                    else if($_GET['formini']=='Sunnyes')
                    {
                        echo "font-family: 'Leckerli One', cursive;";
                    }
                    else if($_GET['formini']=='Big Jou')
                    {
                        echo "font-family: 'Russo One', sans-serif;";
                    }
                 }?> " id="content">
                    <h1><?php if(isset($_GET['formini'])){ echo $_GET['formini'];}?></h1>
                </div>
                <div style="float:right;">
                <?php
*/
                if(isset($_GET['formini']))
                {
                    /*<p style="<?php echo 'color:'.$_GET['colorsini']?>"><?php echo 'color:'.$_GET['formini']?></p><b style="<?php echo 'color:'.$_GET['armazon']?> ">Soy un armazón</b><b style="<?php echo 'color:#'.$_GET['terminal']?>"><?php echo 'color:'.'\'#'.$_GET['terminal'].'\''?></b>*/?>
                    

                    <iframe  width="850" height="800" src="Cat_elementos/<?php echo $_GET['formini'].'.php'?>"></iframe>
                    <?php
                }
                else
                {
                    ?>
                    <iframe  width="850" height="800" src="Cat_elementos/Default.php"></iframe>
                    <?php
                }
                ?>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
</div><!-- /#wrapper -->
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