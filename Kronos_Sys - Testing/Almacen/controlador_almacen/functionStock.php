<?php
function generateStockCodes($unidades,$producto,$nombreProducto,$folio,$cantidad,$mysqli)
{
	$codeList=array();
	for($j=0;$j<$unidades;$j++)
	{
		$codigoB='X'.$producto.$folio.str_pad($j,  2, "0", STR_PAD_LEFT);
		$porcion=$cantidad/$unidades;
		$mysqli->query("INSERT INTO obelisco.pzcodes(codigoB,consec,folio,producto,porcion)
			values('".$codigoB."','".$j."','".$folio."','".$producto."','".$porcion."')");
		$codeList[$j]['producto']=$producto;
		$codeList[$j]['consec']=$j;
		$codeList[$j]['codigo']=$codigoB;
		$codeList[$j]['nombre']=$nombreProducto;
	}
	return $codeList;
}
?>