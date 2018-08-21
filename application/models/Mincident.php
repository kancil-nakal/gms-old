<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MIncident extends CI_Model { 
	public function listincident($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT I.id, ST.site_name FROM incident I LEFT JOIN site ST ON ST.id = I.site_id WHERE (ST.site_name LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT I.id, I.site_id, I.team_id, I.incident_no, I.subject, I.incident_date, I.incident_time, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name, MI.name AS status_name FROM incident I LEFT JOIN site ST ON ST.id = I.site_id LEFT JOIN ta_users U ON U.id = ST.client_id LEFT JOIN team T ON T.id = I.team_id LEFT JOIN master_shift S ON S.id = I.att_shift LEFT JOIN master_incidentstatus MI ON MI.id = I.status WHERE (ST.site_name LIKE '%".$search."%') ORDER BY I.att_date DESC, I.att_shift DESC LIMIT $offset,$limit"); //$sort $order, 

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select('I.*, ST.site_name, U.ta_name AS client_name, T.team_name, S.name AS shift_name, MI.name AS status_name');
		$this->db->from('incident I');
        $this->db->join('site ST', 'ST.id = I.site_id', 'left');
        $this->db->join('ta_users U', 'U.id = ST.client_id', 'left');
        $this->db->join('team T', 'T.id = I.team_id', 'left');
        $this->db->join('master_shift S', 'S.id = I.att_shift', 'left');
        $this->db->join('master_incidentstatus MI', 'MI.id = I.status', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	public function getReport($group, $id, $limit, $offset, $order, $search, $sort)
	{
        if($_SESSION['login']['group_user'] == 2) $client = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT A.id, A.incident_no, ST.site_name, U.ta_name AS client_name, A.subject, A.incident_date, A.incident_time FROM incident A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users U ON U.id = ST.client_id WHERE A.incident_no like '%".$search."%' AND A.status = 3 ".$client." ORDER BY A.incident_date DESC, A.incident_time DESC");

        $result["total"] = $query->num_rows();
        
		$items 	= array();
		while($row = $query->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}
}