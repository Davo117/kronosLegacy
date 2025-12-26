// Reloj
function verReloj()
{ ahora = new Date()

  hora = completaCero(ahora.getHours())
  minuto = completaCero(ahora.getMinutes())
  segundo = completaCero(ahora.getSeconds())

  ahoraTiempo = hora + ":" + minuto + ":" + segundo + " Hrs" 

  document.muestraReloj.textReloj.value = ahoraTiempo 

  setTimeout("verReloj()", 1000) }

function completaCero(i) 
{ if (i < 10) { i = "0" + i; }
  
  return i; }