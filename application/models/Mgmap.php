<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgmap extends CI_Model {
	public function location($group, $id, $province, $client)
	{
		if($group == 1 || $group == 3 || $group == 5) {
			if($client != 'all') {
				$rs	= $this->db->query("SELECT T01.hpnumber, T02.member_name, T01.lat, T01.long FROM tracking_detail T01 JOIN member T02 On T02.mobile_phone = T01.hpnumber JOIN ta_users T03 ON T03.id = T02.client_id JOIN (SELECT id AS gid, lat, `long`, hpnumber FROM tracking_detail WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW()) ORDER BY id DESC LIMIT 1) T04 ON T04.gid = T01.id WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW()) AND T01.alreadystart = 1 AND T02.province = ".$province." AND T02.client_id = ".$client." ORDER BY T01.id DESC");
			} else {
				$rs	= $this->db->query("SELECT T01.hpnumber, T02.member_name, T01.lat, T01.long FROM tracking_detail T01 JOIN member T02 On T02.mobile_phone = T01.hpnumber JOIN ta_users T03 ON T03.id = T02.client_id JOIN (SELECT id AS gid, lat, `long`, hpnumber FROM tracking_detail WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW()) ORDER BY id DESC LIMIT 1) T04 ON T04.gid = T01.id WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW()) AND T01.alreadystart = 1 AND T02.province = ".$province." ORDER BY T01.id DESC");
			}
		} else if($group == 2) {
			$rs	= $this->db->query("SELECT T01.hpnumber, T02.member_name, T01.lat, T01.long FROM tracking_detail T01 JOIN member T02 On T02.mobile_phone = T01.hpnumber JOIN ta_users T03 ON T03.id = T02.client_id JOIN (SELECT id AS gid, lat, `long`, hpnumber FROM tracking_detail WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW())  ORDER BY id DESC LIMIT 1) T04 ON T04.gid = T01.id WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = DATE(NOW()) AND T01.alreadystart = 1 AND T02.client_id = ".$id." ORDER BY T01.id DESC");
		}
		if($rs->num_rows() > 0) {
			$i=0;
			foreach($rs->result() as $row)
			{
				$data[$i]['hpnumber'] 		= $row->hpnumber;
				$data[$i]['member_name'] 	= $row->member_name;
				$data[$i]['lat'] 			= $row->lat;
				$data[$i]['lng'] 			= $row->long;
				$data[$i]['id'] 			= $i + 1;
				$i=$i + 1;
			}
			return $data;
		} else {
			return [];
		}
	}

	public function getMap($id)
	{
		$rs	= $this->db->query("SELECT DISTINCT(T01.hpnumber), T02.member_name, T01.lat, T01.long FROM tracking_detail T01 JOIN member T02 On T02.mobile_phone = T01.hpnumber JOIN ta_users T03 ON T03.id = T02.client_id JOIN (SELECT MAX(pinOnTime) AS starDate, lat, `long`, hpnumber FROM tracking_detail GROUP BY hpnumber) T04 ON T04.starDate = T01.pinOnTime WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".date('Y-m-d')."' AND T03.id = '".intval($id)."' ORDER BY T01.id DESC");

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['hpnumber'] 		= $row->hpnumber;
			$data[$i]['member_name'] 	= $row->member_name;
			$data[$i]['lat'] 			= $row->lat;
			$data[$i]['lng'] 			= $row->long;
			$data[$i]['id'] 			= $i + 1;
			$i=$i + 1;
		}
		return $data;
	}

	function latlng($where, $table)
	{
		$this->db->select('lat, lng, zoom');
		$this->db->from( $table );
		$this->db->where( $where );
		$query = $this->db->get();
		return $query->result();
	}


	// public function GetDataReportClient($id)
	// {
	// 	$query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_km),0) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_viewer),0) END AS total_viewer, CASE WHEN SUM(T01.total_saldo) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_saldo),0) END AS total_saldo, '0'saldo FROM summary_report T01 LEFT JOIN member T02 ON T02.id = T01.client_id WHERE T01.client_id = ".$id);

	// 	return $query->result();
	// }

	// public function getMap($id)
	// {
	// 	$rs	= $this->db->query("SELECT DISTINCT(T01.hpnumber), T02.member_name, T01.lat, T01.long FROM tracking_detail T01 JOIN member T02 On T02.mobile_phone = T01.hpnumber JOIN ta_users T03 ON T03.id = T02.client_id JOIN (SELECT MAX(pinOnTime) AS starDate, lat, `long`, hpnumber FROM tracking_detail GROUP BY hpnumber) T04 ON T04.starDate = T01.pinOnTime WHERE DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".date('Y-m-d')."' AND T03.id = '".intval($id)."' ORDER BY T01.id DESC");

	// 	$i=0;
	// 	foreach($rs->result() as $row)
	// 	{
	// 		$data[$i]['hpnumber'] 		= $row->hpnumber;
	// 		$data[$i]['member_name'] 	= $row->member_name;
	// 		$data[$i]['lat'] 			= $row->lat;
	// 		$data[$i]['lng'] 			= $row->long;
	// 		$data[$i]['id'] 			= $i + 1;
	// 		$i=$i + 1;
	// 	}
	// 	return $data;
	// }


	// public function GetDataReport($table, $where)
	// {
	// 	$this->db->select('CASE WHEN SUM(total_km) IS NULL THEN 0 ELSE SUM(total_km) END AS total_km, CASE WHEN SUM(total_viewer) IS NULL THEN 0 ELSE SUM(total_viewer) END AS total_viewer, CASE WHEN SUM(total_saldo) IS NULL THEN 0 ELSE SUM(total_saldo) END AS total_saldo');
	// 	$this->db->from( $table );
	// 	$this->db->where( $where );
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	

	
}