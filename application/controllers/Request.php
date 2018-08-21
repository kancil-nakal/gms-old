<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mrequest');
		$this->load->library('PHPExcel');
		//$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('request/request');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listRequest()
	{
		$limit		= (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 10;
		$offset 	= (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : 0;
		$order		= (isset($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
		$search 	= (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
		$sort		= (isset($_REQUEST['sort'])) ? $_REQUEST['sort'] : 'name';

		echo $this->mrequest->listRequest($limit, $offset, $order, $search, $sort);
	}

	public function detailRequest($client_id)
	{
		$id 		= intval($client_id);
		$limit		= (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 10;
		$offset 	= (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : 0;
		$order		= (isset($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
		$search 	= (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
		$sort		= (isset($_REQUEST['sort'])) ? $_REQUEST['sort'] : 'request_car';

		echo $this->mrequest->detailRequest($id, $limit, $offset, $order, $search, $sort);
	}

	public function exportExcel($name, $id)
	{
		$id 	= intval($id);
		$data	= $this->mrequest->getExport($id);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("REQUEST CAR"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:D1")->applyFromArray(
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
 
			//table header
			$cols = array("A","B","C","D");

			$val = array("Client","Car","City","Total Request");

			for ($a=0;$a<4; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
			}

			$baris  = 2;
			foreach ($data as $frow) {
				$objset->setCellValue("A".$baris, $frow->ta_name);
				$objset->setCellValue("B".$baris, $frow->car);
				$objset->setCellValue("C".$baris, $frow->city);
				$objset->setCellValue("D".$baris, $frow->request_car);

				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('Request Car');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode($name.'-'.date("d-m-Y").".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			//$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');            
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');        
			$objWriter->save('php://output');
		} else {
			redirect('Excel');
		}
	}
}
