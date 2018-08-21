<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MIklan extends CI_Model {
	function listIklan($client_id, $group_user, $limit, $offset, $order, $search, $sort)
	{
		if($group_user == 1 || $group_user == 5) {
			$rs 	= $this->db->query("SELECT T01.id FROM client_banner T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T02.ta_name LIKE '%".$search."%'");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT T01.id, T02.ta_name, T01.images FROM client_banner T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T02.ta_name LIKE '%".$search."%' ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$rs 	= $this->db->query("SELECT T01.id FROM client_banner T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$client_id);
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT T01.id, T02.ta_name, T01.images FROM client_banner T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$client_id." ORDER BY $sort $order LIMIT $offset,$limit");
		}

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getData($id)
	{
		$rs	= $this->db->query("SELECT	T01.id, T01.client_id, T02.ta_name, T01.images, T01.description FROM client_banner T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.id = ".$id);

		return $rs->result();
	}

	public function getDataRequest($where, $table)
	{
		$this->db->select('request_car');
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();

		return $query->result();
	}
}
