<?php
include("../Produccion/Controlador_produccion/functions.php");
if(isset($_POST['lista']))
{
  $niveles=0;
  $SQL="";
  if($_POST['lista']==1 and !empty($_POST['codigo']))
  {
    $arraySQL=array();
    $arrac=parsearCodigo($_POST['codigo']);
    $arrac=explode('|',$arrac);
    $noop=$arrac[4];
    $lote=$arrac[2];
    $tipo=$arrac[6];
    $procesCdg=$arrac[3];
    $producto=$arrac[0];
    $SQL=$MySQLiconn->query("SELECT id,noop,codigo,proceso,noProceso,divisiones,(SELECT count(DISTINCT noProceso) FROM codigosbarras where lote='".$lote."') as niveles,(SELECT MAX(divisiones) FROM codigosbarras where lote='".$lote."') as maxDiv FROM codigosbarras where lote='".$lote."'");
    $auxc=0;
  //echo "SELECT id,noop,codigo,proceso,noProceso,divisiones,(SELECT count(DISTINCT noProceso) FROM codigosbarras where lote='".$lote."') as niveles,(SELECT MAX(divisiones) FROM codigosbarras where lote='".$lote."') as maxDiv FROM codigosbarras where lote='".$lote."'";
    while($rex=$SQL->fetch_object())
    {
      $arraySQL[$auxc]['id']=$rex->id;
      $arraySQL[$auxc]['noop']=$rex->noop;
      $arraySQL[$auxc]['codigo']=$rex->codigo;
      $arraySQL[$auxc]['proceso']=$rex->proceso;
      $arraySQL[$auxc]['noProceso']=$rex->noProceso;
      $arraySQL[$auxc]['divisiones']=$rex->divisiones;
      $arraySQL[$auxc]['niveles']=$rex->niveles;
      $arraySQL[$auxc]['maxDiv']=$rex->niveles;
      $auxc++;
    }
    $maxDiv=1;
    $contenedor=array();
    for($i=0;$i<count($arraySQL);$i++)
    {
      if($arraySQL[$i]['maxDiv']==$arraySQL[$i]['divisiones'])
      {
        $maxDiv++;
      }
      $bandera=$arraySQL[$i]['noProceso'];
      if(is_bandera_in_array($bandera,$contenedor)==false)
      {
        for($j=0;$j<count($arraySQL);$j++)
        {
          if($arraySQL[$j]['noProceso']==$bandera)
          {
            if(is_bandera_in_array($bandera,$contenedor))
            {
          //echo $bandera;
              $contenedor[$bandera]['repeticiones']=$contenedor[$bandera]['repeticiones']+1;
          //echo $contenedor[$bandera]['repeticiones'];
            }
            else
            {
         // echo $bandera;
              array_push($contenedor, array(
                "id"=>1,"proceso"=>$bandera,"repeticiones"=>1));
         // echo $contenedor[$bandera]['proceso'];
            }
          }
        }
      }

    }

  
  $confChart= array(
    "chart" =>array(
      "caption" =>"Procesos",
      "subCaption"=> "NoOp: ".$noop." [Lote: ".$lote."] -".$producto,
      "theme"=> "fusion",
      "viewmode"=> "1",
      "showrestorebtn"=> "1",
      "valuefontcolor"=> "#000",
      "yaxismaxvalue"=> "900",
      "yaxisminvalue"=> "0",
      "divlinealpha"=> "0",
      "palette"=>"0",
      "baseFontSize"=>"15"
    )
  );

  /*
  Se generan los conectores
  */
  $dataConnectors= array();


  //From, To, Arrowatstart, arrowatend,color
  $counter=0;
  $arrFuertes=array();
  for($i=0;$i<count($arraySQL);$i++)
  {
    for($j=0;$j<count($arraySQL);$j++)
    {
      if(($arraySQL[$i]['noProceso']+1)==$arraySQL[$j]['noProceso'])
      {
        $dataConnectors[$counter][0]=$arraySQL[$i]['noProceso'].$arraySQL[$i]['noop'];
        $dataConnectors[$counter][1]=$arraySQL[$j]['noProceso'].$arraySQL[$j]['noop'];
        $dataConnectors[$counter][2]="0";
        $dataConnectors[$counter][3]="1";
        $dataConnectors[$counter][4]="#000";
        array_push($arrFuertes, $arraySQL[$i]['id']);
        $counter++;
      }

    }
  }


  /////////////////////////////

  $dataChart= array();
  $divisiones=1;
  $counter=0;


  for($i=0;$i<count($arraySQL);$i++)
  {
  //echo $row->noProceso.$row->noop;

    $dataChart[$counter][0]=$arraySQL[$i]['noProceso'].$arraySQL[$i]['noop'];
    $dataChart[$counter][1]=$arraySQL[$i]['noop']."\n".$arraySQL[$i]['proceso'];
    $dataChart[$counter][2]=(($contenedor[$arraySQL[$i]['noProceso']]['repeticiones']/pow(2,$contenedor[$arraySQL[$i]['noProceso']]['repeticiones']))*1000)*$contenedor[$arraySQL[$i]['noProceso']]['id'];
    $dataChart[$counter][3]=1000-((1000/$arraySQL[$i]['niveles'])*$arraySQL[$i]['noProceso']);
    $dataChart[$counter][4]="polygon";
    if($arraySQL[$i]['noop']==$noop && $arraySQL[$i]['noProceso']==$procesCdg)
    {
      $dataChart[$counter][5]="#FFC300";
    }
    else
    {
      $dataChart[$counter][5]="#A8E5EA";
    }
    $dataChart[$counter][6]="0";
    $dataChart[$counter][7]="0";
    $dataChart[$counter][8]=$arraySQL[$i]['noProceso']+4;
    $dataChart[$counter][9]="48";
    $dataChart[$counter][10]="../Calidad/Buscador_Calidad.php?tipo=".$tipo."&codigo=".$noop."&lista=4";
    $dataChart[$counter][11]="No. Proceso:".$arraySQL[$i]['noProceso']." |Código:".$arraySQL[$i]['codigo'];
    if(in_array($arraySQL[$i]['id'],$arrFuertes)==false)
    {
    $dataChart[$counter][12]="1";
    $dataChart[$counter][13]="BOTTOM";
    $dataChart[$counter][14]="30";
    $dataChart[$counter][15]="30";
    $dataChart[$counter][16]="../pictures/deletProducto.png";
    }
    else
    {
    $dataChart[$counter][12]="";
    $dataChart[$counter][13]="";
    $dataChart[$counter][14]="";
    $dataChart[$counter][15]="";
    $dataChart[$counter][16]="";
    }
   
    $divisiones=$arraySQL[$i]['divisiones'];
    $niveles=$arraySQL[$i]['niveles'];
    $counter++;
    $contenedor[$arraySQL[$i]['noProceso']]['id']=$contenedor[$arraySQL[$i]['noProceso']]['id']+1;
  }
  

  //ID, Label, x, y, shape, hoverColor,width, height, numSides, radius, enlace, placeholder, tiene imagen?, posición de imagen,alto imagen, ancho imagen, link imagen.
  /*["01","Home","50","900","polygon","#900C3F","0","0","5","40","www.youtube.com","Soy un tuul text we","1","BOTTOM","30","30","../pictures/deletProducto.png"],
  ["02","Shous","20","500","rectangle","#900C3F","80","40","0","0","www.youtube.com","Soy un tuul text we","0","","","",""],
  ["02.1","Juanito","2","100","rectangle","#900C3F","80","40","0","0","www.youtube.com","Soy un tuul text we","0","","","",""],
  ["03","Mubis","100","500","rectangle","#900C3F","80","40","0","0","www.youtube.com","Soy un tuul text we","0","","","",""]);*/

  

 /* ["01","02","0","1","#555"],
  ["02","02.1","0","1",""],
  ["01","03","0","1","#555"]);*/

//Creo el arreglo para las propiedades de los data
//$labelStyle=array();
  $labelValueData=array();
  for($i=0;$i<count($dataChart);$i++)
  {

    array_push($labelValueData,array(
      "id"=>$dataChart[$i][0],"label"=>$dataChart[$i][1],"x"=>$dataChart[$i][2],"y"=>$dataChart[$i][3],
      "shape"=>$dataChart[$i][4],"color"=>$dataChart[$i][5],"width"=>$dataChart[$i][6],
      "height"=>$dataChart[$i][7],"numSides"=>$dataChart[$i][8],"radius"=>$dataChart[$i][9],
      "link"=>$dataChart[$i][10],"toolText"=>$dataChart[$i][11],"imageNode"=>$dataChart[$i][12],
      "imagealign"=>$dataChart[$i][13],"imageheight"=>$dataChart[$i][14],"imagewidth"=>$dataChart[$i][15],
      "imageurl"=>$dataChart[$i][16]));
  }

//Arreglo para los conectores
  $connectorsChart=array();
  for($i=0;$i<count($dataConnectors);$i++)
  {
    array_push($connectorsChart, array(
      "from"=>$dataConnectors[$i][0],"to"=>$dataConnectors[$i][1],"arrowatstart"=>$dataConnectors[$i][2],
      "arrowatend"=>$dataConnectors[$i][3],"color"=>$dataConnectors[$i][4]));
  }

//Creo el arreglo conectores para introducir la data de estos
  $fatherConnector=array();
  array_push($fatherConnector, array(
    "stdthickness"=>"2","connector"=>$connectorsChart));
//Creo el arreglo para las propiedades del dataset
  $datasetChart=array();
  array_push($datasetChart, array(
    "allowDrag"=>0,"fontsize"=>"15","data"=>$labelValueData));

  $confChart["connectors"]=$fatherConnector;
  $confChart["dataset"]=$datasetChart;
  $jsonEncodeData=json_encode($confChart);

//Instanciar el gráfico
  $columnChart = new FusionCharts('dragnode', 'ex1', '100%', ($niveles+2)*100, 'chart-1', 'json',$jsonEncodeData);
//¨Pintar el gráfico
  $columnChart->render();
}
}

?>