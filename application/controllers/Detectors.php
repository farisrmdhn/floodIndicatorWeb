<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detectors extends CI_Controller {
	public function index() {
		//Check login
		// if(!$this->session->userdata('logged_in')){
		// 	redirect('login');
		// }

		$header['active'] = 'detectors';

		$data['detectors'] = $this->detector_model->get_detector_card();
		
		$data['wLevels'] = $this->input_model->get_latest_waterLevel();

		$data['dboard_waterLevel'] = $this->input_model->get_latest_avg_waterLevel($data['wLevels']);

		$data['last_updated'] = $this->input_model->get_last_updated();

		$this->load->view('templates/header.php', $header);
		$this->load->view('detectors/index.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function detector_details($id) {
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
		
		$header['active'] = null;

		$data['detector'] = $this->detector_model->get_latest_input($id);

		$this->load->view('templates/header.php', $header);
		$this->load->view('detectors/detector_details.php', $data);
		$this->load->view('templates/footer.php');
	}

	private function parseToXML($htmlStr) {
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}

	public function maps_data() {
		$detectors = $this->detector_model->get_detector_card();
		header("Content-type: text/xml");

		echo "<?xml version='1.0' ?>";
		echo '<markers>';

		foreach($detectors as $detector) {
			echo '<marker ';
		  	echo 'id="' . $this->parseToXML($detector['id']) . '" ';
			echo 'name="' . $this->parseToXML($detector['name']) . '" ';
		  	echo 'wLevel="' . $this->parseToXML($detector['wLevel']) . '" ';
		  	echo 'weather="' . $this->parseToXML($detector['weather']) . '" ';
		  	echo 'temprature="' . $this->parseToXML($detector['temprature']) . '" ';
		  	echo 'lat="' . $detector['latitude'] . '" ';
		  	echo 'lng="' . $detector['longitude'] . '" ';	
		  	echo '/>';
		}
		// End XML file
		echo '</markers>';
	}

	//AJAX
	public function get_wl_history($id, $date_from, $date_to) {
		$inputs = $this->input_model->get_wl_history($id, $date_from, $date_to);
		
		echo json_encode($inputs);
	}

	public function get_weather_history($id, $date_from, $date_to) {
		$inputs = $this->input_model->get_weather_history($id, $date_from, $date_to);
		
		echo json_encode($inputs);
	}


	public function get_daily_wl($id, $date = FALSE) {
		if(!$date) {
			$date = date('Y-m-d');
		}
		$inputs = $this->input_model->get_daily_wl($id, $date);
		
		echo json_encode($inputs);
	}

	public function get_daily_weather($id, $date = FALSE) {
		if(!$date) {
			$date = date('Y-m-d');
		}
		$inputs = $this->input_model->get_daily_weather($id, $date);
		
		echo json_encode($inputs);
	}

	// API
	public function getDetectors($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$detectors = $this->detector_model->get_detectors();

			echo json_encode($detectors);
		}
	}

	public function getDetectorById($detector_id, $api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$detector = $this->detector_model->get_latest_input($detector_id);

			echo json_encode($detector);
		}
	}
}