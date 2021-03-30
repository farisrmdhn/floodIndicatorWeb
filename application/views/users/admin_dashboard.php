<div class="row">
	<div class="col-md-12">
		<h1>Administrator Dashboard</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-8 card admin_dboard_card">
		<table class="table">
			<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Email</th>
					<th>Last Login</th>
					<th class="col-md-1"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($users as $user): ?>
					<tr>
						<td><p><?php echo $user['id']?></p></td>
						<td><p><?php echo $user['name']?></p></td>
						<td><p><?php echo $user['email']?></p></td>
						<td><p><?php echo $user['last_login']?></p></td>
						<td>
							<a href="<?php echo base_url(); ?>user_details/<?php echo $user['id']?>" class="btn btn-primary">Details</a>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>