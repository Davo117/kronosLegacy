<?php
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
$SPR_1=sqlsrv_query($SQLconn,"SELECT CAST(table_name as varchar) as tabla  FROM INFORMATION_SCHEMA.TABLES");
while($row= sqlsrv_fetch_array($SPR_1, SQLSRV_FETCH_ASSOC))
   {
   	?>
   	<p style="float:left;border-style: solid;border-radius:5px;border-color:gray;padding:5px;margin:5px"><a href="contados.php?tabla=<?php echo $row['tabla']?>"><?php echo $row['tabla']?></a></p>
   	<?php
   }
   ?>