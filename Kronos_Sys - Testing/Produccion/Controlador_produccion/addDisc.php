<?php
echo "
<form><p style='font:bold 14px Sansation'>Ingresa el disco(s) para completar la longitud de la bobina</p>
	<input type='text'></input>
	<input type='submit></form>";
	?>
	select `pr`.`id` AS `id`,`pr`.`total` AS `total`,if((`pr`.`tipo` = 1),(select `saturno`.`bandaspp`.`nombreBSPP` from `saturno`.`bandaspp` where (`saturno`.`bandaspp`.`IdBSPP` = `pr`.`producto`)),(select `saturno`.`impresiones`.`descripcionImpresion` from `saturno`.`impresiones` where (`saturno`.`impresiones`.`id` = `pr`.`producto`))) AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera` from (((`saturno`.`tbprosliteo` `pr` join `saturno`.`empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `saturno`.`maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `saturno`.`tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`)))