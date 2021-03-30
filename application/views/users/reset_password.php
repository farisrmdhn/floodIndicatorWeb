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
        <?php echo '<p class="alert alert-danger">'.$this->session->flashdata('flashdata_danger').'</p>'; ?>
    <?php endif; ?>

    <div class="text-center">
		<?php echo form_open('reset_password'); ?>

			<div class="row">
				<div id="login_card" class="col-md-4 offset-md-4 card">
					<div id="login_card_top">
						<div class="col-md-12">
			                <img src="<?php echo base_url(); ?>assets/images/logo.png">
						</div>
					</div>
					<h4 class="text-center">Reset your password</h4>
					<div class="form-group text-left">
						<label>Name</label>
						<input type="text" name="name" class="form-control" value="<?php echo $user['name']?>" readonly>
					</div>
					<div class="form-group text-left">
						<label>New password</label>
						<input type="password" name="new_password" minlength="6" class="form-control" required autofocus>
					</div>
					<div class="form-group text-left">
						<label>Confirm New password</label>
						<input type="password" name="new_password2" class="form-control"  required>
					</div>
					
					<input type="hidden" name="id" class="form-control" value="<?php echo $user['id']?>">
					<button id="submit_btn" type="submit" class="btn btn-block">Reset password</button>
				</div>
			</div>

		<?php echo form_close(); ?>
	</div>

</body>
</html>

