<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		var oTable = $('#example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			// "bFilter": false,
			// "bPaginate": false,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "<?php echo base_url();?>kendaraan/get_data",
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
<h3>Kendaraan</h3>
<hr>
<a href="<?php echo base_url();?>kendaraan/form/" class="btn btn-primary">Tambah kendaraan</a>
<p></p>
<?php echo $this->session->flashdata('msg');?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
		<thead>
			<tr>
				<th width="20%">No. Polisi</th>
				<th width="25%">Merk</th>
				<th width="10%">Jenis</th>
				<th width="10%">Warna</th>
				<th width="25%">Nama Supir</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
