<?php 
include("../Database/db.php");

if(isset($_POST['save'])){
    $SQLCanti=$MySQLiconn->query("SELECT dato FROM cache where id=30");
    $SQLProdu=$MySQLiconn->query("SELECT dato FROM cache where id=31");
    $SQLID=$MySQLiconn->query("SELECT dato FROM cache where id=32");
    
    $confiID=$SQLID->fetch_array();
    $canti=$SQLCanti->fetch_array();
    $produ=$SQLProdu ->fetch_array();
    
    $numEmbar = $MySQLiconn->real_escape_string($_POST['slctCdgEmbarque']);
    
    if($numEmbar=='--'){
        echo"<script>alert('Debes Seleccionar un Embarque Compatible')</script>";
    }
    else{
        $SQL = $MySQLiconn->query("UPDATE $embarq SET registrado=NOW(), cantidad='".$canti['dato']."', producto='".$produ['dato']."', idorden='".$confiID['dato']."' WHERE numEmbarque='".$numEmbar."'");  
        $MySQLiconn->query("UPDATE $confirProd SET enlaceEmbarque='$numEmbar' WHERE idConfi='".$confiID['dato']."'");
        //En caso de ser diferente la consulta:
        if(!$SQL){
            //Mandar el mensaje de error
            echo $MySQLiconn->error;
        }
        else{
            //Mandamos un mensaje de exito:
            echo"<script>alert('Exito!')</script>";
        }
    }
} ?>