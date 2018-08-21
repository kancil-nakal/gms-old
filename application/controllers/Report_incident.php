<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_incident extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
        $this->load->helper('pdf_helper');
		$this->load->model('mincident');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('report/incident');
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
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'ASC';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'A.team_name';
		echo @$this->mincident->getReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function export($id)
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
        ob_start();
        
        $query	= $this->db->query("SELECT A.*, S.site_name, C.ta_name AS client_name, T.team_name AS report_maker  FROM incident A LEFT JOIN site S ON S.id = A.site_id LEFT JOIN ta_users C ON C.id = S.client_id LEFT JOIN team T ON T.id = A.team_id WHERE A.id = '".$id."'");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$header = $data;
			}
            
            //var_dump($header->att_date);exit();
		}
        
        $content = '
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="text-align:center;font-weight:bold;font-size:14px">INCIDENT REPORT</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="font-weight:bold">IR Number: '.$header->incident_no.'</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3" style="width:500px">
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">PEMBUAT LAPORAN / REPORTER</td>
                </tr>
                <tr>
                    <td style="width: 80px">Nama</td>
                    <td style="width:170px;font-weight:bold">'.$header->modified_by.'</td>
                    <td style="width: 80px">Alias</td>
                    <td style="width:170px;font-weight:bold">'.$header->modified_alias.'</td>
                </tr>
                <tr>
                    <td style="width: 80px">Department</td>
                    <td style="width:170px;font-weight:bold">'.$header->modified_department.'</td>
                    <td style="width:170px;width: 80px">Job Title</td>
                    <td style="font-weight:bold">'.$header->modified_position.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center;font-weight:bold">KEJADIAN / CASE</td>
                </tr>
                <tr>
                    <td style="width: 80px">Subject</td>
                    <td colspan="3" style="width:420px;font-weight:bold">'.$header->subject.'</td>
                </tr>
                <tr>
                    <td style="width: 80px">Location</td>
                    <td colspan="3" style="width:420px;font-weight:bold">'.$header->location.'</td>
                </tr>
                <tr>
                    <td style="width: 80px">Date</td>
                    <td style="width:170px;font-weight:bold">'.$header->incident_date.'</td>
                    <td style="width: 80px">Time</td>
                    <td style="width:170px;font-weight:bold">'.$header->incident_time.'</td>
                </tr>
                <tr>
                    <td style="width: 80px">Reported by</td>
                    <td style="width:170px;font-weight:bold">'.$header->report_maker.'</td>
                    <td style="width: 80px">Date Reported</td>
                    <td style="width:170px;font-weight:bold">'.$header->created_date.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">PIHAK-PIHAK TERKAIT / RELATED PARTIES</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;">'.$header->parties.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">KRONOLOGIS KEJADIAN / CHRONOLOGY OF INCIDENT</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;">'.$header->description.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">TINDAKAN YANG DIAMBIL / ACTION TAKEN</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;">'.$header->action.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">ANALISA / ANALYSIS</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;">'.$header->analysis.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">SARAN / RECOMMENDATION</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;">'.$header->advice.'</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;font-weight:bold">DOKUMENTASI / DOCUMENTATION</td>
                </tr>
                <tr>
                    <td colspan="4" style="width:500px;text-align:center;">';
                    
                   
                    $query1	= $this->db->query("SELECT * FROM files WHERE attachment_id = '".$header->id."' AND attachment_type = 'incident' ORDER BY id ASC");
                    if($query1->num_rows() > 0) {
                        foreach($query1->result() as $data1) {
                            $photo = $data1->file_name ? 'http://gmsapi.mesinrusak.com/uploads/' . $data1->file_name . '.jpg' :  base_url() . 'assets/images/photo/no-image.png';
                            $photo = 'http://gmsapi.mesinrusak.com/uploads/' . $data1->file_name . '.jpg';
                            $content .= '<img src="'. $photo .'" width="300px"><br/>';
                        }
                    }
                    
                    $content .= '</td>
                </tr>
		</table>';
        
        //$content = ob_get_contents();
        //ob_end_clean();
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        $obj_pdf->Output('output.pdf', 'I');
	}
}