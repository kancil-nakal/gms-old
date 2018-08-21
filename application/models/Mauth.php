<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAuth extends CI_Model {
	public function cek_login($table, $where)
	{
		return $this->db->get_where($table, $where);
	}

	public function getData($table, $where)
	{
		$this->db->select('id, ta_name, ta_email, group_user, ta_image');
		$this->db->from( $table );
		$this->db->where( $where );
		$query = $this->db->get();
		return $query->row();
	}
}
