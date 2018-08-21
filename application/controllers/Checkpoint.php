<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkpoint extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mcheckpoint');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('checkpoint/checkpoint');
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
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'C.att_date';
		echo $this->mcheckpoint->listcheckpoint($limit, $offset, $order, $search, $sort);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
            
            $data['getShift']	= @$this->mapi->getData('master_shift' , null, 'id')->result_array();
            
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('checkpoint/add', $data);
			} else {
				$where = array(
					'C.id'	=> $id
				);
				$data['getData'] = $this->mcheckpoint->getData($where);
				$this->load->view('checkpoint/edit', $data);
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
		echo json_encode($this->mcheckpoint->cek_checkpoint($name));
	}

	public function Save()
	{
		$id             = intval($this->input->post('id'));
		$checkpoint_id  = intval($this->input->post('checkpoint_id'));
		$site_id        = intval($this->input->post('site_id'));
		$team_id        = intval($this->input->post('team_id'));
		$att_date       = trim($this->input->post('att_date'));
		$att_shift      = intval($this->input->post('att_shift'));
		
		$data = array(
			'checkpoint_id' => $checkpoint_id,
			'site_id'       => $site_id,
			'team_id'       => $team_id,
			'att_date'      => $att_date,
			'att_shift'     => $att_shift,
			'created_date'  => date('Y-m-d'),
			'created_by'    => $_SESSION['login']['id']
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'checkpoint');
		redirect('/checkpoint');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'checkpoint');
		echo json_encode($status);
	}
}