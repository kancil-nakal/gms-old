<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMasterPosition extends CI_Model {
	public function listposition($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM master_position T01 WHERE (T01.name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T01.name FROM master_position T01 WHERE (T01.name LIKE '%".$search."%') ORDER BY $sort $order, T01.name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_position($name)
	{
		$rs	= $this->db->query("SELECT id FROM master_position WHERE name = '".$name."'");
		return $rs->num_rows();
	}

	function getData($where) 
	{
		$this->db->select('T01.id, T01.name');
		$this->db->from('master_position T01');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}