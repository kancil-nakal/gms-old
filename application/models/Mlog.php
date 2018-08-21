<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mlog extends CI_Model {
	public function getLog($group, $id, $limit, $offset, $order, $search, $sort)
	{
		if($group == 1 || $group == 3 || $group == 5) {
			$rs 	= $this->db->query("SELECT DISTINCT(T01.driver_id) FROM summary_report_detail_tes T01 LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT DISTINCT(T01.driver_id), T01.phone, T04.ta_name AS company_name, T03.member_name FROM summary_report_detail_tes T01 LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");
			/*
			$rs 	= $this->db->query("SELECT DISTINCT(T01.driver_id) FROM summary_report_detail_tes T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T02.ta_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT DISTINCT(T01.driver_id), T01.phone, T01.client_id, T02.ta_name AS client_name, T04.ta_name AS company_name, T03.member_name FROM summary_report_detail_tes T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T02.ta_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");
			*/

		} else {
			$rs 	= $this->db->query("SELECT DISTINCT(T01.driver_id) FROM summary_report_detail_tes T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T02.ta_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T01.client_id = ".$id);
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT DISTINCT(T01.driver_id), T01.phone, T01.client_id, T02.ta_name AS client_name, T04.ta_name AS company_name, T03.member_name FROM summary_report_detail_tes T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id LEFT JOIN member T03 ON T03.id = T01.driver_id LEFT JOIN ta_users T04 ON T04.id = T03.company_id WHERE (T03.member_name LIKE '%".$search."%' || T02.ta_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T01.client_id = ".$id." ORDER BY $sort $order LIMIT $offset,$limit");
		}

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getLogDetail($phone, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT report_date AS tanggal FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW()) GROUP BY phone, tanggal");
		$result["total"] = $rs->num_rows();
		
		$rs		= $this->db->query("SELECT DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, MAX(trip_to) AS total_rit, ROUND(SUM(total_saldo), 0) AS total_saldo, ROUND(SUM(dana_driver), 0) AS dana_driver, ROUND(SUM(dana_tempelad), 0) AS dana_tempelad, ROUND(SUM(total_km), 0) AS total_km, ROUND(SUM(total_viewer), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW()) GROUP BY phone, tanggal ORDER BY  tanggal desc LIMIT $offset,$limit");
		/*
		$rs 	= $this->db->query("SELECT report_date AS tanggal FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal");
		$result["total"] = $rs->num_rows();
		
		$rs		= $this->db->query("SELECT DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, MAX(trip_to) AS total_rit, ROUND(SUM(total_saldo), 0) AS total_saldo, ROUND(SUM(dana_driver), 0) AS dana_driver, ROUND(SUM(dana_tempelad), 0) AS dana_tempelad, ROUND(SUM(total_km), 0) AS total_km, ROUND(SUM(total_viewer), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal ORDER BY $sort $order LIMIT $offset,$limit");
		*/
		//$rs 	= $this->db->query("SELECT id, report_date AS tanggal FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal");
		//$result["total"] = $rs->num_rows();
		//$rs		= $this->db->query("SELECTs id, DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, ROUND(SUM(total_saldo), 0) AS total_saldo, ROUND(SUM(dana_driver), 0) AS dana_driver, ROUND(SUM(dana_tempelad), 0) AS dana_tempelad, ROUND(SUM(total_km), 0) AS total_km, ROUND(SUM(total_viewer), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal ORDER BY $sort $order LIMIT $offset,$limit");
		//$rs		= $this->db->query("SELECT id, DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, FORMAT(ROUND(SUM(total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(dana_driver), 0), 0) AS dana_driver, FORMAT(ROUND(SUM(dana_tempelad), 0), 0) AS dana_tempelad, FORMAT(ROUND(SUM(total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(total_viewer), 0), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal ORDER BY $sort $order LIMIT $offset,$limit");
		
		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getLogDetailClient($phone, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.report_date AS tanggal FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.phone = '".$phone."' AND T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.client_id = T01.client_id GROUP BY T01.phone, tanggal");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal, MAX(trip_to) AS total_rit, ROUND(SUM(T01.total_saldo), 0) AS total_saldo, ROUND(SUM(T01.total_km), 0) AS total_km, ROUND(SUM(T01.total_viewer), 0) AS total_viewer, T01.phone, T02.client_id, T01.client_id FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.phone = '".$phone."' AND T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.client_id = T01.client_id GROUP BY T01.phone, T01.report_date, T01.phone, T02.client_id, T01.client_id ORDER BY $sort $order LIMIT $offset,$limit");
		//$rs		= $this->db->query("SELECT T01.id, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, T01.phone, T02.client_id, T01.client_id FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.phone = '".$phone."' AND T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.client_id = T01.client_id GROUP BY T01.phone, tanggal ORDER BY $sort $order LIMIT $offset,$limit");
		
		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	function getRoute($phone, $date) 
	{
		$rs	= $this->db->query("SELECT lat, `lng` FROM temp_tracking_detail WHERE hpnumber = '".$phone."' AND DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".$date."' ORDER BY id ASC");

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['lat'] 	= floatval($row->lat);
			$data[$i]['lng'] 	= floatval($row->lng);
			$i=$i + 1;
		}
		return $data;
	}

	public function getExport($phone)
	{
		$query	= $this->db->query("SELECT id, DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, ROUND(SUM(total_saldo), 0) AS total_saldo, ROUND(SUM(dana_driver), 0) AS dana_driver, ROUND(SUM(dana_tempelad), 0) AS dana_tempelad, ROUND(SUM(total_km), 0) AS total_km, ROUND(SUM(total_viewer), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal ORDER BY tanggal DESC");
		//$query	= $this->db->query("SELECT id, DATE_FORMAT(report_date, '%d %M %Y') AS report_date, report_date AS tanggal, FORMAT(ROUND(SUM(total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(dana_driver), 0), 0) AS dana_driver, FORMAT(ROUND(SUM(dana_tempelad), 0), 0) AS dana_tempelad, FORMAT(ROUND(SUM(total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(total_viewer), 0), 0) AS total_viewer, phone FROM summary_report_detail_tes WHERE phone = '".$phone."' AND report_date <= DATE(NOW() - INTERVAL 1 DAY) GROUP BY phone, tanggal ORDER BY tanggal DESC");
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}

	public function getExportClient($phone)
	{
		$query	= $this->db->query("SELECT T01.id, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal, ROUND(SUM(T01.total_saldo), 0) AS total_saldo, ROUND(SUM(T01.total_km), 0) AS total_km, ROUND(SUM(T01.total_viewer), 0) AS total_viewer, phone FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.phone = '".$phone."' AND T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.client_id = T01.client_id GROUP BY T01.phone, tanggal ORDER BY tanggal DESC");
		//$query	= $this->db->query("SELECT T01.id, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, phone FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.phone WHERE T01.phone = '".$phone."' AND T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.client_id = T01.client_id GROUP BY T01.phone, tanggal ORDER BY tanggal DESC");
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}

	public function getExportDetail($phone, $date)
	{
		$query	= $this->db->query("SELECT a.id, a.hpnumber, a.lat, a.long, a.pinOnTime, b.durasi, b.speed, b.jarak, b.rate_driver, b.rate_templelad, b.total_dana, b.dana_driver, b.dana_tempelad, b.viewer, b.total_viewer, b.trip_to FROM tracking_detail a left join temp_tracking_detail b on a.id=b.tracking_id WHERE a.hpnumber = '".$phone."'  AND DATE_FORMAT(a.pinOnTime, '%Y-%m-%d') = '".$date."' ORDER BY a.id");
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}
}