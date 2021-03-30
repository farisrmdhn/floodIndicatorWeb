<?php
class Detector_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function get_detectors($id = FALSE) {
		$sql = 'SELECT * FROM detectors; ' ;

		if($id) {
			$sql = 'SELECT * FROM detectors WHERE id = "'.$id.'" ;' ;

			return $this->db->query($sql)->row_array();
		}

		return $this->db->query($sql)->result_array();
	}

	public function get_detector_ids() {
		$sql = 'SELECT id FROM detectors; ';

		$results = $this->db->query($sql)->result_array();

		$ids = array();

		foreach ($results as $res) {
			array_push($ids, $res['id']);
		}

		return $ids;
	}

	public function get_detector_card() {
		$sql = 'SELECT DISTINCT id FROM detectors ;';
		$ids = $this->db->query($sql)->result_array();

		$data = array();

		foreach ($ids as $id) {
			$input = $this->detector_model->get_latest_input($id['id']);

			array_push($data, $input);
		}


		return $data;
	}

	public function get_latest_input($id) {
		$sql = 'SELECT d.id as detector_id, d.name, d.latitude, d.longitude, i.id, i.sensor_1, i.sensor_2, i.sensor_3, i.weather, i.temprature, i.weather_desc, i.pressure, i.humidity, i.wind_speed, i.wind_dir, i.cloudiness, i.rain_vol, i.timestamp FROM detectors d INNER JOIN inputs i ON d.id = i.detector_id WHERE d.id = "'.$id.'" ORDER BY timestamp DESC LIMIT 1 ;';

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
			'id' => $id,
			'name' => $res['name'],
			'latitude' => $res['latitude'],
			'longitude' => $res['longitude'],
			'weather' => $res['weather'],
			'weather_desc' => $res['weather_desc'],
			'temprature' => $res['temprature'],
			'pressure' => $res['pressure'],
			'humidity' => $res['humidity'],
			'wind_speed' => $res['wind_speed'],
			'wind_dir' => $res['wind_dir'],
			'cloudiness' => $res['cloudiness'],
			'rain_vol' => $res['rain_vol'],
			'api_timestamp' => $res['timestamp'],
			'last_updated' =>  date("d/m/Y h:i:s", strtotime($res['timestamp'])),
			'pic' => $pic
		);

		if($res['sensor_1'] == '0'){
			if($res['sensor_2'] == '1' || $res['sensor_3'] == '1'){
				// 0 1 1 or 0 1 0 or 0 0 1
				$data['wLevel'] = 'ERROR';
				$data['wLevel_color'] = 'color: red;';
			}else{
				// 0 0 0
				$data['wLevel'] = 'SAFE';
				$data['wLevel_color'] = 'color: green;';
			}
		}else{
			if($res['sensor_2'] == '0'){
				if($res['sensor_3'] == '1'){
					// 1 0 1
					$data['wLevel'] = 'ERROR';
					$data['wLevel_color'] = 'color: red;';
				}else{
					// 1 0 0
					$data['wLevel'] = 'SAFE';
					$data['wLevel_color'] = 'color: green;';
				}
			}else{
				if($res['sensor_3'] == '0'){
					// 1 1 0
					$data['wLevel'] = 'WARNING';
					$data['wLevel_color'] = 'color: orange;';
				}else{
					// 1 1 1
					$data['wLevel'] = 'DANGER';
					$data['wLevel_color'] = 'color: red;';
				}
			}
		}

		return $data;
	}

}