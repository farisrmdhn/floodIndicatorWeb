<div class="row">
	<div class="col-md-12">	
		<h1>Profile</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-6 card profile_card">

		<div class="profile_card_top row">
			<div class="col-md-4 text-center">
				<img class="profile_picture" src="<?php echo base_url();?>assets/images/profile/<?php echo $user['picture']?>">
			</div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<h1 class="profile_card_name"><?php echo $user['name']?></h1>
						<small><strong>Created on:</strong> <?php echo date('d-m-Y', strtotime($user['created']))?>, <strong>Last Login:</strong> <?php echo date('d-m-Y h:i:s', strtotime($user['last_login']))?></small>
					</div>
				</div>

				<div class="profile_card_badge">
					<div class="row">
						<div class="col-md-12">
							<span class="badge badge-pill badge-warning "><?php echo $user['id']?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php if($user['user_type'] == 1):?>
								<span class="badge badge-pill badge-secondary ">User</span>
							<?php else:?>
								<span class="badge badge-pill badge-secondary ">Admin</span>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- table -->
		<div class="profile_card_table row">
			<table class="col-md-12">
				<tbody>
					<tr>
						<td><p>Email</p></td>
						<td><p>:</p></td>
						<td><p><?php echo $user['email']?></p></td>
					</tr>
					<tr>
						<td><p>Phone</p></td>
						<td><p>:</p></td>
						<td><p><?php echo $user['phone']?></p></td>
					</tr>
					<tr>
						<td><p>Address</p></td>
						<td><p>:</p></td>
						<td><p><?php echo $user['address']?></p></td>
					</tr>
				</tbody>			
			</table>
		</div>

		<div class="profile_card_bottom row">
			<div class="col-md-12">
				<a href="<?php echo base_url(); ?>change_password" class="float-right btn btn-danger">Change Password</a>
				<a href="<?php echo base_url(); ?>edit_profile" class="float-right btn btn-info">Edit Profile</a>
			</div>
		</div>
	</div>
</div>