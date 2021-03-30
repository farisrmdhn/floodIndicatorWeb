<?php
class Notification_model extends CI_Model{

	public function add_notification($detector_id, $type) {
		$prefix = 'E';
		if($type == 'error') {
			$type = 0;
		} elseif( $type == 'danger') {
			$type = 1;
			$prefix = 'D';
		}

		//tentukan angka id
		$sql = 'SELECT DISTINCT id from notifications ORDER BY timestamp DESC LIMIT 1;';

		$result = $this->db->query($sql)->row_array();

		if($result) {
			$result = str_pad(((int)substr($result['id'], 2,3) + 1), 3, '0', STR_PAD_LEFT);
			if (($result + 0) == 1000) {
				$sql = 'DELETE FROM notifications';
				$this->db->query($sql);

				$result = "001";
			}
		} else {
			$result = "001";
		}

		$data = array(
			'id' => $prefix.'-'.$result,
			'detector_id' => $detector_id,
			'type' => $type,
			'is_new' => 'true'
		);

		return $this->db->insert('notifications', $data);		
	}

	public function get_notifications() {
		$sql = 'SELECT * from notifications ORDER BY timestamp DESC LIMIT 20';

		return $this->db->query($sql)->result_array();
	}

	public function get_notifications_by_id($detector_id) {
		$sql = 'SELECT * from notifications WHERE detector_id = "'.$detector_id.'" ORDER BY timestamp DESC LIMIT 20';

		return $this->db->query($sql)->result_array();
	}

	public function read_notification($id) {
		$sql = 'UPDATE notifications SET is_new = "false" WHERE id = "'.$id.'" ;';

		return $this->db->query($sql);
	}

	public function delete_notification($id) {
		$sql = 'DELETE FROM notifications WHERE id = "'.$id.'" ;';

		return $this->db->query($sql);
	}
}