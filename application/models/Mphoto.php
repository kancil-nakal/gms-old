<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mphoto extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function listPhoto($type, $limit, $offset, $order, $search, $sort) {
		if($type == 2) {
			$rs 	= $this->db->query("SELECT T03.member_name FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T01.rit = T02.rit AND DATE_FORMAT(T01.pic_date, '%Y-%m-%d') = DATE_FORMAT(T02.pic_date, '%Y-%m-%d') AND T02.status = 'stop' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T03.client_id = ".$_SESSION['login']['id']);
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT T03.member_name, T03.no_pol, T04.ta_name, T01.rit, T01.picture AS pic_start, DATE_FORMAT(T01.pic_date, '%d %M %Y') AS date_start, DATE_FORMAT(T01.pic_date, '%H:%i:%s') AS time_start, T02.picture AS pic_end, DATE_FORMAT(T02.pic_date, '%d %M %Y') AS date_end, DATE_FORMAT(T02.pic_date, '%H:%i:%s') AS time_end FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T01.rit = T02.rit AND DATE_FORMAT(T01.pic_date, '%Y-%m-%d') = DATE_FORMAT(T02.pic_date, '%Y-%m-%d') AND T02.status = 'stop' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T03.client_id = ".$_SESSION['login']['id']." ORDER BY T01.pic_date DESC, T01.rit DESC, TRIM(T03.member_name) asc LIMIT ".$offset.",".$limit);
			
			/*
			$rs 	= $this->db->query("SELECT T03.member_name FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T02.pic_status = 'end' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.pic_status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T03.client_id = ".$_SESSION['login']['id']);
			$result["total"] = $rs->num_rows();
			
			$rs		= $this->db->query("SELECT T03.member_name, T03.no_pol, T04.ta_name, T01.picture AS pic_start, DATE_FORMAT(T01.pic_date, '%d %M %Y') AS date_start, DATE_FORMAT(T01.pic_date, '%H:%i:%s') AS time_start, T02.picture AS pic_end, DATE_FORMAT(T02.pic_date, '%d %M %Y') AS date_end, DATE_FORMAT(T02.pic_date, '%H:%i:%s') AS time_end FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T02.pic_status = 'end' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.pic_status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') AND T03.client_id = ".$_SESSION['login']['id']." ORDER BY $sort $order LIMIT $offset,$limit");
			*/
		} else {
			
			$rs 	= $this->db->query("SELECT T03.member_name FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T01.rit = T02.rit AND DATE_FORMAT(T01.pic_date, '%Y-%m-%d') = DATE_FORMAT(T02.pic_date, '%Y-%m-%d') AND T02.status = 'stop' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();
			
			
			$rs		= $this->db->query("SELECT T03.member_name, T03.no_pol, T04.ta_name, T01.rit, T01.picture AS pic_start, DATE_FORMAT(T01.pic_date, '%d %M %Y') AS date_start, DATE_FORMAT(T01.pic_date, '%H:%i:%s') AS time_start, T02.picture AS pic_end, DATE_FORMAT(T02.pic_date, '%d %M %Y') AS date_end, DATE_FORMAT(T02.pic_date, '%H:%i:%s') AS time_end FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T01.rit = T02.rit AND DATE_FORMAT(T01.pic_date, '%Y-%m-%d') = DATE_FORMAT(T02.pic_date, '%Y-%m-%d') AND T02.status = 'stop' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') ORDER BY T01.pic_date DESC, T01.rit DESC, TRIM(T03.member_name) asc LIMIT ".$offset.",".$limit);
			/*
			$rs 	= $this->db->query("SELECT T03.member_name FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T02.pic_status = 'end' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.pic_status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%')");
			$result["total"] = $rs->num_rows();
			
			$rs		= $this->db->query("SELECT T03.member_name, T03.no_pol, T04.ta_name, T01.picture AS pic_start, DATE_FORMAT(T01.pic_date, '%d %M %Y') AS date_start, DATE_FORMAT(T01.pic_date, '%H:%i:%s') AS time_start, T02.picture AS pic_end, DATE_FORMAT(T02.pic_date, '%d %M %Y') AS date_end, DATE_FORMAT(T02.pic_date, '%H:%i:%s') AS time_end FROM driver_picture T01 LEFT JOIN driver_picture T02 ON T02.driver_phone = T01.driver_phone AND T02.pic_status = 'end' JOIN member T03 ON T03.mobile_phone = T01.driver_phone LEFT JOIN ta_users T04 ON T04.id = T03.client_id WHERE T01.pic_status = 'start' AND (T03.member_name LIKE '%".$search."%' || T04.ta_name LIKE '%".$search."%') ORDER BY $sort $order LIMIT $offset,$limit");
			*/
		}

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function get_by_range($page, $limit) {
		return $page . ' - ' .$limit;
	}

	public function record_count($type) {
		if($type == 2) {
			$rs	= $this->db->query("SELECT DISTINCT(T01.member_id), REPLACE(T01.member_id,'+62','0') AS phone, T01.images, T02.member_name,T02.no_pol, T01.update_date FROM tracking_picture T01 JOIN ( SELECT MAX(update_date) AS picDate FROM tracking_picture GROUP BY member_id ) T03 ON T03.picDate = T01.update_date LEFT JOIN member T02 ON T02.mobile_phone = REPLACE(T01.member_id,'+62','0') WHERE T02.client_id = ".$_SESSION['login']['id']." GROUP BY T01.member_id ORDER BY T01.pic_id DESC");
		} else {
			$rs	= $this->db->query("SELECT DISTINCT(T01.member_id), REPLACE(T01.member_id,'+62','0') AS phone, T01.images, T02.member_name,T02.no_pol, T01.update_date FROM tracking_picture T01 JOIN ( SELECT MAX(update_date) AS picDate FROM tracking_picture GROUP BY member_id ) T03 ON T03.picDate = T01.update_date LEFT JOIN member T02 ON T02.mobile_phone = REPLACE(T01.member_id,'+62','0') GROUP BY T01.member_id ORDER BY T01.pic_id DESC");
		}

		return $rs->num_rows();
	}

	public function getPhoto($type, $page, $limit)
	{
		if($type == 2) {
			$rs	= $this->db->query("SELECT DISTINCT(T01.member_id), REPLACE(T01.member_id,'+62','0') AS phone, T01.images, T02.member_name,T02.no_pol, T01.update_date FROM tracking_picture T01 JOIN ( SELECT MAX(update_date) as picDate FROM tracking_picture GROUP BY member_id ) T03 ON T03.picDate = T01.update_date LEFT JOIN member T02 ON T02.mobile_phone = REPLACE(T01.member_id,'+62','0') WHERE T02.client_id = ".$_SESSION['login']['id']." GROUP BY T01.member_id ORDER BY T01.pic_id DESC LIMIT $page, $limit");
		} else {
			$rs	= $this->db->query("SELECT DISTINCT(T01.member_id), REPLACE(T01.member_id,'+62','0') AS phone, T01.images, T02.member_name,T02.no_pol, T01.update_date FROM tracking_picture T01 JOIN ( SELECT MAX(update_date) as picDate FROM tracking_picture GROUP BY member_id ) T03 ON T03.picDate = T01.update_date LEFT JOIN member T02 ON T02.mobile_phone = REPLACE(T01.member_id,'+62','0') GROUP BY T01.member_id ORDER BY T01.pic_id DESC LIMIT $page, $limit");
		}

		$i=0;
		foreach($rs->result() as $row)
		{
			$data[$i]['phone']			= $row->phone;
			$data[$i]['member_id']		= $row->member_id;
			$data[$i]['member_name']	= $row->member_name;
			$data[$i]['no_pol']			= $row->no_pol;
			$data[$i]['images']			= $row->images;
			$data[$i]['date']			= $row->update_date;
			$i=$i + 1;
		}
		return $data;
	}
}