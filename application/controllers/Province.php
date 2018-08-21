<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Province extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mprovince');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('province/province');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listprovince()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'propinsi_name';
		echo $this->mprovince->listprovince($limit, $offset, $order, $search, $sort);
	}

	public function status($id, $user_status)
	{
		$id	= intval($id);
		if( $user_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'status'		=> $status
		);

		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->updStatus($id, $data, $where, 'province');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('province/add');
			} else {
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mapi->getData("province", $where)->result();
				$this->load->view('province/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getprovince($propinsi_name)
	{
		$propinsi_name = str_replace('%20', ' ', $propinsi_name);
		echo json_encode($this->mprovince->cek_province("province", $propinsi_name));
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$propinsi_name	= trim($this->input->post('propinsi_name'));
		$status			= intval($this->input->post('status'));
		$lat			= trim($this->input->post('lat'));
		$lng			= trim($this->input->post('lng'));
		$zoom			= trim($this->input->post('zoom'));
		$date 			= date('Y-m-d');
		
		$data = array(
			'propinsi_name'	=> $propinsi_name,
			'status'		=> $status,
			'lat'			=> $lat,
			'lng'			=> $lng,
			'zoom'			=> $zoom,
			'created_date'	=> $date
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'province');
		redirect('/province');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'province');
		echo json_encode($status);
	}
}