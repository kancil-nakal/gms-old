<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_team extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
        $this->load->helper('pdf_helper');
		$this->load->model('mteam');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('report/team');
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
		echo @$this->mteam->getReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
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
        //ob_start();
        
        $query	= $this->db->query("SELECT A.*, ST.site_name, C.ta_name AS client_name, S.name AS shift_name, MP.name AS position_name, CT.city_name FROM team A LEFT JOIN site ST ON ST.id = A.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN master_shift S ON S.id = A.shift_id LEFT JOIN master_position MP ON MP.id = A.position_id LEFT JOIN city CT ON CT.id = A.city_id WHERE A.id = '".$id."' AND A.team_status = 0");

		if($query->num_rows() > 0) {
			foreach($query->result() as $data) {
				$header = $data;
			}
            
            //var_dump($header->att_date);exit();
		}
        
        $avatar = $header->avatar ? $header->avatar : 'assets/images/photo/no-image.png';
        //$avatar = 'assets/images/photo/no-image.png';
        
        $html = '
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="text-align:center;font-weight:bold;font-size:14px">SECURITY PROFILE</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td rowspan="8" style="width: 130px"><img src="'.base_url(). $avatar.'" width="120px"></td>
                    <td style="width: 120px">NAMA LENGKAP</td>
                    <td style="width: 10px">:</td>
                    <td style="font-weight:bold">'.$header->team_name.'</td>
                </tr>
                <tr>
                    <td>POSISI</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->position_name.'</td>
                </tr>
                <tr>
                    <td>NO HP</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->mobile_phone.'</td>
                </tr>
                <tr>
                    <td>TANGGAL BERGABUNG</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.date('j F Y', strtotime($header->join_date)).'</td>
                </tr>
                <tr>
                    <td>ALAMAT</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->address.'</td>
                </tr>
                <tr>
                    <td>KOTA</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->city_name.'</td>
                </tr>
                <tr>
                    <td>SITE</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->site_name.'</td>
                </tr>
                <tr>
                    <td>SHIFT</td>
                    <td>:</td>
                    <td style="font-weight:bold">'.$header->shift_name.'</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">1.	Pendidikan</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3" width="100%">
            <tbody>
                <tr>
                    <td style="width: 500px">'.$header->education.'</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">2. Pengalaman Kerja</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="width: 500px">'.$header->experience.'</td>
                </tr>
            </tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">3. Training</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="width: 500px">'.$header->training.'</td>
                </tr>
            </tbody>
		</table>';
        
        if(count((array)json_decode($header->certificate)) > 0) { 
        
        $html .='
		<table border="0" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight:bold">4. Sertifikat</td>
                </tr>
            </tbody>
		</table>
		<table border="1" cellspacing="0" cellpadding="3">
            <tbody>
                <tr>
                    <td style="width: 500px">
                        <ul>
        ';
                        foreach(json_decode($header->certificate) as $row) {
                            $html .= '<li>';
                            $url = base_url()."public/uploads/".$row;
                            $html .= '<a href="'.$url.'" dir="ltr">'.$row.'</a>';
                            $html .= '</li>';
                        }
        $html .='
                        </ul>
                    </td>
                </tr>
            </tbody>
		</table>
        ';
        }
        
        $content = $html;
        //$content = ob_get_contents();
        //ob_end_clean();
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        $obj_pdf->Output('output.pdf', 'I');
	}
}