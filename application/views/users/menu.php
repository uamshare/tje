<div style="float: left; width: 550px;">
    <div class="top-title-element" style="float: left; width: 550px;">
        <span class="title-element"><h5>Data User</h5></span>
    </div>
    <table id='listTable' class='table table-bordered'>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>E-mail</th>
                <th width="12%" align="center">Status Aktif</th>				
                <th width="10%" align="center"> Aksi </th>				
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listtabel as $row) {
                $aktif = $row->user_aktif;
                if ($aktif == 1) {
                    $stat = "<a title='Aktif'><img src='assets/ico/aktif-16.png' ></a>";
                } else {
                    $stat = "<a title='Tidak Aktif'><img src='assets/ico/pasif-16.png' ></a>";
                }
                echo "
                    <tr>
                        <td>$row->user_id</td>
                        <td>$row->user_name</td>
                        <td>$row->user_username</td>
                        <td style='text-align:left'>$row->e_mail</td>
                        <td style='text-align:center'>$stat</td>
                        <td style='text-align:center'>
                            <a href='users/menu/$row->user_id/$row->user_name' title='Ubah' class='edit'><img src='assets/ico/ubah.png' ></a>    
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
        <tfoot></tfoot>
    </table>
</div>
<div style="float: right; width: 300px;">
    <form name="formHakAkses" class="form-horizontal" action="users/updatemenu" method="post">
        <div class="top-title-element">
            <span class="title-element"><h5>Akses User</h5></span>
            <span class="title-element">
                Pengaturan Menu Untuk user <span id="nama_elngkap" style="color: blue;"><?php echo $nama;?></span>
            </span>
        </div>
        <hr/>
        <div >
            <input name="user_id" required type="hidden" class="input-large" 
                   id="inputID" placeholder="User ID" value="<?php echo $user_id;?>">
        </div>
        <?php echo $aksesmenu; ?>
        <div id="navigasi" class="btn-bottom">
            <button class="btn" type="submit"><i class="icon-plus-sign"></i>Simpan</button>
            <button class="btn" type="reset"><i class="icon-refresh"></i>Set Ulang</button>
            <button class="btn" type="button" onclick="window.location.href='dashboard'"><i class="icon-remove"></i>Batal</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){ 
        $("#chektree").treeview({
            persist: "location",
            collapsed: true,
            unique: true
        });
        $("#chektree").find('li').children('input[type=checkbox]').click(function(){
            var childrenCheck = $(this).parent('li').find('input[type=checkbox]');
            if($(this).is(':checked')){
                $(childrenCheck).attr('checked',true);
            }else{
                $(childrenCheck).attr('checked',false);
            }
        })
    });
    
//    $('.edit').click(function(a){
//        //        $.ajax({
//        //            url:'menu',
//        //            method:'GET',
//        //            success:function(){
//        //                var iduser = $(document).find('table');
//        //                //var pName = document.getElementById("user_class_id").innerHTML;
//        //                //alert(pName);
//        //                usernya = iduser;
//        //                alert(iduser);
//        //                $(document).find('form[name=formHakAkses]').find('input[name=user_id]').val(5);
//        ////                $(document).find('form[name=formHakAkses]').submit(function(evt){
//        ////                    alert('iuj');
//        ////                    evt.preventDefault();
//        ////                });
//        //            }
//        //        });
//        //        a.preventDefault();
//        $('table tbody tr').click(function(){
//            //var iduser = this.cells[0].innerHTML;
//            var Nama Le = this.cells[0].innerHTML;
//            //$(document).find('form[name=formHakAkses]').find('input[name=user_id]').val(iduser);
//            $('#nama_lengkap').html();
//        });
//        a.preventDefault();
//    });
</script>
<script type="text/javascript">
 
    $("tr").not(':first').hover(
    function () {
        $(this).css("background","#9ccaf8");
    }, 
    function () {
        $(this).css("background","");
    }
);
 
</script>