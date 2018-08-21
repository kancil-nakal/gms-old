<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mrequest extends CI_Model {
	public function listRequest($limit, $offset, $order, $search, $sort) {
		$rs 	= $this->db->query("SELECT DISTINCT(T02.ta_name), T01.request_date FROM temp_request_car T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE (T02.ta_name LIKE '%".$search."%' || T01.request_date LIKE '%".$search."%') GROUP BY T02.ta_name, T01.request_date");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT DISTINCT(T02.ta_name) AS name, T01.client_id, T01.request_date, SUM(T01.request_car) AS total FROM temp_request_car T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE (T02.ta_name LIKE '%".$search."%' || T01.request_date LIKE '%".$search."%') GROUP BY T02.ta_name, T01.request_date ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function detailRequest($id, $limit, $offset, $order, $search, $sort) {
		$rs 	= $this->db->query("SELECT * FROM temp_request_car WHERE client_id = ".$id." AND (city LIKE '%".$search."%' || merk_mobil LIKE '%".$search."%' || type_mobil LIKE '%".$search."%')");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT * FROM temp_request_car WHERE client_id = ".$id." AND (city LIKE '%".$search."%' || merk_mobil LIKE '%".$search."%' || type_mobil LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getExport($id)
	{
		$query	= $this->db->query("SELECT T02.ta_name, CONCAT_WS(' ', T01.merk_mobil, T01.type_mobil) AS car, T01.city, T01.request_car FROM temp_request_car T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.client_id = ".$id."");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}
}
