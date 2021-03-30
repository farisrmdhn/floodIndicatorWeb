<?php
class Input_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function add($weather){
		$data = array(
			'detector_id' => $this->input->post('detector_id'),
			'sensor_1' => $this->input->post('sensor_1'),
			'sensor_2' => $this->input->post('sensor_2'),
			'sensor_3' => $this->input->post('sensor_3'),
			'weather' => $weather['weather'][0]['main'],
			'temprature' => $weather['main']['temp'],
			'weather_desc' => $weather['weather'][0]['description'],
			'pressure' => $weather['main']['pressure'],
			'humidity' => $weather['main']['humidity'],
			'wind_speed' => $weather['wind']['speed'],
			'wind_dir' => $weather['wind']['deg']
		);

		if($weather['weather'][0]['main'] = 'Clouds') {
			$data['cloudiness'] = $weather['clouds']['all'];
		} elseif ($weather['weather'][0]['main'] == 'Rain') {
			$data['rain_vol'] = $weather['rain']['rain_1h'];
		}

		return $this->db->insert('inputs', $data);
	}

	public function get_latest_waterLevel() {
		$sql = 'SELECT DISTINCT detector_id FROM inputs ;';
		$detector_ids = $this->db->query($sql)->result_array();

		$inputs = array();

		foreach ($detector_ids as $id) {
			$sql = 'SELECT sensor_1, sensor_2, sensor_3 FROM inputs WHERE detector_id = "'.$id['detector_id'].'" ORDER BY timestamp DESC LIMIT 1 ;' ;

			$data = $this->db->query($sql)->row_array();

			if($data['sensor_1'] == '0'){
				if($data['sensor_2'] == '1' || $data['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$input = 0;
					array_push($inputs, $input);
				}else{
					// 0 0 0
					$input = 1;
					array_push($inputs, $input);
				}
			}else{
				if($data['sensor_2'] == '0'){
					if($data['sensor_3'] == '1'){
						// 1 0 1
						$input = 0;
						array_push($inputs, $input);
					}else{
						// 1 0 0
						$input = 1;
						array_push($inputs, $input);
					}
				}else{
					if($data['sensor_3'] == '0'){
						// 1 1 0
						$input = 2;
						array_push($inputs, $input);
					}else{
						// 1 1 1
						$input = 3;
						array_push($inputs, $input);
					}
				}
			}
		}


		return $inputs;
	}

	public function get_latest_avg_waterLevel($inputs){
		$count = 0;
		$sum = 0;

		foreach ($inputs as $input) {
			if($input != 0) {
				$sum += $input;
				$count += 1;
			}
		}

		$avg = ceil($sum / $count);
		$color = "color: red;";

		if ($avg == 1) {
			$avg = 'SAFE';
			$color = "color: green;";

		} elseif ($avg == 2) {
			$avg = 'WARNING';
			$color = "color: yellow;";
		} else {
			$avg = 'DANGER';
		}

		$result = array(
			'avg' => $avg,
			'color' => $color
		);

		return $result;
	}

	public function get_last_updated() {
		$sql = 'SELECT timestamp FROM inputs ORDER BY timestamp DESC LIMIT 1';
		$db = $this->db->query($sql)->row_array()['timestamp'];
		$timestamp = strtotime($db);
		$result = date("d/m/Y h:i:s", $timestamp);

		return $result;
	}

	public function get_last_updated_API() {
		$sql = 'SELECT timestamp FROM inputs ORDER BY timestamp DESC LIMIT 1';
		return $this->db->query($sql)->row_array()['timestamp'];
	}

	public function get_wl_history($detector_id, $date_from, $date_to) {
		$date_from = strval($date_from." 00:00:00");
		$date_to = strval($date_to." 23:59:59");
		$sql = 'SELECT timestamp, sensor_1, sensor_2, sensor_3 FROM inputs WHERE detector_id = "'.$detector_id.'" && timestamp >= "'.$date_from.'" && timestamp <= "'.$date_to.'" ORDER BY timestamp ;';

		$results =  $this->db->query($sql)->result_array();
		$inputs = array();

		if(sizeof($results) < 1){
			return array([0],[0]);
		}

		$date = date("Y-m-d", strtotime($results[0]['timestamp']));
		$max = 0;
		$count = 0;

		foreach ($results as $data) {

			$wlevel = 0;


			if($data['sensor_1'] == '0'){
				if($data['sensor_2'] == '1' || $data['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$wlevel = 4;
				}else{
					// 0 0 0
					$wlevel = 1;
				}
			}else{
				if($data['sensor_2'] == '0'){
					if($data['sensor_3'] == '1'){
						// 1 0 1
						$wlevel = 4;
					}else{
						// 1 0 0
						$wlevel = 1;
					}
				}else{
					if($data['sensor_3'] == '0'){
						// 1 1 0
						$wlevel = 2;
					}else{
						// 1 1 1
						$wlevel = 3;
					}
				}
			}

			if($date == date("Y-m-d", strtotime($data['timestamp']))) {
				if($wlevel > $max) {
					$max = $wlevel;
				}
			} else {
				$inputs[$count]['date'] = $date;
				if ($max == 4) {
					$max = 0;
				}
				$inputs[$count]['wlevel'] = $max;
				$date = date("Y-m-d", strtotime($data['timestamp']));
				$count++;
				$max = 0;
			}
		}

		//For the last one
		$inputs[$count]['date'] = $date;
		if ($max == 4) {
			$max = 0;
		}
		$inputs[$count]['wlevel'] = $max;

		$date = array();
		$wlevel = array();

		foreach ($inputs as $input) {
			array_push($date, $input['date']);
			array_push($wlevel, $input['wlevel']);
		}

		return [$date, $wlevel];
	}

	public function get_daily_wl($detector_id, $date){
		$time_from = strval($date." 00:00:00");
		$time_to = strval($date." 23:59:59");
		$sql = 'SELECT timestamp, sensor_1, sensor_2, sensor_3 FROM inputs WHERE detector_id = "'.$detector_id.'" && timestamp >= "'.$time_from.'" && timestamp <= "'.$time_to.'" ORDER BY timestamp ;';

		$results =  $this->db->query($sql)->result_array();
		$inputs = array();

		if(sizeof($results) < 1){
			return array([0],[0]);
		}

		foreach ($results as $data) {
			$input['time'] = date('h:i:s', strtotime($data['timestamp']));

			if($data['sensor_1'] == '0'){
				if($data['sensor_2'] == '1' || $data['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$input['wlevel'] = 0;
				}else{
					// 0 0 0
					$input['wlevel'] = 1;
				}
			}else{
				if($data['sensor_2'] == '0'){
					if($data['sensor_3'] == '1'){
						// 1 0 1
						$input['wlevel'] = 0;
					}else{
						// 1 0 0
						$input['wlevel'] = 1;
					}
				}else{
					if($data['sensor_3'] == '0'){
						// 1 1 0
						$input['wlevel'] = 2;
					}else{
						// 1 1 1
						$input['wlevel'] = 3;
					}
				}
			}

			array_push($inputs, $input);
		}

		$time = array();
		$wlevel = array();

		foreach ($inputs as $input) {
			array_push($time, $input['time']);
			array_push($wlevel, $input['wlevel']);
		}

		return [$time, $wlevel];
	}

	public function get_weather_history($detector_id, $date_from, $date_to) {
		$date_from = strval($date_from." 00:00:00");
		$date_to = strval($date_to." 23:59:59");
		$sql = 'SELECT * FROM inputs WHERE detector_id = "'.$detector_id.'" && timestamp >= "'.$date_from.'" && timestamp <= "'.$date_to.'" ORDER BY timestamp ;';

		$results =  $this->db->query($sql)->result_array();
		$inputs = array();

		if(sizeof($results) < 1){
			return null;
		}

		$date = date("Y-m-d", strtotime($results[0]['timestamp']));
		$max = 0;
		$count = 0;
		// 1 = clear, 2 = clouds, 3 = rain, 4 = storm
		$weather = 0;
		$temp_weather = 'Clear';
		$wcount = 0;

		$temp_sum = 0;
		$pres_sum = 0;
		$humi_sum = 0;
		$wisp_sum = 0;
		$widi_sum = 0;
		$clds_sum = 0;
		$ravo_sum = 0;

		foreach ($results as $data) {

			$wlevel = 0;
			$weath = 0;


			if($data['sensor_1'] == '0'){
				if($data['sensor_2'] == '1' || $data['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$wlevel = 4;
				}else{
					// 0 0 0
					$wlevel = 1;
				}
			}else{
				if($data['sensor_2'] == '0'){
					if($data['sensor_3'] == '1'){
						// 1 0 1
						$wlevel = 4;
					}else{
						// 1 0 0
						$wlevel = 1;
					}
				}else{
					if($data['sensor_3'] == '0'){
						// 1 1 0
						$wlevel = 2;
					}else{
						// 1 1 1
						$wlevel = 3;
					}
				}
			}

			if($data['weather'] == 'Clear') {
				$weath = 1;
			}elseif($data['weather'] == 'Clouds') {
				$weath = 2;
			} elseif($data['weather'] == 'Haze') {
				$weath = 3;
			} elseif($data['weather'] == 'Rain') { 
				$weath = 4;
			} else {
				$weath = 5; 
			}

			$wcount++;

			$temp_sum += floatval($data['temprature']);
			$pres_sum += floatval($data['pressure']);
			$humi_sum += floatval($data['humidity']);
			$wisp_sum += floatval($data['wind_speed']);
			$widi_sum += floatval($data['wind_dir']);
			$clds_sum += floatval($data['cloudiness']);
			$ravo_sum += floatval($data['rain_vol']);

			if($date == date("Y-m-d", strtotime($data['timestamp']))) {
				if($wlevel > $max) {
					$max = $wlevel;
				}

				if($weath > $weather) {
					$weather = $weath;
					$temp_weather = $data['weather'];
				}

			} else {
				$inputs[$count]['date'] = $date;
				if ($max == 4) {
					$max = 0;
				}
				if($max == 0) {
					$inputs[$count]['wlevel'] = 'Error';
				} elseif ($max == 1) {
					$inputs[$count]['wlevel'] = 'Safe';
				} elseif ($max == 2) {
					$inputs[$count]['wlevel'] = 'Warning';
				} else {
					$inputs[$count]['wlevel'] = 'Danger';
				}
				$inputs[$count]['weather'] = $temp_weather;
				$inputs[$count]['temprature'] = round(($temp_sum / floatval($wcount)), 2);
				$inputs[$count]['pressure'] = round(($pres_sum / floatval($wcount)), 2);
				$inputs[$count]['humidity'] = round(($humi_sum / floatval($wcount)), 2);
				$inputs[$count]['wind_speed'] = round(($wisp_sum / floatval($wcount)), 2);
				$inputs[$count]['wind_dir'] = round(($widi_sum / floatval($wcount)), 2);
				$inputs[$count]['cloudiness'] = round(($clds_sum / floatval($wcount)), 2);
				$inputs[$count]['rain_vol'] = round(($ravo_sum / floatval($wcount)), 2);


				$date = date("Y-m-d", strtotime($data['timestamp']));
				$count++;

				$max = 0;
				$weather = 0;
				$temp_weather = 'Clear';

				// $wcount = 0;

				// $temp_sum = 0;
				// $pres_sum = 0;
				// $humi_sum = 0;
				// $wisp_sum = 0;	
				// $widi_sum = 0;
				// $clds_sum = 0;
				// $ravo_sum = 0;
			}
		}

		//For the last one
		$inputs[$count]['date'] = $date;
		if ($max == 4) {
			$max = 0;
		}
		if($max == 0) {
			$inputs[$count]['wlevel'] = 'Error';
		} elseif ($max == 1) {
			$inputs[$count]['wlevel'] = 'Safe';
		} elseif ($max == 2) {
			$inputs[$count]['wlevel'] = 'Warning';
		} else {
			$inputs[$count]['wlevel'] = 'Danger';
		}
		$inputs[$count]['weather'] = $temp_weather;
		$inputs[$count]['temprature'] = round(($temp_sum / floatval($wcount)), 2);
		$inputs[$count]['pressure'] = round(($pres_sum / floatval($wcount)), 2);
		$inputs[$count]['humidity'] = round(($humi_sum / floatval($wcount)), 2);
		$inputs[$count]['wind_speed'] = round(($wisp_sum / floatval($wcount)), 2);
		$inputs[$count]['wind_dir'] = round(($widi_sum / floatval($wcount)), 2);
		$inputs[$count]['cloudiness'] = round(($clds_sum / floatval($wcount)), 2);
		$inputs[$count]['rain_vol'] = round(($ravo_sum / floatval($wcount)), 2);

		return $inputs;
	}

	public function get_daily_weather($detector_id, $date){
		$time_from = strval($date." 00:00:00");
		$time_to = strval($date." 23:59:59");
		$sql = 'SELECT * FROM inputs WHERE detector_id = "'.$detector_id.'" && timestamp >= "'.$time_from.'" && timestamp <= "'.$time_to.'" ORDER BY timestamp ;';

		$results =  $this->db->query($sql)->result_array();
		$inputs = array();

		if(sizeof($results) < 1){
			return null;
		}

		foreach ($results as $data) {
			$input['date'] = $data['timestamp'];

			if($data['sensor_1'] == '0'){
				if($data['sensor_2'] == '1' || $data['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$input['wlevel'] = 'Error';
				}else{
					// 0 0 0
					$input['wlevel'] = 'Safe';
				}
			}else{
				if($data['sensor_2'] == '0'){
					if($data['sensor_3'] == '1'){
						// 1 0 1
						$input['wlevel'] = 'Error';
					}else{
						// 1 0 0
						$input['wlevel'] = 'Safe';
					}
				}else{
					if($data['sensor_3'] == '0'){
						// 1 1 0
						$input['wlevel'] = 'Warning';
					}else{
						// 1 1 1
						$input['wlevel'] = 'Danger';
					}
				}
			}

			$input['weather'] = $data['weather'];
			$input['temprature'] = $data['temprature'];
			$input['pressure'] = $data['pressure'];
			$input['humidity'] = $data['humidity'];
			$input['wind_speed'] = $data['wind_speed'];
			$input['wind_dir'] = $data['wind_dir'];
			$input['cloudiness'] = $data['cloudiness'];
			$input['rain_vol'] = $data['rain_vol'];

			array_push($inputs, $input);
		}

		return $inputs;
	}

	public function get_latest_inputs() {
		$sql = 'SELECT DISTINCT id FROM detectors ;';
		$ids = $this->db->query($sql)->result_array();

		$data = array();

		foreach ($ids as $id) {
			$input = $this->input_model->latest_inputs($id['id']);

			array_push($data, $input);
		}


		return $data;
	}

	public function latest_inputs($id) {
		$sql = 'SELECT i.id, i.detector_id, i.sensor_1, i.sensor_2, i.sensor_3, i.weather, i.temprature, i.weather_desc, i.pressure, i.humidity, i.wind_speed, i.wind_dir, i.cloudiness, i.rain_vol, i.timestamp FROM inputs i WHERE i.detector_id = "'.$id.'" ORDER BY timestamp DESC LIMIT 1 ;';

		$res = $this->db->query($sql)->row_array();

		$pic = '';
		$day = 'day';

		$hour = date('H', strtotime($res['timestamp']));

		if( $hour < 6 && $hour >= 18) {
		  $day = 'night';
		}

		if($res['weather'] == 'Clear') {
			$pic = 'clear_'.$day.'.png';
		} elseif ($res['weather'] == 'Clouds') {
			$pic = 'cloudy_'.$day.'.png';
		} elseif ($res['weather'] == 'Rain') {
			$pic = 'rainy_'.$day.'.png';
		} elseif ($res['weather'] == 'Haze') {
			$pic = 'haze_'.$day.'.png';
		} else {
			$pic = 'storm_'.$day.'.png';
		}

		$data = array(
			'id' => $res['id'],
			'detector_id' => $id,
			'weather' => $res['weather'],
			'weather_desc' => $res['weather_desc'],
			'temprature' => $res['temprature'],
			'pressure' => $res['pressure'],
			'humidity' => $res['humidity'],
			'wind_speed' => $res['wind_speed'],
			'wind_dir' => $res['wind_dir'],
			'cloudiness' => $res['cloudiness'],
			'rain_vol' => $res['rain_vol'],
			'pic' => $pic,
			'last_updated' =>  $res['timestamp']
		);

		if($res['sensor_1'] == '0'){
			if($res['sensor_2'] == '1' || $res['sensor_3'] == '1'){
				// 0 1 1 or 0 1 0 or 0 0 1
				$data['wLevel'] = 'ERROR';
			}else{
				// 0 0 0
				$data['wLevel'] = 'SAFE';
			}
		}else{
			if($res['sensor_2'] == '0'){
				if($res['sensor_3'] == '1'){
					// 1 0 1
					$data['wLevel'] = 'ERROR';
				}else{
					// 1 0 0
					$data['wLevel'] = 'SAFE';
				}
			}else{
				if($res['sensor_3'] == '0'){
					// 1 1 0
					$data['wLevel'] = 'WARNING';
				}else{
					// 1 1 1
					$data['wLevel'] = 'DANGER';
				}
			}
		}

		return $data;
	}
}