<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        var oTable = $('#tje-table1').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sPaginationType": "full_numbers",
            "sAjaxSource": BASEURL + "users/get_data",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records display"
            },
            'fnServerData': function (sSource, aoData, fnCallback) {
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
<h3>Kelola User</h3>
<hr>
<a href="<?php echo base_url('users/add');?>" class="btn btn-primary"><i class="icon-plus-sign"></i>Tambah Data</a>
<p></p>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tje-table1">
    <thead>
        <tr>
            <th width="20%">User Name</th>
            <th width="25%">Company</th>
            <th width="25%">Email</th>
            <th width="15%">Aktif</th>
            <th width="7%">Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
