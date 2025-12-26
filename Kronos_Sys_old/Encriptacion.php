<?php
//Hello word xd
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="stylesheet" href="css/stilo.css">
	<title>Encriptación de texto</title>
	<?php
	if(isset($_POST['encriptar']))
	{
		$datos=$_POST['texto'];
		$nombre_archivo = $_POST['name'].".txt"; 

	if(file_exists($nombre_archivo))
	{
		$mensaje =base64_encode($datos);
	}

	else
	{
		$mensaje = base64_encode($datos);
	}

	if($archivo = fopen($nombre_archivo, "a"))
	{
		if(fwrite($archivo,$mensaje))
		{
			echo "Se ha ejecutado correctamente";
		}
		else
		{
			echo "Ha habido un problema al crear el archivo";
		}

		fclose($archivo);
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
<input type="text" name="name" style="width:300px" required>
</div>
<div style="clear:left;">
<p>Texto</p>
<textarea name="texto" id="texto" required style="width:300px;height:150px;"></textarea>
</div>
<input type="submit" name="encriptar" value="Guardar">
</form>
</div>
</center>
</body>
</html>