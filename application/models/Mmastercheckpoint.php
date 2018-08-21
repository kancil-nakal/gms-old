<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMasterCheckpoint extends CI_Model {
	public function listcheckpoint($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM master_checkpoint T01 WHERE (T01.name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T01.name, T01.beacon_id, T01.site_id, T01.ordering, T01.status, T02.notes as beacon_name, ST.site_name, T03.ta_name as client_name FROM master_checkpoint T01 LEFT JOIN master_beacon T02 ON T02.id = T01.beacon_id LEFT JOIN site ST ON ST.id = T01.site_id LEFT JOIN ta_users T03 ON T03.id = ST.client_id WHERE (T01.name LIKE '%".$search."%') ORDER BY $sort $order, T01.name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function cek_checkpoint($name)
	{
		$rs	= $this->db->query("SELECT id FROM master_checkpoint WHERE name = '".$name."'");
		return $rs->num_rows();
	}

	function getData($where) 
	{
		$this->db->select('T01.*, ST.site_name');
		$this->db->from('master_checkpoint T01');
        $this->db->join('site ST', 'ST.id = T01.site_id', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	function getBeacon() 
	{
		$this->db->select('id, notes');
		$this->db->from('master_beacon');
		$this->db->where(array('status' => 0));
		$query = $this->db->get();
		return $query->result_array();
	}

	function getSite() 
	{
		$this->db->select('id, site_name');
		$this->db->from('site');
		$this->db->where(array('status' => 0));
		$query = $this->db->get();
		return $query->result_array();
	}
}