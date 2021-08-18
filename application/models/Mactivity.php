<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MActivity extends CI_Model {
	public function listactivity($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT A.id, ST.site_name FROM activity A LEFT JOIN site ST ON ST.id = A.site_id WHERE (ST.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT A.id, A.site_id, A.team_id, A.description, A.att_date, A.att_shift, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name FROM activity A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users U ON U.id = ST.client_id LEFT JOIN team T ON T.id = A.team_id LEFT JOIN master_shift S ON S.id = A.att_shift WHERE (ST.site_name LIKE '%".$search."%') ORDER BY A.att_date DESC, A.att_shift DESC LIMIT $offset,$limit"); //$sort $order, 

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('A.id, A.site_id, A.team_id, A.description, A.att_date, A.att_shift, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name');
		$this->db->from('activity A');
        $this->db->join('site ST', 'ST.id = A.site_id', 'left');
        $this->db->join('ta_users U', 'U.id = ST.client_id', 'left');
        $this->db->join('team T', 'T.id = A.team_id', 'left');
        $this->db->join('master_shift S', 'S.id = A.att_shift', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	public function getReport($group, $id, $limit, $offset, $order, $search, $sort)
	{
        if($_SESSION['login']['group_user'] == 2) $client = "AND ST.client_id = " . $id;
		$query_num	= $this->db->query("SELECT A.site_id, A.att_date, A.att_shift, ST.site_name, C.ta_name AS client_name, S.name AS shift_name FROM activity A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN master_shift S ON S.id = A.att_shift WHERE A.att_date like '%".$search."%' ".$client." GROUP BY A.site_id, A.att_date, A.att_shift");

        $result["total"] = $query_num->num_rows();

                $query  = $this->db->query("SELECT A.site_id, A.att_date, A.att_shift, ST.site_name, C.ta_name AS client_name, S.name AS shift_name FROM activity A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN master_shift S ON S.id = A.att_shift WHERE A.att_date like '%".$search."%' ".$client." GROUP BY A.site_id, A.att_date, A.att_shift ORDER BY A.att_date DESC, A.att_shift DESC LIMIT $offset,$limit");
        
		$items 	= array();
		while($row = $query->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}
}
