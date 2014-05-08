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
	
	var url_no = '<?php echo base_url();?>tagih_ongkos/get_no_kirim';
	
	function getNoKirim(){
		$.getJSON(url_no,function(data){
			$('#no_kirim').val(data.no_kirim);
		});
	}
	getNoKirim();
	
	$("#batal").click(function(){
		$('#tanda_terima input[type="text"]').val('');
		$('.dimrow').remove();
		getNoKirim();
	});
	var addDiv = $('#example');
	
	function sum_total(){
		var sum = 0;
		$("input[name *= 'jml_ongkos']").each(function(){
			sum += +$(this).val();
		});
		$("#total").val(sum);
	}
	
	$('#tanda_terima').submit(function() {
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
				$('#keterangan').val('');
				$('.dimrow').remove();
				bootWindow(data.resp);
				getNoKirim();
			}
		})
		return false;
	});
	
	function bootWindow(msg){
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
	}
	function addTable(data){
		for(var i=0;i<data.length;i++){
		
			var no_stt = 'no_stt'+i;
			var kode = 'kode'+i;
			var banyak = 'banyak'+i;
			var satuan = 'satuan'+i;
			var pengirim = 'pengirim'+i;
			var penerima = 'penerima'+i;
			var status = 'status'+i;
			
			if (i == 0){
				$('#'+no_stt).val(data[i].no_stt);
				$('#'+kode).val(data[i].kode);
				$('#'+banyak).val(data[i].banyak);
				$('#'+satuan).val(data[i].satuan);
				$('#'+pengirim).val(data[i].pengirim);
				$('#'+penerima).val(data[i].penerima);
				$('#'+status).val(data[i].status);
			}else{
				$('<tr class="dimrow">'
				+'<td><input type="text" class="span12" name="no_stt[]" id="'+no_stt+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="kode[]" id="'+kode+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="banyak[]" id="'+banyak+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="satuan[]" id="'+satuan+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="pengirim[]" id="'+pengirim+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="penerima[]" id="'+penerima+'" readonly="true"/></td>'
				+'<td><input type="text" class="span12" name="status[]" id="'+status+'" readonly="true"/></td>'
				+'</tr>').appendTo(addDiv);
				$('#'+no_stt).val(data[i].no_stt);
				$('#'+kode).val(data[i].kode);
				$('#'+banyak).val(data[i].banyak);
				$('#'+satuan).val(data[i].satuan);
				$('#'+pengirim).val(data[i].pengirim);
				$('#'+penerima).val(data[i].penerima);
				$('#'+status).val(data[i].status);
			}
		}
	}
	$('#no_polisi').typeahead({
		source: function (query, process) {
			objects = [];
			map = {};
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url();?>tagih_ongkos/get_nopol",
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
		,updater: function(item) {
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url();?>tagih_ongkos/get_detail_muat",
				data: "no_polisi="+item, 
				dataType:"json",
				success:function(data){
					$('.dimrow').remove();
					addTable(data.grid);
				}
			});
			return item;
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
<h3>PERINCIAN PENAGIHAN ONGKOS</h3>
<hr>
<form method="post" action="<?php echo base_url();?>tagih_ongkos/simpan" id="tanda_terima">
<table  cellpadding="0" cellspacing="0" border="0" id="t_form">
	<tr>
		<td>NO.</td>
		<td><input type="text" id="no_kirim" name="no_kirim"></td>
		<td>KETERANGAN</td>
		<td rowspan="2"><textarea id="keterangan" name="keterangan"></textarea></td>
	</tr>
	<tr>
		<td>TANGGAL</td>
		<td><input type="text" id="tanggal" name="tanggal" class="datepicker" autocomplete="off"></td>
		<td></td>
	</tr>
	<tr>
		<td><b>NO. POLISI</b></td>
		<td><input type="text" id="no_polisi" name="no_polisi"  autocomplete="off"></td>
		<td></td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
	<thead>
		<tr>
			<th width="20%">NO.STT</th>
			<th width="10%">KODE</th>
			<th width="10%">BANYAK</th>
			<th width="10%">SATUAN</th>
			<th width="20%">PENGIRIM</th>
			<th width="20%">PENERIMA</th>
			<th width="10%">STATUS</th>
		</tr>
	</thead>
	<tbody>
		<tr class="addrow">
			<td><input type="text" class="span12" name="no_stt[]" id="no_stt0" readonly="true"/></td>
			<td><input type="text" class="span12" name="kode[]" id="kode0" readonly="true"/></td>
			<td><input type="text" class="span12" name="banyak[]" id="banyak0" readonly="true"/></td>
			<td><input type="text" class="span12" name="satuan[]" id="satuan0" readonly="true"/></td>
			<td><input type="text" class="span12" name="pengirim[]" id="pengirim0" readonly="true"/></td>
			<td><input type="text" class="span12" name="penerima[]" id="penerima0" readonly="true"/></td>
			<td><input type="text" class="span12" name="status[]" id="status0" readonly="true"/></td>
		</tr>
	</tbody>
</table>
<p></p>
<p></p>
<button type="submit" class="btn btn-primary">Simpan</button>
<button type="button" class="btn" id="batal">Batal</button>
</form>
	

