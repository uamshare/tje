<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <base href="<?php echo base_url(); ?>" />
        <title>TJE || APPLICATION</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <link href="assets/css/bootstrap-responsive.css" rel="stylesheet" />
        
        <link rel="stylesheet" type="text/css" href="media/DT_bootstrap.css" />
        <script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="assets/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" language="javascript" src="media/js/jquery.jeditable.js"></script>
        <script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" language="javascript" src="media/DT_bootstrap.js"></script>

        <!-- Jquery Tree View -->
        <link rel="stylesheet" type="text/css" href="assets/css/jquery.treeview.css" />
        <script type="text/javascript" src="assets/js/plugins/jquery.treeview.js"></script>
        <!--<link rel="stylesheet" type="text/css" href="assets/css/style.css" />-->
        <script>
            var BASEURL = "<?php echo base_url(); ?>";
            function deleteData(obj){
                return window.confirm("Data akan dihapus ?");
            }
            function toRP(angka){
                var rev = parseInt(angka,10).toString().split('').reverse().join('');
                var rev2 = '';
                for(var i=0;i<rev.length;i++){
                    rev2 += rev[i];
                    if((i + 1) % 3 === 0 && i !==(rev.length - 1)){
                        rev2 += '.';
                    }
                }
                return rev2.split('').reverse().join('');
            }
        </script>
        <link href="assets/css/datepicker.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="media/css/style.css" />
    </head>
    <body>

        <div class="container-narrow">

            <div class="row-fluid">
                <div class="header">
                    <div class="span6 title-logo ">
                        <img class="img_login"  src="media/images/administrator.png" width="140" height="140"/>
                        <p class="title-admin">TJE <br> APPLICATION</p>
                    </div>
                    <div class="span6 info-login">
                        <font style="color: white;" class="btn btn-large btn-primary disabled">
                        <?php echo " Selamat Datang, " .$this->session->userdata(SESS_PREFIK . 'nama')." &nbsp;&nbsp;|| <a class='logout' href='dashboard/dologout'><b>Logout</b></a>"; ?>
                        </font>
                        <!--<a class="btn btn-danger logout" type="button" href="dashboard/dologout" style="float: right;">Logout</a>-->
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="row-fluid all-contents">
                <div class="span3 well sidebar-costum">
                    <!--Sidebar content-->
                    <?php $this->load->view('menu'); ?> 
                </div>
                <div class="span9 well the-contents">
                    <!--Body content-->
                    <?php $this->load->view($content); ?> 
                </div>

            </div>
        </div>
        <div class="footer">
            <br />
            <i><b>&copy; 2014</b></i>
        </div>
    </body>
</html>