<?php include '../datos/mysql.php'; 
?><!DOCTYPE html>
<html>
  <head>    
    <style>
      body {
        background: #E6E6E6;
        color: #262626;
        font-family: Arial, san-serif; 
        font-size: 10px; }

      a { text-decoration: none; }

      #calendario {
        background-color: rgba(123,123,123,0.1); 
        border-radius: 10px;
        -moz-border-radius: 10px;  
        -webkit-border-radius: 10px; 
        margin: 25px auto;  
        padding: 10px;        
        width: 94%; }

      #month {
        font-size: 280%;
        text-align: center; }

      .headcal {
        background-color: rgba(0,0,255,0.1); 
        border-radius: 6px;
        -moz-border-radius: 6px;  
        -webkit-border-radius: 6px;     
        font-style: bold; 
        font-size: 140%;
        padding: 5px;
        text-align: center; }

      .foulcal { 
        background-color: rgba(0,0,0,0);
        border-radius: 6px;
        -moz-border-radius: 6px;  
        -webkit-border-radius: 6px;     
        font-size: 120%;
        padding: 5px;
        text-align: center; }

      .daycal { 
        background-color: rgba(100,100,0,0.2); 
        border-radius: 6px;    
        -moz-border-radius: 6px;  
        -webkit-border-radius: 6px;        
        height: 60px;
        min-width: 100px;        
        padding: 5px;
        text-align: right;
        vertical-align: text-top;

        -moz-transition: all 1s;
        -webkit-transition: all 1s;
        -o-transition: all 1s; }

      .daycal:hover {
        background: white;
        cursor: pointer; 

        -moz-transition: all .1s;
        -webkit-transition: all .1s;
        -o-transition: all .1s;}    

    </style>
  </head>
  <body>
    <div id="contenedor"><?php

  if ($_GET[fecha])
  { $datescal = $_GET[fecha]; 
  } else
  { $datescal = date("Y-m-d"); }

  $datecal = explode("-", $datescal);

  $yearcal = $datecal[0];
  $monthcal = str_pad($datecal[1],2,'0',STR_PAD_LEFT);

  if ($monthcal == date("n"))
  { $daycal = date("j"); 
  } else
  { $daycal = ""; }

  // --> Nombre de los dias
  $dayname[1] = "Domingo";
  $dayname[2] = "Lunes";
  $dayname[3] = "Martes";
  $dayname[4] = "Miercoles";
  $dayname[5] = "Jueves";
  $dayname[6] = "Viernes";
  $dayname[7] = "Sabado";
  // <-- Nombre de los dias

  // --> Nombre de los meses
  $monthname[1] = "Enero";
  $monthname[2] = "Febrero";
  $monthname[3] = "Marzo";
  $monthname[4] = "Abril";
  $monthname[5] = "Mayo";
  $monthname[6] = "Junio";
  $monthname[7] = "Julio";
  $monthname[8] = "Agosto";
  $monthname[9] = "Septiembre";
  $monthname[10] = "Octubre";
  $monthname[11] = "Noviembre";
  $monthname[12] = "Diciembre";
  // <-- Nombre de los meses  

  $dateselect = $monthname[abs($monthcal)];

  if ($monthcal == '12')
  { $nextmonth = '1'; 
    $nextyear = $yearcal+1; 
  } else
  { $nextmonth = $monthcal+1; 
    $nextyear = $yearcal; }

  if ($monthcal == '01')
  { $previousmonth = '12'; 
    $previousyear = $yearcal-1; 
  } else
  { $previousmonth = $monthcal-1; 
    $previousyear = $yearcal; }

  $nextCal = $nextyear."-".$nextmonth;
  $previousCal = $previousyear."-".$previousmonth;

  $daysCal = count($dayname)+1;
  $locateCal = mktime(0,0,0,$monthcal,1,$yearcal);
  $beginCal = date("w", $locateCal);

  echo '
      <table id="calendario">  
        <tr>
          <td class="foulcal"><a href="prodCalendar.php?fecha='.$previousCal.'" />'.$monthname[$previousmonth].'<br><b>'.$previousyear.'</b></a></td>
          <td id="month" colspan="5">'.$dateselect.' '.$yearcal.'</td>
          <td class="foulcal"><a href="prodCalendar.php?fecha='.$nextCal.'" />'.$monthname[$nextmonth].'<br><b>'.$nextyear.'</b></a></td></tr>
        <tr>'; 
      
  for ($day = 1; $day <= 7; $day++)
  { echo '
          <td class="headcal">'.$dayname[$day].'</td>'; }

  echo '</tr>';

  for ($week = 1; !$lastWeek; $week++)
  { echo '
        <tr>';

    if ($week == 1) { $locate = $week; } 

    for ($day = 1; $day <= 7; $day++)
    { $locate = str_pad($locate, 2, '0', STR_PAD_LEFT);

      if (date("t", $locateCal) == $locate) { $lastWeek = 1; }
        
      if (($day > $beginCal and $week == 1) or (checkdate($monthcal, $locate, $yearcal) and $week != 1))
      { echo '
          <td class="daycal">'.$locate.'</td>';

        $locate++;
      } else
      { echo '
          <td></td>'; }
    } 
    
    echo '</tr>';
  }

  echo '</table>';
?>

    </div>
  </body>
</html>