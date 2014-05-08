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
	
        $("#ongkos1").keyup(function(){
            var total = $("#jumlah1").val() * $("#ongkos1").val();
            $("#jml_ongkos1").val(total);
        });
	
        $("#jumlah1").keyup(function(){
            var total = $("#jumlah1").val() * $("#ongkos1").val();
            $("#jml_ongkos1").val(total);
        });
	
        $("#batal").click(function(){
            window.location = "<?php echo base_url(); ?>terima_barang";
        });
	
        var addDiv = $('#example');
        var i = $('#example tr').size();
        $('#addNew').live('click', function() {
            var banyak = 'banyak'+i;
            var satuan = 'satuan'+i;
            var jenis = 'jenis'+i;
            var jumlah = 'jumlah'+i;
            var kg_m3 = 'kg_m3'+i;
            var ongkos = 'ongkos'+i;
            var jml_ongkos = 'jml_ongkos'+i;
            var remNew = 'remNew'+i;
		
            $('<tr class="dimrow">'
                +'<td><input type="text" class="span12" name="banyak[]" id="'+banyak+'"/></td>'
                +'<td><input type="text" class="span12" name="satuan[]" id="'+satuan+'"/></td>'
                +'<td><input type="text" class="span12" name="jenis_barang[]" id="'+jenis+'"/></td>'
                +'<td><input type="text" class="span12" name="jumlah[]" id="'+jumlah+'" autocomplete="off"/></td>'
                +'<td><select name="kg_m3[]" id="'+kg_m3+'" class="span12"><option value="KG">KG</option><option value="M3">M3</option></select></td>'
                +'<td><input type="text" class="span12" name="ongkos[]" id="'+ongkos+'" autocomplete="off"/></td>'
                +'<td><input type="text" class="span12" name="jml_ongkos[]" id="'+jml_ongkos+'" /></td>'
                +'<td><a id="'+remNew+'" href="javascript:void(0);"><i class="icon-minus-sign"></i></a></td>'
                +'</tr>').appendTo(addDiv);
		
            $("#"+jumlah).keyup(function(){
                var total = $("#"+jumlah).val() * $("#"+ongkos).val();
                $("#"+jml_ongkos).val(total);
            });
		
            $("#"+ongkos).keyup(function(){
                var total = $("#"+jumlah).val() * $("#"+ongkos).val();
                $("#"+jml_ongkos).val(total);
            });
		
            $('#'+remNew).live('click', function() {
                $(this).parents('tr').remove();
            });
		
            i++;
            return false;
        });
	
        $('#tanda_terima').submit(function() {
            var no_terima = $("#no_terima").val();
            if (no_terima.length == 0){
                bootWindow('NO TERIMA HARUS DIISI');
                return false;
            }
            var pengirim = $("#pengirim").val();
            if (pengirim.length == 0){
                bootWindow('PENGIRIM HARUS DIISI');
                return false;
            }
            var penerima = $("#penerima").val();
            if (penerima.length == 0){
                bootWindow('PENERIMA HARUS DIISI');
                return false;
            }
            var tanggal = $("#tanggal").val();
            if (tanggal.length == 0){
                bootWindow('TANGGAL HARUS DIISI');
                return false;
            }
            var banyak1 = $("#banyak1").val();
            if (banyak1.length == 0){
                bootWindow('TABEL HARUS DIISI');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if(data.save){
                        bootWindow(data.resp,BASEURL + 'terima_barang');
                    }else{
                        bootWindow(data.resp);
                    }
                    
//                    if (data.save == true){
//                        $('#tanda_terima input[type="text"]').val('');
//                        $('.dimrow').remove();
//                    }else{
//                        $('#no_terima').val('');
//                        $('#no_terima').focus();
//                    }
                }
            })
            return false;
        });
	
        function bootWindow(msg,turl){
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
            if(typeof(turl) !== 'undefined'){
                setTimeout(function(){window.location = turl},200);
            }
        }
	
        $('#pengirim').typeahead({
            source: function (query, process) {
                objects = [];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>terima_barang/get_pengirim",
                    data: "query="+query, 
                    dataType:"json",
                    success:function(data){
                        if (data == false){
                            $('#pengirim').val('');
                            return false;
                        }
                        $.each(data, function(i, object) {
                            map[object.nmpengirim] = object;
                            objects.push(object.nmpengirim);
                        });
                        process(objects);
                    }
                });
            }
        });
        $('#penerima').typeahead({
            source: function (query, process) {
                objects = [];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>terima_barang/get_penerima",
                    data: "query="+query, 
                    dataType:"json",
                    success:function(data){
                        if (data == false){
                            $('#penerima').val('');
                            return false;
                        }
                        $.each(data, function(i, object) {
                            map[object.nmpenerima] = object;
                            objects.push(object.nmpenerima);
                        });
                        process(objects);
                    }
                });
                return false
            }
        });
    });
</script>
<style>
    #t_form td{
        padding-left:10px;
        padding-right:10px;
    }
</style>
<h3>TAMBAH TANDA TERIMA BARANG</h3>
<hr>
<form method="post" action="<?php echo base_url(); ?>terima_barang/simpan" id="tanda_terima">
    <table  cellpadding="0" cellspacing="0" border="0" id="t_form">
        <tr>
            <td colspan="2">NO. TERIMA</td>
            <td><input type="text" id="no_terima" name="no_terima"></td>
            <td colspan="2">TANGGAL</td>
            <td colspan="3"><input type="text" id="tanggal" name="tanggal" class="datepicker" autocomplete="off"></td>
        </tr>
        <tr>
            <td colspan="2">PENGIRIM</td>
            <td><input type="text" id="pengirim" name="pengirim" autocomplete="off"></td>
            <td colspan="2">LUNAS BAYAR</td>
            <td colspan="3"><input type="checkbox" name="status"></td>
        </tr>
        <tr>
            <td colspan="2">PENERIMA</td>
            <td><input type="text" id="penerima" name="penerima" autocomplete="off"></td>
            <td colspan="5"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
        <thead>
            <tr>
                <th width="5%">BANYAK</th>
                <th width="5%">SATUAN</th>
                <th width="40%">JENIS BARANG</th>
                <th width="5%">JUMLAH</th>
                <th width="10%">KG/M3</th>
                <th width="15%">ONGKOS PER KG/M3</th>
                <th width="15%">JUMLAH ONGKOS</th>
                <th width="5%">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <tr class="addrow">
                <td><input type="text" class="span12" name="banyak[]" id="banyak1"/></td>
                <td><input type="text" class="span12" name="satuan[]" id="satuan1"/></td>
                <td><input type="text" class="span12" name="jenis_barang[]" id="jenis1"/></td>
                <td><input type="text" class="span12" name="jumlah[]" id="jumlah1" autocomplete="off"/></td>
                <td><select name="kg_m3[]" id="kg_m31" class="span12"><option value="KG">KG</option><option value="M3">M3</option></select></td>
                <td><input type="text" class="span12" name="ongkos[]" id="ongkos1" autocomplete="off"/></td>
                <td><input type="text" class="span12" name="jml_ongkos[]" id="jml_ongkos1" /></td>
                <td><a id="addNew" href="javascript:void(0);"><i class="icon-plus-sign"></i></a></td>
            </tr>
        </tbody>
    </table>
    <p></p>
    <p></p>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" class="btn" id="batal">Kembali</button>
</form>


