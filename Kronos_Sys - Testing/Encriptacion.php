<?php
//Hello word xd
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="stylesheet" href="css/stilo.css">
	<title>Encriptación de texto</title>
	<?php
	if(isset($_POST['enviar']))
	{
		$texto=$_POST['body'];
		$destino= $_POST['to']; 
echo $texto;
echo $destino;
	if(mail($destino, "Prueba Kronos", $texto))
	{
		echo "Todo salió bien";
	}
	else
	{
		echo "Nada salió bien";
	}
}

	?>
</head>
<body>
	<center>
		<div class="decrip">
	<form method="POST">
<div >
<p>Nombre del archivo</p>
<input type="text" name="to" style="width:300px" required>
</div>
<div style="clear:left;">
<p>Texto</p>
<textarea name="body" id="body" required style="width:300px;height:150px;"></textarea>
</div>
<input type="submit" name="enviar" value="Enviar correo">
</form>
</div>
</center>
</body>
</html>