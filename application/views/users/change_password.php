<div class="row">
	<div class="col-md-12">
		<h1>Change Password</h1>
	</div>
</div>
<a href="<?php echo base_url();?>profile"><p id="details_back_button">&#8592; Back</p></a>
<div class="row">
	<div class="col-md-6 card edit_profile_card">

		<?php echo validation_errors(); ?>
		<?php echo form_open('change_password');?>
			<div class="form-group">
				<label>Password</label>
				<input type="password" name="old_password" class="form-control"required autofocus>
			</div>
			<div class="form-group">
				<label>New Password</label>
				<input type="password" name="new_password" class="form-control" minlength="6" required>
			</div>
			<div class="form-group">
				<label>Confirm New Password</label>
				<input type="password" name="new_password2" class="form-control" required>
			</div>

  			<button type="submit" class="btn btn-danger float-right">Change Password</button>
		</form>
	</div>
</div>