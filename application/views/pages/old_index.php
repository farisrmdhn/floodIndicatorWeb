
	<div class="container">
		<h1>Flood Indicator Input Log</h1>
		<p>Detects water level using float sensors and send the data with web client using ethernet shield. Reads every 3 seconds.</p>
		<ul>
			<li class="text-success">Sensor 1: Lowest Point - SAFE</li>
			<li class="text-danger">Sensor 2: Middle Point - WARNING</li>
			<li class="text-primary">Sensor 3: Highest Point - Danger</li>
		</ul>

		<table class="table table-hover">
			<thead>
		  		<tr class="table-info">
		    		<th scope="col">Timestamp</th>
		    		<th scope="col">Id</th>
		    		<th scope="col">Sensor 1</th>
		    		<th scope="col">Sensor 2</th>
		    		<th scope="col">Sensor 3</th>
		    		<th scope="col">Weather</th>
		    		<th scope="col">Temprature</th>
		    		<th scope="col">Status</th>
				</tr>
			</thead>
		  	<tbody>
		  		<?php foreach($inputs as $input):?>
		    	<tr class="table-default">
		      		<th scope="row"><?php echo $input['timestamp']; ?></th>
		      		<td><?php echo $input['detector_id']; ?></td>
		      		<td><?php echo $input['sensor_1']; ?></td>
		      		<td><?php echo $input['sensor_2']; ?></td>
		      		<td><?php echo $input['sensor_3']; ?></td>
		      		<td><?php echo $input['weather']; ?></td>
		      		<td><?php echo $input['temprature']; ?></td>
		      		<td><?php echo $input['status']; ?></td>
		    	</tr>
		   		<?php endforeach;?>
		  	</tbody>
		</table> 
	</div>