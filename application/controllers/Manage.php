<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmanage');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('manage/manage');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listCar()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'city';

		echo $this->mmanage->listCar($_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function Add()
	{
		if($this->session->userdata('login')) {
			if($_SESSION['login']['group_user'] == '2') {
				$this->load->view('blocks/header');
				$this->load->view('manage/add');
				$this->load->view('blocks/footer');
			} else {
				$this->output->set_status_header('404');
				$this->load->view('404');
			}
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getArea()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'total';

		echo $this->mmanage->getArea($limit, $offset, $order, $search, $sort);		
	}

	public function getDetail($area)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'totals';

		echo $this->mmanage->getDetail($_SESSION['login']['id'], $area, $limit, $offset, $order, $search, $sort);		
	}

	public function requet()
	{
//		$session	= $this->session->userdata('login');
		$area		= (isset($_GET['area'])) ? $_GET['area'] : 'all';
		$unit		= (isset($_GET['unit'])) ? intval($_GET['unit']) : 0;
		$merk		= (isset($_GET['merk'])) ? $_GET['merk'] : 'all';
		$type		= (isset($_GET['type'])) ? $_GET['type'] : 'all';

		if($unit == 0) {
			$where = array(
				'client_id'		=> $_SESSION['login']['id'],
				'city'			=> $area,
				'merk_mobil'	=> $merk,
				'type_mobil'	=> $type
			);
			$status	= $this->mmanage->dltCar($where, 'temp_request_car');
		} else {
			$cekData = array(
				'client_id'		=> $_SESSION['login']['id'],
				'city'			=> $area,
				'merk_mobil'	=> $merk,
				'type_mobil'	=> $type
			);
			$cek = $this->mmanage->cek_data("temp_request_car", $cekData)->num_rows();
			if($cek == 0) {
				$data = array(
					'client_id'		=> $_SESSION['login']['id'],
					'city'			=> $area,
					'merk_mobil'	=> $merk,
					'type_mobil'	=> $type,
					'request_car'	=> $unit,
					'request_date'	=> date('Y-m-d')
				);
				$status	= $this->mmanage->addCar($data, 'temp_request_car');
			} else if($cek > 0) {
				$data = array(
					'request_car'	=> $unit,
				);

				$where = array(
					'client_id'		=> $_SESSION['login']['id'],
					'city'			=> $area,
					'merk_mobil'	=> $merk,
					'type_mobil'	=> $type
				);
				$status	= $this->mmanage->updCar($data, $where, 'temp_request_car');
			}
		}
		echo json_encode($status);
 	}
}