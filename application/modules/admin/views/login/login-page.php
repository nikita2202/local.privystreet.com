

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Admin Wrap</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="login-register login-sidebar" style="background-image:url(<?php echo BASE_URL; ?>/images/background/login-register.jpg);">
        <div class="login-box card">
            <div class="card-body">
                <!--                <form class="form-horizontal form-material" id="loginform" action="index.html">-->
                <?php echo form_open_multipart('', array('id' => 'loginform', 'class' => 'form-horizontal form-material')); ?>
                <a href="javascript:void(0)" class="text-center db"><img src="<?php echo BASE_URL; ?>/images/logo-icon.png" alt="Home" /><br/><img src="<?php echo BASE_URL; ?>/images/logo-text.png" alt="Home" /></a>
                <div class="form-group m-t-40">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" placeholder="Email" name="email">
                        <?php echo form_error('email', '<label class="alert-danger">', '</label>'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" placeholder="Password" name="password">
                        <?php echo form_error('password', '<label class="alert-danger">', '</label>'); ?>                        </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">

                        <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot Password?</a> </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <input class="btn btn-info btn-lg btn-block text-uppercase btn-rounded" type="submit">
                    </div>
                </div>


                <?php echo form_close(); ?>
                <form class="form-horizontal" id="recoverform" action="index.html">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
