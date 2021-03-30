<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Flood Indicator</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login_style.css">

    <!-- Poppins Font -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/poppins.css">

    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="<?php echo base_url(); ?>assets/js/solid.js"></script>
    <script defer src="<?php echo base_url(); ?>assets/js/fontawesome.js"></script>

</head>

<body>

	<?php if($this->session->flashdata('flashdata_danger')): ?>
        <?php echo '<p class="alert alert-danger flashData">'.$this->session->flashdata('flashdata_danger').'</p>'; ?>
    <?php endif; ?>

    <div class="text-center">
		<?php echo form_open('forgot_password'); ?>

			<div class="row">
				<div id="login_card" class="col-md-4 offset-md-4 card">
					<div id="login_card_top">
						<div class="col-md-12">
			                <img src="<?php echo base_url(); ?>assets/images/logo.png">
						</div>
					</div>
					<h4 class="text-center">Reset your password</h4>
					<p>Send password reset request via email. Please enter your email below:</p>
					<div class="form-group">
						<input type="email" name="email" class="form-control" placeholder=" Enter Email" required autofocus>
					</div>
					<button id="submit_btn" type="submit" class="btn btn-block">Send Request</button>
					<div class="text-left">
						<a href="<?php echo base_url(); ?>login">
							<p id="forgot_link">Back</p>
						</a>
					</div>
				</div>
			</div>

		<?php echo form_close(); ?>
	</div>

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
        setTimeout($('.flashData').css('display', 'none'), 5000);
    });
</script>
</html>

