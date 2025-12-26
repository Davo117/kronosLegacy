<?php 
include("../Database/db.php");

if(isset($_POST['save'])){
    /*$SQLCanti=$MySQLiconn->query("SELECT dato FROM cache where id=30");
    $SQLProdu=$MySQLiconn->query("SELECT dato FROM cache where id=31");
    $SQLID=$MySQLiconn->query("SELECT dato FROM cache where id=32");
    
    $confiID=$SQLID->fetch_array();
    $canti=$SQLCanti->fetch_array();
    $produ=$SQLProdu ->fetch_array();
    */
    $confiID=$MySQLiconn->real_escape_string($_POST['dato1']); 
    $canti=$MySQLiconn->real_escape_string($_POST['dato2']); 
    $produ=$MySQLiconn->real_escape_string($_POST['dato3']); 

    $numEmbar = $MySQLiconn->real_escape_string($_POST['slctCdgEmbarque']);
    echo"<script>alert('".$confiID."')</script>";
    if($numEmbar=='--'){
        echo"<script>alert('Debes Seleccionar un Embarque Compatible')</script>";
    }
    else{
            $MySQLiconn->query("UPDATE $embarq SET registrado=NOW(), cantidad='".$canti."', producto='".$produ."', idorden='".$confiID."' WHERE idEmbarque='".$numEmbar."'");
            $SQL=$MySQLiconn->query("UPDATE $confirProd SET enlaceEmbarque='$numEmbar' WHERE idConfi='".$confiID."'");
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