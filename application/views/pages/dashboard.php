<div class="row">
	<div class="col-md-12">	
		<h1>Dashboard</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<small>Last Updated on <?php echo $last_updated; ?> </small>
		<p id="dboard_waterLevel">Water Level: <span style="<?php echo $dboard_waterLevel['color']; ?> font-weight: bold;"><?php echo $dboard_waterLevel['state']; ?></span></p>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<h4>Latest Input</h4>
	</div>

	<div class="col-md-6">
		<h4>Weather</h4>
	</div>
</div>
<div class="row">
	<div id="dboard_chart" class="card col-md-6">
		<canvas id="dboard_canvas"></canvas>
	</div>
	<div id ="dboard_weather" class="col-md-6">
		<div class="row">
			<?php foreach($weathers as $weather):?>
			<div id="dboard_weather_card" class="card col-md-3">
				<div class="card-body">
					<small><?php echo $weather['id'] ;?></small>
					<h6 class="card-title"><?php echo $weather['name'] ;?></h6>
					<div class="row">
						<img src="<?php echo base_url(); ?>assets/images/weather/<?php echo $weather['pic']; ?>">
						<div>
							<p>Temprature: <?php echo $weather['temprature'] ;?> C</p>
							<p>Humidity: <?php echo $weather['humidity'] ;?> %</p>
							<p>Weather: <?php echo $weather['weather'] ;?></p>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
</div>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.min.js"></script>
<!-- CHART JS + Moment Bundle-->
<script src="<?php echo base_url(); ?>assets/js/Chart.bundle.min.js"></script>
<!-- Popper.JS -->
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<!-- jQuery Custom Scroller CDN -->
<script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

<script type="text/javascript">
    // Dashboard Chart
    $(document).ready(function(){
		dboard_chart();
	});

    function dboard_chart() {
        if( typeof (Chart) === 'undefined'){
            return; 
        }

        console.log('init_dboard_chart');

        if ($('#dboard_canvas').length){
            var yLabels = {
                0: 'Error', 1 : 'Safe', 2 : 'Warning', 3 : 'Danger'
            }
            var config = {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($ids) ;?>,
                    datasets: [{
                        label: 'Water Level',
                        backgroundColor: '#e0f2f1',
                        borderColor: 'teal',
                        data: <?php echo json_encode($wLevels) ;?>,
                        fill: false,
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    responsive: true,
                    title: {
                        display: false,
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Detectors'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Water Level'
                            },
                            ticks: {
                                min: 0,
                                stepSize: 1,
                                max: 3,
                                callback: function(value, index, values) {
                                    return yLabels[value];
                                }
                            }
                        }]
                    }
                }
            };
	        var ctx = document.getElementById('dboard_canvas').getContext('2d');
	        window.myLine = new Chart(ctx, config);    
        }
    }
</script>
