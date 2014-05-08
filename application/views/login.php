<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html> <!--<![endif]-->
    <head>
        <base href="<?php echo base_url(); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta charset="utf-8"/>
        <meta name="description" content="">
        <meta name="author" content="">

        <title>TJE SYSTEM AUTH</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
<!--        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>-->

    </head>
    <body>
        <div class="container-narrow">
            <div class="row-fluid">
                <div class="span9">
                    <?php if (isset($message)): ?>
                        <div class="message">
                            <i class="icon-remove"></i>
                            <?php echo $message; ?>
                        </div>    
                        <?php
                    endif;
                    ?>
                    <div class="well log">
                        <form action="dashboard/dologin" method="post">
                            <!--                            <legend>LOGIN ADMIN</legend>-->
                            <div class="img_login"><img src="assets/img/login_logo_blue_transparent.png" width="333" height="100"/></div>
                            <center> <h6><i>Please Sign in Your Administrator</i></h6></center>
                            <br>
                            <div class="control-group">
                                <label class="control-label" for="inputUsername">Username</label>
                                <div class="controls">
                                    <input type="text" id="inputUsername" class="span12" placeholder="Username" name="tje_username" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputPassword">Password</label>
                                <div class="controls">
                                    <input type="password" id="inputPassword" class="span12" placeholder="Password" name="tje_password" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <button type="submit" class="btn btn-large btn-block btn-primary">Sign in</button>
                                </div>
                            </div>


                        </form>
                    </div><!--/.well -->

                </div><!--/span-->
            </div>
        </div>
    </body>
</html>
