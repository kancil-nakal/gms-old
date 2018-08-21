<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MClient extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function save($id, $data, $where, $table) {
		if($id == 0) {
			$query = $this->db->insert($table, $data);
		} else {
			$this->db->where($where);
			$query = $this->db->update($table, $data);
		}
		return $query;
	}

	public function getData($table, $where)
	{
		return $this->db->get_where($table, $where);
	}

	public function delClient($where,$table)
	{
		$this->db->where($where);
		$query = $this->db->delete($table);

		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "success";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}

	public function getRate($id)
	{
		$this->db->where( array('client_id' => $id ));
		$query = $this->db->get('client_rate');
		return $query->result();
	}
}
