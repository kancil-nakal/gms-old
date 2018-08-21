<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->library('PHPExcel');
		//$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('mapi');
		$this->load->model('mwithdraw');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('withdraw/withdraw');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function withdraw()
	{
		$status 	= '0';
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T01.tanggal_withdraw';

		echo $this->mwithdraw->getWithdraw($status, $limit, $offset, $order, $search, $sort);
	}

	public function export($status)
	{
		$data	= $this->mwithdraw->getExport($status);

		if(count($data) > 0) {
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("TEMPELAD")
						->setTitle("WITHDRAW"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Sample Sheet');

			$objget->getStyle("A1:G1")->applyFromArray(
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
			$cols = array("A","B","C","D","E","F","G");
			$val = array("Nama Driver","Tanggal","Nama Bank","Nama Akun","No Rekening","Nama Client","Total");

			for ($a=0;$a<7; $a++)
			{
				$objset->setCellValue($cols[$a].'1', $val[$a]);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

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
				$objset->setCellValue("B".$baris, $frow->withdraw_date);
				$objset->setCellValue("C".$baris, $frow->nama_bank);
				$objset->setCellValue("D".$baris, $frow->nama_akun);
				$objset->setCellValue("E".$baris, $frow->no_rekening);
				$objset->setCellValue("F".$baris, $frow->ta_name);
				$objset->setCellValue("G".$baris, $frow->withdraw_balance);

				$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$baris)->getNumberFormat()->setFormatCode('0');
 
				$baris++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('Withdraw');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('ListWithdraw-'.date("d-m-Y").".xls");

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0'); 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');          
			$objWriter->save('php://output');
		} else {
			redirect('Excel');
		}
	}

	public function pay($id)
	{
		$id	= intval($id);
		$data1 = array(
			'status_eksekusi'	=> '1',
			'tanggal_eksekusi'	=> date('Y-m-d')
		);

		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->updStatus($id, $data1, $where, 'withdraw');
		echo json_encode($status);
	}

	public function finance()
	{
		$status 	= '1';
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T01.tanggal_withdraw';

		echo $this->mwithdraw->getWithdraw($status, $limit, $offset, $order, $search, $sort);
	}

	public function apti()
	{
		$status 	= '2';
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T01.tanggal_withdraw';

		echo $this->mwithdraw->getWithdraw($status, $limit, $offset, $order, $search, $sort);
	}

	public function transfer($id)
	{
		$id	= intval($id);
		$data1 = array(
			'status_eksekusi'	=> '2',
			'tanggal_eksekusi'	=> date('Y-m-d')
		);

		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->updStatus($id, $data1, $where, 'withdraw');
		echo json_encode($status);
	}
}