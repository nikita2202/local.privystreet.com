
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo BASE_URL;?>/node_modules/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo BASE_URL;?>/node_modules/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo BASE_URL;?>/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL;?>/js/global-msg.js"></script>
    <script src="<?php echo BASE_URL;?>/js/common-validation.js"></script>
    <script src="<?php echo BASE_URL;?>/js/jquery.validate.min.js"></script>
    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
    </script>
    
</body>

</html>