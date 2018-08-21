<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('msite');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('site/site');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Form($id = null)
	{
		$id 	= (isset($id)) ? intval($id) : 0;

		$data['getCity']	= $this->mapi->getCityDetail();
        
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('site/add', $data);
			} else {
				$where = array( 'S.id' => $id );
				$data['getData'] = $this->msite->getData($where);

				$this->load->view('site/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id         = intval($this->input->post('id'));
		$client_id	= intval($this->input->post('client_id'));
		$site_name	= strip_tags(trim($this->input->post('site_name')));
		$city_id    = intval($this->input->post('city_id'));
		$notes		= strip_tags(trim($this->input->post('notes')));
		$status		= intval($this->input->post('status'));

        $data = array(
            'client_id'	    => $client_id,
            'site_name'	    => $site_name,
            'city_id'		=> $city_id,
            'notes'		    => $notes,
            'status'		=> $status,
            'created_date'	=> date('Y-m-d H:i:s'),
            'created_by'	=> $_SESSION['login']['id'],
            'modified_date'	=> date('Y-m-d H:i:s'),
            'modified_by'	=> $_SESSION['login']['id']
        );

		$where = array(
			'id'	=> $id
		);
        
		$this->mapi->save($id, $data, $where, 'site');
		
		redirect('/site');
	}

	public function Edit()
	{
		$id         = intval($this->input->post('id'));
		$client_id	= intval($this->input->post('client_id'));
		$site_name	= strip_tags(trim($this->input->post('site_name')));
		$city_id    = intval($this->input->post('city_id'));
		$notes		= strip_tags(trim($this->input->post('notes')));
		$status		= intval($this->input->post('status'));


        $data = array(
            'client_id'	    => $client_id,
            'site_name'	    => $site_name,
            'city_id'		=> $city_id,
            'notes'		    => $notes,
            'status'		=> $status,
            'modified_date'	=> date('Y-m-d H:i:s'),
            'modified_by'	=> $_SESSION['login']['id']
        );

		$where = array(
			'id'	=> $id
		);
		

		$this->mapi->save($id, $data, $where, 'site');
		
		redirect('/site');
	}

	public function listSite() 
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'asc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'site_name';
		echo $this->msite->listSite($limit, $offset, $order, $search, $sort);
	}
}