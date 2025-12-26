<?php
  include "sm_sistema/link.php";
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Visor</title>
    <style>
      * {
      	font-family: Arial;
      }
      #main {
      	background-color: #E1DEDE;      
        margin: 0 auto; }

      #login {
      	top: 10px;
        right: 40px;
        position: fixed;
        display: block;
      }

      .lblUser {
        text-align: right;
        display: block;
        right: 60px;
      	position: fixed;
      }

      #login img {
        display: block;
        right: 10px;
      	position: fixed;
      }

      #artUser {
      	display: inherit;
      }

      #loader {
        border-radius: 5px;
      }
      
    </style>
  </head>
  <body id="main">
    <div id="container">
      <div id="login">
<?php
  include "sm_sistema/login.php";
?>
      <div>
      <div id="loader">
<?php
  
?>
      </div>
    </div>
  </body>
</html>