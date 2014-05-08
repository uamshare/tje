<h3>Kelola User :: Tambah Data</h3>
<hr>
<div  style="margin-top: 30px;">
    <form class="form-horizontal" action="users/create" method="post">
        <input name="user_id" value="" type="hidden">
        <div class="control-group">
            <label class="control-label" for="user_name">Nama Lengkap</label>
            <div class="controls">
                <input name="user_name" required type="text" class="input-large" id="inputNama" placeholder="Nama Lengkap" value="">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputNama">Username</label>
            <div class="controls">
                <input name="user_username" required type="text" class="input-large" id="inputNama" placeholder="Username" value="">
            </div>
        </div>
<!--        <div class="control-group">
            <label class="control-label" for="inputCompany">Company</label>
            <div class="controls">
                <input name="company" type="text" class="input-large" id="inputCompany" placeholder="Company" value="">
            </div>
        </div>-->
        <div class="control-group">
            <label class="control-label" for="inputEmail">E-mail</label>
            <div class="controls">
                <input name="e_mail" value="" required type="email" class="input-large" id="inputEmail" placeholder="E-mail">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
                <input name="user_password" value="" required type="password" class="input-large" id="inputPassword" placeholder="Password">
            </div>
        </div>
        
        <div id="navigasi" class="btn-bottom">
            <button class="btn" type="submit"><i class="icon-plus-sign"></i>Simpan</button><!--<input value="Simpan" type="submit">-->
            <button class="btn" type="reset"><i class="icon-refresh"></i>Set Ulang</button><!--<input value="Set Ulang" type="reset"> -->
            <button class="btn" type="button" onclick="window.location.href='users'"><i class="icon-remove"></i>Batal</button><!--<input value="Batal" onclick="window.location.href='pegawai'" type="button">-->
        </div>
    </form>
</div>