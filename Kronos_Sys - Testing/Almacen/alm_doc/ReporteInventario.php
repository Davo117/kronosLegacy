<?php 
if(isset($_POST['cmbLapso']))
{
$fchProgramacion = $_POST['cmbLapso'];
}
else
{

$fchProgramacion = date('Y-m-d');
}
if(isset($_POST['cmbfchFinal']))
{
$fchFinal = $_POST['cmbfchFinal'];
}
else
{

$fchFinal = date('Y-m-d');
}



?>