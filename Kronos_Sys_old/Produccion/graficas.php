<?PHP
   header ("Content-type: image/png");
   
// Calcular ángulos
   $votos1 =200;
   $votos2 = 300;
   $totalVotos = $votos1 + $votos2;
 
   $porcentaje1 = round (($votos1/$totalVotos)*100,2);
   $angulo1 = 1.4 * $porcentaje1;
   $porcentaje2 = round (($votos2/$totalVotos)*100,2);
   $angulo2 = 1.4 * $porcentaje2;
 
// Crear imagen
   $imagen = imagecreate (200, 200);
   $colorfondo = imagecolorallocate ($imagen, 203, 203, 203); // CCCCCC
   $color1 = imagecolorallocate ($imagen, 255, 0, 0); // FF0000
   $color2 = imagecolorallocate ($imagen, 0, 255, 0); // 00FF00
   $colortexto = imagecolorallocate ($imagen, 0, 0, 0); // 000000
 
// Mostrar tarta
   imagefilledrectangle ($imagen, 0, 0, 100, 100, $colorfondo);
   imagefilledarc ($imagen, 50, 20, 100, 100, 0, $angulo1, $color1, IMG_ARC_PIE);
   imagefilledarc ($imagen, 50, 20, 100, 100, $angulo1, 360, $color2, IMG_ARC_PIE);
 
// Mostrar leyenda
   imagefilledrectangle ($imagen, 60, 250, 80, 260, $color1);
   $texto1 = "Histórico: " . $votos1 . " (" . $porcentaje1 . "%)";
   imagestring ($imagen, 3, 90, 250, $texto1, $colortexto);
   imagefilledrectangle ($imagen, 60, 270, 80, 280, $color2);
   $texto2 = "Scrap: " . $votos2 . " (" . $porcentaje2 . "%)";
   imagestring ($imagen, 3, 90, 270, $texto2, $colortexto);
 
   imagepng ($imagen);
   imagedestroy ($imagen);
?>