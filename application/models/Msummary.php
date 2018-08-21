<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msummary extends CI_Model {
	public function getReport($group, $id, $limit, $offset, $order, $search, $sort)
	{
		if($group == 1 || $group == 3 || $group == 5) {
			$rs 	= $this->db->query("SELECT T01.client_id, T02.ta_name FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' GROUP BY T01.client_id, T02.ta_name, T01.report_date");
			$result["total"] = $rs->num_rows();

			$rs	= $this->db->query("SELECT T01.client_id, T02.ta_name AS client_name, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' GROUP BY T01.client_id, T02.ta_name, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$rs 	= $this->db->query("SELECT T01.client_id, T02.ta_name FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$id." GROUP BY T01.client_id, T02.ta_name, T01.report_date");
			$result["total"] = $rs->num_rows();

			$rs	= $this->db->query("SELECT T01.client_id, T02.ta_name AS client_name, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal FROM summary_report_header T01	LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$id." GROUP BY T01.client_id, T02.ta_name, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");
		} 
		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function detailReport($tanggal, $id, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T02.member_name FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.id = T01.driver_id WHERE T01.report_date = '".$tanggal."' AND T01.client_id = ".$id." GROUP BY T01.driver_id, T01.report_date");
			$result["total"] = $rs->num_rows();

		$rs	= $this->db->query("SELECT T02.member_name, CONCAT_WS(' ', T02.merk_mobil, T02.type_mobil) AS mobil, T02.city, MAX(T01.trip_to) AS total_rit, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.dana_driver), 0), 0) AS dana_driver, FORMAT(ROUND(SUM(T01.dana_tempelad), 0), 0) AS dana_tempelad, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, T01.phone, T01.report_date FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.id = T01.driver_id WHERE T01.report_date = '".$tanggal."' AND T01.client_id = ".$id." GROUP BY T01.driver_id,  T02.member_name, T01.phone, T02.merk_mobil, T02.type_mobil, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getExport($client_id, $date)
	{
		$query	= $this->db->query("SELECT T02.member_name, CONCAT_WS(' ', T02.merk_mobil, T02.type_mobil) AS mobil, T02.city, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.dana_driver), 0), 0) AS dana_driver, FORMAT(ROUND(SUM(T01.dana_tempelad), 0), 0) AS dana_tempelad, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, T01.phone, T01.report_date FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.id = T01.driver_id WHERE T01.report_date = '".$date."' AND T01.client_id = ".$client_id." GROUP BY T01.driver_id, T01.report_date ORDER BY T02.member_name ASC");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}
}