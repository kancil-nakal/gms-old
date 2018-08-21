<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMasterCity extends CI_Model {
	public function listcity($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM city T01 WHERE (T01.city_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T01.city_name FROM city T01 WHERE (T01.city_name LIKE '%".$search."%') ORDER BY $sort $order, T01.city_name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_city($city_name)
	{
		$rs	= $this->db->query("SELECT id FROM city WHERE city_name = '".$city_name."'");
		return $rs->num_rows();
	}

	function getData($where) 
	{
		$this->db->select('T01.id, T01.city_name');
		$this->db->from('city T01');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}