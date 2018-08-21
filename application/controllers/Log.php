<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->library('PHPExcel');
		//$this->load->library('PHPExcel/IOFactory');
		//$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('mlog');
		$this->load->model('mdashboard');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('log/log');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getLog()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'TRIM(T03.member_name)';
		echo $this->mlog->getLog($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function getLogDetail($phone)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'tanggal desc, trip_to';
		echo $this->mlog->getLogDetail($phone, $limit, $offset, $order, $search, $sort);
	}

	public function getLogDetailClient($phone)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'tanggal';
		echo $this->mlog->getLogDetailClient($phone, $limit, $offset, $order, $search, $sort);
	}

	public function getmarker()
	{
		$hpnumber	= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
		$pinOnTime	= (isset($_GET['date'])) ? $_GET['date'] : '0000-00-00';
		$where 	= array(
			"hpnumber"								=> $hpnumber,
			"DATE_FORMAT(pinOnTime, '%Y-%m-%d') = " => $pinOnTime,
		);
		echo $this->mdashboard->getmarker($where);
	}

	public function location()
	{
		$hpnumber	= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
		$pinOnTime	= (isset($_GET['date'])) ? $_GET['date'] : '0000-00-00';
		echo $this->mdashboard->getLocation($hpnumber, $pinOnTime);
	}

	public function getRoute() {
		$hpnumber	= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
		$pinOnTime	= (isset($_GET['date'])) ? $_GET['date'] : '0000-00-00';
		echo json_encode($this->mlog->getRoute($hpnumber, $pinOnTime));
	}

	public function export($phone)
	{
		$data	= $this->mlog->getExport($phone);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("LOG DRIVER"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:F1")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '92d050')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
 
			$cols = array("A","B","C","D","E","F");
			$val = array("Tanggal","Total Dana","Dana Driver","Dana Tempel`AD","Total KM","Total Viewer");

			for ($a=0;$a<6; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->report_date);
				$objset->setCellValue("B".$baris, $frow->total_saldo);
				$objset->setCellValue("C".$baris, $frow->dana_driver);
				$objset->setCellValue("D".$baris, $frow->dana_tempelad);
				$objset->setCellValue("E".$baris, $frow->total_km);
				$objset->setCellValue("F".$baris, $frow->total_viewer);

				$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('LOG DRIVER');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('LogDriver-'.$phone.".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
			redirect('log');
		}
	}

	public function exportClient($phone)
	{
		$data	= $this->mlog->getExportClient($phone);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("LOG DRIVER"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:F1")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '92d050')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
 
			$cols = array("A","B","C","D");
			$val = array("Tanggal","Total Dana","Total KM","Total Viewer");

			for ($a=0;$a<4; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->report_date);
				$objset->setCellValue("B".$baris, $frow->total_saldo);
				$objset->setCellValue("C".$baris, $frow->total_km);
				$objset->setCellValue("D".$baris, $frow->total_viewer);

				$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$baris);//->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris);//->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$baris);//->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('LOG DRIVER');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('LogDriver-'.$phone.".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
			redirect('log');
		}
	}

	public function exportDetail($phone, $date)
	{
		$data	= $this->mlog->getExportDetail($phone, $date);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("LOG DETAIL DRIVER PER DAY"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('LOG DETAIL DRIVER PER DAY');

			$objget->getStyle("A1:N1")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '92d050')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
 
			$cols = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N");
			$val = array("ID","PHONE","LAT & LONG","TIMESTAMP","DURATION","SPEED","DISTANCE","DRIVER RATE","TEMPELAD RATE","DRIVER SHARE","TEMPELAD SHARE","TOTAL","VIEWER","TRIP");

			for ($a=0;$a<14; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(26);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(9);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(7);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			$prev_lat = "";
			$prev_lng = "";
			$prev_time = "";
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->id);
				$objset->setCellValue("B".$baris, $frow->hpnumber);
				$objset->setCellValue("C".$baris, $frow->lat.",".$frow->long);
				$objset->getCell("C".$baris)->getHyperlink()->setUrl("https://www.google.co.id/maps/search/".$frow->lat.",".$frow->long."/");
				$objset->setCellValue("D".$baris, $frow->pinOnTime);
				$objset->setCellValue("E".$baris, $frow->durasi);
				$objset->setCellValue("F".$baris, $frow->speed);
				$objset->setCellValue("G".$baris, $frow->jarak);
				$objset->setCellValue("H".$baris, $frow->rate_driver);
				$objset->setCellValue("I".$baris, $frow->rate_templelad);
				$objset->setCellValue("J".$baris, $frow->dana_driver);
				$objset->setCellValue("K".$baris, $frow->dana_tempelad);
				$objset->setCellValue("L".$baris, $frow->total_dana);
				$objset->setCellValue("M".$baris, $frow->total_viewer);
				$objset->setCellValue("N".$baris, $frow->trip_to);
				
				if($frow->jarak == "") {
					if($prev_lat != "") {
						$jarak = 111.111 * rad2deg(ACOS(COS(deg2rad($prev_lat)) * COS(deg2rad($frow->lat)) * COS(deg2rad($prev_lng - $frow->long)) + SIN(deg2rad($prev_lat)) * SIN(deg2rad($frow->lat))));	
						$jarak = is_nan($jarak) ? 0 : $jarak;
						$objset->setCellValue("G".$baris, number_format($jarak,3));
						
						$durasi = strtotime($frow->pinOnTime) - strtotime($prev_time); 
						$speed = $durasi > 0 ? ($jarak / ($durasi /3600)) : 0; //(strtotime("1970-01-01 $frow->durasi UTC") / 3600); 
						$objset->setCellValue("F".$baris, round($speed));
						
						$objset->setCellValue("E".$baris, gmdate("H:i:s", $durasi)); //$durasi); //date("H:i:s",strtotime("1970-01-01 $durasi UTC")));
					} 
					$objset->getStyle("A".$baris.":N".$baris)->applyFromArray(
						array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb' => 'ffff00')
							)
						)
					);
				} else {
					$prev_lat = $frow->lat;
					$prev_lng = $frow->long;
					$prev_time = $frow->pinOnTime;
				}

				//$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$baris)->getNumberFormat()->setFormatCode('0');
				//$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$baris)->getNumberFormat()->setFormatCode('0');
				//$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$baris)->getNumberFormat()->setFormatCode('0');
				//$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$baris)->getNumberFormat()->setFormatCode('0');
				//$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			$objPHPExcel->getActiveSheet()->setTitle('LOG DETAIL DRIVER PER DAY');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('LogDetailDriverPerDay-'.$phone."-".$date.".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
			redirect('log');
		}
	}
}