<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mactivity');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('activity/activity');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listactivity()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'A.att_date';
		echo $this->mactivity->listactivity($limit, $offset, $order, $search, $sort);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
            
            $data['getShift']	= @$this->mapi->getData('master_shift' , null, 'id')->result_array();
            
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('activity/add', $data);
			} else {
				$where = array(
					'A.id'	=> $id
				);
                
                $query	= $this->db->query("SELECT * FROM files WHERE attachment_id = '".$id."' AND attachment_type = 'activity' ORDER BY id ASC");
                $data['getFile']	= $query->result();
                
				$data['getData'] = $this->mactivity->getData($where);
                
				$this->load->view('activity/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getactivity($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mactivity->cek_activity($name));
	}

	public function Save()
	{
		$id             = intval($this->input->post('id'));
		$site_id        = intval($this->input->post('site_id'));
		$team_id        = intval($this->input->post('team_id'));
		$description    = trim($this->input->post('description'));
		$att_date       = trim($this->input->post('att_date'));
		$att_shift      = intval($this->input->post('att_shift'));
		
		$data = array(
			'site_id'       => $site_id,
			'team_id'       => $team_id,
			'description'   => $description,
			'att_date'      => $att_date,
			'att_shift'     => $att_shift
		);

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'activity');
		redirect('/activity');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'activity');
		echo json_encode($status);
	}

	public function del_file($id, $file_id)
	{
		$id	= intval($id);
		$file_id	= intval($file_id);
        
        $this->load->library('curl');
        $url = 'http://gmsapi.mesinrusak.com/del_files/' .$_SESSION['login']['id'].'/'.$file_id;
        $result = $this->curl->simple_get($url);
        //var_dump($url . ' | ' . $result);
        
        redirect('/activity/form/' . $id);
	}
}