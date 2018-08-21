<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_checkpoint extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmastercheckpoint');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('master_checkpoint/checkpoint');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listcheckpoint()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'asc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'ordering';
		echo $this->mmastercheckpoint->listcheckpoint($limit, $offset, $order, $search, $sort);
	}

	public function status($id, $user_status)
	{
		$id	= intval($id);
		if( $user_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'status' => $status
		);

		$where = array(
			'id' => $id
		);

		$status	= $this->mapi->updStatus($id, $data, $where, 'master_checkpoint');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
            
            $data['getBeacon'] = $this->mmastercheckpoint->getBeacon();
            $data['getSite'] = $this->mmastercheckpoint->getSite(); 
            
			if ($id == 0) {
				$this->load->view('master_checkpoint/add', $data);
			} else {
				$where = array(
					'T01.id'	=> $id
				);
				$data['getData'] = $this->mmastercheckpoint->getData($where);
				$this->load->view('master_checkpoint/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getcheckpoint($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mmastercheckpoint->cek_checkpoint($name));
	}

	public function Save()
	{
		$id	        = intval($this->input->post('id'));
		$name       = trim($this->input->post('name'));
		$location   = trim($this->input->post('location'));
		$beacon_id  = intval($this->input->post('beacon_id'));
		$site_id    = intval($this->input->post('site_id'));
		$ordering   = intval($this->input->post('ordering'));
		$status     = intval($this->input->post('status'));
		
		$data = array(
			'name'		    => $name,
			'location'      => $location,
			'beacon_id'     => $beacon_id,
			'site_id'       => $site_id,
			'ordering'		=> $ordering,
			'status'		=> $status,
			'modified_date' => date('Y-m-d'),
			'modified_by'   => $_SESSION['login']['id']
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'master_checkpoint');
		redirect('/master_checkpoint');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'master_checkpoint');
		echo json_encode($status);
	}
}