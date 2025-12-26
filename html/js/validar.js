
function permitir(elEvento, filtro) 
{	// 	Variables que definen los caracteres permitidos
	var numeros = "0123456789.";
	var numerosint = "0123456789";
	var caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var numeros_caracteres = numeros + caracteres + "/ ,ñÑ";
	var identificadores = numeros + caracteres + "-";	
	var teclas_especiales = [8, 9, 13];
	/*  8 = Retroceso 		
	 *  9 = Tabulador 
	 * 13 = <ENTER> */
		
	// 	Seleccionar los caracteres a partir del parámetro de la función	
	switch(filtro) 
	{	case 'num':
		filtro = numeros;
		break;
		
		case 'numint':
		filtro = numerosint;
		break;
		
		case 'car':
		filtro = caracteres;
		break;

		case 'ids':
		filtro = identificadores;
		break;
				
		case 'num_car':
		filtro = numeros_caracteres;
		break; 
	}
		
	// 	Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);

	/* Comprobar si la tecla pulsada es alguna de las teclas especiales
	 * (teclas de borrado y flechas horizontales) */
	var tecla_especial = false;
	for(var i in teclas_especiales) 
	{	if(codigoCaracter == teclas_especiales[i]) 
		{	tecla_especial = true;
			break; }
	}
	
	/* Comprobar si la tecla pulsada 
	 * se encuentra en los caracteres permitidos
	 * o si es una tecla especial */
	return filtro.indexOf(caracter) != -1 || tecla_especial;
}

function limite(maximo) 
{	var capturado = document.getElementById("txa_observ");

	if(capturado.value.length >= maximo ) 
	{	return false; }
	else 
	{	return true; }
}
	
	
	/*
	function validarCliente() 
	{	var idcliente = document.getElementById("text_idcliente").value;
		var cliente = document.getElementById("text_cliente").value;
		var cdgpais = document.getElementById("select_pais").selectedIndex;
		var cdgestado = document.getElementById("select_estado").selectedIndex;
		var cdgciudad = document.getElementById("select_ciudad").selectedIndex;
		
		document.getElementById("button_addcliente").disabled = true;
		document.getElementById("button_addcliente").value = "Salvando...";
	
		if (idcliente == null || idcliente.length == 0 || /^\s+$/.test(idcliente)) 
		{	alert('El campo -IdCliente- no puede estar vacío.');
			document.getElementById("text_idcliente").focus();
			document.getElementById("button_addcliente").disabled = false;
			document.getElementById("button_addcliente").value = "Salvar";
			
			return false; 
		}
		else
		{ 	if (cliente == null || cliente.length == 0 || /^\s+$/.test(cliente)) 
			{	alert('El campo -Nombre del Cliente- no puede estar vacío.');
				document.getElementById("text_cliente").focus();
				document.getElementById("button_addcliente").disabled = false;
				document.getElementById("button_addcliente").value = "Salvar";
				
				return false; 
			}
			else
			{	if (cdgciudad == null || cdgciudad == 0) 
				{	alert('El campos -Ciudad- en necesario para los reportes de ventas.');
					if (cdgpais == null || cdgpais == 0)
					{ 	document.getElementById("select_pais").focus(); }
					else
					{ 	if (cdgestado == null || cdgestado == 0)
						{ 	document.getElementById("select_estado").focus(); }
						else
						{ 	document.getElementById("select_ciudad").focus(); }
					}
					document.getElementById("button_addcliente").disabled = false;
					document.getElementById("button_addcliente").value = "Salvar";
				
					return false;
				}
				else
				{	document.getElementById("button_addcliente").disabled = false;
					document.getElementById("button_addcliente").value = "Salvar";
					
					return true; }
			}
		} 
	}
	
	function ValidarEmail()	
	{	correoe = document.getElementById("text_correoe").value;
		if (!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)/.test(valor))) 
		{ 	return false; }
	}

function validarNewMarca() 
{	marca = document.getElementById("idsreg_txt").value;
	if( marca == null || marca.length == 0 || /^\s+$/.test(marca) ) 
	{	alert('El identificador de Marca no puede estar vacío.');
		document.getElementById("idsreg_txt").focus();
		
		return false; 
	}
}

function validarNewColeccion() 
{	marca = document.getElementById("cdgmrc_cmb").selectedIndex;
	if( marca == null || marca == 0 ) 
	{	alert('Es necesario indicar una Marca valida.');
		document.getElementById("cdgmrc_cmb").focus();
		
		return false;
	}
	else
	{	coleccion = document.getElementById("idsreg_txt").value;
		if( coleccion == null || coleccion.length == 0 || /^\s+$/.test(coleccion) ) 
		{	alert('El identificador de Colección no puede estar vacío.');
			document.getElementById("idsreg_txt").focus();
			
			return false;
		}
	}
}

function validarNewElmColeccion() 
{	marca = document.getElementById("cdgmrc_cmb").selectedIndex;
	if( marca == null || marca == 0 ) 
	{	alert('Es necesario indicar una Marca valida.');
		document.getElementById("cdgmrc_cmb").focus();
		
		return false;
	}
	else
	{	coleccion = document.getElementById("cdgclc_cmb").selectedIndex;
		if( coleccion == null || coleccion == 0 ) 
		{	alert('Es necesario indicar una Colección valida.');
			document.getElementById("cdgclc_cmb").focus();
			
			return false;
		}
		else
		{	modelo = document.getElementById("idsreg_txt").value;
			if( modelo == null || modelo.length == 0 || /^\s+$/.test(modelo) ) 
			{	alert('El identificador de Elemento no puede estar vacío.');
				document.getElementById("idsreg_txt").focus();
				
				return false;
			}
		}
	}
}

function validarNewModelo() 
{	marca = document.getElementById("cdgmrc_cmb").selectedIndex;
	if( marca == null || marca == 0 ) 
	{	alert('Es necesario indicar una Marca valida.');
		document.getElementById("cdgmrc_cmb").focus();
		
		return false;
	}
	else
	{	coleccion = document.getElementById("cdgclc_cmb").selectedIndex;
		if( coleccion == null || coleccion == 0 ) 
		{	alert('Es necesario indicar una Colección valida.');
			document.getElementById("cdgclc_cmb").focus();
			
			return false;
		}
		else
		{	modelo = document.getElementById("idsreg_txt").value;
			if( modelo == null || modelo.length == 0 || /^\s+$/.test(modelo) ) 
			{	alert('El identificador de Modelo no puede estar vacío.');
				document.getElementById("idsreg_txt").focus();
				
				return false;
			}
		}
	}
}

function validarNewElmModelo() 
{	marca = document.getElementById("cdgmrc_cmb").selectedIndex;
	if( marca == null || marca == 0 ) 
	{	alert('Es necesario indicar una Marca valida.');
		document.getElementById("cdgmrc_cmb").focus();
		
		return false;
	}
	else
	{	coleccion = document.getElementById("cdgclc_cmb").selectedIndex;
		if( coleccion == null || coleccion == 0 ) 
		{	alert('Es necesario indicar una Colección valida.');
			document.getElementById("cdgclc_cmb").focus();
			
			return false;
		}
		else
		{	modelo = document.getElementById("cdgmdl_cmb").selectedIndex;
			if( modelo == null || modelo == 0 ) 
			{	alert('Es necesario indicar un Modelo valido.');
				document.getElementById("cdgmdl_cmb").focus();
				
				return false;
			}
			else
			{	articulo = document.getElementById("idsreg_txt").value;
				if( articulo == null || articulo.length == 0 || /^\s+$/.test(articulo) ) 
				{	alert('El identificador de Elemento no puede estar vacío.');
					document.getElementById("idsreg_txt").focus();
					
					return false;
				}
			}
		}
	}
}

function validarNewArticulo() 
{	marca = document.getElementById("cdgmrc_cmb").selectedIndex;
	if( marca == null || marca == 0 ) 
	{	alert('Es necesario indicar una Marca valida.');
		document.getElementById("cdgmrc_cmb").focus();
		
		return false;
	}
	else
	{	coleccion = document.getElementById("cdgclc_cmb").selectedIndex;
		if( coleccion == null || coleccion == 0 ) 
		{	alert('Es necesario indicar una Colección valida.');
			document.getElementById("cdgclc_cmb").focus();
			
			return false;
		}
		else
		{	modelo = document.getElementById("cdgmdl_cmb").selectedIndex;
			if( modelo == null || modelo == 0 ) 
			{	alert('Es necesario indicar un Modelo valido.');
				document.getElementById("cdgmdl_cmb").focus();
				
				return false;
			}
			else
			{	articulo = document.getElementById("idsreg_txt").value;
				if( articulo == null || articulo.length == 0 || /^\s+$/.test(articulo) ) 
				{	alert('El identificador de Artículo no puede estar vacío.');
					document.getElementById("idsreg_txt").focus();
					
					return false;
				}
			}
		}
	}
}

function validarCaptura11()
{	empleado = document.getElementById("idsemp_txt").value;
	if( empleado == null || empleado.length == 0 || /^\s+$/.test(empleado) ) 
	{	alert('El identificador de Empleado no puede estar vacío.');
		document.getElementById("idsemp_txt").focus();
					
		return false;
	}
	else
	{	operacion = document.getElementById("idsope_txt").value;
		if( operacion == null || operacion.length == 0 || /^\s+$/.test(operacion) ) 
		{	alert('El identificador de Operación no puede estar vacío.');
			document.getElementById("idsope_txt").focus();
						
			return false;
		}
		else
		{	ofl = document.getElementById("cdgofs_txt").value;
			if( ofl == null || ofl.length == 0 || /^\s+$/.test(ofl) ) 
			{	alert('Debes indicar una Orden de Fabricación con su Lote o ingresar su Código de Barras.');
				document.getElementById("cdgofs_txt").focus();
							
				return false;
			}		
		}
	}
}*/
