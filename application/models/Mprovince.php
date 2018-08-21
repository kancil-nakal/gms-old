<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MProvince extends CI_Model {
	public function listprovince($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT id FROM province WHERE propinsi_name LIKE '%".$search."%'");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT id, propinsi_name, lat, lng, status FROM province WHERE propinsi_name LIKE '%".$search."%' ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_province($table, $where)
	{
		$rs	= $this->db->query("SELECT id FROM province WHERE propinsi_name = '".$where."'");
		return $rs->num_rows();
	}

	function getRoute($phone, $date) 
	{
		$rs	= $this->db->query("SELECT lat, `long` FROM tracking_detail WHERE hpnumber = '".$phone."' AND DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".$date."' ORDER BY id ASC");

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['lat'] 	= floatval($row->lat);
			$data[$i]['lng'] 	= floatval($row->long);
			$i=$i + 1;
		}
		return $data;
	}
}