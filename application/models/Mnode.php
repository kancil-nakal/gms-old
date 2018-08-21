<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MNode extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function save($data, $table)
	{
		$query = $this->db->insert($table, $data);
		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "Save";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}
}