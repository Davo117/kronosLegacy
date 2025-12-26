<?php
$serverName = "192.168.1.90,59275";
$connectionOptions = array(
    "database" => "adGRUPO_CEYLA_SA_DE",
    "uid" => "sa",
    "pwd" => "MSQLServer2005"
);

// Establishes the connection
$SQLconn = sqlsrv_connect($serverName, $connectionOptions);
if ($SQLconn=== false) {
    die(formatErrors(sqlsrv_errors()));
}

/*Select Query
$tsql = "SELECT @@Version AS SQL_VERSION";

// Executes the query
$stmt = sqlsrv_query($SQLconn, $tsql);

// Error handling
if ($stmt === false) {
    die(formatErrors(sqlsrv_errors()));
}
?>

<h1> Results : </h1>

<?php
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo $row['SQL_VERSION'] . PHP_EOL;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($SQLconn);
*/
function formatErrors($errors)
{
    // Display errors
    echo "<div class='alert alert-warning alert-dismissible fade in'>
            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
    echo "<strong>No se encuentra el servidor: </strong><br/>";
    foreach ($errors as $error) {
        echo "SQLSTATE: ". $error['SQLSTATE'] . "<br/>";
        echo "Code: ". $error['code'] . "<br/>";
        echo "Message: ". $error['message'] . "<br/>";
    }
    echo "</div>";
}

?>