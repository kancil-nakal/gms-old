<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mxtools extends CI_Model {

	public function getDataFundClient()
	{
		$que = "SELECT b.client_id, sum(saldo) as saldo FROM `driver_balance` a left join member b on a.driver_id = b.id group by b.client_id";
		$query = $this->db->query($que);
		foreach($query->result() as $row)
		{
			$saldoawal  = $this->getSaldoAwal($row->client_id);
			$saldoakhir = $row->saldo;
			if(0.40 * $saldoawal >= ($saldoawal - $saldoakhir))
			//40% * 100.000.000 = 40.000.000 /// 100.000.000 - 80.000.000 = 20.000.000
			{
				mail("m.iqbal.hanifah@gmail.com","Saldo Kurang dari 40%"," SAldo client sudah mencapai batas 40% atau kurang");
			}
		}
		echo "end process";
	}

	public function getSaldoAwal($id)
	{
		$que = "SELECT beginning_saldo from client_saldo where client_id = '".$id."'";
		echo $que;
		$query = $this->db->query($que);
		foreach($query->result() as $row)
		{
			$saldoawal = $row->beginning_saldo;
		}
		return @$saldoawal;
	}

	public function getSetoranAwal($clientID)
	{
		$i = 0;
		$que = "select *, sum(total_km) as total_km, sum(total_saldo) as total_saldo, sum(dana_driver) as dana_driver from summary_report_detail_tes a left join member b on a.phone = b.mobile_phone where a.client_id = '".$clientID."' group by report_date, driver_id";
		$query = $this->db->query($que);
		foreach($query->result() as $row)
		{
			
			$result[$i]['date'] = $row->report_date;
			$result[$i]['phone'] = $row->phone;
			$result[$i]['membername'] = $row->member_name;
			$result[$i]['totalkm'] = $row->total_km;
			$result[$i]['totalsaldo'] = $row->total_saldo;
			$result[$i]['danadriver'] = floor($row->dana_driver/1000) * 1000;
			$result[$i]['danadriverbaru'] = floor($row->total_km) * 750;
			$result[$i]['danatempelad'] = $row->dana_tempelad;
			$result[$i]['danaselisih'] = $row->total_saldo - ($result[$i]['danadriver'] + $row->dana_tempelad);
			$i = $i + 1;
		}

		return $result;
	}

	public function getMoneyData($clientID)
	{
		$que = "select *, sum(jumlah_asli) as jumlah_asli from member a left join withdraw b on a.mobile_phone = b.hpnumber where client_id = '".$clientID."' and status_eksekusi in ('2') group by status_eksekusi, b.hpnumber";
		
		$query = $this->db->query($que);
		echo $this->db->last_query();
		$i=0;
		foreach($query->result() as $row)
		{
			$ret[$i]['jumlahwithdraw'] = $row->jumlah_withdraw;
			$ret[$i]['membername'] = $row->member_name;
			$ret[$i]['statusexe'] = $row->status_eksekusi;
			$ret[$i]['totalearn'] = $this->getEarned($row->mobile_phone,$clientID);
			$i = $i + 1;
		}
		//print_r($ret);
		return $ret;
	}

	public function getBayar($nomorhp,$clientid)
	{
		$que = "select *, sum(jumlah_asli) as jumlah_asli from member a left join withdraw b on a.mobile_phone = b.hpnumber where client_id = '".$clientID."' and status_eksekusi in ('0') group by status_eksekusi, b.hpnumber";
		$query = $this->db->query($que);
		foreach($query->result() as $row)
		{

		}
	}

	public function getEarned($nomorhp,$clienid)
	{
		$this->db->select("sum(dana_driver) as totalearn");
		$this->db->where('phone',$nomorhp);
		$this->db->where('client_id',$clienid);
		$que = $this->db->get('summary_report_detail_tes');
		//echo $this->db->last_query();
		foreach($que->result() as $row)
		{
			$return = floor($row->totalearn/1000) * 1000;
		}
		return $return;
	}
	public function getReport($group, $id, $limit, $offset, $order, $search, $sort)
	{
		if($group == 1 || $group == 5) {
			$rs 	= $this->db->query("SELECT T01.client_id, T02.ta_name FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' GROUP BY T01.client_id, T01.report_date");
			$result["total"] = $rs->num_rows();

			$rs	= $this->db->query("SELECT T01.client_id, T02.ta_name AS client_name, FORMAT(ROUND(T01.total_km, 0), 0) AS total_km, FORMAT(ROUND(T01.total_saldo, 0), 0) AS total_saldo, FORMAT(ROUND(T01.total_viewer, 0), 0) AS total_viewer, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' GROUP BY T01.client_id, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$rs 	= $this->db->query("SELECT T01.client_id, T02.ta_name FROM summary_report_header T01 LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$id." GROUP BY T01.client_id, T01.report_date");
			$result["total"] = $rs->num_rows();

			$rs	= $this->db->query("SELECT T01.client_id, T02.ta_name AS client_name, FORMAT(ROUND(T01.total_km, 0), 0) AS total_km, FORMAT(ROUND(T01.total_saldo, 0), 0) AS total_saldo, FORMAT(ROUND(T01.total_viewer, 0), 0) AS total_viewer, DATE_FORMAT(T01.report_date, '%d %M %Y') AS report_date, T01.report_date AS tanggal FROM summary_report_header T01	LEFT JOIN ta_users T02 ON T02.id = T01.client_id WHERE T01.report_date <= DATE(NOW() - INTERVAL 1 DAY) AND T02.ta_name LIKE '%".$search."%' AND T01.client_id = ".$id." GROUP BY T01.client_id, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");
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

		$rs	= $this->db->query("SELECT T02.member_name, CONCAT_WS(' ', T02.merk_mobil, T02.type_mobil) AS mobil, T02.city, FORMAT(ROUND(SUM(T01.total_km), 0), 0) AS total_km, FORMAT(ROUND(SUM(T01.total_saldo), 0), 0) AS total_saldo, FORMAT(ROUND(SUM(T01.dana_driver), 0), 0) AS dana_driver, FORMAT(ROUND(SUM(T01.dana_tempelad), 0), 0) AS dana_tempelad, FORMAT(ROUND(SUM(T01.total_viewer), 0), 0) AS total_viewer, T01.phone, T01.report_date FROM summary_report_detail_tes T01 LEFT JOIN member T02 ON T02.id = T01.driver_id WHERE T01.report_date = '".$tanggal."' AND T01.client_id = ".$id." GROUP BY T01.driver_id, T01.report_date ORDER BY $sort $order LIMIT $offset,$limit");

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