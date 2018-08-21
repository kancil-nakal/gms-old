<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMerk extends CI_Model {
	public function listmerk($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM merk_car T01 LEFT JOIN brand_car T02 ON T02.brand_name = T01.brand_name WHERE (T01.brand_name LIKE '%".$search."%' || T01.merk_car LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T01.brand_name, T01.merk_car, T01.`status` FROM merk_car T01 LEFT JOIN brand_car T02 ON T02.brand_name = T01.brand_name WHERE (T01.brand_name LIKE '%".$search."%' || T01.merk_car LIKE '%".$search."%') ORDER BY $sort $order, T01.merk_car ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_merk($table, $where)
	{
		return $this->db->get_where($table, $where);
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