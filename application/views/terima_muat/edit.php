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
	
	
        $("#batal").click(function(){
            window.location = "<?php echo base_url(); ?>terima_muat";
        });
        var addDiv = $('#example');
	
        function sum_total(){
            var sum = 0;
            $("input[name *= 'jumlah']").each(function(){
                sum += +$(this).val();
            });
            $("#total").val(sum);
        }
	
        function addRow(length,data){
            for(var i=2;i<=length;i++){
                var banyak = 'banyak'+i;
                var satuan = 'satuan'+i;
                var jenis = 'jenis'+i;
                var jumlah = 'jumlah'+i;
                var kg_m3 = 'kg_m3'+i;
                var ongkos = 'ongkos'+i;
                var jml_ongkos = 'jml_ongkos'+i;
                var remNew = 'remNew'+i;
		
                $('<tr class="dimrow">'
                    +'<td><input type="text" class="span12" name="banyak[]" id="'+banyak+'" /></td>'
                    +'<td><input type="text" class="span12" name="satuan[]" id="'+satuan+'" /></td>'
                    +'<td><input type="text" class="span12" name="jenis_barang[]" id="'+jenis+'" /></td>'
                    +'<td><input type="text" class="span12" name="jumlah[]" id="'+jumlah+'" /></td>'
                    +'<td><select name="kg_m3[]" id="'+kg_m3+'" class="span12"><option value="KG">KG</option><option value="M3">M3</option></select></td>'
                    +'<td><input type="text" class="span12" name="ongkos[]" id="'+ongkos+'" /></td>'
                    +'<td><input type="text" class="span12" name="jml_ongkos[]" id="'+jml_ongkos+'" /></td>'
                    +'</tr>').appendTo(addDiv);
            }
        }
        $('#tanda_terima').submit(function() {
            var no_terima = $("#no_terima").val();
            if (no_terima.length == 0){
                bootWindow('NO TERIMA HARUS DIISI');
                return false;
            }
            var no_muat = $("#no_muat").val();
            if (no_muat.length == 0){
                bootWindow('NO MUAT HARUS DIISI');
                return false;
            }
            var no_polisi = $("#no_polisi").val();
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
                    $('#tanda_terima input[type="text"]').val('');
                    $('.dimrow').remove();
                    bootWindow(data.resp,BASEURL + 'terima_muat');
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
            url: "<?php echo base_url(); ?>terima_muat/get_table",
            data: "no_terima=<?php echo $rows[0]['noterima']; ?>", 
            dataType:"json",
            success:function(data){
                addTable(data.grid);
                sum_total();
            }
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
			
                if (i == 0){
                    $('#'+banyak).val(data[i].BANYAK);
                    $('#'+satuan).val(data[i].Satuan);
                    $('#'+jenis).val(data[i].Barang);
                    $('#'+jumlah).val(data[i].JUMLAH);
                    $('#'+kg_m3).val(data[i].SAT);
                    $('#'+ongkos).val(data[i].Ongkos);
                    $('#'+jml_ongkos).val(toRP(data[i].Jml_ongkos));
                }else{
                    $('<tr class="dimrow">'
                        +'<td><input type="text" class="span12" name="banyak[]" id="'+banyak+'" /></td>'
                        +'<td><input type="text" class="span12" name="satuan[]" id="'+satuan+'" /></td>'
                        +'<td><input type="text" class="span12" name="jenis_barang[]" id="'+jenis+'" /></td>'
                        +'<td><input type="text" class="span12" name="jumlah[]" id="'+jumlah+'" /></td>'
                        +'<td><input type="text" class="span12" name="kg_m3[]" id="'+kg_m3+'" /></td>'
                        +'<td><input type="text" class="span12" name="ongkos[]" id="'+ongkos+'" /></td>'
                        +'<td><input type="text" class="span12" name="jml_ongkos[]" id="'+jml_ongkos+'" /></td>'
                        +'</tr>').appendTo(addDiv);
                    $('#'+banyak).val(data[i].BANYAK);
                    $('#'+satuan).val(data[i].Satuan);
                    $('#'+jenis).val(data[i].Barang);
                    $('#'+jumlah).val(data[i].JUMLAH);
                    $('#'+kg_m3).val(data[i].SAT);
                    $('#'+ongkos).val(data[i].Ongkos);
                    $('#'+jml_ongkos).val(toRP(data[i].Jml_ongkos));
                }
            }
        }
        $('#no_terima').typeahead({
            source: function (query, process) {
                objects = [];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>terima_muat/get_no_terima",
                    data: "query="+query, 
                    dataType:"json",
                    success:function(data){
                        if (data == false){
                            $('#no_terima').val('');
                            return false;
                        }
                        $.each(data, function(i, object) {
                            map[object.no_terima] = object;
                            objects.push(object.no_terima);
                        });
                        process(objects);
                    }
                });
            }
            ,updater: function(item) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>terima_muat/get_detail_terima",
                    data: "no_terima="+item, 
                    dataType:"json",
                    success:function(data){
                        $('#pengirim').val(data.detail[0].nmpengirim);
                        $('#penerima').val(data.detail[0].nmpenerima);
                        $('#status').val(data.detail[0].STATUS);
                        $('#alamat').val(data.detail[0].alamat);
                        $('.dimrow').remove();
                        addTable(data.grid);
                        sum_total();
                    }
                });
                return item;
            }
        });
	
        $('#no_polisi').typeahead({
            source: function (query, process) {
                objects = [];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>terima_muat/get_nopol",
                    data: "query="+query, 
                    dataType:"json",
                    success:function(data){
                        if (data == false){
                            $('#no_polisi').val('');
                            return false;
                        }
                        $.each(data, function(i, object) {
                            map[object.nopol] = object;
                            objects.push(object.nopol);
                        });
                        process(objects);
                    }
                });
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
<h3>EDIT TANDA TERIMA MUAT BARANG</h3>
<hr>
<form method="post" action="<?php echo base_url(); ?>terima_muat/update" id="tanda_terima">
    <table  cellpadding="0" cellspacing="0" border="0" id="t_form">
        <tr>
            <td>&nbsp;</td>
            <td align="right"><b>NO. POLISI</b></td>
            <td><input type="text" id="no_polisi" name="no_polisi"  autocomplete="off" value="<?php echo isset($rows[0]['nopol']) ? $rows[0]['nopol'] : null; ?>"></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>NO. MUAT</td>
            <td><input type="text" id="no_muat" name="no_muat"  value="<?php echo isset($rows[0]['noterima']) ? $rows[0]['noterima'] : null; ?>"></td>
            <td>NO. TERIMA</td>
            <td><input type="text" id="no_terima" name="no_terima" autocomplete="off" value="<?php echo isset($rows[0]['NOMOR']) ? $rows[0]['NOMOR'] : null; ?>" readonly="true"></td>
        </tr>
        <tr>
            <td>TANGGAL</td>
            <td><input type="text" id="tanggal" name="tanggal" class="datepicker"  value="<?php echo isset($rows[0]['TGL']) ? $rows[0]['TGL'] : null; ?>" autocomplete="off"></td>
            <td colspan="2"><input type="hidden" id="tgl_terima" name="tgl_terima" value="<?php echo isset($rows[0]['tglterima']) ? $rows[0]['tglterima'] : null; ?>"><b>STATUS PEMBAYARAN : </b></td>
        </tr>
        <tr>
            <td>PENGIRIM</td>
            <td><input type="text" id="pengirim" name="pengirim"  value="<?php echo isset($rows[0]['nmpengirim']) ? $rows[0]['nmpengirim'] : null; ?>"></td>
            <td colspan="2"><input type="text" id="status" name="status" value="<?php echo isset($rows[0]['STATUS']) ? $rows[0]['STATUS'] : null; ?>" readonly="true"></td>
        </tr>
        <tr>
            <td>PENERIMA</td>
            <td colspan="3"><input type="text" id="penerima" name="penerima"  value="<?php echo isset($rows[0]['NMPENERIMA']) ? $rows[0]['NMPENERIMA'] : null; ?>"></td>
        </tr>
        <tr>
            <td>ALAMAT</td>
            <td colspan="3"><input type="text" id="alamat" name="alamat" class="span10" value="<?php echo isset($rows[0]['alamat']) ? $rows[0]['alamat'] : null; ?>"></td>
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
            </tr>
        </thead>
        <tbody>
            <tr class="addrow">
                <td><input type="text" class="span12" name="banyak[]" id="banyak0" /></td>
                <td><input type="text" class="span12" name="satuan[]" id="satuan0" /></td>
                <td><input type="text" class="span12" name="jenis_barang[]" id="jenis0" /></td>
                <td><input type="text" class="span12" name="jumlah[]" id="jumlah0" /></td>
                <td><input type="text" class="span12" name="kg_m3[]" id="kg_m30" /></td>
                <td><input type="text" class="span12" name="ongkos[]" id="ongkos0" "/></td>
                <td><input type="text" class="span12" name="jml_ongkos[]" id="jml_ongkos0" /></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5"><h4 style="text-align:right;">TOTAL</h4></th>
        <th colspan="2"><input type="text" name="total" id="total" class="span12" readonly="true"/></th>
        </tr>
        </tfoot>
    </table>
    <p></p>
    <p></p>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" class="btn" id="batal">Batal</button>
</form>


