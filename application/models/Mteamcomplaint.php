<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mteamcomplaint extends CI_Model {
	public function listteamcomplaint($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT A.id, A.created_by FROM team_complaint A WHERE (A.created_by LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT A.id, A.team_id, A.problem, A.status, A.created_by AS team_name, A.created_date, CASE A.status WHEN '0' THEN 'Open' WHEN '1' THEN 'Closed' ELSE 'Open' END as status_name FROM team_complaint A WHERE (A.created_by LIKE '%".$search."%') ORDER BY A.created_date DESC LIMIT $offset,$limit"); //$sort $order

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getData($where) 
	{
		$this->db->select("A.id, A.team_id, A.problem, A.status, A.created_date, A.created_by AS team_name, B.comment, B.created_date AS comment_date, B.created_by AS comment_by, CASE A.status WHEN '0' THEN 'Open' WHEN '1' THEN 'Closed' ELSE 'Open' END as status_name", FALSE);
		$this->db->from('team_complaint A');
        $this->db->join('team_complaint_comment B', 'A.id = B.team_complaint_id', 'left');
        $this->db->order_by("B.created_date", "asc");
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
}