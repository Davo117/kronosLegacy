<?php
include_once '../../Database/db.php';
echo "<table  border='0' cellpadding='15' class='table table-hover'>	<th style='width:100px;'>Responsable</th>	<th>Acción</th>	<th style='width:250px;'>Localización</th>	<th style='width:140px;'>Fecha de Registro</th>";
//Mientras que se tengan resultados se le asignan a $rows mediante 
$q=$_GET['q'];
$resultado = $MySQLiconn->query("SELECT * FROM $reporte where departamento='$q' ORDER BY ID DESC  ");
while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
echo "<tr>";
//Traemos la tabla y la imprimimos debidamente donde pertenece 
echo "<td title='Usuario'>".$rows["nombre"]."</td>
<td style=' text-align:left;'title='Acción'>".$rows["accion"]."</td>
<td style=' text-align:left;'>Módulo: ".$rows["modulo"]."<br>
Departamento: ".$rows["departamento"]."</td>
<td title='Fecha'>".$rows["registro"]."</td>
</tr>";
}

//Contar registros:
$row_cnt=$resultado->num_rows;
echo "<h4 class='ordenMedio'>$q</h4>
<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>";
$resultado->close();
?>