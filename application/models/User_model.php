<?php
	class User_model extends CI_Model{
		public function login($email, $password){
			//Validate
			$this->db->where('email', $email);
			$this->db->where('password', $password);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				$result = $result->row_array();

				$sql = 'UPDATE users SET last_login= CURRENT_TIMESTAMP WHERE id = "'.$result['id'].'" ;';

				$this->db->query($sql);

				return $result;
			} else{
				return false;
			}
		}

		public function update_user_mobile($id, $data){

			$sql = 'UPDATE users SET name="'.$data[0].'", phone= "'.$data[1].'", address= "'.$data[2].'" WHERE id = "'.$id.'" ;';

			//Change the data
			$this->db->query($sql);

			//Validate
			$this->db->where('id', $id);
			$result = $this->db->get('users')->row_array();

			return $result;
		}

		public function get_user($id){
			$this->db->where('id', $id);

			return $this->db->get('users')->row_array();
		}

		public function update($picture){

			$sql = 'UPDATE users SET name="'.$this->input->post('name').'", email= "'.$this->input->post('email').'", phone= "'.$this->input->post('phone').'", address= "'.$this->input->post('address').'", picture= "'.$picture.'" WHERE id = "'.$this->session->userdata('user_id').'" ;';

			//Change the data
			return $this->db->query($sql);
		}

		public function update_user_password($id, $new_password) {
			$sql = 'UPDATE users SET password = "'.$new_password.'" WHERE id = "'.$id.'" ;';

			//Change the data
			return $this->db->query($sql);
		}

		public function update_user_password_mobile($id, $new_password) {
			$sql = 'UPDATE users SET password = "'.$new_password.'" WHERE id = "'.$id.'" ;';

			//Change the data
			$this->db->query($sql);

			//Validate
			$this->db->where('id', $id);
			$result = $this->db->get('users')->row_array();

			return $result;
		}

		public function get_emails() {
			$sql = 'SELECT email FROM USERS;';
			$results =  $this->db->query($sql)->result_array();

			$emails = array();
			foreach($results as $result) {
				array_push($emails, $result['email']);
			}

			return $emails;
		}

		public function check_email(){
			//Validate
			$this->db->where('email', $this->input->post('email'));

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				$result = $result->row_array();

				return $result;
			} else{
				return false;
			}
		}

		public function check_email_mobile($email){
			//Validate
			$this->db->where('email', $email);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				$result = $result->row_array();

				return $result;
			} else{
				return false;
			}
		}

		public function set_token($id, $token) {
			$sql = 'UPDATE users SET reset_token = "'.$token.'" WHERE id = "'.$id.'" ;';

			//Change the data
			return $this->db->query($sql);
		}

		public function check_reset_token() {
			$id = $this->input->get('id');
			$token = $this->input->get('token');

			//Validate
			$sql = 'SELECT id, name FROM users WHERE id = "'.$id.'" AND reset_token = "'.$token.'" ;';
			$result = $this->db->query($sql);

			if($result->num_rows() == 1){
				$result = $result->row_array();

				return $result;
			} else{
				return false;
			}

		}

		// Admin
		public function get_all_users() {
			$this->db->where('user_type', 1);

			return $this->db->get('users')->result_array();;
		}

		public function create_user($enc_password, $picture){
			$prefix = 'A';
			if($this->input->post('user_type') == 0) {
				$user_type = 0;
				$prefix = 'A';
			} elseif( $this->input->post('user_type') == 1) {
				$user_type = 1;
				$prefix = 'U';
			}

			//tentukan angka id
			$sql = 'SELECT DISTINCT id from users WHERE user_type = "'.$user_type.'" ORDER BY created DESC LIMIT 1;';

			$result = $this->db->query($sql)->row_array();

			if($result) {
				$result = str_pad(((int)substr($result['id'], 2,3) + 1), 3, '0', STR_PAD_LEFT);
				// Kalo udah 1000 user
				if (($result + 0) == 1000) {
					$result = "001";
				}
			} else {
				//Kalo Belom Ada
				$result = "001";
			}

			// User data array
			$data = array(
				'id' => $prefix.'-'.$result,
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'password' => $enc_password,
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address'),
				'user_type' => $this->input->post('user_type'),
				'picture' => $picture
			);

			//Insert user

			return $this->db->insert('users', $data);
		}

		// Check old password
		public function check_old_password($password){
			$this->db->where('id', $this->session->userdata('user_id'));
			$this->db->where('password', $password);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return true;
			} else{
				return false;
			}
		}

		public function check_old_password_mobile($id,$password){
			$this->db->where('id', $id);
			$this->db->where('password', $password);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return true;
			} else{
				return false;
			}
		}

		public function update_user($picture, $enc_password){

			$sql = 'UPDATE users SET name="'.$this->input->post('name').'", password = "'.$enc_password.'", email= "'.$this->input->post('email').'", phone= "'.$this->input->post('phone').'", address= "'.$this->input->post('address').'", picture= "'.$picture.'" WHERE id = "'.$this->input->post('id').'" ;';

			//Change the data
			return $this->db->query($sql);
		}

		public function delete_user(){

			$sql = 'DELETE FROM users WHERE id = "'.$this->input->post('id').'" ;';

			//Change the data
			return $this->db->query($sql);
		}


		//Check email exist

		public function check_email_exist($email){
			$query = $this->db->get_where('users', array('email' => $email));
			if(empty($query->row_array())){
				return true;
			} else{
				return false;
			}
		}

	}