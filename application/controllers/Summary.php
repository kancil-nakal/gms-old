<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->library('PHPExcel');
		//$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('msummary');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('summary/summary');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getReport()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T01.report_date';
		echo @$this->msummary->getReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function reportDetail($tanggal, $id = null)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T02.member_name';
		echo $this->msummary->detailReport($tanggal, $id, $limit, $offset, $order, $search, $sort);
	}

	public function export($client_id, $date)
	{
		$data	= $this->msummary->getExport($client_id, $date);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("SUMMARY REPORT"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:H1")->applyFromArray(
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
 
			$cols = array("A","B","C","D","E","F","G","H");
			$val = array("Nama Driver","Mobil","Area","Total KM","Total Dana","Dana Driver","Dana Tempel`AD","Viewer");

			for ($a=0;$a<8; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->member_name);
				$objset->setCellValue("B".$baris, $frow->mobil);
				$objset->setCellValue("C".$baris, $frow->city);
				$objset->setCellValue("D".$baris, $frow->total_km);
				$objset->setCellValue("E".$baris, $frow->total_saldo);
				$objset->setCellValue("F".$baris, $frow->dana_driver);
				$objset->setCellValue("G".$baris, $frow->dana_tempelad);
				$objset->setCellValue("H".$baris, $frow->total_viewer);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('Summary Report');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('SummaryReport-'.$client_id.'-'.$date.".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
			redirect('summary');
		}
	}


	public function exportClient($client_id, $date)
	{
		$data	= $this->msummary->getExport($client_id, $date);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("SUMMARY REPORT"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:E1")->applyFromArray(
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
 
			$cols = array("A","B","C","D","E");
			$val = array("Nama Driver","Mobil","Area","Total KM","Viewer");

			for ($a=0;$a<5; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->member_name);
				$objset->setCellValue("B".$baris, $frow->mobil);
				$objset->setCellValue("C".$baris, $frow->city);
				$objset->setCellValue("D".$baris, $frow->total_km);
				$objset->setCellValue("E".$baris, $frow->total_viewer);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$baris)->getNumberFormat()->setFormatCode('0');
				$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('Summary Report');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('SummaryReport-'.$client_id.'-'.$date.".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
			redirect('summary');
		}
	}
}