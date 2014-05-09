<script type="text/javascript" charset="utf-8">
    var oTable;
    $(document).ready(function() {
        oTable = $('#tje-table1').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sPaginationType": "full_numbers",
            "sAjaxSource": BASEURL + "penagihan_ongkos/get_data",
            "sPaginationType": "bootstrap",
            "aaSorting": [[ 1, "desc" ]],
            "oLanguage": {
                "sLengthMenu": "_MENU_ records display"
            },
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push( { 'name' : 'date_min', 'value' : $("#datefrom0").val() } );
                aoData.push( { 'name' : 'date_max', 'value' : $("#dateto0").val() } );
                $.ajax
                ({
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
<script type="text/javascript" src="media/js/daterange.js"></script>
<h3>PENAGIHAN ONGKOS</h3>
<hr>
<a href="<?php echo base_url('penagihan_ongkos/add'); ?>" class="btn btn-primary"><i class="icon-plus-sign"></i>Tambah Data</a>
<p></p>
<div class="filter-table">
    <table>
        <tr>
            <th>
        <div class="input-append date" id="datefrom" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input class="" id="datefrom0" size="80" type="text" value="<?php echo date('Y-m-d'); ?>" />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        </th>
        <th><p>s/d</p></th>
        <th>
        <div class="input-append date" id="dateto" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input class="" id="dateto0" size="16" type="text" value="<?php echo date('Y-m-d'); ?>">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
        </th>
        </tr>
    </table>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tje-table1">
    <thead>
        <tr>
            <th width="10%">TGL</th>
            <th width="10%">NO</th>
            <th width="10%">NO CROSSCEK</th>
            <th width="10%">TGL CROSSCEK</th>
            <th width="10%">NOPOL</th>
            <th >KET</th>
            <th width="10%">AKSI</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
