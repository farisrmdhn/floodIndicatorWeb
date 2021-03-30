<form action="<?php echo base_url(); ?>add" method="post">
	<select name="detector_id">
		<?php foreach($detectors as $detector):?>
			<option value="<?php echo $detector['id']; ?>"><?php echo $detector['name']?></option>
		<?php endforeach;?>
	</select>
	<input type="text" name="sensor_1" value="1">
	<input type="text" name="sensor_2" value="0">
	<input type="text" name="sensor_3" value="0">
	<input type="submit" value="submit">
</form>

<form action="<?php echo base_url(); ?>pages/get_wl_history" method="post">
	<input type="date" name="date_from">
	<input type="date" name="date_to">
	<input type="submit" value="submit">
</form>