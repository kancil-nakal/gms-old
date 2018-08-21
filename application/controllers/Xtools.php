<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class XTools extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmessage');
		$this->load->model('mxtools');
		$this->load->model('mapi');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {

			$clientID = $_GET['client_id'];
			if($clientID == '' || !$clientID){$clientID = '15';}
			$ret['transaksi'] = $this->mxtools->getSetoranAwal($clientID);

			$ret['rupiah'] = $this->mxtools->getMoneyData($clientID);

			$this->load->view('blocks/header');
			$this->load->view('xtools/xtools',$ret);
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getclientfund()
	{
		$ret['transaksi'] = $this->mxtools->getDataFundClient();
		
	}

	public function listMessage()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'active_date';

		echo $this->mmessage->listMessage($limit, $offset, $order, $search, $sort);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0; ;

			$this->load->view('blocks/header');
			if($id == 0) {
				$this->load->view('message/add');
			} else {
				$where = array(
					'id'	=> $id
				);
				if ($id != 0) {
					$data['getData'] = $this->mmessage->getMessage("notification", $where)->result();
				}
				$this->load->view('message/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}		
	}

	public function save()
	{
		$session 		= $this->session->userdata('login');
		$id				= intval($this->input->post('id'));
		$message_name	= trim($this->input->post('message_name'));
		$message		= trim($this->input->post('message'));
		$date 			= explode('/', $this->input->post('active_date'));
		$active_date	= $date[2].'-'.$date[0].'-'.$date[1];
		$created_date	= date('Y-m-d');
		$created_by		= $session['id'];

		if($id == 0) {
			$data = array(
				'message_name'	=> $message_name,
				'message'		=> $message,
				'active_date'	=> $active_date,
				'created_date'	=> date('Y-m-d'),
				'created_by'	=> $created_by
			);
		} else {
			$data = array(
				'message_name'	=> $message_name,
				'message'		=> $message,
				'active_date'	=> $active_date
			);			
		}
		$where = array(
			'id'	=> $id
		);

		$this->mmessage->save($id, $data, $where, 'notification');
		redirect('/message');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'notification');
		echo json_encode($status);
	}
}