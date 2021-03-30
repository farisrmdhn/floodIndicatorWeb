<div class="row">
	<div class="col-md-12">
		<h1>Edit User</h1>
	</div>
</div>
<a href="<?php echo base_url();?>user_details/<?php echo $user['id']; ?>"><p id="details_back_button">&#8592; Back</p></a>
<div class="row">
	<div class="col-md-6 card edit_profile_card">
		<?php echo form_open_multipart('update_user');?>

            <?php echo validation_errors(); ?>
			<div class="form-group">
				<label>Id</label>
				<input type="text" name="id" class="form-control" value="<?php echo $user['id']; ?>" readonly required>
			</div>
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
			</div>
			<div class="form-group">
				<label>Phone Number</label>
				<input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
			</div>
			<div class="form-group">
				<label>Address</label>
				<textarea name="address" class="form-control" required=""><?php echo $user['address']; ?></textarea> 
			</div>
			<div class="form-group">
				<label>New Password</label>
				<small class="text-danger">Only fill this field if you want to change <?php echo $user['name']?>'s password</small>
				<input type="password" name="new_password"  minlength="6" class="form-control">
			</div>
			<div class="form-group">
				<label>Confirm new password</label>
				<input type="password" name="new_password2" class="form-control">
			</div>
			<div class="form-group">
				<label>Upload Image</label>
				<small class="text-danger">* JPG, JPEG, PNG only</small>
				<br />
				<input type="file" name="picture" class="form-control" size="20">
			</div>

			<input type="hidden" name="old_picture" value="<?php echo $user['picture']?>">
			<input type="hidden" name="old_password" value="<?php echo $user['password']?>">

  			<button type="submit" class="btn btn-info float-right">Update</button>
		</form>
	</div>
</div>