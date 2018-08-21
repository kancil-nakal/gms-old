<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_complaint extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mteamcomplaint');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('team_complaint/teamcomplaint');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listteamcomplaint()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'A.created_date';
		echo $this->mteamcomplaint->listteamcomplaint($limit, $offset, $order, $search, $sort);
	}

	public function form($id)
	{
		if($this->session->userdata('login')) {
			$id 	= intval($id);
            
			$this->load->view('blocks/header');

            $where = array(
                'A.id'	=> $id
            );
            $data['getData'] = $this->mteamcomplaint->getData($where);
            $this->load->view('team_complaint/add', $data);

			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id         = intval($this->input->post('id'));
		$comment    = trim($this->input->post('comment'));
		$status     = trim($this->input->post('status'));
		
        if($comment) {
            $data = array(
                'team_complaint_id' => $id,
                'comment'           => $comment,
                'created_date'      => date('Y-m-d H:i:s'),
                'created_by'        => $_SESSION['login']['ta_name']
            );

            $this->mapi->save(0, $data, array(), 'team_complaint_comment');
        }
        
        $data1 = array(
            'status'        => $status,
            'modified_date' => date('Y-m-d H:i:s'),
            'modified_by'   => $_SESSION['login']['ta_name']
        );

        $this->mapi->updStatus($id, $data1, array('id' => $id), 'team_complaint');
        
        
        
		redirect('/team_complaint');
	}
}