<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MMessage extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	function listMessage($opt, $limit, $offset, $order, $search, $sort)
	{
		if($opt == 'N') {
			$rs = $this->db->query("SELECT id FROM notification WHERE is_personal = '".$opt."' AND (message_name LIKE '%".$search."%' || active_date LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();

			$rs = $this->db->query("SELECT n.id, n.message_name, n.active_date, n.status, n.recipient, CASE n.recipient WHEN 'all' THEN 'All' WHEN 'client' THEN CONCAT('Client: ',(SELECT ta_name FROM ta_users WHERE id = n.recipient_id)) WHEN 'team' THEN CONCAT('Team: ',(SELECT team_name FROM team WHERE id = n.recipient_id)) END AS recipient_to FROM notification n WHERE n.is_personal = '".$opt."' AND (n.message_name LIKE '%".$search."%' || n.active_date LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$rs = $this->db->query("SELECT n.id FROM notification n INNER JOIN notification_detail nd ON nd.notif_id=n.id INNER JOIN team m ON m.mobile_phone=nd.notif_mobile WHERE (message_name LIKE '%".$search."%' || active_date LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();

			$rs = $this->db->query("SELECT n.id, n.message_name, n.active_date, n.status, m.team_name, nd.notif_status FROM notification n INNER JOIN notification_detail nd ON nd.notif_id=n.id INNER JOIN team m ON m.mobile_phone=nd.notif_mobile WHERE (n.message_name LIKE '%".$search."%' || n.active_date LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");
		}
		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function save($id, $data, $where, $table) {
		if($id == 0) {
			$query = $this->db->insert($table, $data);
		} else {
			$this->db->where($where);
			$query = $this->db->update($table, $data);
		}
		return $query;
	}

	public function getMessage($table, $where)
	{
		return $this->db->get_where($table, $where);
	}

}