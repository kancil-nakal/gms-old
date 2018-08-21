<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmanage extends CI_Model {
	function listCar($id, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT city FROM member WHERE (city LIKE '%".$search."%' || merk_mobil LIKE '%".$search."%' || type_mobil LIKE '%".$search."%') AND client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).") GROUP BY city, type_mobil");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT city, merk_mobil, type_mobil, COUNT(0) AS total FROM member WHERE (city LIKE '%".$search."%' || merk_mobil LIKE '%".$search."%' || type_mobil LIKE '%".$search."%') AND client_id IN (SELECT id FROM ta_users WHERE id = ".intval($id).") GROUP BY city, merk_mobil, type_mobil ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getArea($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.city FROM member T01 LEFT JOIN province T02 ON T02.id = T01.province WHERE T01.client_id = 0 AND T02.status = 0 GROUP BY T01.city");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.city, COUNT(0) AS total FROM member T01 LEFT JOIN province T02 ON T02.id = T01.province WHERE T01.client_id = 0 AND T02.status = 0 GROUP BY T01.city ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);		
	}

	public function getDetail($id, $area, $limit, $offset, $order, $search, $sort)
	{
		if ($area == 'all') {
			$city = '';
		} else {
			$city = str_replace('%20', ' ', $area);
		}

		$rs 	= $this->db->query("SELECT T01.type_mobil FROM member T01 LEFT JOIN province T02 ON T02.id = T01.province LEFT JOIN temp_request_car T03 ON T03.city = T01.city AND T03.merk_mobil = T01.merk_mobil AND T03.type_mobil = T03.type_mobil AND T03.status = '0' AND T03.client_id = ".$id." WHERE T01.driver_status = 0 AND T02.status = 0 AND T01.city LIKE '%".$city."%' GROUP BY T01.type_mobil");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.type_mobil, T01.merk_mobil, COUNT(0) AS totals, T03.request_car FROM member T01 LEFT JOIN province T02 ON T02.id = T01.province LEFT JOIN temp_request_car T03 ON T03.city = T01.city AND T03.merk_mobil = T01.merk_mobil AND T03.type_mobil = T03.type_mobil AND T03.status = '0' AND T03.client_id = ".$id." WHERE T01.driver_status = 0 AND T02.status = 0 AND T01.city LIKE '%".$city."%' GROUP BY T01.type_mobil, T01.merk_mobil ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function dltCar($where, $table)
	{
		$this->db->where($where);
		$query = $this->db->delete($table);
		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "Succes";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}

	public function cek_data($table, $where)
	{
		return $this->db->get_where($table, $where);
	}

	public function addCar($data, $table)
	{
		$query = $this->db->insert($table, $data);
		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "Succes";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}

	public function updCar($data, $where, $table)
	{
		$this->db->where($where);
		$query = $this->db->update($table, $data);
		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "Succes";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}
}