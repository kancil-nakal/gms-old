<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MCheckpoint extends CI_Model {
	public function listcheckpoint($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT C.id, ST.site_name FROM checkpoint C LEFT JOIN site ST ON ST.id = C.site_id WHERE (ST.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT C.id, C.checkpoint_id, C.site_id, C.team_id, C.att_date, C.att_shift, C.created_date, MC.name AS checkpoint_name, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name FROM checkpoint C LEFT JOIN master_checkpoint MC ON MC.id = C.checkpoint_id LEFT JOIN site ST ON ST.id = C.site_id LEFT JOIN ta_users U ON U.id = ST.client_id LEFT JOIN team T ON T.id = C.team_id LEFT JOIN master_shift S ON S.id = C.att_shift WHERE (ST.site_name LIKE '%".$search."%') ORDER BY C.att_date DESC, C.att_shift DESC LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('C.id, C.checkpoint_id, C.site_id, C.team_id, C.att_date, C.att_shift, C.created_date, MC.name AS checkpoint_name, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name');
		$this->db->from('checkpoint C');
        $this->db->join('master_checkpoint MC', 'MC.id = C.checkpoint_id', 'left');
        $this->db->join('site ST', 'ST.id = C.site_id', 'left');
        $this->db->join('ta_users U', 'U.id = ST.client_id', 'left');
        $this->db->join('team T', 'T.id = C.team_id', 'left');
        $this->db->join('master_shift S', 'S.id = C.att_shift', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}