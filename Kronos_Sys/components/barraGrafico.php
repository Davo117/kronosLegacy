<?php
ob_start();
 include("../Database/db.php")?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../pictures/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicon-16x16.png">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script type="text/javascript" src="../bootstrap/Popper.js"></script>
    <script src="../bootstrap/bootstrap-confirmation.js"></script>
    

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style type="text/css">  
 @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
@media(min-width:768px) {
    body {
        margin-top: 40px;
    }
}    </style>
    <link rel="stylesheet" href="../css/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">
    
<!-- Latest compiled and minified JavaScript -->
<script src="../css/bootstrap-select-1.12.4/js/bootstrap-select.js"></script>
<!-- Librería de FusionCharts -->
<script type="text/javascript" src="../FusionCharts/js/fusioncharts.js"></script>

<script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.gammel.js"></script>

            <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fint.js"></script>
            <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.zune.js"></script>
            <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.carbon.js"></script>
            <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.ocean.js"></script>
<script type="text/javascript" src="../FusionCharts/js/themes/fusioncharts.theme.candy.js"></script>
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top bg-secondary" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="../Menu.php">
                <img src="../pictures/logo-labro.png" alt="Grupo Labro" width="160" height="50">
            </a>
        </div>
    </nav>
</div>
<?php ob_end_flush(); ?>