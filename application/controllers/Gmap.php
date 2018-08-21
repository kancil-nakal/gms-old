<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gmap extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mgmap');
		// $this->load->model('mdashboard');
	}

	public function location($province, $client)
	{
		echo json_encode(@$this->mgmap->location($_SESSION['login']['group_user'], $_SESSION['login']['id'], $province, $client));
	}

	public function map($id)
	{
		$id = intval($id);
		echo json_encode( $this->mgmap->getMap($id) );
	}

	public function latlng($province_id)
	{
		$id = intval($province_id);
		$where = array(
			'id'	=> $id
		);
		echo json_encode( $this->mgmap->latlng($where, 'province') );
	}

	// public function getDetail($area)
	// {
	// 	$session	= $this->session->userdata('login');
	// 	$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 5;
	// 	$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
	// 	$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
	// 	$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
	// 	$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'member_name';

	// 	echo $this->mdashboard->getDetail($session['id'], $area, $limit, $offset, $order, $search, $sort);
	// }

	// public function map()
	// {
	// 	// $id = 476;
	// 	$session	= $this->session->userdata('login');
	// 	echo json_encode($this->mdashboard->getMap($session['id']));
	// 	// echo json_encode( $this->mdashboard->getMap($id) );
	// }


	// public function tesdidi()
	// {
	// 	if($this->session->userdata('login')) {
	// 		$session			= $this->session->userdata('login');
	// 		$this->load->view('blocks/header');
	// 		if($session['user_type'] == '99') {
	// 			$data['dailytask']	= $this->mdashboard->GetDataDashboard();
	// 			$this->load->view('dashboard/dashboard', $data);
	// 		} else if($session['user_type'] == '1') {
	// 			$where = array(
	// 				'client_id'	=> $session['id']
	// 			);

	// 			$cek = $this->mapi->cek_data("client_detail", $where)->num_rows();
	// 			if($cek > 0) {
	// 				$data['car_per_area']	= $this->mapi->getDataPerArea($session['id']);
	// 			} else {
	// 				$data['car_per_area']	= [];
	// 			}
	// 			$this->load->view('dashboard/dashboardclienttes', $data);
	// 			// $this->load->view('dashboard/dashboardclient');
	// 		}
	// 		$this->load->view('blocks/footer');
	// 	} else {
	// 		$this->load->helper(array('form'));
	// 		redirect('/auth');
	// 	}
	// }
}
