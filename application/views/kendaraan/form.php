<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		
	});
</script>
<h3>Form Kendaraan</h3>
<hr>
<form method="post">
	<div class="span5">
    <div class="control-group">
		<label class="control-label">Nopol</label>
		<div class="controls">
			<?php
			$value = "";
			$class = "";
			if (isset($result[0]['nopol'])){
				$value = $result[0]['nopol'];
				$class = 'class="uneditable-input" readonly="true"';
			}
			?>
			<input type="text" id="nopol" name="nopol" <?php echo $class;?> value="<?php echo $value;?>">
			<?php echo form_error('nopol'); ?>
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Merk</label>
		<div class="controls">
			<input type="text" id="merk" name="merk" value="<?php echo isset($result[0]['merk'])?$result[0]['merk']:null;?>">
			<?php echo form_error('merk'); ?>
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Jenis</label>
		<div class="controls">
			<input type="text" id="jenis" name="jenis" value="<?php echo isset($result[0]['jenis'])?$result[0]['jenis']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Tahun</label>
		<div class="controls">
			<input type="text" id="tahun" name="tahun" value="<?php echo isset($result[0]['tahun'])?$result[0]['tahun']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Warna</label>
		<div class="controls">
			<input type="text" id="warna" name="warna" value="<?php echo isset($result[0]['warna'])?$result[0]['warna']:null;?>">
		</div>
    </div>
	</div>
	<div class="control-group">
		<label class="control-label">Rangka</label>
		<div class="controls">
			<input type="text" id="rangka" name="rangka" value="<?php echo isset($result[0]['rangka'])?$result[0]['rangka']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Mesin</label>
		<div class="controls">
			<input type="text" id="mesin" name="mesin" value="<?php echo isset($result[0]['mesin'])?$result[0]['mesin']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">BPKB</label>
		<div class="controls">
			<input type="text" id="bpkb" name="bpkb" value="<?php echo isset($result[0]['bpkb'])?$result[0]['bpkb']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Supir</label>
		<div class="controls">
			<input type="text" id="supir" name="supir" value="<?php echo isset($result[0]['supir'])?$result[0]['supir']:null;?>">
		</div>
    </div>
	<div class="control-group">
		<label class="control-label">Keterangan</label>
		<div class="controls">
			<textarea rows="2" name="ket" id="ket"><?php echo isset($result[0]['ket'])?$result[0]['ket']:null;?></textarea>
		</div>
    </div>
    <div class="control-group">
		<div class="controls">
		<button type="submit" class="btn">Simpan</button>
		<button type="button" class="btn" onclick="self.history.back()">Kembali</button>
		</div>
    </div>
</form>

