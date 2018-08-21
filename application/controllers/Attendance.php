<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mattendance');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('attendance/attendance');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listattendance() 
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'A.att_date';
		echo $this->mattendance->listattendance($limit, $offset, $order, $search, $sort);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
            
            $data['getShift']	= @$this->mapi->getData('master_shift' , null, 'id')->result_array();
            $data['getAttType']	= @$this->mapi->getData('master_attendance' , null, 'id')->result_array();
            
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('attendance/add', $data);
			} else {
				$where = array(
					'A.id'	=> $id
				);
				$data['getData'] = $this->mattendance->getData($where);
				$this->load->view('attendance/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getattendance($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mattendance->cek_attendance($name));
	}

	public function Save()
	{
		$id         = intval($this->input->post('id'));
		$site_id  = intval($this->input->post('site_id'));
		$team_id    = intval($this->input->post('team_id'));
		$att_type   = intval($this->input->post('att_type'));
		$att_date   = trim($this->input->post('att_date'));
		$att_shift  = intval($this->input->post('att_shift'));
		$att_reason = trim($this->input->post('att_reason'));
		
		$data = array(
			'site_id'       => $site_id,
			'team_id'       => $team_id,
			'att_type'      => $att_type,
			'att_date'      => $att_date,
			'att_shift'     => $att_shift,
			'att_reason'    => $att_reason,
			'created_date'  => date('Y-m-d'),
			'created_by'    => $_SESSION['login']['id']
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'team_attendance');
		redirect('/attendance');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'team_attendance');
		echo json_encode($status);
	}
}