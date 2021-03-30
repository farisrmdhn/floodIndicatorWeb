<a href="<?php echo base_url();?>detectors"><p id="details_back_button">&#8592; Back</p></a>
<div class="row">
	<div class="col-md-6">
		<div class="card col-md-12 detector_card_details">
			<div class="card-body">
				<h1 class="card-title"><?php echo $detector['name']?></h1>
				<div class="row">
					<div class="col-md-5">
						<div id="detector_card_badge_details">
							<span class="badge badge-pill badge-secondary"><?php echo $detector['id']?></span>
							<span class="badge badge-pill badge-warning">Last Updated: <?php echo $detector['last_updated']?></span>
						</div>
						<p class="detector_card_location_details">Location: <?php echo $detector['latitude']?>, <?php echo $detector['longitude']?></p>
						<p class="detector_card_wLevel_details">Water Level: <span style="<?php echo $detector['wLevel_color']; ?> font-weight: bold; font-size: 50px;"><?php echo $detector['wLevel']?></span></p>
					</div>
					<div class="col-md-7">
						<h5>Latest Weather</h5>
						<div class="row detector_card_right_details">
							<div class="col-md-4 detector_card_weather_image">
								<img src="<?php echo base_url(); ?>assets/images/weather/<?php echo $detector['pic']?>">
								<p id="card_weather"><?php echo $detector['weather']?></p>
								<p id="card_weather_desc"><?php echo $detector['weather_desc']?></p>
							</div>
							<div class="col-md-8 detector_card_weather_details">
								<p>Temprature: <?php echo $detector['temprature']?>&deg; C</p>
								<p>Pressure: <?php echo $detector['pressure']?> hPa</p>
								<p>Humidity: <?php echo $detector['humidity']?> %</p>
								<?php if($detector['cloudiness'] != 0): ?>
								<p>Cloudiness: <?php echo $detector['cloudiness']?> %</p>
								<?php else: ?>
								<p>Rain Volume: <?php echo $detector['rain_vol'] ?> mm</p>
								<?php endif;?>

								<p>Wind Speed: <?php echo $detector['wind_speed']?> m/s</p>
								<p>Wind Direction: <?php echo $detector['wind_dir']?>&deg;</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="map" class="col-md-6"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="row details_choose">
			<h3 id="wl_history" onclick="chooseWL()" class="choose_active">Water Level History</h3>
			<h3 id="weather_history" onclick="chooseWeather()">Weather History</h3>
		</div>
	</div>	
	<div class="col-md-12" style="margin-left: 20px;">
		<div class="row">
			<select id="time_select" class="form-control col-md-1">
				<option onclick="chooseMonthly()">Monthly</option>
				<option onclick="chooseDaily()">Daily</option>
			</select>
			<div class="col-md-11">
				<div class="row">

					<div id="choose_default" class="btn btn_choose_active monthly_inputs">Last 30 Days</div>
					<div id="choose_custom" class="btn btn_choose monthly_inputs">Custom</div>

					<input class="form-control col-md-2 history_date monthly_inputs" type="date" id="date_from" style="margin-right: 20px; margin-left: 10px; display: none;" value="<?php echo date('Y-m-d', strtotime('-30 days'));?>">
					<input class="form-control col-md-2 history_date monthly_inputs" type="date" id="date_to" value="<?php echo date('Y-m-d'); ?>" style="display:none">

					<div id="go_btn" class="btn btn_choose history_date monthly_inputs" style="display: none;">Go</div>

					<div id="choose_default_daily" class="btn btn_choose_active daily_inputs" style="display:none">This day</div>
					<div id="choose_custom_daily" class="btn btn_choose daily_inputs" style="display:none">Custom</div>

					<input class="form-control col-md-2 daily_inputs custom_daily" type="date" id="date" value="<?php echo date('Y-m-d'); ?>" style="display:none">

					<div id="go_btn_daily" class="btn btn_choose daily_inputs custom_daily" style="display: none;">Go</div>

				</div>
			</div>
		</div>
	</div>

	<div id="history_chart" class="card" style="width:100%;margin-left: 20px;max-height: 300px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
		<canvas id="details_canvas" height="300"></canvas>
	</div>

	<div id="history_table" class="card" style="display: none;margin-left: 20px;width:100%; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
		<table class="table table-hover text-center">
			<thead>
		  		<tr class="table-info">
		    		<th scope="col">Date</th>
		    		<th scope="col">Status</th>
		    		<th scope="col">Weather</th>
		    		<th scope="col">Temprature</th>
		    		<th scope="col">Pressure</th>
		    		<th scope="col">Humidity</th>
		    		<th scope="col">Wind Speed</th>
		    		<th scope="col">Wind Direction</th>
		    		<th scope="col">Cloudiness</th>
		    		<th scope="col">Rain Volume</th>
				</tr>
			</thead>
		  	<tbody id="weather_data">
		  	</tbody>
		</table> 
	</div>

	
</div>

<p id="test"></p>

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

<!-- GMAPS -->
<script>
 var lat = parseFloat("<?php echo $detector['latitude']?>");
 var lng = parseFloat("<?php echo $detector['longitude']?>");
// Initialize and add the map
function initMap() {
		// The location of Uluru
		var uluru = {lat: lat, lng: lng};
		// The map, centered at Uluru
		var map = new google.maps.Map(
		document.getElementById('map'), {zoom: 16, center: uluru});
		// The marker, positioned at Uluru
		var marker = new google.maps.Marker({position: uluru, map: map});
	}
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=XXXXXXXX&callback=initMap">
</script>

<!-- CHARTS -->
<script type="text/javascript">
	var id = "<?php echo $detector['id']?>" ;
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	var date = $('#date').val();

	var inputs = $(".history_date");
	var custom_btn = $("#choose_custom");
	var default_btn = $("#choose_default");
	var go_btn = $("#go_btn");

	var display = 'wLevel';

	$(document).ready(function(){
		chooseWL();
		// $('#test').html(date_to);
	});

	//Monthly -Custom
	custom_btn.click(function() {
		inputs.css("display", "block");

		custom_btn.removeClass("btn_choose");
		custom_btn.addClass("btn_choose_active");

		default_btn.removeClass("btn_choose_active");
		default_btn.addClass("btn_choose");
	});

	go_btn.click(function() {
		var custom_date_from = $('#date_from').val();
		var custom_date_to = $('#date_to').val();

		if(display == 'wLevel') {
			show_monthly_wlevel(custom_date_from, custom_date_to);
		} else{
			show_monthly_weather(custom_date_from, custom_date_to);
		}
	});

	//Monthly - Default
	default_btn.click(function() {
		inputs.css("display", "none");

		default_btn.removeClass("btn_choose");
		default_btn.addClass("btn_choose_active");

		custom_btn.removeClass("btn_choose_active");
		custom_btn.addClass("btn_choose");

		if(display == 'wLevel') {
			show_monthly_wlevel(date_from, date_to);
		} else{
			show_monthly_weather(date_from, date_to);
		}
	});

	// Monthly
	function chooseMonthly() {
		custom_btn.css("display", "block");
		default_btn.css("display", "block");


		inputs.css("display", "none");

		default_btn.removeClass("btn_choose");
		default_btn.addClass("btn_choose_active");

		custom_btn.removeClass("btn_choose_active");
		custom_btn.addClass("btn_choose");

		$('.daily_inputs').css('display', 'none');

		if(display == 'wLevel') {
			show_monthly_wlevel(date_from, date_to);
		} else{
			show_monthly_weather(date_from, date_to);
		}
	}

	// Daily
	function chooseDaily() {

		$('#choose_custom_daily').css("display", "none");
		$('#choose_default_daily').css("display", "none");

		$('#choose_default_daily').removeClass("btn_choose");
		$('#choose_default_daily').addClass("btn_choose_active");

		$('#choose_custom_daily').removeClass("btn_choose_active");
		$('#choose_custom_daily').addClass("btn_choose");

		$('.monthly_inputs').css('display', 'none');
		$('.daily_inputs').css('display', 'block');

		$('.custom_daily').css("display", "none");

		if(display == 'wLevel') {
			show_daily_wlevel(date);
		} else{
			show_daily_weather(date);
		}
	}

	//Daily -Custom
	$('#choose_custom_daily').click(function() {
		$('.custom_daily').css("display", "block");

		$('#choose_custom_daily').removeClass("btn_choose");
		$('#choose_custom_daily').addClass("btn_choose_active");

		$('#choose_default_daily').removeClass("btn_choose_active");
		$('#choose_default_daily').addClass("btn_choose");
	});

	$('#go_btn_daily').click(function() {
		var custom_date = $('#date').val();
		if(display == 'wLevel') {
			show_daily_wlevel(custom_date);
		} else{
			show_daily_weather(custom_date);
		}
	});

	//Daily - Default
	$('#choose_default_daily').click(function() {
		$('.custom_daily').css("display", "none");

		$('#choose_default_daily').removeClass("btn_choose");
		$('#choose_default_daily').addClass("btn_choose_active");

		$('#choose_custom_daily').removeClass("btn_choose_active");
		$('#choose_custom_daily').addClass("btn_choose");

		if(display == 'wLevel') {
			show_daily_wlevel(date);
		} else{
			show_daily_weather(date);
		}
	});


	function chooseWeather() {
		display = 'weather';

		$('#time_select').val('Monthly');

		$("#wl_history").removeClass("choose_active");

		$("#weather_history").addClass("choose_active");

		$('#history_chart').css('display', 'none');
		$('#history_table').css('display', 'block');

		// $('#test').html(display);


		chooseMonthly();
	}

	function chooseWL() {
		display = 'wLevel';

		$('#time_select').val('Monthly');

		$("#weather_history").removeClass("choose_active");
		$("#wl_history").addClass("choose_active");

		$('#history_chart').css('display', 'block');
		$('#history_table').css('display', 'none');

		chooseMonthly();
	}

	function show_monthly_wlevel(df, dt) {
		$.ajax({
			type: 'ajax',
			url: '<?php echo base_url(); ?>detectors/get_wl_history/'+ id + '/' + df + '/' + dt,
			async: true,
			dataType: 'json',
			success: function(inputs){
				console.log('Water Levels: ' + df +' to ' + dt);

				init_wl_history_chart(inputs[0], inputs[1]);
			}
		});
	}

	function show_daily_wlevel(d) {
		$.ajax({
			type: 'ajax',
			url: '<?php echo base_url(); ?>detectors/get_daily_wl/'+ id + '/' + d,
			async: true,
			dataType: 'json',
			success: function(inputs){
				console.log('Daily Water Level: ' + d);

				init_wl_history_chart(inputs[0], inputs[1]);
			}
		});
	}

	function show_monthly_weather(df, dt) {
		$.ajax({
			type: 'ajax',
			url: '<?php echo base_url(); ?>detectors/get_weather_history/'+ id + '/' + df + '/' + dt,
			async: true,
			dataType: 'json',
			success: function(inputs){
				console.log('Weather: ' + df +' to ' + dt);

				init_weather_table(inputs);
			}
		});
	}

	function show_daily_weather(d) {
		$.ajax({
			type: 'ajax',
			url: '<?php echo base_url(); ?>detectors/get_daily_weather/'+ id + '/' + d,
			async: true,
			dataType: 'json',
			success: function(inputs){
				console.log('Daily Weather: ' + d);

				init_weather_table(inputs);
			}
		});
	}

	function init_wl_history_chart(dates, wlevels) {

		if( typeof (Chart) === 'undefined'){
			return; 
		}

		if ($('#details_canvas').length){
			var yLabels = {
		   		0: 'Error', 1 : 'Safe', 2 : 'Warning', 3 : 'Danger'
			}

			var config = {
				type: 'line',
				data: {
					labels: dates,
					datasets: [{
						label: 'Water Level',
						backgroundColor: '#e0f2f1',
						borderColor: 'teal',
						data: wlevels,
						fill: false,
					}]
				},
				options: {
					maintainAspectRatio: false,
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
								display: false,
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

			var ctx = $('#details_canvas');
			if(window.myLine != undefined) {
				window.myLine.destroy();
			}

			window.myLine = new Chart(ctx, config);
		}
	}

	function init_weather_table(table_data){
		var html = '';
		if(table_data == null) {
			html = 'Data not available';
		}else {
			for(var i = 0; i < table_data.length; i++){
				html += '<tr class="table-default">' +
				      		'<th scope="row">' + table_data[i]['date'] + '</th>' +
				      		'<td>' + table_data[i]['wlevel'] + '</td>' +
				      		'<td>' + table_data[i]['weather'] + '</td>' +
				      		'<td>' + table_data[i]['temprature'] + '&deg; C</td>' +
				      		'<td>' + table_data[i]['pressure'] + ' hPa</td>' +
				      		'<td>' + table_data[i]['humidity'] + ' %</td>' +
				      		'<td>' + table_data[i]['wind_speed'] + ' m/s</td>' +
				      		'<td>' + table_data[i]['wind_dir'] + '&deg;</td>' +
				      		'<td>' + table_data[i]['cloudiness'] + ' %</td>' +
				      		'<td>' + table_data[i]['rain_vol'] + ' mm</td>' +
				    	'</tr>'
			}
		}
		$('#weather_data').html(html);
	}
</script>

