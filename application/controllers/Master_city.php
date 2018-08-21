<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_city extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmastercity');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('master_city/city');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listcity()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'city_name';
		echo $this->mmastercity->listcity($limit, $offset, $order, $search, $sort);
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

		$status	= $this->mapi->updStatus($id, $data, $where, 'city');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('master_city/add');
			} else {
				$where = array(
					'T01.id'	=> $id
				);
				$data['getData'] = $this->mmastercity->getData($where);
				$this->load->view('master_city/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getcity($city_name)
	{
		$city_name = str_replace('%20', ' ', $city_name);
		echo json_encode($this->mmastercity->cek_city($city_name));
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$city_name		= trim($this->input->post('city_name'));
		
		$data = array(
			'city_name'		=> $city_name
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'city');
		redirect('/master_city');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'city');
		echo json_encode($status);
	}
}