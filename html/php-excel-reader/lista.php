<?php

require_once 'excel_reader2.php';
$data = new Spreadsheet_Excel_Reader("lista.xls");

  echo '
  <table>'; 

  for ($i = 5; $i <= 22; $i++) {
	echo '
    <tr>';

    for ($j = 2; $j <= 7; $j++) {
		echo '
      <td>'.$data->sheets[0]['cells'][$i][$j].'</td>';
	}

    echo '
      <td>'.$data->boundsheets[0]['name'].'</td>
    </tr>'; 
}  

  echo '
  </table>'; 

?>
