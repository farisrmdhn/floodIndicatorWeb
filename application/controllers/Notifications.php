<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {
	public function index() {
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
		
		$header['active'] = 'notifications';

		// Get all detectors data
		$data['notifications'] = $this->notification_model->get_notifications();

		$this->load->view('templates/header.php', $header);
		$this->load->view('notifications/index.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function update_notification(){
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}

		$this->notification_model->read_notification($this->input->post('id'));
		redirect('notifications');
	}

	public function delete_notification(){
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}

		$this->notification_model->delete_notification($this->input->post('id'));
		redirect('notifications');
	}

	// API
	public function getNotifications($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$notifications = $this->notification_model->get_notifications();

			echo json_encode($notifications);
		}

	}

	public function getNotificationsById($detector_id, $api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			$notifications = $this->notification_model->get_notifications_by_id($detector_id);

			echo json_encode($notifications);
		}

	}

	public function updateNotification($id, $detector_id, $api_key){
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {

			// Update notif nya
			$this->notification_model->read_notification($id);

			// Fetch by ID
			$notifications = $this->notification_model->get_notifications_by_id($detector_id);

			echo json_encode($notifications);
		}
	}



	public function deleteNotification($id, $detector_id, $api_key){
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {

			// Delete notifnya
			$this->notification_model->delete_notification($id);

			// Fetch by ID
			$notifications = $this->notification_model->get_notifications_by_id($detector_id);

			echo json_encode($notifications);
		}
	}
}