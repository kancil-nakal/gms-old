<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_activity extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
        $this->load->helper('pdf_helper');
		$this->load->model('mactivity');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('report/activity');
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
		echo @$this->mactivity->getReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function export($site_id, $date, $shift)
	{
		
        tcpdf();
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        //$title = "PT TRIMITRA PUTRA MANDIRI";
        $title = "GMS - Guard Management System";
        $obj_pdf->SetTitle($title);	
        //$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, "Monthly Report");
        //$obj_pdf->SetHeaderData(null, null, $title, "Activity Report");
        $obj_pdf->SetHeaderData(null, null, $title, "PT TRIMITRA PUTRA MANDIRI");
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->AddPage();
        //ob_start();
        
        $query	= $this->db->query("SELECT A.site_id, A.att_date, A.att_shift, ST.site_name, C.ta_name AS client_name, S.name AS shift_name, T.team_name AS danru FROM activity A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN team T ON T.site_id = A.site_id AND T.shift_id = A.att_shift AND T.position_id = 1 LEFT JOIN master_shift S ON S.id = A.att_shift WHERE A.att_date = '".$date."' AND A.att_shift = '".$shift."' AND A.site_id = ".$site_id." GROUP BY A.site_id, A.att_date, A.att_shift, T.team_name ORDER BY A.att_date DESC, A.att_shift DESC");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$header = $data;
			}
            
            //var_dump($header->att_date);exit();
		}
        
        $html = '
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="text-align:center;font-weight:bold;font-size:14px">LAPORAN KEGIATAN HARIAN SECURITY TPM</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td style="width: 100px">HARI / TGL</td>
                    <td style="width: 10px">:</td>
                    <td style="font-weight:bold">'.date('j F Y', strtotime($header->att_date)).'</td>
                </tr>
                <tr>
                    <td>SHIFT</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->shift_name.'</td>
                </tr>
                <tr>
                    <td>LOKASI TUGAS</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->site_name.'</td>
                </tr>
                <tr>
                    <td>DANRU</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->danru.'</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">1.	Laporan kegiatan / Kejadian Harian</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3" width="100%">
            <thead>
                <tr>
                    <th style="width:30px;text-align:center;font-weight:bold">NO</th>
                    <th style="width:40px;text-align:center;font-weight:bold">JAM</th>
                    <th style="width:230px;text-align:center;font-weight:bold">URAIAN KEGIATAN / KEJADIAN HARIAN</th>
                    <th style="width:200px;text-align:center;font-weight:bold">DOKUMENTASI</th>
                </tr>
            </thead>
            ';
            $query	= $this->db->query("SELECT A.id, A.created_date, A.description, A.created_by FROM activity A WHERE A.att_date = '".$date."' AND A.att_shift = '".$shift."' AND A.site_id = ".$site_id." ORDER BY A.created_date ASC");

            if($query->num_rows() > 0) {
                $i = 1;
                foreach($query->result() as $data) {
            $html .= '
            <tbody>
                <tr>
                    <td style="width: 30px;text-align:center">'.$i++.'</td>
                    <td style="width: 40px;text-align:center">'.substr($data->created_date, -8, 5).'</td>
                    <td style="width: 230px">Nama Petugas: '.$data->created_by.'<br/><br/>'.$data->description.'</td>
                    <td style="width: 200px;text-align:center">
            ';  
                    $query1	= $this->db->query("SELECT * FROM files WHERE attachment_id = '".$data->id."' AND attachment_type = 'activity' ORDER BY id ASC");
                    if($query1->num_rows() > 0) {
                        foreach($query1->result() as $data1) {
                            $photo = $data1->file_name ? 'http://gmsapi.mesinrusak.com/uploads/' . $data1->file_name . '.jpg' :  base_url() . 'assets/images/photo/no-image.png';
                            $photo = 'http://gmsapi.mesinrusak.com/uploads/' . $data1->file_name . '.jpg';
                            $html .= '<img src="'. $photo .'" width="195px">&nbsp;';
                        }
                    }
            $html .= '
                    </td>
                </tr>
            </tbody>';

                }  
            }   
            $html .= '
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">2. Laporan Patroli / Guard Tour</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3">
            <thead>
                <tr>
                    <th style="width: 30px;text-align:center;font-weight:bold">NO</th>
                    <th style="width: 120px;font-weight:bold">NAMA PETUGAS</th>
                    <th style="width: 40px;text-align:center;font-weight:bold">JAM</th>
                    <th style="width: 310px;font-weight:bold">LOKASI</th>
                </tr>
            </thead>
            ';
            $query	= $this->db->query("SELECT A.created_by, A.created_date, MC.location FROM checkpoint A LEFT JOIN master_checkpoint MC ON MC.id = A.checkpoint_id WHERE A.att_date = '".$date."' AND A.att_shift = '".$shift."' AND A.site_id = ".$site_id." ORDER BY A.created_date ASC");

            if($query->num_rows() > 0) {
                $i = 1;
                foreach($query->result() as $data) {
            $html .= '
            <tbody>
                <tr>
                    <td style="width: 30px;text-align:center">'.$i++.'</td>
                    <td style="width: 120px">'.$data->created_by.'</td>
                    <td style="width: 40px;text-align:center">'.substr($data->created_date, -8, 5).'</td>
                    <td style="width: 310px">'.$data->location.'</td>
                </tr>
            </tbody>
            ';
                }  
            }            
            $html .= '
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">3. Laporan Absensi</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3">
            <thead>
                <tr>
                    <th style="width:30px;text-align:center;font-weight:bold">NO</th>
                    <th style="width:120px;font-weight:bold">NAMA PETUGAS</th>
                    <th style="width:100px;font-weight:bold">POSISI</th>
                    <th style="width:70px;text-align:center;font-weight:bold">KEHADIRAN</th>
                    <th style="width:180px;font-weight:bold">KETERANGAN</th>
                </tr>
            </thead>
            ';
            $query	= $this->db->query("SELECT T.team_name, MP.name AS position_name, MA.name AS atttype_name, A.att_reason FROM team_attendance A LEFT JOIN team T ON T.id = A.team_id LEFT JOIN master_attendance MA ON MA.id = A.att_type LEFT JOIN master_position MP ON MP.id = T.position_id WHERE A.att_date = '".$date."' AND A.att_shift = '".$shift."' AND A.site_id = ".$site_id." ORDER BY MP.ordering, T.team_name");

            if($query->num_rows() > 0) {
                $i = 1;
                foreach($query->result() as $data) {
            $html .= '
            <tbody>
                <tr>
                    <td style="width:30px;text-align:center">'.$i++.'</td>
                    <td style="width:120px">'.$data->team_name.'</td>
                    <td style="width:100px">'.$data->position_name.'</td>
                    <td style="width:70px;text-align:center">'.$data->atttype_name.'</td>
                    <td style="width:180px">'.$data->att_reason.'</td>
                </tr>
            </tbody>
            ';
                }  
            }            
        $html .= '
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">4. Laporan Tamu</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            ';
            $query	= $this->db->query("SELECT SUM(A.total) AS total FROM visitor A WHERE A.att_date = '".$date."' AND A.att_shift = '".$shift."' AND A.site_id = ".$site_id);

            if($query->num_rows() > 0) {
                $i = 1;
                foreach($query->result() as $data) {
                    $visitor = $data;
                }  
            }    
            $html .= '
            <tbody>
                <tr>
                    <td>Jumlah Tamu yang datang: <b>'.($visitor->total > 0 ? $visitor->total : 0) .' orang</b></td>
                </tr>
            </tbody>
		</table>
        ';
        
        $content = $html;
        //$content = ob_get_contents();
        //ob_end_clean();
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        $obj_pdf->Output('ActivityReport_'.$header->att_date.'_'.$header->shift_name.'_'.$header->site_name.'.pdf', 'I');
	}
}