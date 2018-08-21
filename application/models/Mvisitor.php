<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MVisitor extends CI_Model { 
	public function listvisitor($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT V.id, ST.site_name FROM visitor V LEFT JOIN site ST ON ST.id = V.site_id WHERE (ST.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT V.id, V.site_id, V.team_id, V.total, V.notes, V.att_date, V.att_shift, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name FROM visitor V LEFT JOIN site ST ON ST.id = V.site_id LEFT JOIN ta_users U ON U.id = ST.client_id LEFT JOIN team T ON T.id = V.team_id LEFT JOIN master_shift S ON S.id = V.att_shift WHERE (ST.site_name LIKE '%".$search."%') ORDER BY V.att_date DESC, V.att_shift DESC LIMIT $offset,$limit"); //$sort $order, 

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('V.id, V.site_id, V.team_id, V.total, V.notes, V.att_date, V.att_shift, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name');
		$this->db->from('visitor V');
        $this->db->join('site ST', 'ST.id = V.site_id', 'left');
        $this->db->join('ta_users U', 'U.id = ST.client_id', 'left');
        $this->db->join('team T', 'T.id = V.team_id', 'left');
        $this->db->join('master_shift S', 'S.id = V.att_shift', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}