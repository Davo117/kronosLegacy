<?php
ob_start();
session_start();
if (isset($_SESSION['logan']) && $_SESSION['logan'] == true) { ?>

  <head>
   <title>Layout | Almacén</title>
   <!-- Latest compiled and minified CSS -->


 </head>
 <?php
 include("../components/barra_latera_almacen.php");
 include "modelo_almacen/entrada.php";
 include("controlador_almacen/crud_almacen.php");
 include("../Database/SQLConnection.php");
 include("../Database/conexionphp.php");


?>
<body>
  <div id="page-wrapper">
    <div class="container-fluid"></div>
      <div id="page-wrapper">
        <style type="text/css">
        <?php
        $conti=1;
        $SqC=$mysqli->query("SELECT place FROM obelisco.productosCK");
        while($rum=$SqC->fetch_array())
        {
          $selec=explode(' ',$rum['place']);
          if(!empty($selec[1]))
          {
            $selec=$selec[0].$selec[1];
          }
          else
          {
            $selec=$selec[0].$conti;
            
          }
          $conti++;
          echo '.'.$selec." { background: gray;border-radius:5px;padding:10px;margin:5px }";
        }
        ?>
        </style>
      <div class="container-fluid">
        <h4 class="ordenMedio" style="font-size:25px">OCUPACIÓN DEL LAYOUT</h4>
        <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
        <h4 class="ordenMedio">Cajón 1</h4>
          <table class="table table-hover" width="100">
            <thead>
              <th colspan="15">Estante</th>
              <tr>  
              <th colspan="3" style="text-align:center;">E1</th><th colspan="4" style="text-align:center;">E2</th><th colspan="4" style="text-align:center;">E3</th> <th colspan="4" style="text-align:center;">E4</th></tr>
            </thead>
                <td><button class="btn E15-1 espacio" id="E1 5-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-1</button></td>
                <td><button  class="btn E15-2 espacio" id="E1 5-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-2</button></td>
                <td><button  class="btn E15-3 espacio" id="E1 5-3" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-3</button></td>
                <td></td>
                <td><button  class="btn E25-1 espacio" id="E2 5-1" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-1</button></td>
                <td><button  class="btn E25-2 espacio" id="E2 5-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-2</button></td>
                <td><button  class="btn E25-3 espacio" id="E2 5-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-3</button></td>
                <td></td>
                <td><button  class="btn E35-1 espacio" id="E3 5-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-1</button></td>
                <td><button  class="btn E35-2 espacio" id="E3 5-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-2</button></td>
                <td><button  class="btn E35-3 espacio" id="E3 5-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-3</button></td>
                <td></td>
                <td><button  class="btn E45-1 espacio" id="E4 5-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-1</button></td>
                <td><button  class="btn E45-2 espacio" id="E4 5-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-2</button></td>
                <td><button  class="btn E45-3 espacio" id="E4 5-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-3</button></td>
              </tr>
              <tr>
                <td><button  class="btn E14-1 espacio" id="E1 4-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                <td><button  class="btn E14-2 espacio" id="E1 4-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                <td><button  class="btn E14-3 espacio" id="E1 4-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-3</button></td>
                <td></td>
                <td><button  class="btn E24-1 espacio" id="E2 4-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                <td><button  class="btn E24-2 espacio"id="E2 4-2"  title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                <td><button  class="btn E24-3 espacio" id="E2 4-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-3</button></td>
                <td></td>
                <td><button  class="btn E34-1 espacio" id="E3 4-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                <td><button  class="btn E34-2 espacio" id="E3 4-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                <td><button  class="btn E34-3 espacio" id="E3 4-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-3</button></td>
                <td></td>
                <td><button  class="btn E44-1 espacio" id="E4 4-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                <td><button  class="btn E44-2 espacio" id="E4 4-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                <td><button  class="btn E44-3 espacio" id="E4 4-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-3</button></td>
              </tr>
              <tr>
                <td class=""><button  class="btn E13-1 espacio" id="E1 3-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                <td class=""><button  class="btn E13-2 espacio" id="E1 3-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                <td class=""><button  class="btn E13-3 espacio" id="E1 3-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-3</button></td>
                <td></td>
                <td class=""><button  class="btn E23-1 espacio" id="E2 3-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                <td class=""><button  class="btn E23-2 espacio" id="E2 3-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                <td class=""><button  class="btn E23-3 espacio" id="E2 3-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-3</button></td>
                <td></td>
                <td class=""><button  class="btn E33-1 espacio" id="E3 3-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                <td class=""><button  class="btn E33-2 espacio" id="E3 3-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                <td class=""><button  class="btn E33-3 espacio" id="E3 3-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-3</button></td>
                <td></td>
                <td class=""><button  class="btn E43-1 espacio" id="E4 3-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                <td class=""><button  class="btn E43-2 espacio" id="E4 3-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                <td class=""><button  class="btn E43-3 espacio" id="E4 3-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-3</button></td>
              </tr>
              <tr>
                <td class=""><button  class="btn E12-1 espacio" id="E1 2-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                <td class=""><button  class="btn E12-2 espacio" id="E1 2-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                <td class=""><button  class="btn E12-3 espacio" id="E1 2-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-3</button></td>
                <td></td>
                <td class=""><button  class="btn E22-1 espacio" id="E2 2-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                <td class=""><button  class="btn E22-2 espacio" id="E2 2-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                <td class=""><button  class="btn E22-3 espacio" id="E2 2-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-3</button></td>
                <td></td>
                <td class=""><button  class="btn E32-1 espacio" id="E3 2-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                <td class=""><button  class="btn E32-2 espacio" id="E3 2-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                <td class=""><button  class="btn E32-3 espacio" id="E3 2-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-3</button></td>
                <td></td>
                <td class=""><button  class="btn E42-1 espacio" id="E4 2-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                <td class=""><button  class="btn E42-2 espacio" id="E4 2-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                <td class=""><button  class="btn E42-3 espacio" id="E4 2-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-3</button></td>
              </tr>
              <tr>
                <td class=""><button  class="btn E11-1 espacio" id="E1 1-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                <td class=""><button  class="btn E11-2 espacio" id="E1 1-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                <td class=""><button  class="btn E11-3 espacio" id="E1 1-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-3</button></td>
                <td></td>
                <td class=""><button  class="btn E21-1 espacio" id="E2 1-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                <td class=""><button  class="btn E21-2 espacio" id="E2 1-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                <td class=""><button  class="btn E21-3 espacio" id="E2 1-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-3</button></td>
                <td></td>
                <td class=""><button  class="btn E31-1 espacio" id="E3 1-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                <td class=""><button  class="btn E31-2 espacio" id="E3 1-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                <td class=""><button  class="btn E31-3 espacio" id="E3 1-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-3</button></td>
                <td></td>
                <td class=""><button  class="btn E41-1 espacio" id="E4 1-1" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                <td class=""><button  class="btn E41-2 espacio" id="E4 1-2" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                <td class=""><button  class="btn E41-3 espacio" id="E4 1-3" title="Ver productos"  style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-3</button></td>
              </tr>
          </table>
        </div>

              <div class="table-responsive col-xs-2 col-lg-2 col-md-2">
                <table class="table table-hover" width="100">
                  <thead>
                    <th colspan="3">Jaulas</th>
                    <tr>
                      <th colspan="3" style="text-align:center;">J1</th>
                    </tr>
                  </thead>
                  <tr>
                      <td><button class="btn J12-1 espacio" id="J1 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                  </tr>
                  <tr>
                      <td><button  class="btn J11-1 espacio" id="J1 1-1" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                      <td><button  class="btn J11-2 espacio" id="J1 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                  </tr>
                </table>
              </div>

              
              <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
              <h4 class="ordenMedio">Cajón 2</h4>
                <table class="table table-hover" width="100">
                  <thead>
                    <th colspan="2">Racks</th>
                    <tr>
                      <th colspan="3" style="text-align:center;">R1</th>
                      <th colspan="4" style="text-align:center;">R2</th>
                      <th colspan="4" style="text-align:center;">R3</th>
                      <th colspan="4" style="text-align:center;">R4</th>
                      <th colspan="4" style="text-align:center;">R5</th>
                      <th colspan="4" style="text-align:center;">R6</th>
                    </tr>
                    </thead>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R3C1 espacio" id="R3 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R3C2 espacio" id="R3 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R4C1 espacio" id="R4 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R4C2 espacio" id="R4 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R5C1 espacio" id="R5 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R5C2 espacio" id="R5 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R6C1 espacio" id="R6 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R6C2 espacio" id="R6 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                      </tr>
                      <tr>
                        <td><button class="btn R1B1 espacio" id="R1 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R1B2 espacio" id="R1 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R2B1 espacio" id="R2 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R2C2 espacio" id="R2 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R3B1 espacio" id="R3 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R3B2 espacio" id="R3 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R4B1 espacio" id="R4 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R4B2 espacio" id="R4 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R5B1 espacio" id="R5 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R5B2 espacio" id="R5 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R6B1 espacio" id="R6 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R6B2 espacio" id="R6 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                      </tr>
                      <tr>
                        <td><button class="btn R1A1 espacio" id="R1 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R1A2 espacio" id="R1 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R2A1 espacio" id="R2 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R2A2 espacio" id="R2 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R3A1 espacio" id="R3 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R3A2 espacio" id="R3 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R4A1 espacio" id="R4 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R4A2 espacio" id="R4 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R5A1 espacio" id="R5 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R5A2 espacio" id="R5 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R6A1 espacio" id="R6 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R6A2 espacio" id="R6 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                      </tr>
                  </table>
                </div>

            
            <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
            <h4 class="ordenMedio">Cajón 3</h4>
              <table class="table table-hover" width="100">
                  <thead>
                    <th colspan="2">Racks</th>
                    <tr>
                      <th colspan="3" style="text-align:center;">R7</th>
                      <th colspan="4" style="text-align:center;">R8</th>
                      <th colspan="4" style="text-align:center;">R9</th>
                      <th colspan="4" style="text-align:center;">R10</th>
                      <th colspan="4" style="text-align:center;">R11</th>
                      <th colspan="4" style="text-align:center;">R12</th>
                    </tr>
                    </thead>
                      <tr>
                        <td><button class="btn R7C1 espacio" id="R7 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R7C2 espacio" id="R7 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R8C1 espacio" id="R8 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R8C2 espacio" id="R8 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R9C1 espacio" id="R9 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R9C2 espacio" id="R9 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R10C1 espacio" id="R10 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R10C2 espacio" id="R10 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R11C1 espacio" id="R11 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R11C2 espacio" id="R11 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R12C1 espacio" id="R12 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                        <td><button class="btn R12C2 espacio" id="R12 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                      </tr>
                      <tr>
                        <td><button class="btn R7B1 espacio" id="R7 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R7B2 espacio" id="R7 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R8B1 espacio" id="R8 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R8B2 espacio" id="R8 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R9B1 espacio" id="R9 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R9B2 espacio" id="R9 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R10B1 espacio" id="R10 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R10B2 espacio" id="R10 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R11B1 espacio" id="R11 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R11B2 espacio" id="R11 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R12B1 espacio" id="R12 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                        <td><button class="btn R12B2 espacio" id="R12 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                      </tr>
                      <tr>
                        <td><button class="btn R7A1 espacio" id="R7 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R7A2 espacio" id="R7 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R8A1 espacio" id="R8 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R8A2 espacio" id="R8 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R9A1 espacio" id="R9 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R9A2 espacio" id="R9 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R10A1 espacio" id="R10 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R10A2 espacio" id="R10 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R11A1 espacio" id="R11 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R11A2 espacio" id="R11 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn R12A1 espacio" id="R12 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                        <td><button class="btn R12A2 espacio" id="R12 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                      </tr>
                    </table>
                </div>
            
                
                <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
                <h4 class="ordenMedio">Cajón 4</h4>
                  <table class="table table-hover" width="100">
                      <thead>
                        <th colspan="2">Racks</th>
                        <tr>
                          <th colspan="3" style="text-align:center;">R13</th>
                          <th colspan="4" style="text-align:center;">R14</th>
                          <th colspan="4" style="text-align:center;">R15</th>
                          <th colspan="4" style="text-align:center;">R16</th>
                          <th colspan="4" style="text-align:center;">R17</th>
                          <th colspan="4" style="text-align:center;">R18</th>
                        </tr>
                        </thead>
                          <tr>
                          <td><button class="btn R13A1 espacio" id="R13 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R13A2 espacio" id="R13 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R14A1 espacio" id="R14 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R14A2 espacio" id="R14 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R15A1 espacio" id="R15 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R15A2 espacio" id="R15 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R16A2 espacio" id="R16 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R16A2 espacio" id="R16 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R17A1 espacio" id="R17 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R17A2 espacio" id="R17 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R18A1 espacio" id="R18 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button  class="btn R18A2 espacio" id="R18 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                          </tr>
                          <tr>
                            <td><button class="btn R13A1 espacio" id="R13 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R13A2 espacio" id="R13 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R14A1 espacio" id="R14 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R14A2 espacio" id="R14 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R15A1 espacio" id="R15 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R15A2 espacio" id="R15 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R16A1 espacio" id="R16 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R16A2 espacio" id="R16 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R17A1 espacio" id="R17 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R17A2 espacio" id="R17 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R18A1 espacio" id="R18 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button  class="btn R18A2 espacio" id="R18 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                          </tr>
                          <tr>
                            <td><button class="btn R13A1 espacio" id="R13 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R13A2 espacio" id="R13 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R14A1 espacio" id="R14 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R14A2 espacio" id="R14 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R15A1 espacio" id="R15 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R15A1 espacio" id="R15 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R16A1 espacio" id="R16 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R16A1 espacio" id="R16 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R17A1 espacio" id="R17 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R17A2 espacio" id="R17 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R18A1 espacio" id="R18 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R18A2 espacio" id="R18 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                          </tr>
                        </table>
                    </div>

                    
                    <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
                    <h4 class="ordenMedio">Cajón 5 · Tintas</h4>
                      <table class="table table-hover" width="100">
                          <thead>
                            <th colspan="2">Estantes</th>
                            <tr>
                              <th colspan="1" style="text-align:center;">E5</th>
                              <th colspan="6" style="text-align:center;">E6</th>
                              <th colspan="2" style="text-align:center;">E7</th>
                              <th colspan="3" style="text-align:center;">E8</th>
                            </tr>
                            </thead>
                              <tr>
                                <td><button class="btn E55-1 espacio" id="E5 5-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-1</button></td>
                                <td><button class="btn E55-2 espacio" id="E5 5-1" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">5-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E65-1 espacio" id="E6 5-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                                <td><button class="btn E65-2 espacio" id="E6 5-1" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E71-1 espacio" id="E7 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E81-1 espacio" id="E8 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td><button class="btn E54-1 espacio" id="E5 4-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                                <td><button class="btn E54-2 espacio" id="E5 4-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E64-1 espacio" id="E6 4-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-1</button></td>
                                <td><button class="btn E64-1 espacio" id="E6 4-1" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">4-2</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              <tr>
                                <td><button class="btn E53-1 espacio" id="E5 3-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                                <td><button class="btn E53-1 espacio" id="E5 3-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E63-1 espacio" id="E6 3-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-1</button></td>
                                <td><button class="btn E63-1 espacio" id="E6 3-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">3-2</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td><button class="btn E52-1 espacio" id="E5 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                <td><button class="btn E52-1 espacio" id="E5 2-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E62-1 espacio" id="E6 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                <td><button class="btn E62-1 espacio" id="E6 2-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td><button class="btn E51-1 espacio" id="E5 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                <td><button class="btn E51-2 espacio" id="E5 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                <td></td>
                                <td></td>
                                <td><button class="btn E61-1 espacio" id="E6 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                <td><button class="btn E61-1 espacio" id="E6 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                            </table>
                          </div>
                            <div class="table-responsive col-xs-8 col-lg-8 col-md-8">
                              <table class="table table-hover" width="100">
                                <thead>
                                <th colspan="4">Tarimas</th>
                                <tr>
                                  <th colspan="2" style="text-align:center;">T1</th>
                                  <th colspan="3" style="text-align:center;">T2</th>
                                  <th colspan="3" style="text-align:center;">T3</th>
                                  <th colspan="4" style="text-align:center;">T4</th>
                                </tr>
                                <tr>
                                  <th colspan="2" style="text-align:center;">Adhesivos</th>
                                  <th colspan="3" style="text-align:center;">Bases</th>
                                  <th colspan="3" style="text-align:center;">Gan | Proccess | Reflex</th>
                                  <th colspan="4" style="text-align:center;">Extender | Primer | Reductor</th>
                                </tr>
                                </thead>
                                <tr>
                                  <td><button class="btn T12-1 espacio" id="T1 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                  <td><button class="btn T12-2 espacio" id="T1 2-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                  <td></td>
                                  <td><button class="btn T22-1 espacio" id="T2 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                  <td><button class="btn T22-2 espacio" id="T2 2-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                  <td></td>
                                  <td><button class="btn T32-1 espacio" id="T3 2-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                  <td><button class="btn T32-2 espacio" id="T3 2-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                  <td></td>
                                  <td><button class="btn T42-1 espacio" id="T1 4-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-1</button></td>
                                  <td><button class="btn T43-2 espacio" id="T1 4-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">2-2</button></td>
                                </tr>
                                  <td><button class="btn T11-1 espacio" id="T1 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                  <td><button class="btn T11-2 espacio" id="T1 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                  <td></td>
                                  <td><button class="btn T21-1 espacio" id="T2 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                  <td><button class="btn T21-2 espacio" id="T2 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                  <td></td>
                                  <td><button class="btn T31-1 espacio" id="T3 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                  <td><button class="btn T31-2 espacio" id="T3 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                  <td></td>
                                  <td><button class="btn T41-1 espacio" id="T4 1-1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-1</button></td>
                                  <td><button class="btn T41-2 espacio" id="T4 1-2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">1-2</button></td>
                                <tr>
                              </table>
                              
                            </div>

                            <div class="table-responsive col-xs-7 col-lg-7 col-md-7">
                              <table class="table table-hover" width="100">
                                <thead>
                                  <th colspan="4">Racks</th>
                                  <tr>
                                  <th colspan="2" style="text-align:center;">R19</th>
                                  <th colspan="4" style="text-align:center;">R20</th>
                                  </tr>
                              </thead>
                                  <tr>
                                    <td><button class="btn R19C1 espacio" id="R19 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                                    <td><button class="btn R19C2 espacio" id="R19 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                                    <td></td>
                                    <td><button class="btn R20C1 espacio" id="R20 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                                    <td><button class="btn R20C2 espacio" id="R20 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                                  </tr>
                                  <tr>
                                    <td><button class="btn R19B1 espacio" id="R19 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                                    <td><button class="btn R19B2 espacio" id="R19 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                                    <td></td>
                                    <td><button class="btn R20B1 espacio" id="R20 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                                    <td><button class="btn R20B2 espacio" id="R20 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                                  </tr>
                                  <tr>
                                    <td><button class="btn R19A1 espacio" id="R19 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                                    <td><button class="btn R19A2 espacio" id="R19 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                                    <td></td>
                                    <td><button class="btn R20A1 espacio" id="R20 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                                    <td><button class="btn R20A2 espacio" id="R20 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                                  </tr>
                              </table>
                            </div>

                  
                  <div class="table-responsive col-xs-12 col-lg-12 col-md-12">
                  <h4 class="ordenMedio">Cajón 6 · Cores</h4>
                  <table class="table table-hover" width="100">
                      <thead>
                        <th colspan="2">Racks</th>
                        <tr>
                          <th colspan="3" style="text-align:center;">R21</th>
                          <th colspan="4" style="text-align:center;">R22</th>
                          <th colspan="4" style="text-align:center;">R23</th>
                          <th colspan="4" style="text-align:center;">R24</th>
                        </tr>
                        </thead>
                          <tr>
                            <td><button class="btn R21C1 espacio" id="R21 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button class="btn R21C2 espacio" id="R21 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R22C1 espacio" id="R22 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button class="btn R22C2 espacio" id="R22 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R23C1 espacio" id="R23 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button class="btn R23C2 espacio" id="R23 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R24C1 espacio" id="R24 C1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C1</button></td>
                            <td><button class="btn R24C2 espacio" id="R24 C2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">C2</button></td>
                          </tr>
                          <tr>
                            <td><button class="btn R21B1 espacio" id="R21 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button class="btn R21B2 espacio" id="R21 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R22B1 espacio" id="R22 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button class="btn R22B2 espacio" id="R22 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R23B1 espacio" id="R23 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button class="btn R23B2 espacio" id="R23 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R24B1 espacio" id="R24 B1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B1</button></td>
                            <td><button class="btn R24B2 espacio" id="R24 B2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">B2</button></td>
                          </tr>
                          <tr>
                            <td><button class="btn R21A1 espacio" id="R21 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R21A2 espacio" id="R21 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R22A1 espacio" id="R22 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R22A2 espacio" id="R22 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R23A1 espacio" id="R23 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R23A2 espacio" id="R23 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn R24A1 espacio" id="R24 A1" title="Ver productos" style=""><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A1</button></td>
                            <td><button class="btn R24A2 espacio" id="R24 A2" title="Ver productos" style="" ><img src="../pictures/alone.png" class="img-responsive" width="30" height="30">A2</button></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </table>
                    </div>
            </body>

            <div id="window_in" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title" style="text-align: center">Detalle Ubicación</h2>
                  </div>
                  <div class="modal-body" id="detalleIn">
                  </div>
                  <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>  
                  </div>  
                </div>
              </div> 
            </div> 
          </html>

          <script type="text/javascript">
          $(document).ready(function(){
            $(function() {
              $(document).on('click', 'button.espacio', function(event){
                var id = this.id;

                $.ajax({
                  type: "POST",
                  url: "controlador_almacen/crud_almacen.php",
                  data: "show_prod="+id,
                  dataType: "html",
                  error: function(){
                    alert("Error cargando detalle")
                  },
                  success: function(data){
                    console.log(id);
                    $("#detalleIn").html(data);
                    $("#window_in").modal("show");
                  }
                });
              }); 
            });
          });
          </script>

      <?php
      ob_end_flush();
    } else {
      echo '<script language="javascript">alert("Necesitas iniciar sesión para estar aquí");</script>'; 
      include "../ingresar.php";
    }
    ?>