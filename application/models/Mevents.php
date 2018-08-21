<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MEvents extends CI_Model {
	public function listEvents($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT id FROM client_events WHERE event_name LIKE '%".$search."%' AND status IN (0,1)");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT id, event_name, DATE_FORMAT(date_start, '%d %M %Y') AS date_start, DATE_FORMAT(date_end, '%d %M %Y') AS date_end, status FROM client_events WHERE event_name LIKE '%".$search."%' AND status IN (0,1) ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}
}