<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mpanic');
		$this->load->model('mapi');
	}

	public function index()
	{
        
	}

	public function download()
	{
		
        
        //require '/var/www/tapin/baspc/application/libraries/PhpSpreadsheet/samples/Header.php';
        
        error_reporting(E_ALL);
        ini_set('memory_limit', '-1');
        
        require_once '/var/www/tapin/baspc/application/libraries/PhpSpreadsheet/src/Bootstrap.php';
        $helper = new Sample();
        // Return to the caller script when runs by CLI
        if ($helper->isCli()) {
            return;
        }
        
		$link = "/var/www/tapin/baspc/loader/master/";
		$save = "/var/www/tapin/baspc/loader/new_data_hm/";
		
        /*
		$this->load->library("PHPExcel/Classes/PHPExcel");
		
		//$objPHPExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($link."400MSO_BAS_Opt.1_Equip. Operating Statistics_V02 BS02.xlsm");
        */
        
        $inputFileName = $link."400MSO_BAS_Opt.1_Equip. Operating Statistics_V02 BS02.xlsm";
        $inputFileName = $link."400MSO.xlsm";
        //$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
        $spreadsheet = IOFactory::load($inputFileName);
        
        
		$tanggal1 = isset($_GET['tgl_from']) ? $_GET['tgl_from'] : date('Y-m-d', strtotime('-1 day'));
		$tanggal1 = date('Y-m-d', strtotime($tanggal1));
		$tanggalconv = date('dmY', strtotime($tanggal1));
		$tanggal2 = isset($_GET['tgl_to']) ? $_GET['tgl_to'] : date('Y-m-d');
		$tanggal2 = date('Y-m-d', strtotime($tanggal2));
		$shift = isset($_GET['shift']) ? ($_GET['shift']!='' ? " and shift=".$_GET['shift'] : "") : "";
		$unit = isset($_GET['shift']) ? ($_GET['unit']!='' ? " and unit like '%".$_GET['unit']."%'" : "") : "";
		
		$shiftdetail = 'Shift Night';
		$todaydate = date("d-M-Y");
		$todaytime = date("H-i-s");
								
		$row = 2;
		
        /*
		$srvmysql = "localhost";
		$usrmysql = "root";
		$passmysql = "basis12345";
		$dbmysql = "baspc_tapin";
		$conn1 = mysql_connect($srvmysql,$usrmysql,$passmysql) or die ("Couldn't connect to MySQL Server on $srvmysql");
		mysql_select_db($dbmysql,$conn1) or die ("Couldn't open database $dbmysql");

		$data1 = "SELECT tanggal, unit, hm_akhir FROM prestart_inspect WHERE (tanggal >= '$tanggal1' AND tanggal <= '$tanggal2') ".$unit.$shift." ORDER BY tanggal, unit, hm_akhir";
		$data2 = mysql_query($data1);
		$jml = mysql_num_rows($data2);	
		while($data = mysql_fetch_array($data2))
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'M')
	                              ->setCellValue('B'.$row, date('dmY', strtotime($data[0])))
	                              ->setCellValue('C'.$row, 'HM')
	                              ->setCellValue('D'.$row, $data[1])
	                              ->setCellValue('E'.$row, $data[2]);
			$row++;
		}
        */
        
        
		$where_input['tanggal >='] = $tanggal1;
		$where_input['tanggal <='] = $tanggal2;
		if(isset($_GET['shift'])) if($_GET['shift'] != '') $where_input['shift'] = $_GET['shift'];
		if(isset($_GET['unit'])) if($_GET['shift'] != '') $where_input['unit like'] = '%'.$_GET['unit'].'%';
        
        $query = $this->m_db->select('prestart_inspect', $where_input, 'tanggal, unit, hm_akhir desc');
        
        if($query) {
            foreach($query as $q) {
                $spreadsheet->getActiveSheet()->setCellValue('A'.$row, 'M')
                                      ->setCellValue('B'.$row, date('dmY', strtotime($q['tanggal'])))
                                      ->setCellValue('C'.$row, 'HM')
                                      ->setCellValue('D'.$row, $q['unit'])
                                      ->setCellValue('E'.$row, $q['hm_akhir']);
                $row++;
            }
				
			if($tanggal1==$tanggal2){
			$filename = "400MSO_BAS_BS05 (Date ".date("d-M-Y",strtotime($tanggal1))." ".$shiftdetail.") (Create Date ".$todaydate." Time ".$todaytime.").xlsm";
			} else {
			$filename = "400MSO_BAS_BS05 (Filter Date From ".date("d-M-Y",strtotime($tanggal1))." Date To ".date("d-M-Y",strtotime($tanggal2))." ".$shiftdetail.") (Create Date ".$todaydate." Time ".$todaytime.").xlsm";
			}
			$new_file = $save.$filename;
            
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter->save($new_file);
            
            
            //header('Content-Type: application/vnd.ms-excel');
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Expires: 0");
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            //header("Pragma: no-cache");
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($new_file);
            $writer->save('php://output');
			exit();	
			echo 'Generate file successful!';
			echo '<br />File<b><u> '.$filename. '</u></b> created';
			echo '<br />You can access that file from share folder BASPC Server <b><u>\\\192.168.100.9\Loader HM</u></b>';
			echo '<br />If you cannot access share folder, please contact <b>Administrator</b> OR <b>Application Support</b>';
        }
        
	}
}