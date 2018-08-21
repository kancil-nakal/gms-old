<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_position extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmasterposition');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('master_position/position');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listposition()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'name';
		echo $this->mmasterposition->listposition($limit, $offset, $order, $search, $sort);
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

		$status	= $this->mapi->updStatus($id, $data, $where, 'master_position');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('master_position/add');
			} else {
				$where = array(
					'T01.id'	=> $id
				);
				$data['getData'] = $this->mmasterposition->getData($where);
				$this->load->view('master_position/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getposition($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mmasterposition->cek_position($name));
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$name		= trim($this->input->post('name'));
		
		$data = array(
			'name'		=> $name
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'master_position');
		redirect('/master_position');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'master_position');
		echo json_encode($status);
	}
}