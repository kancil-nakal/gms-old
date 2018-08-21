<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('miklan');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			if($_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 2 || $_SESSION['login']['group_user'] == 5) {
				$this->load->view('blocks/header');
				$this->load->view('iklan/iklan');
				$this->load->view('blocks/footer');
			} else {
				$this->output->set_status_header('404');
				$this->load->view('404');
			}
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listIklan()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'T02.ta_name';
		echo $this->miklan->listIklan($_SESSION['login']['id'], $_SESSION['login']['group_user'], $limit, $offset, $order, $search, $sort);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('iklan/add');
			} else {
				$data['getData'] = $this->miklan->getData($id);
				$this->load->view('iklan/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id			= intval($this->input->post('id'));
		$client_id	= intval($this->input->post('client_id'));
		$message	= trim($this->input->post('message'));

		if($_FILES['file']['name'])
		{
			$config['upload_path']		= './assets/images/photo/iklan/';
			$config['allowed_types']	= '*';
			$config['file_name']		= md5(time().$id).'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file'))
			{
				$gbr = $this->upload->data();
				$data = array(
					'client_id'		=> $client_id,
					'description'	=> $message,
					'images'		=> 'assets/images/photo/iklan/'.$gbr['file_name']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'client_id'		=> $client_id,
				'description'	=> $message
			);	
		}

		$where = array(
			'advert_id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'client_banner');
		redirect('/iklan');
	}

	public function Edit()
	{
		$id			= intval($this->input->post('id'));
		$client_id	= intval($this->input->post('client_id'));
		$message	= trim($this->input->post('message'));

		if($_FILES['file']['name'])
		{
			$config['upload_path']		= './assets/images/photo/iklan/';
			$config['allowed_types']	= '*';
			$config['file_name']		= md5(time().$id).'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$data = $this->miklan->getData($id);
			unlink('./'.$data[0]->images);

			if ($this->upload->do_upload('file'))
			{

				$gbr = $this->upload->data();
				$data = array(
					'client_id'		=> $client_id,
					'description'	=> $message,
					'images'		=> 'assets/images/photo/iklan/'.$gbr['file_name']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'client_id'		=> $client_id,
				'description'	=> $message
			);
		}

		$where = array(
			'id'	=> $id
		);
		$this->mapi->save($id, $data, $where, 'client_banner');
		redirect('/iklan');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'client_banner');
		echo json_encode($status);
	}
}