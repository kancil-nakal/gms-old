<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MWithdraw extends CI_Model {
	function getWithdraw($status, $limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT T01.id FROM withdraw T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.hpnumber WHERE T01.status_eksekusi = '".$status."' AND T02.member_name LIKE '%".$search."%'");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT T01.id, T02.member_name, DATE_FORMAT(T01.tanggal_withdraw, '%d %M %Y') AS withdraw_date, DATE_FORMAT(T01.tanggal_eksekusi, '%d %M %Y') AS pay_date, T02.nama_bank, T02.nama_akun, T02.no_rekening, FORMAT(T01.jumlah_withdraw, 0) AS withdraw_balance, DATE_FORMAT(T01.tanggal_eksekusi, '%d %M %Y') AS tanggal_eksekusi, US.ta_name FROM withdraw T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.hpnumber LEFT JOIN ta_users US ON US.id = T02.client_id WHERE T01.status_eksekusi = '".$status."' AND T02.member_name LIKE '%".$search."%' ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function getExport($status)
	{ 
		$query	= $this->db->query("SELECT T02.member_name, DATE_FORMAT(T01.tanggal_withdraw, '%d %M %Y') AS withdraw_date, T02.nama_bank, T02.nama_akun, T02.no_rekening, FORMAT(T01.jumlah_withdraw, 0) AS withdraw_balance, US.ta_name FROM withdraw T01 LEFT JOIN member T02 ON T02.mobile_phone = T01.hpnumber LEFT JOIN ta_users US ON US.id = T02.client_id WHERE T01.status_eksekusi = '".$status."' ORDER BY T01.tanggal_withdraw");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$hasil[] = $data;
			}
			return $hasil;
		}
	}
}
