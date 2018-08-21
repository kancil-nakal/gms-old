<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSite extends CI_Model {
	public function listSite($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT S.id FROM site S WHERE (S.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows(); 

		$rs		= $this->db->query("SELECT S.id, S.client_id, S.city_id, S.site_name, U.ta_name AS client_name, C.city_name FROM site S LEFT JOIN ta_users U ON U.id = S.client_id LEFT JOIN city C ON C.id = S.city_id WHERE (S.site_name LIKE '%".$search."%') ORDER BY $sort $order, S.site_name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('S.*, U.ta_name AS client_name, C.city_name');
		$this->db->from('site S');
        $this->db->join('ta_users U', 'U.id = S.client_id', 'left');
        $this->db->join('city C', 'C.id = S.city_id', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}