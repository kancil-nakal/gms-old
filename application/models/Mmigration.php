<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMigration extends CI_Model {
	public function summaryDetail($where)
	{
		$this->db->select("id");
		$this->db->where($where);
		$query = $this->db->get("ta_users");

		foreach($query->result() as $row)
		{
			$driver_id	= $this->getDriverID($row->id);
			$nomor		= $this->getNomorHP($row->id);
			echo '<br />'.$nomor.'<br />';
		}
//		return $data;
	}

	public function getDriverID($client_id)
	{
		$this->db->select("id");
		$this->db->where('client_id',$client_id);
		$query = $this->db->get("member");
		$return = '';
		while ($row = $query->unbuffered_row())
		{
			$return .= $row->id;
		}
		return $return;
	}

	public function getNomorHP($client_id)
	{
		$this->db->select("mobile_phone");
		$this->db->where('client_id',$client_id);
		$query = $this->db->get("member");
		$return = '';
		while ($row = $query->unbuffered_row())
		{
			$return .= $row->mobile_phone;
		}
		return $return;
	}


}
