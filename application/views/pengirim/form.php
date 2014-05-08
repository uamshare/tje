<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		
	});
</script>
<h3>Form Pengirim</h3>
<hr>
<form method="post">
	<div class="span5">
    <div class="control-group">
		<label class="control-label">Nama pengirim</label>
		<div class="controls">
			<?php
			$value = "";
			$class = "";
			if (isset($result[0]['nmpengirim'])){
				$value = $result[0]['nmpengirim'];
				$class = 'class="uneditable-input" readonly="true"';
			}
			?>
			<input type="text" id="nmpengirim" name="nmpengirim" <?php echo $class;?> value="<?php echo $value;?>">
			<?php echo form_error('nmpengirim'); ?>
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Alamat</label>
		<div class="controls">
			<textarea rows="2" id="alamat" name="alamat"><?php echo isset($result[0]['alamat'])?$result[0]['alamat']:null;?></textarea>
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Domisili</label>
		<div class="controls">
			<input type="text" id="domisili" name="domisili" value="<?php echo isset($result[0]['domisili'])?$result[0]['domisili']:null;?>">
		</div>
    </div>
    </div>
	<div class="control-group">
		<label class="control-label">Telepon</label>
		<div class="controls">
			<input type="text" id="telp" name="telp" value="<?php echo isset($result[0]['telp'])?$result[0]['telp']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Faximili</label>
		<div class="controls">
			<input type="text" id="fax" name="fax" value="<?php echo isset($result[0]['fax'])?$result[0]['fax']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Email</label>
		<div class="controls">
			<input type="text" id="email" name="email" value="<?php echo isset($result[0]['email'])?$result[0]['email']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Attn</label>
		<div class="controls">
			<input type="text" id="attn" name="attn" value="<?php echo isset($result[0]['attn'])?$result[0]['attn']:null;?>">
		</div>
    </div>
    <div class="control-group">
		<div class="controls">
		<button type="submit" class="btn">Simpan</button>
		<button type="button" class="btn" onclick="self.history.back()">Kembali</button>
		</div>
    </div>
</form>

