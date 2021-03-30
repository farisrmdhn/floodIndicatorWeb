<div class="row">
	<div class="col-md-12">	
		<h1>Dashboard</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<small>Last Updated on <?php echo $last_updated; ?> </small>
		<p id="dboard_waterLevel">Water Level: <span style="<?php echo $dboard_waterLevel['color']; ?> font-weight: bold;"><?php echo $dboard_waterLevel['avg']; ?></span></p>
	</div>
</div>
<div class="row">
	<div class="col-md-6" style="height: 700px; overflow: scroll;">
		<?php foreach($detectors as $detector):?>
		<div class="col-md-12">
			<a href="<?php echo base_url(); ?>detector/<?php echo $detector['id']?>">	
			<div class="card col-md-12 detector_card">
				<div class="card-body">
					<h3 class="card-title"><?php echo $detector['name']?></h3>
					<div class="row">
						<div id="detector_card_badge" class="col-md-5">
							<span class="badge badge-pill badge-secondary"><?php echo $detector['id']?></span>
							<span class="badge badge-pill badge-warning">Last Updated: <?php echo $detector['last_updated']?></span>
							<p class="detector_card_location">Location: <?php echo $detector['latitude']?>, <?php echo $detector['longitude']?></p>
							<p class="detector_card_wLevel">Water Level: <span style="<?php echo $detector['wLevel_color']; ?> font-weight: bold;"><?php echo $detector['wLevel']?></p>
						</div>
						<div class="row col-md-7 detector_card_right">
							<img src="<?php echo base_url(); ?>assets/images/weather/<?php echo $detector['pic']?>">
							<div class="detector_card_weather">
								<p>Temprature: <?php echo $detector['temprature']?>&deg; C</p>
								<p>Humidity: <?php echo $detector['humidity']?> %</p>
								<p><strong>Weather: <?php echo $detector['weather']?></strong></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<?php endforeach;?>
		<audio id="siren" controls loop hidden>
			<source src="<?php echo base_url();?>assets/sirens/siren.wav" type="audio/wav">
			Your browser does not support the audio element.
		</audio>
	</div>
	<div class="col-md-6">
		
    	<div id="map" style="height: 100%;"></div>

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
	var x = document.getElementById("siren");

	function playAudio() {
	  x.play();
	}

	function pauseAudio() {
		x.pause();
	}
</script>

<script>

	function initMap() {
		// Center of map
		var map_center = {lat: -6.208909, lng: 106.842553};

		var map = new google.maps.Map(document.getElementById('map'), {
			center: map_center,
			zoom: 12
		});

		var infoWindow = new google.maps.InfoWindow;

		downloadUrl('<?php echo base_url(); ?>detectors/maps_data', function(data) {

			var xml = data.responseXML;
			var markers = xml.documentElement.getElementsByTagName('marker');


			Array.prototype.forEach.call(markers, function(markerElem) {
				var id = markerElem.getAttribute('id');
				var name = markerElem.getAttribute('name');
				var wLevel = markerElem.getAttribute('wLevel');
				var weather = markerElem.getAttribute('weather');
				var temprature = markerElem.getAttribute('temprature');
				var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

				var infowincontent = document.createElement('div');
				var strong = document.createElement('strong');
				strong.textContent = name
				infowincontent.appendChild(strong);
				infowincontent.appendChild(document.createElement('br'));

				var waterLevel = document.createElement('text');
				waterLevel.textContent = wLevel
				infowincontent.appendChild(waterLevel);
				infowincontent.appendChild(document.createElement('br'));

				var weathertemp = document.createElement('text');
				weathertemp.textContent = weather + ', ' + temprature + ' C'
				infowincontent.appendChild(weathertemp);

				var marker = new google.maps.Marker({
					map: map,
					position: point
				});

				marker.addListener('click', function() {
				  infoWindow.setContent(infowincontent);
				  infoWindow.open(map, marker);
				});
			});
		});
	}



	function downloadUrl(url, callback) {
		var request = window.ActiveXObject ?
			new ActiveXObject('Microsoft.XMLHTTP') :
			new XMLHttpRequest;

		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				request.onreadystatechange = doNothing;
				callback(request, request.status);
			}
		};

		request.open('GET', url, true);
		request.send(null);
	}

	function doNothing() {}
</script>
<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAf7PhURzWNew7hwBV_mOX_1GJTwuow3vk&callback=initMap">
</script>


