<?php
if(isset($_POST['lista'])){
	if($_POST['lista']=="4"){ ?>
		<div class="col-xs-3">	<label for="tipo">Seleccione el tipo</label>
		<select name="tipo" id="tipo" class="form-control" required>

		<option value="">--</option><?php
		$SQL=$MySQLiconn->query("SELECT tipo FROM tipoproducto WHERE baja=1");
		while($row=$SQL->fetch_array()){?>
			<option value="<?php echo $row['tipo'];?>"> <?php echo $row['tipo'];?></option>
			<?php
		} ?>
		</select>
		</div>

		<div class="col-xs-3"><label for="codigo">Número de operación</label>
		<input type="text" id="codigo" class="form-control" name="codigo" value="" size="20" placeholder="Código/NoOp" required ONKEYPRESS="return numeros(event)">
		</div><?php
	}
	else if($_POST['lista']=="5")
	{ ?>
			<div class="col-xs-3"><label for="codigo">Referencia</label>
		<input type="text" class="form-control" name="codigo" value="" id="codigo" size="20" placeholder="Referencia de bobina" required>
		</div><?php
	}
	else{ ?>
		<div class="col-xs-3"><label for="codigo">Código</label>
		<input type="text" class="form-control" name="codigo" value="" id="codigo" size="20" placeholder="Código/NoOp" required ONKEYPRESS="return numeros(event)">
		</div><?php
	}
}
else{?>
	<div class="col-xs-3"><label for="codigo">Código</label>
	<input type="text" name="codigo" value="" class="form-control" id="codigo" size="20" placeholder="Código/NoOp" required ONKEYPRESS="return numeros(event)">
	</div> <?php
} ?>