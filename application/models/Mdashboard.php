<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdashboard extends CI_Model {
	public function GetDailyReport($group, $id, $date)
	{
		if($group == 2) {
			//$query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_km),0) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_viewer),0) END AS total_viewer, ROUND(MAX(T02.beginning_saldo) - MAX(T02.remain_saldo),0) AS total_saldo, ROUND(MAX(T02.remain_saldo),0) AS saldo, (ROUND(MAX(T02.beginning_saldo),0) * 40)/100 AS warning_saldo, (ROUND(MAX(T02.beginning_saldo),0) * 20)/100 AS alert_saldo FROM summary_report_detail_tes T01 LEFT JOIN client_saldo T02 ON T02.client_id = T01.client_id WHERE  T01.report_date <= '".$date."' AND T01.client_id = ".$id);
			$query	= $this->db->query("SELECT 1 as total_km");
			// $query	= $this->db->query("SELECT CASE WHEN SUM(T02.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T02.total_km),0) END AS total_km, CASE WHEN SUM(T02.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T02.total_viewer),0) END AS total_viewer, CASE WHEN SUM(T02.total_saldo) IS NULL THEN 0 ELSE ROUND(SUM(T02.total_saldo),0) END AS total_saldo, ROUND(T01.remain_saldo,0) AS saldo, (ROUND(T01.beginning_saldo,0) * 40)/100 AS warning_saldo, (ROUND(T01.beginning_saldo,0) * 20)/100 AS alert_saldo FROM client_saldo T01 LEFT JOIN summary_report_detail_tes T02 ON T02.client_id = T01.client_id AND T02.report_date <= '".$date."' WHERE T01.client_id = ".$id);
		} else {	
			//$query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE SUM(T01.total_km) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE SUM(T01.total_viewer) END AS total_viewer, ( SELECT SUM(beginning_saldo) - SUM(remain_saldo) FROM client_saldo WHERE client_id != 9 ) AS total_saldo FROM summary_report_detail_tes T01 WHERE T01.report_date <= DATE(NOW()) - INTERVAL 1 DAY AND T01.client_id != 9");
			//dissabled by neo -- format . dihilangkan
			$query	= $this->db->query("SELECT 1 as total_km");
			// $query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_km),0) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_viewer),0) END AS total_viewer, CASE WHEN SUM(T01.total_saldo) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_saldo),0) END AS total_saldo FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.id = T01.client_id WHERE T01.report_date <= '".$date."'");
		}
		return $query->result();
	}
    
	public function GetAllSecurityToday($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT MA.name AS att_type, COUNT(*) AS jumlah FROM team_attendance TA INNER JOIN master_attendance MA ON MA.id = TA.att_type INNER JOIN site ST ON ST.id = TA.site_id WHERE TA.att_date = '".date('Y-m-d')."' ".$where." GROUP BY TA.att_type ORDER BY TA.att_type");

		return $query->result();
	}
    
	public function GetClientSecurityToday($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT U.ta_name AS client_name, COUNT(*) AS jumlah FROM team_attendance TA INNER JOIN site ST ON ST.id = TA.site_id INNER JOIN ta_users U ON U.id = ST.client_id WHERE TA.att_date = '".date('Y-m-d')."' AND TA.att_type = 1 ".$where."  GROUP BY U.ta_name ORDER BY U.ta_name");

		return $query->result();
	}
    
	public function GetClientIncidentMonthly($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT U.ta_name AS client_name, COUNT(*) AS jumlah FROM incident I INNER JOIN site ST ON ST.id = I.site_id INNER JOIN ta_users U ON U.id = ST.client_id WHERE DATE_FORMAT(I.incident_date,'%Y%m') = '".date('Ym')."' AND I.status = 3 ".$where."  GROUP BY U.ta_name, DATE_FORMAT(I.incident_date,'%Y%m') ORDER BY U.ta_name");

		return $query->result();
	}
    
	public function GetAllCheckpointToday($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT ST.client_id, U.ta_name AS client_name, C.att_shift, COUNT(*) AS jumlah FROM checkpoint C INNER JOIN site ST ON ST.id = C.site_id INNER JOIN ta_users U ON U.id = ST.client_id WHERE C.att_date = '".date('Y-m-d')."' ".$where."  GROUP BY C.site_id, ST.site_name, U.ta_name, C.att_shift ORDER BY U.ta_name, C.att_shift");

		return $query->result();
	}
    
	public function GetAllMasterCheckpoint($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT ST.client_id, U.ta_name AS client_name, COUNT(*) AS jumlah FROM master_checkpoint C INNER JOIN site ST ON ST.id = C.site_id INNER JOIN ta_users U ON U.id = ST.client_id WHERE C.status = '0' ".$where."  GROUP BY ST.client_id, U.ta_name ORDER BY U.ta_name");

		return $query->result();
	}
    
	public function GetAllClient($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND U.id = " . $id;
		$query	= $this->db->query("SELECT U.id, U.ta_name AS client_name FROM ta_users U WHERE U.ta_status = '0' AND U.group_user = '2' ".$where."  ORDER BY U.ta_name");

		return $query->result();
	}
    
	public function GetAllVisitorToday($id)
	{
        $where = '';
        if($_SESSION['login']['group_user'] == 2) $where = "AND ST.client_id = " . $id;
		$query	= $this->db->query("SELECT ST.client_id, U.ta_name AS client_name, V.att_shift, V.total AS jumlah FROM visitor V INNER JOIN site ST ON ST.id = V.site_id INNER JOIN ta_users U ON U.id = ST.client_id WHERE V.att_date = '".date('Y-m-d')."' ".$where."  ORDER BY U.ta_name, V.att_shift");

		return $query->result();
	}
    
    

	public function GetDataReportClient($id)
	{
		$query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_km),0) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_viewer),0) END AS total_viewer, FORMAT(T02.beginning_saldo - T02.remain_saldo,0) AS total_saldo, FORMAT(T02.remain_saldo,0) AS saldo, (ROUND(T02.beginning_saldo,0) * 40)/100 AS warning_saldo, (ROUND(T02.beginning_saldo,0) * 20)/100 AS alert_saldo FROM summary_report_detail_tes T01 LEFT JOIN client_saldo T02 ON T02.client_id = T01.client_id WHERE  T01.report_date <= DATE(NOW()) - INTERVAL 1 DAY AND T01.client_id = ".$id);
		// $query	= $this->db->query("SELECT CASE WHEN SUM(T01.total_km) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_km),0) END AS total_km, CASE WHEN SUM(T01.total_viewer) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_viewer),0) END AS total_viewer, CASE WHEN SUM(T01.total_saldo) IS NULL THEN 0 ELSE FORMAT(SUM(T01.total_saldo),0) END AS total_saldo, '0'saldo FROM summary_report_detail T01 LEFT JOIN member T02 ON T02.id = T01.client_id WHERE T01.client_id = ".$id);

		return $query->result();
	}

	public function GetDailyTask($date)
	{
		$sql 		= "SELECT DISTINCT DISTINCT(report_date), FORMAT(SUM(total_saldo),0) AS total_saldo, FORMAT(ROUND(SUM(total_km),0),0) as total_km, FORMAT(ROUND(SUM(total_viewer),0),0) AS total_viewer FROM summary_report_detail WHERE report_date = '".$date."' GROUP BY report_date ORDER BY report_date DESC LIMIT 7";
		$queries 	= $this->db->query($sql);
		$i 			= 0;
		foreach($queries->result() as $row)
		{
			$result['tanggal'][$i] 		= $row->report_date; 
			$result['totalKm'][$i] 		= $row->total_km;
			$result['totalViewer'][$i]	= $row->total_viewer;
			$result['totalSaldo'][$i]	= $row->total_saldo;
			$i = $i + 1;
		}
		return $result;
	}

	function getDataPerAreas($id, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT DISTINCT(city) FROM member WHERE client_id IN (SELECT id FROM ta_users WHERE client_id = ".intval($id).") GROUP BY city");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT DISTINCT(city), COUNT(*) AS total, client_id AS id FROM member WHERE client_id IN (SELECT id FROM ta_users WHERE client_id = ".intval($id).") GROUP BY city ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getDataPerArea($id)
	{
		$rs	= $this->db->query("SELECT DISTINCT(city), COUNT(*) AS total FROM member WHERE id IN (SELECT id FROM ta_users WHERE client_id = ".intval($id).") GROUP BY city");

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['area'] 	= $row->city;
			$data[$i]['total'] 	= $row->total;
			$i=$i + 1;
		}
		return $data;
	}

	function getDetail($id, $area, $limit, $offset, $order, $search, $sort)
	{
		if ($area == 'all') {
			$rs 	= $this->db->query("SELECT id FROM member WHERE client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).")");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT DISTINCT(T01.id), TRIM(T01.member_name) AS member_name, T01.merk_mobil, T01.type_mobil, T01.mobile_phone, CASE WHEN T02.id IS NOT NULL THEN 1 ELSE 0 END driverStatus FROM member T01 LEFT JOIN tracking_detail T02 ON T02.hpnumber = T01.mobile_phone AND DATE_FORMAT(T02.pinOnTime, '%Y-%m-%d') = '".date('Y-m-d')."' WHERE T01.client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).") ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$city	= str_replace('%20', ' ', $area);
			$rs 	= $this->db->query("SELECT id FROM member WHERE city = '".$city."' AND client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).")");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT DISTINCT(T01.id), TRIM(T01.member_name) AS member_name, T01.merk_mobil, T01.type_mobil, T01.mobile_phone, CASE WHEN T02.id IS NOT NULL THEN 1 ELSE 0 END driverStatus FROM member T01 LEFT JOIN tracking_detail T02 ON T02.hpnumber = T01.mobile_phone AND DATE_FORMAT(T02.pinOnTime, '%Y-%m-%d') = '".date('Y-m-d')."' WHERE T01.city = '".$city."' AND T01.client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).") ORDER BY $sort $order LIMIT $offset,$limit");
		}

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getmarker($where) 
	{
		$this->db->select('lat, lng');
		$this->db->from( "temp_tracking_detail" );
		$this->db->where( $where );
		$this->db->order_by('pinOnTime', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return json_encode($query->row());
	}

	function getLocation($phone, $start, $end) 
	{
		$rs	= $this->db->query("(SELECT T01.lat, T01.`lng`, FORMAT(ROUND(0,2),2) AS total_km, 'start' position FROM temp_tracking_detail T01 LEFT JOIN summary_report_detail_tes T02 ON T02.phone = T01.hpnumber AND T02.id_start = T01.tracking_id WHERE T01.hpnumber = '".$phone."' AND T01.tracking_id = ".$start.") UNION (SELECT T01.lat, T01.`lng`, FORMAT(ROUND(T02.total_km,2),2) AS total_km, 'finish' position FROM temp_tracking_detail T01 LEFT JOIN summary_report_detail_tes T02 ON T02.phone = T01.hpnumber AND T02.id_end = T01.tracking_id WHERE T01.hpnumber = '".$phone."' AND T01.tracking_id = ".$end.")");
		return json_encode($rs->result());
	}

	function GetBestDriver($type) 
	{
		if($type == 'max') {
			$rs	= $this->db->query("SELECT T02.member_name, FORMAT(ROUND(SUM(T01.total_km),0),0) AS total_km, FORMAT(ROUND(SUM(T01.dana_driver),0),0) AS dana_driver, FORMAT(ROUND(SUM(T01.total_viewer),0),0) AS total_viewer FROM summary_report_detail T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.report_date = DATE(NOW() - INTERVAL 1 DAY) GROUP BY T01.driver_id, T01.report_date, T02.member_name ORDER BY dana_driver DESC LIMIT 1");
		} else {
			$rs	= $this->db->query("SELECT T02.member_name, FORMAT(ROUND(SUM(T01.total_km),0),0) AS total_km, FORMAT(ROUND(SUM(T01.dana_driver),0),0) AS dana_driver, FORMAT(ROUND(SUM(T01.total_viewer),0),0) AS total_viewer FROM summary_report_detail T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.report_date = DATE(NOW() - INTERVAL 1 DAY) GROUP BY T01.driver_id, T01.report_date, T02.member_name ORDER BY dana_driver ASC LIMIT 1");
		}
		return $rs->result();
	}


	function countRoute($phone, $date)
	{
		$rs	= $this->db->query("SELECT phone, report_date, id_start, id_end, trip_to FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date = '".$date."' ORDER BY id ASC");
		return $rs->result();
	}

	function getRoute($phone, $start, $end) 
	{
		$rs	= $this->db->query("SELECT lat, lng FROM temp_tracking_detail WHERE tracking_id BETWEEN ".$start." AND ".$end." AND hpnumber = '".$phone."' ORDER BY id ASC");

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['lat'] 	= floatval($row->lat);
			$data[$i]['lng'] 	= floatval($row->lng);
			$i=$i + 1;
		}
		return $data;
	}
}