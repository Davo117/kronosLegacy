
   <html>
<head>
</head>
<body>
  <?php


  header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=nominas-".$_GET['tabla'].".xls"); 

$serverName = "192.168.1.90,49172";
$connectionOptions = array(
    "database" => "ctGrupo_Ceyla_SA",
    "uid" => "sa",
    "pwd" => "MSQLServer2005"
);

// Establishes the connection
$SQLconn = sqlsrv_connect($serverName, $connectionOptions);
if ($SQLconn=== false) {
    die(formatErrors(sqlsrv_errors()));
}
$SPR_1=sqlsrv_query($SQLconn,"SELECT s.name, t.name as tipo, s.length
from syscolumns s left join systypes t on s.xtype = t.xtype
left join sysobjects o on s.id = o.id Where o.name = '".$_GET['tabla']."'
Order by s.name");

 


  ?>
  <table border="1">
    <tr>
  <?php
  while($row= sqlsrv_fetch_array($SPR_1, SQLSRV_FETCH_ASSOC))
  {?>
    <th style="background-color:#94C2F1; padding: 5px 10px;"><?php echo $row['name']?></th>
  <?php
  }
  
?>
  </tr>
  <?php
  $SQL=sqlsrv_query($SQLconn,"SELECT*FROM ".$_GET['tabla']."");
  while($raw=sqlsrv_fetch_array($SQL, SQLSRV_FETCH_ASSOC))
  {
    echo "<tr>";

  while($rew= sqlsrv_fetch_array($SPR_1, SQLSRV_FETCH_ASSOC))
    {
      ?>
<td><?php echo $raw[$row['name']]  ?></td>
<?php
    }

  }
  echo " <tr>";
  ?>
 </table>