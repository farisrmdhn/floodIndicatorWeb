<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
	public function dashboard(){
		$header['active'] = 'dashboard';

		// Get all detectors data
		$data['ids'] = $this->detector_model->get_detector_ids();
		$data['wLevels'] = $this->input_model->get_latest_waterLevel();
		$data['dboard_waterLevel'] = $this->input_model->get_latest_avg_waterLevel($data['wLevels']);
		$data['last_updated'] = $this->input_model->get_last_updated();
		$data['weathers'] = $this->detector_model->get_detector_card();

		$this->load->view('templates/header.php', $header);
		$this->load->view('pages/dashboard.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function about() {

		$header['active'] = 'about';

		$this->load->view('templates/header.php', $header);
		$this->load->view('pages/about.php');
		$this->load->view('templates/footer.php');
	}

	public function help() {
		
		$header['active'] = 'help';

		$this->load->view('templates/header.php', $header);
		$this->load->view('pages/help.php');
		$this->load->view('templates/footer.php');
	}

	public function old_index(){
		$header['active'] = 'dashboard';

		//Get counts of entries and images
		$data['inputs'] = $this->float_sensor_model->get_all_inputs();

		$this->load->view('templates/header.php', $header);
		$this->load->view('pages/old_index.php', $data);
		$this->load->view('templates/footer.php');
	}

	// START -  BAGIAN INI DI MASUKIN KONTROLER API

	//Page for receiving post data from ethernet shield
	public function add(){
		$detector_id = $this->input->post('detector_id');
		$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';
		$detector = $this->detector_model->get_detectors($detector_id);
		$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?lat='.$detector['latitude'].'&lon='.$detector['longitude'].'&units=metric&appid='.$api_key);
		$weather = json_decode($json,true);

		$this->input_model->add($weather);

		$id = $this->input->post('detector_id');
		$sensor_1 = $this->input->post('sensor_1');
		$sensor_2 = $this->input->post('sensor_2');
		$sensor_3 = $this->input->post('sensor_3');
		$alert = 'no alert';

		// Check for alerting condition (danger / error)
		if($sensor_1 == '0'){
			if($sensor_2 == '1' || $sensor_3 == '1'){
				// 0 1 1 or 0 1 0 or 0 0 1
				$alert = 'error';
			}
		}else{
			if($sensor_2 == '0'){
				// 1 0 1
				if($sensor_3 == '1'){
					$alert = 'error';
				}
			}else{
				if($sensor_3 != '0'){
					$alert = 'danger';
				}
			}
		}

		// Send Notification
		if($alert != 'no alert') {
			$this->send_notification($id, $alert);
		}

		redirect('manual_input');
	}

	private function send_notification($id, $alert) {
		$emails = $this->user_model->get_emails();

		$this->notification_model->add_notification($id, $alert);

		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://mail.farisramadhan.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'floodindicator@farisramadhan.com',
		    'smtp_pass' => 'MjtcaPuYVfyF',
		    'mailtype'  => 'html', 
		    'charset'   => 'iso-8859-1'
		);

		$this->load->library('email', $config);
		$this->email->from('floodindicator@farisramadhan.com', 'Flood Indicator - ALERT');
		$this->email->to($emails);

		$subject = '';
		$message = '';
		if($alert == 'error') {
			$subject = 'Error detected on '.$id;
			$message = '<p>ALERT!!! Error detected on Detector: '.$id.'. Please check immediately</p>';
		} elseif($alert == 'danger') {
			$subject = 'Dangerous water level detected on '.$id;
			$message = '<p>ALERT!!! Dangerous water level detected on Detector: '.$id.'. Please check immediately and take necessary actions.</p>';
		} 

		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send($emails);
	}

	public function send_mobile_notification() {
		$key = "AAAAcbN2nAY:APA91bHA7d6b-Cn0vSo7xIuKf-8ykj5V2JE4eZ6ie-WGJMOPly0eL_nKJzv5Z1q5eKaXZbznl-71r2F-Lz2Xnqh-fvqfi3QXqAkGykoljaIP7ACvvDnNrx4w8WeP6QIhqs2uqEDDToIx";

		$fcm_url = 'https://fcm.googleapis.com/fcm/send';

		$token = "cG95SaEGQRCwiO43Z1AeUS:APA91bF1VWhxRdFcRNzqM1_kB9IniPsisKdaUnpU9C5hR2b_zYgLfniFTDLhYX6tej7Xvw99qNLFzGIm09pO4gkXLPaOU9HM0ZELXF36Gpi9DBe4NJCC1yZ_Bq1G5WyN8-Gulfi7HV9j";

		$notification = [
			// 'title' => $title,
			// 'body' => $body,
			'title' => "TITLE",
			'body' => 'BODY',
			'click_action' => "FLUTTER_NOTIFICATION_CLICK"
		];

		$data = [
			"name" => "farisramadhan",
			"age" => "22"
		];

		$fcmNotification = [
			//'registration_ids' => $tokenList, //multple token array
			'to' => $token, //single token
			'notification' => $notification,
			'data' => $data
		];

		$headers = [
			'Authorization: key=' . $key,
			'Content-Type: application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$fcm_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
		$result = curl_exec($ch);
		curl_close($ch);


		echo $result;
	}

	// END -  BAGIAN INI DI MASUKIN KONTROLER API

	public function manual_input(){
		$data['detectors'] = $this->detector_model->get_detectors();

		$this->load->view('pages/manual_input.php', $data);
	}

	public function test(){
		$input = $this->input->post('date_from');
		$date = date("d/m/Y", strtotime($this->input->post('date_from')))." 00:00:00";
		echo strval($date);
	}

	public function get_wl_history($id, $date_from = FALSE, $date_to = FALSE) {
		if(!$date_from && !$date_to) {
			$date_from = date('Y-m-d', strtotime('-30 days'));
			$date_to = date('Y-m-d');
		}
		$inputs = $this->input_model->get_wl_history($id, $date_from, $date_to);

		echo json_encode($inputs);
		die();
	}

	// API
	public function getLatestInput($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$inputs = $this->input_model->get_latest_inputs();

			$wLevels = $this->input_model->get_latest_waterLevel();
			$avg_wlevel = $this->input_model->get_latest_avg_waterLevel($wLevels)['avg'];

			$last_updated = $this->input_model->get_last_updated_API();

			echo json_encode([[$avg_wlevel, $last_updated], $inputs]);
		}

	}

	public function getInputMonthlyHistory($detector_id, $date_from, $date_to, $api_key){
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$inputs = $this->input_model->get_weather_history($detector_id, $date_from, $date_to);

			echo json_encode($inputs);
		}
	}

	public function getInputsDailyHistory($detector_id, $date, $api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$inputs = $this->input_model->get_daily_weather($detector_id, $date);
			echo json_encode($inputs);
		}
	}
}
