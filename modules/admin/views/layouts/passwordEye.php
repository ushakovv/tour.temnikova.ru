<div class="pass_block">
	<div class="input-group">
		<input type="password" name="<?= $name ?>" value="<?= $value ?>" class="form-control" autocomplete="off">
		<span class="input-group-addon pointer" onclick="Password.showhide(this)"><div class="pass"></div></span>
	</div>
	<a href="#" onclick="return Password.generate(this, 10)">Сгенерировать пароль</a>
</div>