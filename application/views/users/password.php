<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="top-title-element">
    <span class="title-element"><h5>Perubahan Password</h5></span>
</div>
<div style="margin-top: 30px;">
    <form class="form-horizontal" action="users/updatepass" method="post">
        <div class="control-group">
            <label style="width: 200px;" class="control-label" for="inputOldPassword">Password Lama <font style="color: red;">*</font></label>
            <div class="controls">
                <input type="password" required name="password" placeholder="Isi password lama" value="" class="input-large" id="inputOldPassword" />
            </div>
        </div>
        <div class="control-group">
            <label style="width: 200px;" class="control-label" for="inputNewPassword">Password Baru <font style="color: red;">*</font></label>
            <div class="controls">
                <input type="password" required name="newpassword" placeholder="Isi password baru" value="" class="input-large" id="inputNewPassword" />
            </div>
        </div>
        <div class="control-group">
            <label style="width: 200px;" class="control-label" for="inputConfirmPassword">Konfirmasi Password Baru <font style="color: red;">*</font></label>
            <div class="controls">
                <input type="password" required name="confirmpassword" placeholder="Konfirmasi Password" value="" class="input-large" id="inputConfirmPassword" />
            </div>
        </div>
        <div id="navigasi" class="btn-bottom">
            <button class="btn" type="submit"><i class="icon-plus-sign"></i>Simpan</button><!--<input value="Simpan" type="submit">-->
            <button class="btn" type="reset"><i class="icon-refresh"></i>Set Ulang</button><!--<input value="Set Ulang" type="reset"> -->
            <button class="btn" type="button" onclick="window.location.href='password'"><i class="icon-remove"></i>Batal</button><!--<input value="Batal" onclick="window.location.href='pegawai'" type="button">-->
        </div>
    </form>
</div>