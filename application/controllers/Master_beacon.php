<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_beacon extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmasterbeacon');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('master_beacon/beacon');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listbeacon()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'notes';
		echo $this->mmasterbeacon->listbeacon($limit, $offset, $order, $search, $sort);
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

		$status	= $this->mapi->updStatus($id, $data, $where, 'master_beacon');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
            
            $data['getType'] = array(
                                    array('id' => '1', 'name' => 'NFC'),
                                    array('id' => '2', 'name' => 'BLT')
                                    );
            
			if ($id == 0) {
				$this->load->view('master_beacon/add', $data);
			} else {
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mmasterbeacon->getData($where);
				$this->load->view('master_beacon/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getbeacon($notes)
	{
		$notes = str_replace('%20', ' ', $notes);
		echo json_encode($this->mmasterbeacon->cek_beacon($notes));
	}

	public function Save()
	{
		$id	    = intval($this->input->post('id'));
		$type   = 'NFC'; //trim($this->input->post('type'));
		$notes  = trim($this->input->post('notes'));
		$status = intval($this->input->post('status'));
		
		$data = array(
			'type'		    => $type,
			'notes'		    => $notes,
			'status'		=> $status,
			'modified_date' => date('Y-m-d'),
			'modified_by'   => $_SESSION['login']['id']
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'master_beacon');
		redirect('/master_beacon');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'master_beacon');
		echo json_encode($status);
	}
}