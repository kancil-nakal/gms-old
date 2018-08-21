<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MTopup extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function cek_topup($id)
	{
		$return = 0;
		$this->db->select("remain_saldo");
		$this->db->where("client_id",$id);
		$query = $this->db->get("client_saldo");
		while ($row = $query->unbuffered_row())
		{
			$return = $row->remain_saldo;
		}

		return $return;
	}
}
