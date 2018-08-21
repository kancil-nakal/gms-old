<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAttendance extends CI_Model { 
	public function listattendance($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT A.id, ST.site_name FROM team_attendance A LEFT JOIN site ST ON ST.id = A.site_id WHERE (ST.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows(); 

		$rs		= $this->db->query("SELECT A.id, A.site_id, A.team_id, A.att_type, A.att_reason, A.att_date, A.att_shift, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name, MA.name AS atttype_name FROM team_attendance A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users U ON U.id = ST.client_id LEFT JOIN team T ON T.id = A.team_id LEFT JOIN master_shift S ON S.id = A.att_shift LEFT JOIN master_attendance MA ON MA.id = A.att_type WHERE (ST.site_name LIKE '%".$search."%') ORDER BY A.att_date DESC, A.att_shift DESC LIMIT $offset,$limit"); //$sort $order, 

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('A.*, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name, MA.name AS atttype_name');
		$this->db->from('team_attendance A');
        $this->db->join('site ST', 'ST.id = A.site_id', 'left');
        $this->db->join('ta_users U', 'U.id = ST.client_id', 'left');
        $this->db->join('team T', 'T.id = A.team_id', 'left');
        $this->db->join('master_shift S', 'S.id = A.att_shift', 'left');
        $this->db->join('master_attendance MA', 'MA.id = A.att_type', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	public function getReport($group, $id, $limit, $offset, $order, $search, $sort)
	{
        if($_SESSION['login']['group_user'] == 2) $client = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT A.id, A.team_name, ST.site_name, C.ta_name AS client_name, S.name AS shift_name, MP.name AS position_name, CT.city_name FROM team A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN master_shift S ON S.id = A.shift_id LEFT JOIN master_position MP ON MP.id = A.position_id LEFT JOIN city CT ON CT.id = A.city_id WHERE A.team_name like '%".$search."%' AND A.team_status = 0 ".$client." ORDER BY A.team_name ASC");

        $result["total"] = $query->num_rows();
        
		$items 	= array();
		while($row = $query->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}
}