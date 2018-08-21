<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MTeam extends CI_Model {
	function listTeam($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM team T01 LEFT JOIN site ST ON ST.id = T01.site_id WHERE (T01.team_name LIKE '%".$search."%' || ST.site_name LIKE '%".$search."%' || T01.mobile_phone LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, TRIM(T01.team_name) AS team_name, T01.mobile_phone, P.name as position, ST.site_name, T02.ta_name AS client_name, T01.app_status, T01.team_status, CASE WHEN LENGTH(T01.app_uuid) > 1 THEN 0 ELSE 1 END AS uuid_status, S.name AS shift_name FROM team T01 LEFT JOIN site ST ON ST.id = T01.site_id LEFT JOIN ta_users T02 ON T02.id = ST.client_id LEFT JOIN master_position P ON P.id = T01.position_id LEFT JOIN master_shift S ON S.id = T01.shift_id WHERE (T01.team_name LIKE '%".$search."%' || ST.site_name LIKE '%".$search."%' || T01.mobile_phone LIKE '%".$search."%') GROUP BY T01.id, T01.team_name, T01.mobile_phone, ST.site_name, T01.app_status, T01.team_status, T01.app_uuid ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getData($id)
	{
		$rs	= $this->db->query("SELECT T01.*, T01.site_id, ST.site_name FROM team T01 LEFT JOIN site ST ON ST.id = T01.site_id WHERE T01.id = ".$id);

		return $rs->result();
	}

	public function request($where, $table)
	{
		return $this->db->get_where($table, $where);
	}

	public function getDataRequest($where, $table)
	{
		$this->db->select('request_car');
		$this->db->from($table);
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

	// public function save($id, $data, $where, $table) {
	// 	if($id == 0) {
	// 		$this->db->insert($table, $data);
	// 		$query = $this->db->insert_id();
	// 	} else {
	// 		$this->db->where($where);
	// 		$query = $this->db->update($table, $data);
	// 	}
	// 	return $query;
	// }

	// public function getData($id)
	// {
	// 	$rs	= $this->db->query("SELECT T01.id, T01.team_name, T01.email_address, T01.user_image, T01.mobile_phone, T01.province, T01.city, T01.merk_mobil, T01.type_mobil, T01.nama_bank, T01.nama_akun, T01.no_rekening, T03.id AS client_id, CASE WHEN T02.id IS NULL THEN 0 ELSE T02.id END AS detail_id, CASE WHEN T02.id IS NULL THEN '' ELSE T03.team_name END AS client_name, T01.no_pol FROM team T01 LEFT JOIN client_detail T02 ON T02.team_id = T01.id LEFT JOIN team T03 ON T03.id = T02.client_id WHERE T01.id = ".$id);

	// 	return $rs->result();
	// 	// $i=0;
	// 	// foreach($rs->result() as $row)
	// 	// {
	// 	// 	$data[$i]['client_id']		= $row->client_id;
	// 	// 	$data[$i]['report_date']	= $row->report_date;
	// 	// 	$data[$i]['total_saldo']	= number_format($row->total_saldo,0,",",".");
	// 	// 	$data[$i]['total_viewer']	= $row->total_viewer;
	// 	// 	$data[$i]['total_km']	= $row->total_km;
	// 	// 	$i=$i + 1;
	// 	// }
	// 	// return json_encode($data);

	// 	// return $this->db->get_where($table, $where);
	// }

	// public function delTeam($where,$table)
	// {
	// 	$this->db->where($where);
	// 	$query = $this->db->delete($table);

	// 	if ($query) {
	// 		$msg['success']	= true;
	// 		$msg['msg']		= "success";
	// 	} else {
	// 		$msg['success']	= false;
	// 		$msg['msg'] 	= $this->db->_error_message();
	// 	}
	// 	return $msg;
	// }

	// public function saveClient($data, $table)
	// {
	// 	$query = $this->db->insert($table, $data);
	// 	return $query;
	// }

	// public function updClient($data, $where, $table)
	// {
	// 	$this->db->where($where);
	// 	$query = $this->db->update($table, $data);
	// 	return $query;
	// }
}
