<?php
include_once '../../Database/db.php';
print("<table  width='55%'' border='1' cellpadding='15' style='margin-left:200px'>");
print("<th>Responsable</th>");
print("<th>Acción</th>");
print("<th>Módulo</th>");
print("<th>Departamento</th>");
print("<th>Fecha de Registro</th>");

//Mientras que se tengan resultados se le asignan a $rows mediante 

$q=$_GET['q'];

$resultado = $MySQLiconn->query("SELECT * FROM $reporte where departamento='$q'");



while ($rows = $resultado->fetch_array()) {
//Realizamos una acción en donde la imagen de eliminar o modificar mandan a llamar su respectivo documento donde se realiza su proceso:
print("<tr>");


//Traemos la tabla y la imprimimos debidamente donde pertenece 

print("<td title='Usuario'>".$rows["nombre"]."</td>");
print("<td title='Acción'>".$rows["accion"]."</td>");
print("<td title='Módulo'>".$rows["modulo"]."</td>");
print("<td title='Departamento'>".$rows["departamento"]."</td>");
print("<td title='Fecha'>".$rows["registro"]."</td>");
print("</tr>");
}

//Contar registros:
$row_cnt=$resultado->num_rows;
printf("<p id='mostrar'>Se muestran: ".$row_cnt." Registros</p>");
$resultado->close();

?>
