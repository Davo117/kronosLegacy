<?php
    include("../FusionCharts/integrations/php/samples/includes/fusioncharts.php");
    include("../Database/db.php");
    include("../components/barraGrafico.php");?>
  <div class="panel panel-default col-xs-10">
  <h4>Reporting Pannel</h4>
<div class="panel panel-default col-md-6 col-xs-12 order-3">
  <h4>Gráfica de Barras de Maquinas</h4>

<?php //Realizamos la consulta de todas las maquinas y las ordenamos por subprocesos
$SQL1=$MySQLiconn->query("SELECT subproceso, count(subproceso) as holi FROM maquinas where baja='1' group by subproceso");
            //Asignamos a la grafrica los parametros basicos.
        $chartData ="{
            \"chart\":{  
                \"caption\": \"Gráfica de Producción\",
                \"xaxisname\": \"Máquinas\",
                \"yaxisname\": \"Total Producido\",
                \"exportEnabled\":\"1\",
                \"theme\": \"carbon\"
            },\"data\": [";
        // usaremos un contador solo para validar si hay mas de una maquina
$i=0;
 $count=$SQL1->num_rows;
while ($row1=$SQL1->fetch_array()){
        //le pasamos los datos : Nombre de la barra, su valor y el link servirá para actualizar otras barras
            $chartData.="{\"label\": \"".$row1["subproceso"]."\",
                    \"value\": \"".$row1["holi"]."\",
                    \"link\": \"j-updateChart-".$row1["subproceso"]."\"
                }";
    //Entre mas maquinas significa que hay mas datos y para separar las barras usamos la ","
    if($i<$count){
        $chartData.=",";
    }
    $i++;
}
    $chartData.="]
}";
//fin de la barra principal
// chart object
      $ChartBARRA = new FusionCharts("column2d", "epe", '100%', '500%', "chart-B", "json", $chartData);

$ChartBARRA->render(); ?>

<div id="chart-B">Chart will render here!</div>

</div>

<div class="panel panel-default col-md-6 col-xs-12 order-2">
  <h4>Gráfica Circular de Merma</h4>
    <?php
                $chartData = "{
                    \"chart\": {
                      \"caption\": \"Gráfica de Merma\",
                      \"subCaption\" : \"Total de Merma por Producto\",
                      \"showValues\":\"1\",
                      \"showPercentInTooltip\" : \"0\",
                      \"exportEnabled\":\"1\",
                      \"enableMultiSlicing\":\"1\",
                      \"theme\": \"gammel\"
                    },
                    \"data\": [{
                      \"label\": \"GEPP\",
                      \"value\": \"300000\"
                    }, {
                      \"label\": \"Comex\",
                      \"value\": \"230000\"
                    }, {
                      \"label\": \"Cristal\",
                      \"value\": \"180000\"
                    }, {
                      \"label\": \"OSTOK\",
                      \"value\": \"270000\"
                    }, {
                      \"label\": \"OTROS\",
                      \"value\": \"20000\"
                    }]
                  }";
      // chart object
      $ChartC = new FusionCharts("pie3d", "chart-1" , "100%", "500%", "chart-container", "json", $chartData);
      // Render the chart
      $ChartC->render(); ?>
        <div id="chart-container">Chart will render here!</div>
</div>

<br>
<br>

<div class="panel panel-default col-xs-12">

<div id="maquinaG"><h4 >Gráficas de Máquinas</h4></div>
</div>

<br>
<br>

<div class="panel panel-default col-xs-12">
  <h4>Entregas Pendientes</h4>
  <?php  
  $SQL=$MySQLiconn->query("SELECT c.nombrecli, sum(co.cantidadConfi)as total, sum(co.surtido) surtido from tablacliente c inner join tablasuc s on c.ID=s.idcliFKS inner join confirmarprod co on s.idsuc=co.ordenConfi where co.surtido<co.cantidadConfi GROUP BY c.nombrecli");
//inner join embarque e  on co.ordenConfi=e.idorden  where e.cerrar=0 
while ($row=$SQL->fetch_array()) { ?>
  <div class="col-xs-6 col-md-4">
  <h4><?php echo $row['nombrecli']; ?></h4>
  <h5>Progreso: <?php echo number_format($row['surtido']).'/'.number_format($row['total']) ?>
  <div class="progress">
  <?php $set=($row['surtido']/$row['total'])*100;
  if($set<=25){ $clase="danger";  }
  else if($set>25 && $set<=60){ $clase="warning"; }
  else if($set>60 && $set<=99){ $clase="info";  }
  else if($set>100){  $clase="success"; }?>
    <div class="progress-bar progress-bar-<?php echo $clase?>" role="progressbar" aria-valuenow="<?php echo number_format($set)?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format($set);?>px">
                    <span class="sr-only"><?php echo number_format($set);?>%</span>
                  </div>
                </div>
      </div> <?php
} ?>
</div>

</div>
<br> <br>
<div class="panel panel-default col-md-2  hidden-sm hidden-xs order-1" style=" background-color:rgba(247,120,0,.1)">
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
  <h4>Historico</h4>
</div>
</body>

<script type="text/javascript">
function updateChart(str){
$('#maquinaG').load('maqGauge.php?proceso='+str);
}
</script>
</html>