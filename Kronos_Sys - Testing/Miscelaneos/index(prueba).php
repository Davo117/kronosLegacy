<!DOCTYPE HTML>
<html>
<head>
<script language="javascript">
function drag(parrafo, evento) {
evento.dataTransfer.setData('Text', parrafo.id);
}
function drop(contenedor, evento) {
var id = evento.dataTransfer.getData('Text');
contenedor.appendChild(document.getElementById(id));
evento.preventDefault();
}
</script>
<style>
body{ background: #045FB4;  }
div#contenedor{
background:#F9f9f9; display:inline-block;
border:solid 1px #FF8000; width:600px; 
height: 600px; position:fixed; 
bottom:0px; top:10px;
right:10px; color: #045FB4;
text-align:center; 
}
img#img{
width:120px;
height: 120px;
}
span#span{
color:#FFA500;
font-size:20px;
}
p#parrafo{
width:120px;
text-align:center;
}
</style>
</head>
<body>
<img id="img" draggable="true" src="http://t0.gstatic.com/images?q=tbn:HQhMN5ibPav1DM:http://thecitylovesyou.com/cinerex/wp-content/uploads/2009/10/500-days-of-summer-soundtrack.jpg" ondragstart="drag(this, event)"/><p id="parrafo" draggable="true" id="span" ondragstart="drag(this, event)">
<img id="img" src="http://t0.gstatic.com/images?q=tbn:HQhMN5ibPav1DM:http://thecitylovesyou.com/cinerex/wp-content/uploads/2009/10/500-days-of-summer-soundtrack.jpg" /><br/>
<span id="span">Arrastrame!</span>
</p>
<div id="div1" ondrop="drop(event)"
ondragover="allowDrop(event)">didve</div>
<div id="contenedor" ondrop="drop(this, event)" ondragenter="return false" ondragover="return false">Contenendor</div>
</body>
</html>