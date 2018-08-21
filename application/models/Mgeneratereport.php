<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgeneratereport extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function getDataDriver($phone, $date)
	{
		$this->db->select("*");
		$this->db->where('mobile_phone',$phone);
		$query = $this->db->get("member");
		$km = '';
		foreach($query->result() as $row)
		{
			$client = $this->getDataClient($row->client_id);
			$km 	.= $this->getDataKM($row->mobile_phone, $client, $row->client_id, $row->id, $date);
		}

		// $data = '[';
		// $split = explode('/', $km);
		// for($i=0; $i<(count($split) - 1 ); $i++)
		// {
		// 	if($i < (count($split) - 2 )) {
		// 		$delimeter = ',';
		// 	} else {
		// 		$delimeter = '';
		// 	}
		// 	$data .= $split[$i].$delimeter;
		// }
		// $data .= ']';
		return $km;
	}

	public function getDataClient($client_id)
	{
		$this->db->select("ta_name");
		$this->db->where('id',$client_id);
		$query = $this->db->get("ta_users");
		while ($row = $query->unbuffered_row())
		{
			$return = $row->ta_name;
		}
		return $return;
	}

	public function getDataKM($nomor, $client, $client_id, $member_id, $date)
	{
		$total = 0;
		$jarak = '';
		$totaljarak=0;
		$totalrupiah = 0;
		$totalviewer = 0;

		$queryx = "SELECT COUNT(*) AS total FROM tracking_detail WHERE hpnumber = '".$nomor."' AND DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".$date."' AND alreadystart = 1";
		$que = $this->db->query($queryx);
		foreach($que->result() as $row){
			$total = $row->total;
		}

		$iquery = "SELECT * FROM tracking_detail WHERE hpnumber = '".$nomor."' AND DATE_FORMAT(pinOnTime, '%Y-%m-%d') = '".$date."' AND alreadystart = 1 ORDER BY pinOnTime ASC";
		$q = $this->db->query($iquery);
		$i = 0;
		$j = 0;
		$lat[0] = '';

		foreach($q->result() as $rowx)
		{
			$j = $i + 1;

			$pinOnTime[$i]	= $rowx->pinOnTime;
			$hpnumber[$i] = $rowx->hpnumber;
			$lat[$i] = $rowx->lat;
			$long[$i] = $rowx->long;
			$speed[$i] = $rowx->speed;
			$i = $i + 1;
		}

		if($lat[0] !='')
		{
			for($m=0;$m<$i-1;$m++)
			{
				$j = $m + 1;
				$tambahjarak = $this->getDistance($lat[$m],$long[$m],$lat[$j],$long[$j]);				
				$tambahharga = $this->getPriceonSpeed($speed[$m],$tambahjarak);
				$tambahviewer = $this->getPeopleViewer($speed[$m],$tambahjarak);
				$totaljarak = $totaljarak + $tambahjarak;

				if($tambahharga > 0)
				{
					$totalrupiah = $totalrupiah + $tambahharga;
				}
				$totalviewer = $totalviewer + $tambahviewer;
			}

			$jarak .= '{';
				$jarak .= '"report_date": "' . substr($pinOnTime[$m], 0, 10)  .'",';
				$jarak .= '"client_id": ' . $client_id . ',';
				$jarak .= '"client_name": "' . $client .'",';
				$jarak .= '"driver_id": ' . $member_id .',';
				$jarak .= '"driver_phone": "' . $hpnumber[$m] .'",';
				$jarak .= '"total_saldo": ' . floor($totalrupiah) .',';
				$jarak .= '"dana_driver": ' . floor($totalrupiah)*0.75 .',';
				$jarak .= '"total_km": ' . ceil($totaljarak/1000) . ',';
				$jarak .= '"total_viewer": ' . ceil($totalviewer);

			$jarak .= '}/';
		}
		return $jarak;
	}

	public function save_report($filter_data)
	{
		$query = $this->db->insert("summary_report", $filter_data);
	}

	// public function cek_data($table, $where)
	// {
	// 	return $this->db->get_where($table, $where);
	// }

	// public function delReport($table, $where)
	// {
	// 	$this->db->where($where);
	// 	$query = $this->db->delete($table);
	// }

	function rad($x)
	{
		$pi = pi();
		return $x * $pi / 180;
	}

	public function getPriceonSpeed($speed,$km)
	{
    	$harga = 0;
		if($speed > 90)
		{
			$harga = 750;
		}else if($speed > 50 && $speed <=90)
		{
			$harga = 850;
		}else if($speed > 20 && $speed <=50)
		{
			$harga = 950;
		}
		else if($speed > 0 && $speed <=20)
		{
			$harga = 1050;
		}

		$ret = $harga * $km / 1000;
		return $ret;
    }
    public function getPeopleViewer($speed,$km)
    {
    	$viewer = 0;
		if($speed > 90)
		{
			$viewer = 0.5 * (15 / 100);
		}else if($speed > 50 && $speed <=90)
		{
			$viewer = 1.5 * (20 / 100);
		}else if($speed > 20 && $speed <=50)
		{
			$viewer = 3.5 * (50 / 100);
		}
		else if($speed > 0 && $speed <=20)
		{
			$viewer = 4;
		}

		$ret = $viewer * $km / 1000;
		return $ret;
    }

    public function getDistance($p1lat, $p1lng, $p2lat, $p2lng) {
    	//echo $p1lat." ".$p1lng." ".$p2lat." ".$p2lng;
     

      $R = 6378137;
      $dLat = $this->mgeneratereport->rad($p2lat - $p1lat);
      
      $dLong = $this->mgeneratereport->rad($p2lng - $p1lng);
      $a = sin($dLat / 2) * sin($dLat / 2) + cos($this->mgeneratereport->rad($p1lat)) * cos($this->mgeneratereport->rad($p2lat)) * sin($dLong / 2) * sin($dLong / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      $d = $R * $c;
      return $d;
      
    }

	public function akumulasi_saldo($report_date, $client_id)
	{
		$query 	= "SELECT total_saldo, client_id, report_date FROM summary_report WHERE report_date = '".$report_date."' AND client_id = ".$client_id;
		$que 	= $this->db->query($query);

		return $que->result();
	}
}