<!DOCTYPE html>
<html>
<head>
	<title>Agregar perrito</title>
</head>
<body>
<h1>Agregar perrito</h1>
<a href="Main">Vista principal</a>
<form method="POST" action="add/load">
	<div>
	<label>Nombre del perrito</label>
	<input type="text" name="nombre" placeholder="Nombre completo">
	</div>
	<div>
	<label>Raza</label>
	<select name="raza">
		<option value="Snausher">Snausher
		</option>
		<option value="Snausher">Buldog
		</option>
		<option value="Snausher">Mastín tibetano
		</option></select>
	</div>
	<input type="submit" name="Agregar perrito">
</form>
</body>
</html>