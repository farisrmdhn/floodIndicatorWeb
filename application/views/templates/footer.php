
    
</div><!--Wrapper-->
</body>
<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.min.js"></script>
<!-- CHART JS + Moment Bundle-->
<script src="<?php echo base_url(); ?>assets/js/Chart.bundle.min.js"></script>
<!-- Popper.JS -->
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<!-- jQuery Custom Scroller CDN -->
<script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#sidebar").mCustomScrollbar({
            theme: "minimal"
        });

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar, #content').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });
</script>
</html>