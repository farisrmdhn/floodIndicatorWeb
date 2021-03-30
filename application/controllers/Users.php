<?php
class Users extends CI_Controller {

	public function login(){
		$data['title'] = 'Sign In';

		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('users/login', $data);
		}else{
			//Get Username
			$email = $this->input->post('email');
			//Get and encrypt the password
			$password = md5($this->input->post('password'));

			//Login User
			$user = $this->user_model->login($email, $password);

			if($user){
				if($user['user_type'] == 0) {
					//if admin
					$user_data = array(
						'user_id' => $user['id'],
						'name' => $user['name'],
						'logged_in' => true,
						'admin' => true
					);

				} else {
					//Create session - User
					$user_data = array(
						'user_id' => $user['id'],
						'name' => $user['name'],
						'logged_in' => true,
						'admin' => false
					);
				}
				

				$this->session->set_userdata($user_data);

				//Set Message
				$this->session->set_flashdata('flashdata_success', 'You are now logged in');

				if($this->session->userdata('admin') == true) {
					redirect('admin_dashboard');
				} else{
					redirect('');
				}
			} else{
				//Set Message
				$this->session->set_flashdata('flashdata_danger', 'Login is invalid');

				redirect('login');
			}	
		}
	}

	public function logout(){
		// Unset user data
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('user_id');

		//Set Message
		$this->session->set_flashdata('flashdata_danger', 'You are now logged out');

		redirect('login');
	}

	public function profile(){
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}

		$header['active'] = 'profile';

		$data['user'] = $this->user_model->get_user($this->session->userdata('user_id'));


		if($this->session->userdata('admin') == true) {
			$this->load->view('templates/admin_header.php', $header);
			$this->load->view('users/profile.php', $data);
			$this->load->view('templates/admin_footer.php');
		} else {
			$this->load->view('templates/header.php', $header);
			$this->load->view('users/profile.php', $data);
			$this->load->view('templates/footer.php');
		}
	}

	public function edit_profile() {
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}

		$header['active'] = 'profile';

		$data['user'] = $this->user_model->get_user($this->session->userdata('user_id'));

		if($this->session->userdata('admin') == true) {
			$this->load->view('templates/admin_header.php', $header);
			$this->load->view('users/edit_profile.php', $data);
			$this->load->view('templates/admin_footer.php');
		} else {
			$this->load->view('templates/header.php', $header);
			$this->load->view('users/edit_profile.php', $data);
			$this->load->view('templates/footer.php');
		}

	}

	public function change_password() {
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}

		$header['active'] = 'profile';

		$data['user'] = $this->user_model->get_user($this->session->userdata('user_id'));

		$this->form_validation->set_rules('old_password', 'Old Password', 'required|callback_check_old_password');
		$this->form_validation->set_rules('new_password', 'New Password', 'required');
		$this->form_validation->set_rules('new_password2', 'Confirm New Password', 'matches[new_password]');

		if($this->form_validation->run() === FALSE){
			$this->load->view('templates/header.php', $header);
			$this->load->view('users/change_password.php', $data);
			$this->load->view('templates/footer.php');
		} else {
			$id = $this->session->userdata('user_id');
			$new_password = md5($this->input->post('new_password'));

			$this->user_model->update_user_password($id, $new_password);

			$this->session->set_flashdata('flashdata_success', 'Your Passsword have been updated');

			redirect('profile');
		}
	}

	public function update_profile(){
		//Check login
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
		
		//upload image
		$config['upload_path'] = './assets/images/profile';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = '10000';

		$this->load->library('upload', $config);

		//If the image falied to upload, replace with noimage.jpg
		if(!$this->upload->do_upload('picture')){
			$errors = array('error' => $this->upload->display_errors());
			$picture = $this->input->post('old_picture');
		} else {
			$data = array('upload_data' => $this->upload->data());
			$picture = $_FILES['picture']['name'];
		}

		//Update the founder's data to db via model
		$this->user_model->update($picture);

		$this->session->set_flashdata('flashdata_success', 'Your profile have been updated');

		redirect('profile');
	}

	public function forgot_password() {
		$this->form_validation->set_rules('email', 'Email', 'required');
		//captcha validation

		if($this->form_validation->run() === FALSE){
			$this->load->view('users/forgot_password.php');
		}else {
			// Check email
			$id = $this->user_model->check_email()['id'];

			if($id) {
				// Insert token to db
				$token = bin2hex(random_bytes(60));
				$this->user_model->set_token($id, $token);	
			} else {
				$this->session->set_flashdata('flashdata_danger', 'There are no accounts  with that email... Please make sure to type in your email correctly.');

				redirect('forgot_password');
			}
			

			//Send reset request
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
			$this->email->from('floodindicator@farisramadhan.com', 'Flood Indicator');
			$this->email->to($this->input->post('email'));

			$this->email->subject('Password reset request');
			$this->email->message("<p>Hello, we hear that you forgot your password... No Problem! Click <a href'".base_url()."reset_password?id=".$id."&token=".$token."'>here</a> to reset your password.</p><br / ><p><strong>Backup Link: </strong> ".base_url()."reset_password?id=".$id."&token=".$token."</p>");

			$this->email->send();

			//Set Message
			$this->session->set_flashdata('flashdata_success', 'Your email has been sent! Please check your email to reset your password');

			redirect('login');
		}
	}

	public function reset_password() {
		$data['user'] = array();
		

		$this->form_validation->set_rules('new_password', 'New Password', 'required');
		$this->form_validation->set_rules('new_password2', 'Confirm New Password', 'matches[new_password]');

		if($this->form_validation->run() === FALSE){
			//Check HTTP GET input
			if (!$this->input->get('id') || !$this->input->get('token')) {
				redirect('login');
			} else {
				//Check token validity
				$user = $this->user_model->check_reset_token();
				if ($user) {
					$data['user'] = $user;
				} else {
					redirect('login');
				}
			}
			$this->load->view('users/reset_password.php', $data);
		}else {
			$id = $this->input->post('id');
			$new_password = md5($this->input->post('new_password'));

			$this->user_model->update_user_password($id, $new_password);

			// Insert token to db
			// Biar token beda lagi dan gaada yg tau
			$token = bin2hex(random_bytes(60));
			$this->user_model->set_token($id, $token);

			$this->session->set_flashdata('flashdata_success', 'Your Passsword have been updated');

			redirect('login');
		}
	}

	//check if username exist
	public function check_old_password($password){
		$password = md5($password);
		
		$this->form_validation->set_message('check_old_password', 'The Password you specify does not match current password');

		if($this->user_model->check_old_password($password)){
			return true;
		} else{
			return false;
		}
	}

	// Admin

	public function admin_dashboard() {
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}

		$header['active'] = 'dashboard';
		$data['users'] = $this->user_model->get_all_users();

		$this->load->view('templates/admin_header', $header);
		$this->load->view('users/admin_dashboard', $data);
		$this->load->view('templates/admin_footer');

	}

	public function create_user(){
		//Check login & admin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}

		$header['active'] = 'create';

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exist');
		$this->form_validation->set_rules('phone', 'Phone', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');

		if($this->form_validation->run() === FALSE){
			$this->load->view('templates/admin_header', $header);
			$this->load->view('users/create_user');
			$this->load->view('templates/admin_footer');
		}else{
			// Encrypt password
			$enc_password = md5($this->input->post('password'));

			//upload image
			$config['upload_path'] = './assets/images/profile';
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = '10000';

			$this->load->library('upload', $config);

			//If the image falied to upload, replace with noimage.jpg
			if(!$this->upload->do_upload('picture')){
				$errors = array('error' => $this->upload->display_errors());
				$picture = 'no_pic.jpg';
			} else {
				$data = array('upload_data' => $this->upload->data());
				$picture = $_FILES['picture']['name'];
			}

			$this->user_model->create_user($enc_password, $picture);

			//Set Message
			$this->session->set_flashdata('flashdata_success', 'New user registered');

			redirect('admin_dashboard');
		}
	}

	public function user_details($id){
		//Check login & admin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}

		$header['active'] = '';

		$data['user'] = $this->user_model->get_user($id);


		$this->load->view('templates/admin_header.php', $header);
		$this->load->view('users/user_details.php', $data);
		$this->load->view('templates/admin_footer.php');
		
	}

	public function edit_user($id) {
		//Check login & admin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}

		$header['active'] = '';

		$data['user'] = $this->user_model->get_user($id);

		$this->load->view('templates/admin_header.php', $header);
		$this->load->view('users/edit_user.php', $data);
		$this->load->view('templates/admin_footer.php');

	}

	public function update_user(){
		//Check login & admin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}
		
		//upload image
		$config['upload_path'] = './assets/images/profile';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = '10000';

		$this->load->library('upload', $config);

		//If the image falied to upload, replace with noimage.jpg
		if(!$this->upload->do_upload('picture')){
			$errors = array('error' => $this->upload->display_errors());
			$picture = $this->input->post('old_picture');
		} else {
			$data = array('upload_data' => $this->upload->data());
			$picture = $_FILES['picture']['name'];
		}

		$enc_password = $this->input->post('old_password');
		if($this->input->post('new_password')) {
			if($this->input->post('new_password') != $this->input->post('new_password2')) {
				$this->session->set_flashdata('flashdata_danger', 'Password did not match');
				redirect('edit_user/'.$this->input->post('id'));
			}else {
				$enc_password = md5($this->input->post('new_password'));
			}
		}

		//Update the founder's data to db via model
		$this->user_model->update_user($picture, $enc_password);

		$this->session->set_flashdata('flashdata_success', 'User have been updated');

		redirect('admin_dashboard');
	}

	public function delete_user() {
		//Check login & admin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		} elseif ($this->session->userdata('admin') != true) {
			redirect('');
		}

		$this->user_model->delete_user();

		redirect('admin_dashboard');
	}

	//check if email exist
	public function check_email_exist($email){
		$this->form_validation->set_message('check_email_exist', 'That email is already taken. Please choose a diffrent one');

		if($this->user_model->check_email_exist($email)){
			return true;
		} else{
			return false;
		}
	}

	// APIs

	public function userLogin($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			// Getting the received JSON into $json variable.
			 $json = file_get_contents('php://input');
			 
			 // Decoding the received JSON and store into $obj variable.
			 $obj = json_decode($json,true);
			 
			 // Getting User email from JSON $obj array and store into $email.
			 $email = $obj['email'];
			 
			 // Getting Password from JSON $obj array and store into $password.
			 $password = md5($obj['password']);

			//Login User
			$user = $this->user_model->login($email, $password);
			if($user){
				$user_data = array(
					'login' => true,
					'id' => $user['id'],
					'name' => $user['name'],
					'email' => $user['email'],
					'phone' => $user['phone'],
					'address' => $user['address'],
					'picture' => 'http://192.168.43.22/floodIndicator/assets/images/profile/'.$user['picture'],
					'user_type' => $user['user_type']
				);

				echo json_encode($user_data);
			} else {
				$user_data = array('login' => false);
				echo json_encode($user_data);
			}
		}
	}

	public function editProfile($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			// Getting the received JSON into $json variable.
			 $json = file_get_contents('php://input');
			 
			 $obj = json_decode($json,true);
			 $id = $obj['id'];
			 $data = array($obj['name'], $obj['phone'], $obj['address']);

			//Login User
			$user = $this->user_model->update_user_mobile($id, $data);
			if($user){
				$user_data = array(
					'success' => true,
					'id' => $user['id'],
					'name' => $user['name'],
					'email' => $user['email'],
					'phone' => $user['phone'],
					'address' => $user['address'],
					'password' => $user['password'],
					'picture' => 'http://192.168.43.22/floodIndicator/assets/images/profile/'.$user['picture'],
					'user_type' => $user['user_type']
				);

				echo json_encode($user_data);
			} else {
				$user_data = array('success' => false);
				echo json_encode($user_data);
			}
		}
	}

	public function forgotPassword($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			// Getting the received JSON into $json variable.
			 $json = file_get_contents('php://input');
			 
			 $obj = json_decode($json,true);
			 $email = $obj['email'];

			// Check email
			$id = $this->user_model->check_email_mobile($email)['id'];
			if($id) {
				// Insert token to db
				$token = bin2hex(random_bytes(60));
				$this->user_model->set_token($id, $token);	

				//Send reset request
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
				$this->email->from('floodindicator@farisramadhan.com', 'Flood Indicator');
				$this->email->to($email);

				$this->email->subject('Password reset request');
				$this->email->message("<p>Hello, we hear that you forgot your password... No Problem! Click <a href'".base_url()."reset_password?id=".$id."&token=".$token."'>here</a> to reset your password.</p><br / ><p><strong>Backup Link: </strong> ".base_url()."reset_password?id=".$id."&token=".$token."</p>");

				$this->email->send();

				$user_data = array('success' => true);
				echo json_encode($user_data);
			} else {
				$user_data = array('success' => false);
				echo json_encode($user_data);
			}
		}
	}

	public function changePassword($api_key) {
		if($api_key != "b6353c2d-1ddd-4f41-8920-edc9cc66dae8") {
			echo "";
		}else {
			// Getting the received JSON into $json variable.
			 $json = file_get_contents('php://input');
			 
			 $obj = json_decode($json,true);
			 $id = $obj['id'];
			 $old_password = $obj['old_password'];
			 $new_password = $obj['new_password'];

			if(!$this->user_model->check_old_password_mobile($id, $old_password)){
				$user_data = array('success' => false, 'message' => "Your old password is incorrect");
				echo json_encode($user_data);
			}else{
				$user = $this->user_model->update_user_password_mobile($id ,$new_password);
				if($user){
					$user_data = array(
						'success' => true,
						'id' => $user['id'],
						'name' => $user['name'],
						'email' => $user['email'],
						'phone' => $user['phone'],
						'address' => $user['address'],
						'password' => $user['password'],
						'picture' => 'http://192.168.43.22/floodIndicator/assets/images/profile/'.$user['picture'],
						'user_type' => $user['user_type']
					);

					echo json_encode($user_data);
				} else {
					$user_data = array('success' => false);
					echo json_encode($user_data);
				}
			}
		}
	}
}