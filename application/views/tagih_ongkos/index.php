<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#form_cari').submit(function() {
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				dataType: 'json',
				success: function(data) {
					console.log(data);
				}
			})
			return false;
		});
		$('.datepicker').datepicker({
			format : 'yyyy-mm-dd'
		});
		var oTable = $('#example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			// "bFilter": false,
			// "bPaginate": false,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "<?php echo base_url();?>tagih_ongkos/get_data",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records"
			},
			'fnServerData': function (sSource, aoData, fnCallback) {
				$.ajax({
					'dataType': 'json',
					'type': 'POST',
					'url': sSource,
					'data': aoData,
					'success': fnCallback
				});
			}
		});
		
	});
</script>
<h3>TANDA KIRIM BARANG</h3>
<hr>
<form class="form-inline" action="<?php echo base_url();?>tagih_ongkos/get_data" method="post" id="form_cari">
<input type="text" name="start_date" id="start_date" class="datepicker" autocomplete="off"> S/D 
<input type="text" name="end_date" id="end_date" class="datepicker" autocomplete="off">
<button type="submit" class="btn" id="cari">CARI</button>
<a href="<?php echo base_url();?>tagih_ongkos/add/" class="pull-right btn btn-primary">Tambah Kirim</a>
</form>
<p></p>
<?php echo $this->session->flashdata('msg');?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
		<thead>
			<tr>
				<th width="15%">No. Kirim</th>
				<th width="15%">Tgl Kirim</th>
				<th width="15%">No. Polisi</th>
				<th width="45%">Keterangan</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
