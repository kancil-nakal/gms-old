<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MEmergency extends CI_Model {
	public function listemergency($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT E.id FROM emergency E LEFT JOIN master_contacttype CT ON CT.id = E.contact_type WHERE (E.name LIKE '%".$search."%') OR (CT.name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT E.id, E.name, E.phone, E.address, E.city_id, E.status, E.contact_type, CT.name AS contacttype_name, C.city_name AS city_name FROM emergency E LEFT JOIN master_contacttype CT ON CT.id = E.contact_type LEFT JOIN city C ON C.id = E.city_id WHERE (E.name LIKE '%".$search."%') OR (CT.name LIKE '%".$search."%') ORDER BY $sort $order, E.name ASC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('E.id, E.name, E.phone, E.address, E.city_id, E.status, E.contact_type, CT.name AS contacttype_name, C.city_name AS city_name');
		$this->db->from('emergency E');
        $this->db->join('master_contacttype CT', 'CT.id = E.contact_type', 'left');
        $this->db->join('city C', 'C.id = E.city_id', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}