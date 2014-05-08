<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		var oTable = $('#example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			// "bFilter": false,
			// "bPaginate": false,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "<?php echo base_url();?>penerima/get_data",
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
<h3>Penerima</h3>
<hr>
<a href="<?php echo base_url();?>penerima/form/" class="btn btn-primary">Tambah penerima</a>
<p></p>
<?php echo $this->session->flashdata('msg');?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
		<thead>
			<tr>
				<th width="25%">Nama Penerima</th>
				<th width="20%">Alamat</th>
				<th width="15%">Telp</th>
				<th width="20%">Email</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
