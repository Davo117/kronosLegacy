<?php
session_start();
$comparador=0;

?>
<p style="font:bold 15px Sansation Light;padding-top:10px;"><?php echo "codigos agregados:  ";?><b style="font:bold 20px Sansation;"><?php echo count($_SESSION['array']);?></b><?php
if(!empty($_SESSION['estatus'][0]))
{
	if($_SESSION['estatus'][0]=='*')
{
?><b style="color:green;float:right;font:bold 12px Sansation;margin-right:10px;"><?php echo $_SESSION['estatus'];
}
 
else 
{
?><b style="color:red;float:right;font:bold 12px Sansation;margin-right:10px;"><?php echo $_SESSION['estatus'];
} 
}
?>
</b>
</p>
<?php
for($i=0;$i<count($_SESSION['array']);$i++)
  {
    ?>
      <p class='paquetes'><?php echo $_SESSION['array'][$i];?></p>
      <?php
      $comparador=$i;
  }
  
  ?>