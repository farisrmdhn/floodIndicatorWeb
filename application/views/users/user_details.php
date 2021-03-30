<div class="row">
	<div class="col-md-12">	
		<h1>User Details</h1>
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
				<button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal" style="margin-left: 10px;">Delete User</button>
				<a href="<?php echo base_url(); ?>edit_user/<?php echo $user['id']?>" class="float-right btn btn-info">Edit User</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		<form method="post" action="<?php echo base_url(); ?>delete_user">
			<input type="hidden" name="id" value="<?php echo $user['id']?>">
			<input type="submit" name="submit" value="Delete" class="btn btn-danger float-right" style="margin-left: 10px;">
		</form>
      </div>
    </div>
  </div>
</div>