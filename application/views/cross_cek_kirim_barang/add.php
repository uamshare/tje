<style>
    #t_form td{
        padding-left:10px;
        padding-right:10px;
    }
</style>
<h3>CROSS CEK PENGIRIMAN BARANG</h3>
<hr>
<form method="post" action="<?php echo base_url(); ?>crosscek_kirim_barang/simpan" id="tanda_terima">
    <table  cellpadding="0" cellspacing="0" border="0" id="t_form">
        <tr>
            <td>NO.</td>
            <td><input type="text" id="m_cek_no" name="m_cek_no"></td>
            <td><b>NO KIRIM</b></td>
            <td><input type="text" id="m_cek_fk" name="m_cek_fk" autocomplete="off"></td>
        </tr>
        <tr>
            <td>TANGGAL</td>
            <td><input type="text" id="m_cek_tgl" name="m_cek_tgl" class="datepicker" autocomplete="off"></td>
            <td>TGL KIRIM</td>
            <td><input type="text" id="tgl_kirim" name="tgl_kirim" disabled ></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>NO. POLISI</b></td>
            <td><input type="text" id="no_polisi" name="no_polisi"  autocomplete="off"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
        <thead>
            <tr>
                <th width="10%">NO.STT</th>
                <th width="10%">KODE</th>
                <th width="10%">BANYAK</th>
                <th width="10%">SATUAN</th>
                <th width="25%">PENGIRIM</th>
                <th width="25%">PENERIMA</th>
                <th width="10%" style="text-align: center;"><input type="checkbox" id="parent-check" /></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <p></p>
    <p></p>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" class="btn" id="batal" onclick="return history.back(-1)">Batal</button>
</form>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format : 'yyyy-mm-dd'
        });
        var oTable = $('#example').dataTable({
            "bProcessing": true,
            "bFilter": false,
            "bPaginate": false,
            "bSort": false,
            "sPaginationType": "bootstrap",
            "bScrollCollapse": true
        });
	
        var url_no = '<?php echo base_url(); ?>crosscek_kirim_barang/get_no_kirim';
	
        function getNoKirim(){
            $.getJSON(url_no,function(data){
                $('#m_cek_no').val(data.no_kirim);
            });
        }
        getNoKirim();
	
        $("#batal").click(function(){
            $('#tanda_terima input[type="text"]').val('');
            $('.dimrow').remove();
            getNoKirim();
        });
        var addDiv = $('#example');
	
        $('#tanda_terima').submit(function() {
            var no_polisi = $("#no_polisi").val();
            if ($("#m_cek_no").val().length == 0){
                bootWindow('NO HARUS DIISI');
                return false;
            }
            if ($("#m_cek_tgl").val().length == 0){
                bootWindow('TGL HARUS DIISI');
                $("#m_cek_tgl").focus();
                return false;
            }
            if ($("#m_cek_fk").val().length == 0){
                bootWindow('NO KIRIM HARUS DIISI');
                return false;
            }
            if (no_polisi.length == 0){
                bootWindow('NO POLISI HARUS DIISI');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    //$('#tanda_terima input[type="text"]').val('');
                    //alert();
                    if(data.success == true){
                        bootWindow(data.resp,BASEURL + 'crosscek_kirim_barang');
                    }else{
                        bootWindow(data.resp);
                    }
                    
                    
                }
            })
            return false;
        });
	
        function bootWindow(msg,url){
            $('<div class="modal hide fade" id="myModal">'
                +'<div class="modal-header">'
                +'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'
                +'<h3>KONFIRMASI</h3>'
                +'</div>'
                +'<div class="modal-body">'
                +'<p></p>'
                +'</div>'
                +'<div class="modal-footer">'
                +'<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>'
                +'</div>'
                +'</div>').appendTo('body');
            $('#myModal .modal-body p').text(msg);
            $('#myModal').modal('show');
            //sleep(300);
            if(typeof(url) != 'undefined'){
                setTimeout(function(){window.location = url}, 1000);
                
            }
            
        }
        function addTable(data){
            for(var i=0;i<data.length;i++){
                //console.log(i);
//                var no_stt = 'no_stt'+i;
//                var kode = 'kode'+i;
//                var banyak = 'banyak'+i;
//                var satuan = 'satuan'+i;
//                var pengirim = 'pengirim'+i;
//                var penerima = 'penerima'+i;
//                var status = 'status'+i;
			
                $('<tr class="dimrow">'
                    +'<td>'+ data[i].NOKIRIM + '</td>'
                    +'<td>' + data[i].NOTERIMA + '</td>'
                    +'<td>' + data[i].BANYAK + '</td>'
                    +'<td>' + data[i].SATUAN + '</td>'
                    +'<td>' + data[i].NMPENGIRIM + '</td>'
                    +'<td>' + data[i].NMPENERIMA + '</td>'
                    +'<td><input type="checkbox" class="span12 child-check" name="stat[]" value="' + data[i].id + '" onclick="clickcek()" /></td>'
                    +'</tr>').appendTo(addDiv);

            }
        }
        var dtobj = [];
        $('#m_cek_fk').typeahead({
            source: function (query, process) {
                var objects = [];
                var map = {};
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>crosscek_kirim_barang/get_nopol",
                    data: "query="+query, 
                    dataType:"json",
                    success:function(data){
                        if (data == false){
                            $('#m_cek_fk').val('');
                            return false;
                        }
                        dtobj = data;
                        $.each(data, function(i, object) {
                            map[object.nokirim] = object;
                            objects.push(object.nokirim);
                            
                        });
                        process(objects);
                    }
                    
                });
            },
            updater: function(item) {
                $.each(dtobj, function(i, object) {
                    if(item == object.nokirim){
//                        alert(object.nopol);
                        $('#tgl_kirim').val(object.tglkirim);
                        $('#no_polisi').val(object.nopol);
                    }      
                });
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>crosscek_kirim_barang/get_detail_kirim",
                    data: "no_polisi="+item, 
                    dataType:"json",
                    success:function(data){
                        $('.odd').remove();
                        $('.dimrow').remove();
                        addTable(data.grid);
                    }
                });
                return item;
            }
        });
        
        
//        $('#no_polisi').typeahead({
//            source: function (query, proses) {
//                var objects = [];
//                var map = {};
//                $.ajax({
//                    type: 'POST',
//                    url: "<?php echo base_url(); ?>crosscek_kirim_barang/get_nopol",
//                    data: "query="+query, 
//                    dataType:"json",
//                    success:function(data){
//                        if (data == false){
//                            $('#no_polisi').val('');
//                            return false;
//                        }
//                        dtobj = data;
//                        $.each(data, function(i, object) {
//                            map[object.NOPOL] = object;
//                            objects.push(object.NOPOL);
//                            
//                        });
//                        proses(objects);
//                    }
//                    
//                });
//            },
//            updater: function(item) {
//                $.each(dtobj, function(i, object) {
//                    if(item == object.nopol){
//                        alert(object.nokirim);
//                    }      
//                });
//                $.ajax({
//                    type: 'POST',
//                    url: "<?php echo base_url(); ?>crosscek_kirim_barang/get_detail_kirim",
//                    data: "\
                no_polisi=//"+item, 
//                    dataType:"json",
//                    success:function(data){
//                        $('.odd').remove();
//                        addTable(data.grid);
//                    }
//                });
//                return item;
//            }
//        });
        
        $('#parent-check').click(function(){
            //alert('tes');
            if($(this).attr('checked') == 'checked'){
                $('#example > tbody > tr > td > .child-check').attr('checked',true);
            }else{
                $('#example > tbody > tr > td > .child-check').attr('checked',false);
            }
                
        });
    });
    function clickcek(){
        //alert($(this).attr('checked'));
        if($(this).attr('checked') != 'checked')
            $('#parent-check').attr('checked',false);
    };
</script>