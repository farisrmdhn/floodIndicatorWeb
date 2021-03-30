<?php
class Float_sensor_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function get_all_inputs(){
		$sql = 'SELECT * FROM inputs ORDER BY timestamp DESC;' ;
		$results = $this->db->query($sql)->result_array();

		//Generate status based on inputs ($result['sensor_2'] == '1' || $result['sensor_3'] == '1')
		$inputs = array();
		foreach ($results as $result){
			$data = array(
				'detector_id' => $result['detector_id'],
				'timestamp' => $result['timestamp'],
				'sensor_1' => $result['sensor_1'],
				'sensor_2' => $result['sensor_2'],
				'sensor_3' => $result['sensor_3'],
				'weather' => $result['weather'],
				'temprature' => $result['temprature'],
				'water_level' => ''
			);

			if($result['sensor_1'] == '0'){
				if($result['sensor_2'] == '1' || $result['sensor_3'] == '1'){
					// 0 1 1 or 0 1 0 or 0 0 1
					$data['status'] = 'Error';
					array_push($inputs, $data);
				}else{
					// 0 0 0
					$data['status'] = 'Safe';
					array_push($inputs, $data);
				}
			}else{
				if($result['sensor_2'] == '0'){
					if($result['sensor_3'] == '1'){
						// 1 0 1
						$data['status'] = 'Error';
						array_push($inputs, $data);
					}else{
						// 1 0 0
						$data['status'] = 'Safe';
						array_push($inputs, $data);
					}
				}else{
					if($result['sensor_3'] == '0'){
						// 1 1 0
						$data['status'] = 'Warning';
						array_push($inputs, $data);
					}else{
						// 1 1 1
						$data['status'] = 'Danger';
						array_push($inputs, $data);
					}
				}
			}
		}

		return $inputs;
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

	public function get_detector_names(){
		$sql = 'SELECT id, name FROM detectors' ;

		return $this->db->query($sql)->result_array();
	}

	public function get_location($detector_id) {
		$sql = 'SELECT latitude, longitude FROM detectors WHERE id = "'.$detector_id.'" ;' ;

		return $this->db->query($sql)->row_array();
	}

	public function get_detectors() {
		$sql = 'SELECT * FROM detectors; ' ;

		return $this->db->query($sql)->result_array();
	}
}