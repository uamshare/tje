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
	
        $("#ongkos0").keyup(function(){
            var total = $("#jumlah0").val() * $("#ongkos0").val();
            $("#jml_ongkos0").val(total);
        });
	
        $("#jumlah0").keyup(function(){
            var total = $("#jumlah0").val() * $("#ongkos0").val();
            $("#jml_ongkos0").val(total);
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
	
        function addTable(data){
            for(var i=0;i<data.length;i++){
                var banyak = 'banyak'+i;
                var satuan = 'satuan'+i;
                var jenis = 'jenis'+i;
                var jumlah = 'jumlah'+i;
                var kg_m3 = 'kg_m3'+i;
                var ongkos = 'ongkos'+i;
                var jml_ongkos = 'jml_ongkos'+i;
                var remNew = 'remNew'+i;

                if (i == 0){
                    $('#'+banyak).val(data[i].BANYAK);
                    $('#'+satuan).val(data[i].Satuan);
                    $('#'+jenis).val(data[i].Barang);
                    $('#'+jumlah).val(data[i].JUMLAH);
                    $('#'+kg_m3).val(data[i].SAT);
                    $('#'+ongkos).val(data[i].Ongkos);
                    $('#'+jml_ongkos).val(toRP(data[i].jml_ongkos));
                }else{
                    $('<tr class="dimrow">'
                        +'<td><input type="text" class="span12" name="banyak[]" id="'+banyak+'" /></td>'
                        +'<td><input type="text" class="span12" name="satuan[]" id="'+satuan+'" /></td>'
                        +'<td><input type="text" class="span12" name="jenis_barang[]" id="'+jenis+'" /></td>'
                        +'<td><input type="text" class="span12" name="jumlah[]" id="'+jumlah+'" /></td>'
                        +'<td><select name="kg_m3[]" id="'+kg_m3+'" class="span12"><option value="KG">KG</option><option value="M3">M3</option></select></td>'
                        +'<td><input type="text" class="span12" name="ongkos[]" id="'+ongkos+'" /></td>'
                        +'<td><input type="text" class="span12" name="jml_ongkos[]" id="'+jml_ongkos+'" /></td>'
                        +'<td><a id="'+remNew+'" href="javascript:void(0);"><i class="icon-minus-sign"></i></a></td>'
                        +'</tr>').appendTo(addDiv);
                    $('#'+banyak).val(data[i].BANYAK);
                    $('#'+satuan).val(data[i].Satuan);
                    $('#'+jenis).val(data[i].Barang);
                    $('#'+jumlah).val(data[i].JUMLAH);
                    $('#'+kg_m3).val(data[i].SAT);
                    $('#'+ongkos).val(data[i].Ongkos);
                    $('#'+jml_ongkos).val(toRP(data[i].jml_ongkos));
                    $('#'+remNew).live('click', function(){
                        $(this).parents('tr').remove();
                    });
                }
			
            }
            return false;
        }
	
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
            var banyak1 = $("#banyak0").val();
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
                    bootWindow(data.resp,BASEURL + 'terima_barang');
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
	
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>terima_barang/get_table/",
            data: "no_terima=<?php echo $rows[0]['NoTerima']; ?>", 
            dataType:"json",
            success:function(data){
                addTable(data.grid);
            }
        });
			
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
<h3>EDIT TANDA TERIMA BARANG</h3>
<hr>
<form method="post" action="<?php echo base_url(); ?>terima_barang/update" id="tanda_terima">
    <table  cellpadding="0" cellspacing="0" border="0" id="t_form">
        <tr>
            <td colspan="2">NO. TERIMA</td>
            <td><input type="text" id="no_terima" name="no_terima" value="<?php echo isset($rows[0]['NoTerima']) ? $rows[0]['NoTerima'] : null; ?>" readonly="true"></td>
            <td colspan="2">TANGGAL</td>
            <td colspan="3"><input type="text" id="tanggal" name="tanggal" class="datepicker" value="<?php echo isset($rows[0]['tglterima']) ? $rows[0]['tglterima'] : null; ?>" autocomplete="off"></td>
        </tr>
        <tr>
            <td colspan="2">PENGIRIM</td>
            <td><input type="text" id="pengirim" name="pengirim" value="<?php echo isset($rows[0]['nmpengirim']) ? $rows[0]['nmpengirim'] : null; ?>" autocomplete="off"></td>
            <td colspan="2">LUNAS BAYAR</td>
            <td colspan="3"><input type="checkbox" name="status" <?php if ($rows[0]['STATUS'] == 'LUNAS') {
    echo "checked";
} ?> ></td>
        </tr>
        <tr>
            <td colspan="2">PENERIMA</td>
            <td><input type="text" id="penerima" name="penerima" value="<?php echo isset($rows[0]['nmpenerima']) ? $rows[0]['nmpenerima'] : null; ?>" autocomplete="off"></td>
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
                <td><input type="text" class="span12" name="banyak[]" id="banyak0"/></td>
                <td><input type="text" class="span12" name="satuan[]" id="satuan0"/></td>
                <td><input type="text" class="span12" name="jenis_barang[]" id="jenis0"/></td>
                <td><input type="text" class="span12" name="jumlah[]" id="jumlah0" autocomplete="off"/></td>
                <td><select name="kg_m3[]" id="kg_m30" class="span12"><option value="KG">KG</option><option value="M3">M3</option></select></td>
                <td><input type="text" class="span12" name="ongkos[]" id="ongkos0" autocomplete="off"/></td>
                <td><input type="text" class="span12" name="jml_ongkos[]" id="jml_ongkos0" /></td>
                <td><a id="addNew" href="javascript:void(0);"><i class="icon-plus-sign"></i></a></td>
            </tr>
        </tbody>
    </table>
    <p></p>
    <p></p>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" class="btn" id="batal">Kembali</button>
</form>


