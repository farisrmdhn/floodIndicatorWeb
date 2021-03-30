<div class="row">
	<div class="col-md-12">
		<h1>Create New User</h1>
	</div>
</div>
<a href="<?php echo base_url();?>profile"><p id="details_back_button">&#8592; Back</p></a>
<div class="row">
	<div class="col-md-6 card edit_profile_card">
		<?php echo form_open_multipart('create_user');?>
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="email" name="email" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" name="password" class="form-control" minlength="6" required>
			</div>
			<div class="form-group">
				<label>Confirm Password</label>
				<input type="password" name="password2" class="form-control" required>
			</div>

			<div class="form-group">
				<label>Phone Number</label>
				<input type="text" name="phone" class="form-control" required>
			</div>
			<div class="form-group">
				<label>Address</label>
				<textarea name="address" class="form-control" required=""></textarea> 
			</div>
			<div class="form-group">
				<label>User Type</label>
				<select name="user_type" class="form-control" required="">
					<option value="1" selected="">User</option>
					<option value="0">Admin</option>
				</select>
			</div>
			<div class="form-group">
				<label>Upload Image</label>
				<small class="text-danger">* JPG, JPEG, PNG only</small>
				<br />
				<input type="file" name="picture" class="form-control" size="20">
			</div>

  			<button type="submit" class="btn btn-success float-right">Create</button>
		</form>
	</div>
</div>