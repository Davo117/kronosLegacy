<?php

if (isset($_GET) && isset($_GET['proceso'])) {
    proce($_GET['proceso']);
  }

function proce($data){
  include("../Database/db.php");
include("../FusionCharts/integrations/php/samples/includes/fusioncharts.php");
$process="";
$process="&& subproceso='".$data."'"; ?>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <link rel="stylesheet" href="../css/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">
    
<!-- Latest compiled and minified JavaScript -->
<script src="../css/bootstrap-select-1.12.4/js/bootstrap-select.js"></script>
<!-- Librería de FusionCharts -->
<script type="text/javascript" src="../FusionCharts/js/fusioncharts.js"></script>

<script type="text/javascript" src="../FusionCharts/js/themes/fusioncharts.theme.candy.js"></script>
 
<h4 >Gráficas de Máquinas de <?php echo $_GET['proceso']; ?></h4>
 <?php
$SQL=$MySQLiconn->query("SELECT idMaq, codigo, subproceso FROM maquinas where baja='1' $process");

while ($row=$SQL->fetch_array()) {
  ${"gaugeData".$row['idMaq']} = "{
    \"chart\": {
      \"caption\": \"Máquina ".$row['codigo']."\",
      \"lowerlimit\": \"0\",
      \"upperlimit\": \"100\",
      \"showvalue\": \"1\",
      \"numbersuffix\": \"%\",
      \"theme\": \"candy\",
      \"showtooltip\": \"0\"
    },
    \"colorrange\": {
      \"color\": [
        {
          \"minvalue\": \"0\",
          \"maxvalue\": \"50\",
          \"code\": \"#F2726F\"
        },
        {
          \"minvalue\": \"50\",
          \"maxvalue\": \"75\",
          \"code\": \"#FFC533\"
        },
        {
          \"minvalue\": \"75\",
          \"maxvalue\": \"100\",
          \"code\": \"#62B58F\"
       }
     ]
    },
    \"dials\": {
      \"dial\": [
        {
          \"value\": \"81\",
          \"id\": \"dial".$row['idMaq']."\"
        }
      ]
    }
  }";
      // chart object
  $ex="ex".$row['idMaq'];
  $contenedor="angulargauge".$row['idMaq'];
    
  ${"Chart".$row['idMaq']}=new FusionCharts("angulargauge", $ex, "230", "150", $contenedor, "json", ${"gaugeData".$row['idMaq']});

    // Render the chart
  ${"Chart".$row['idMaq']}->render(); ?>
      <script type="text/javascript">
  $(document).ready(function() {
    function <?php echo"changeNumber".$row['idMaq']; ?>() {
        $.ajax({
            type: "POST",
            url: "add.php",
            success: function(data) {
               FusionCharts("<?php echo $ex; ?>").setDataForId("<?php echo"dial".$row['idMaq']; ?>",data);
            }
        });
    }
    setInterval(<?php echo"changeNumber".$row['idMaq']; ?>, 2000);
});
</script>
<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="height: 160px;">
  <div id="<?php echo $contenedor; ?>" >Aquí se verá un Gráfico.</div>
</div>
<?php }
} ?>