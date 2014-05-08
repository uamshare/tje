<script type="text/javascript" charset="utf-8">
	var oTable
        $(document).ready(function() {
		oTable = $('#example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sPaginationType": "full_numbers",
			"sAjaxSource": "<?php echo base_url();?>terima_muat/get_data",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records"
			},
			'fnServerData': function (sSource, aoData, fnCallback) {
				aoData.push( { 'name' : 'date_min', 'value' : $("#datefrom0").val() } );
                aoData.push( { 'name' : 'date_max', 'value' : $("#dateto0").val() } );
				$.ajax({
					'dataType': 'json',
					'type': 'POST',
					'url': sSource,
					'data': aoData,
					'success': fnCallback
				});
			}
		});
		$('#datefrom0, #dateto0').keyup( function() {
			oTable.fnDraw();
		});
	});
</script>
<script type="text/javascript" src="media/js/daterange.js"></script>
<h3>TANDA TERIMA MUAT BARANG</h3>
<hr>
<a href="<?php echo base_url();?>terima_muat/add/" class="pull-right btn btn-primary">Tambah Terima Muat Barang</a>
<p></p>
<div class="filter-table">
    <table>
        <tr>
            <th>
        <div class="input-append date" id="datefrom" data-date="" data-date-format="yyyy-mm-dd">
            <input class="" id="datefrom0" size="80" type="text" value="<?php echo date('Y-m-d');?>" />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        </th>
        <th><p>s/d</p></th>
        <th>
            <div class="input-append date" id="dateto" data-date="" data-date-format="yyyy-mm-dd">
            <input class="" id="dateto0" size="16" type="text" value="<?php echo date('Y-m-d');?>">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        </th>
        </tr>
    </table>
</div>
<?php echo $this->session->flashdata('msg');?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
		<thead>
			<tr>
				<th width="10%">No. Muat</th>
				<th width="10%">Tgl Muat</th>
				<th width="15%">No. Polisi</th>
				<th width="20%">Nama Pengirim</th>
				<th width="20%">Nama Penerima</th>
				<th width="10%">No. Terima</th>
				<th width="10%">Status</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
