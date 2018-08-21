<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panic extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mpanic');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('panic/panic');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listpanic()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'name';
		echo $this->mpanic->listpanic($limit, $offset, $order, $search, $sort);
	}

	public function status($id, $user_status)
	{
		$id	= intval($id);
		if( $user_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'status'		=> $status
		);

		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->updStatus($id, $data, $where, 'panic');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('panic/add');
			} else {
				$where = array(
					'P.id'	=> $id
				);
				$data['getData'] = $this->mpanic->getData($where);
				$this->load->view('panic/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getpanic($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mpanic->cek_panic($name));
	}

	public function Save()
	{
		$id         = intval($this->input->post('id'));
		$client_id  = trim($this->input->post('client_id'));
		$name       = trim($this->input->post('name'));
		$phone      = trim($this->input->post('phone'));
		$notes      = trim($this->input->post('notes'));
		$status     = intval($this->input->post('status'));
		
        if($id > 0) {
            $data = array(
                'client_id'     => $client_id,
                'name'		    => $name,
                'phone'		    => $phone,
                'notes'		    => $notes,
                'status'		=> $status,
                'modified_date' => date('Y-m-d'),
                'modified_by'   => $_SESSION['login']['id']
            );
        } else {
            $data = array(
                'client_id'     => $client_id,
                'name'		    => $name,
                'phone'		    => $phone,
                'notes'		    => $notes,
                'status'		=> $status,
                'created_date'  => date('Y-m-d'),
                'created_by'    => $_SESSION['login']['id'],
                'modified_date' => date('Y-m-d'),
                'modified_by'   => $_SESSION['login']['id']
            );
        }

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'panic');
		redirect('/panic');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'panic');
		echo json_encode($status);
	}
}