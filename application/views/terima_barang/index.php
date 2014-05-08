<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            // "bFilter": false,
            // "bPaginate": false,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "<?php echo base_url(); ?>terima_barang/get_data",
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
<h3>TANDA TERIMA BARANG</h3>
<hr>
<a href="<?php echo base_url(); ?>terima_barang/add/" class="btn btn-primary">Tambah Terima Barang</a>
<p></p>
<?php echo $this->session->flashdata('msg'); ?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
    <thead>
        <tr>
            <th width="15%">No. Terima</th>
            <th width="15%">Tgl Terima</th>
            <th width="25%">Nama Penerima</th>
            <th width="25%">Nama Pengirim</th>
            <th width="10%">Status</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
