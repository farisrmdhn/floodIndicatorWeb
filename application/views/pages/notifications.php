<div class="row">
	<div class="col-md-12">	
		<h1>Notifications</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-8 card notifications_card">
		<table class="col-md-12">
			<thead>
				<tr class="table">
					<th>Id</th>
					<th>Time Stamp</th>
					<th>Message</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($notifications as $notification):?>
					<tr>
						<td>
							<?php if($notification['is_new'] == 'true'):?>
							<p><strong><?php echo $notification['id']; ?></strong></p>
							<?php else:?>
							<p><?php echo $notification['id']; ?></p>
							<?php endif;?>
						</td>
						<td>
							<?php if($notification['is_new'] == 'true'):?>
							<p><strong><?php echo date('d-m-Y h:i:s', strtotime($notification['timestamp'])); ?></strong></p>
							<?php else:?>
							<p><?php echo date('d-m-Y h:i:s', strtotime($notification['timestamp'])); ?></p>
							<?php endif;?>
						</td>
						<td>
							<?php if($notification['is_new'] == 'true'):?>
								<?php if($notification['type'] == 0):?>
								<p><strong>Error detected on <a href="<?php echo base_url(); ?>detector/<?php echo $notification['detector_id']?>"><?php echo $notification['detector_id']?></a></strong></p>
								<?php else:?>
								<p><strong>Dangerous water level on <a href="<?php echo base_url(); ?>detector/<?php echo $notification['detector_id']?>"><?php echo $notification['detector_id']?></a></strong></p>
								<?php endif;?>
							<?php else:?>
								<?php if($notification['type'] == 0):?>
								<p>Error detected on <a href="<?php echo base_url(); ?>detector/<?php echo $notification['detector_id']?>"><?php echo $notification['detector_id']?></a></p>
								<?php else:?>
								<p>Dangerous water level on <a href="<?php echo base_url(); ?>detector/<?php echo $notification['detector_id']?>"><?php echo $notification['detector_id']?></a></p>
								<?php endif;?>
							<?php endif;?>
							</td>
						<td>
							<?php if($notification['is_new'] == 'true'):?>
							<form method="post" action="<?php echo base_url();?>pages/update_notification">
								<input type="hidden" name="id" value="<?php echo $notification['id']?>">
								<input type="submit" name="submit" value="OK" class="btn btn-success">
							</form>
							<?php else:?>
							<form method="post" action="<?php echo base_url();?>pages/delete_notification">
								<input type="hidden" name="id" value="<?php echo $notification['id']?>">
								<input type="submit" name="submit" value="DELETE" class="btn btn-danger">
							</form>
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>