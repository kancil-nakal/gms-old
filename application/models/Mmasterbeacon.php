<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMasterBeacon extends CI_Model {
	public function listbeacon($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM master_beacon T01 WHERE (T01.notes LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T01.notes, T01.status, T01.uuid, T01.type FROM master_beacon T01 WHERE (T01.notes LIKE '%".$search."%') ORDER BY $sort $order, T01.notes ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_beacon($notes)
	{
		$rs	= $this->db->query("SELECT id FROM master_beacon WHERE notes = '".$notes."'");
		return $rs->num_rows();
	}

	function getData($where) 
	{
		$this->db->select('T01.id, T01.notes, T01.status, T01.uuid, T01.type');
		$this->db->from('master_beacon T01');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}