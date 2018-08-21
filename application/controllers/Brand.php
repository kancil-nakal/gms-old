<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mbrand');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('brand/brand');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth', 'refresh');
		}
	}

	public function listbrand()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'brand_name';
		echo $this->mbrand->listbrand($limit, $offset, $order, $search, $sort);
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

		$status	= $this->mapi->updStatus($id, $data, $where, 'brand_car');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('brand/add');
			} else {
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mapi->getData("brand_car", $where)->result();
				$this->load->view('brand/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth', 'refresh');
		}
	}

	public function getbrand($brand_name)
	{
		$where = array(
			'brand_name'	=> $brand_name,
		);
		echo json_encode($this->mbrand->cek_brand("brand_car", $where)->num_rows());
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$brand_name		= trim($this->input->post('brand_name'));
		$status			= intval($this->input->post('status'));
		$date 			= date('Y-m-d');
		
		$data = array(
			'brand_name'	=> $brand_name,
			'status'		=> $status,
			'created_date'	=> $date
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'brand_car');
		redirect('/brand', 'refresh');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'brand_car');
		echo json_encode($status);
	}
}