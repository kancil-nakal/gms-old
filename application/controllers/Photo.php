<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photo extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library("pagination");
		$this->load->library('paginationlib');
		$this->load->model('mapi');
		$this->load->model('mphoto');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('photo/photos');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listPhoto()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'TRIM(T03.member_name)';
		echo $this->mphoto->listPhoto($_SESSION['login']['group_user'], $limit, $offset, $order, $search, $sort);
	}

	public function viewPhoto($page = 1)
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$idClient = array(
				'client_id' => $_SESSION['login']['id']
			);
			if($_SESSION['login']['group_user'] == 2) {
				$cekDriver	= $this->mapi->cekDriver("member", $idClient)->num_rows();
				if($cekDriver == 0) {
					redirect('');
				}
			}
			$pagingConfig   = $this->paginationlib->initPagination("/photo/viewPhoto", $this->mphoto->record_count($_SESSION['login']['group_user']));
			$data["pagination_helper"]   = $this->pagination;
			$data["getPhoto"] = @$this->mphoto->getPhoto($_SESSION['login']['group_user'], (($page-1) * $pagingConfig['per_page']), $pagingConfig['per_page']);
			$this->load->view('photo/photo', $data);
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}
}