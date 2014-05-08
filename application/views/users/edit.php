<div class="top-title-element">
    <span class="title-element"><h5>Data User</h5></span>
</div>
<div  style="margin-top: 30px;">
    <form class="form-horizontal" action="users/update" method="post">
        <div class="control-group">
            <label class="control-label" for="user_name">Nama Lengkap</label>
            <div class="controls">
                <input name="user_name" required type="text" class="input-large" id="inputNama" placeholder="Nama Lengkap" value="<?php echo $rowedit->user_name; ?>">
            </div>
        </div>
        <input name="user_id" value="<?php echo $rowedit->user_id; ?>" type="hidden">
        <div class="control-group">
            <label class="control-label" for="inputNama">Username</label>
            <div class="controls">
                <input name="user_username" required type="text" class="input-large" id="inputNama" placeholder="Username" value="<?php echo $rowedit->user_username; ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" >E-mail</label>
            <div class="controls">
                <input name="e_mail" value="<?php echo $rowedit->e_mail; ?>" required type="text" class="input-large" id="inputEmail" placeholder="E-mail">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputCompany">Aktif</label>
            <div class="controls">
                <input name="user_aktif" value="1" <?php echo ($rowedit->user_aktif == '1') ? 'checked' : '';?> 
                       type="radio" class="input-large">&nbsp;Y
                <input name="user_aktif" value="0" <?php echo ($rowedit->user_aktif == '0') ? 'checked' : '';?> 
                       type="radio" class="input-large">&nbsp;N
            </div>
        </div>
        
        <div id="navigasi" class="btn-bottom">
            <button class="btn" type="submit"><i class="icon-plus-sign"></i>Simpan</button><!--<input value="Simpan" type="submit">-->
            <button class="btn" type="reset"><i class="icon-refresh"></i>Set Ulang</button><!--<input value="Set Ulang" type="reset"> -->
            <button class="btn" type="button" onclick="window.location.href='users'"><i class="icon-remove"></i>Batal</button><!--<input value="Batal" onclick="window.location.href='pegawai'" type="button">-->
        </div>
    </form>
</div>