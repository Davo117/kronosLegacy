<?php
  if ($_SESSION[cdgusuario]!='')
  { $link_mysqli = conectar();
    $querySelect = $link_mysqli->query("
      SELECT * FROM rechempleado
      WHERE cdgempleado = '".$_SESSION[cdgusuario]."'");

    if ($querySelect->num_rows > 0)
    { $regQuery = $querySelect->fetch_object();

      $logInNombre = $regQuery->nombre;
      $logInApellido = $regQuery->apellido; }

    echo '
    <label class="lblUser"><b>'.$logInNombre.'</b><br>'.$logInApellido.'</label>
    <img src="images/user.png" height="40px"/>'; }
  else
  { echo '
    <article id="artUser">
      <label><b>User</b></label>
      <input id="txtUser" type="text" />
    </article>
    <article id="artPass">
      <label><b>Password</b></label>
      <input id="passWord" type="password" />
    </article>'; }
?>