<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('mdashboard');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$date 				= date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y') ));
			$this->load->view('blocks/header');
            
			$data['allsecday']	    = @$this->mdashboard->GetAllSecurityToday($_SESSION['login']['id']);
			$data['preclisecday']   = @$this->mdashboard->GetClientSecurityToday($_SESSION['login']['id']);
			$data['cliincmon']	    = @$this->mdashboard->GetClientIncidentMonthly($_SESSION['login']['id']);
			$data['allchpoday']	    = @$this->mdashboard->GetAllCheckpointToday($_SESSION['login']['id']);
			$data['allmaschpo']	    = @$this->mdashboard->GetAllMasterCheckpoint($_SESSION['login']['id']);
			$data['allclient']	    = @$this->mdashboard->GetAllClient($_SESSION['login']['id']);
			$data['allvisday']	    = @$this->mdashboard->GetAllVisitorToday($_SESSION['login']['id']);
            
			if($_SESSION['login']['group_user'] == 2) { //  client
            
                $query	= $this->db->query("SELECT count(*) FROM team t JOIN site s ON s.id = t.site_id WHERE s.client_id = ".$_SESSION['login']['id']);
                $cekTeam = $query->result();
                
				if($cekTeam == 0) {
					$this->load->view('manage/manage');
				} else {
					$data['dailyreport'] 	= @$this->mdashboard->GetDailyReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $date);
					$this->load->view('dashboard/dashboardclient', $data);
				}
			} else { // selain client
				$data['dailyreport'] 	= @$this->mdashboard->GetDailyReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $date);
				$data['client']			= $this->mapi->getClient();
				$this->load->view('dashboard/dashboard', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			//redirect('/auth', 'refresh');
			redirect('/auth');
		}
	}

	public function mapAdmin()
	{
		echo json_encode(@$this->mdashboard->getMapAdmin());
	}

	public function getArea()
	{
		$id			= intval($_SESSION['login']['id']);
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 5;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'total';

		echo $this->mdashboard->getDataPerAreas($id, $limit, $offset, $order, $search, $sort);
	}

	public function getSaldo($id = null)
	{
		$id = intval($id);
		echo json_encode($this->mdashboard->GetDataReportClient($id));
	}

	public function getDetail($area)
	{
		$id			= intval($_SESSION['login']['id']);
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 5;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'member_name';

		echo $this->mdashboard->getDetail($id, $area, $limit, $offset, $order, $search, $sort);
	}

	public function getmarker()
	{
		$phone	= (isset($_GET['id'])) ? $_GET['id'] : '0';
		$where 	= array(
			'hpnumber'	=> $phone
		);
		echo $this->mdashboard->getmarker($where);
	}

	public function location()
	{
		$phone		= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
		$start		= (isset($_GET['start'])) ? intval($_GET['start']) : '0';
		$end		= (isset($_GET['end'])) ? intval($_GET['end']) : '0';
//		$pinOnTime	= date('Y-m-d');
		echo $this->mdashboard->getLocation($phone, $start, $end);
	}

	// public function location()
	// {
	// 	$hpnumber	= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
	// 	$pinOnTime	= date('Y-m-d');
	// 	echo $this->mdashboard->getLocation($hpnumber, $pinOnTime);
	// }


	public function countRoute() {
		$phone	= (isset($_GET['id'])) ? $_GET['id'] : '0';
		$date	= (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
		echo json_encode($this->mdashboard->countRoute($phone, $date));
	}

	public function getroute() {
		$phone		= (isset($_GET['phone'])) ? $_GET['phone'] : '0';
		$start		= (isset($_GET['start'])) ? intval($_GET['start']) : '0';
		$end		= (isset($_GET['end'])) ? intval($_GET['end']) : '0';		
		echo json_encode($this->mdashboard->getRoute($phone, $start, $end));
	}

	// public function getroute() {
	// 	$phone	= (isset($_GET['id'])) ? $_GET['id'] : '0';
	// 	echo json_encode($this->mdashboard->getRoute($phone));
	// }
}
