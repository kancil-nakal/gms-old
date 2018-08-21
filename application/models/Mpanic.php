<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPanic extends CI_Model {
	public function listpanic($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT P.id FROM panic P LEFT JOIN ta_users U ON U.id = P.client_id WHERE (P.name LIKE '%".$search."%') OR (U.ta_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT P.id, P.name, P.phone, P.notes, P.status, U.ta_name AS client_name FROM panic P LEFT JOIN ta_users U ON U.id = P.client_id WHERE (P.name LIKE '%".$search."%') OR (U.ta_name LIKE '%".$search."%') ORDER BY $sort $order, P.name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('P.id, P.client_id, P.name, P.phone, P.notes, P.status, U.ta_name AS client_name');
		$this->db->from('panic P');
        $this->db->join('ta_users U', 'U.id = P.client_id', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}