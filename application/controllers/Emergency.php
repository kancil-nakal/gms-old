<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emergency extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('memergency');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('emergency/emergency');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listemergency()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'name';
		echo $this->memergency->listemergency($limit, $offset, $order, $search, $sort);
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

		$status	= $this->mapi->updStatus($id, $data, $where, 'emergency');
		echo json_encode($status);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
            
            $data['getCity']	    = @$this->mapi->getCityDetail();
            $data['getContactType']	= @$this->mapi->getData('master_contacttype' , null, 'name')->result_array();
            
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('emergency/add', $data);
			} else {
				$where = array(
					'E.id'	=> $id
				);
				$data['getData'] = $this->memergency->getData($where);
				$this->load->view('emergency/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getemergency($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->memergency->cek_emergency($name));
	}

	public function Save()
	{
		$id             = intval($this->input->post('id'));
		$name           = trim($this->input->post('name'));
		$phone          = trim($this->input->post('phone'));
		$address        = trim($this->input->post('address'));
		$city_id        = intval($this->input->post('city_id'));
		$status         = intval($this->input->post('status'));
		$contact_type   = intval($this->input->post('contact_type'));
		
        if($id > 0) {
            $data = array(
                'name'		    => $name,
                'phone'		    => $phone,
                'address'       => $address,
                'city_id'       => $city_id,
                'status'		=> $status,
                'contact_type'  => $contact_type,
                'modified_date' => date('Y-m-d'),
                'modified_by'   => $_SESSION['login']['id']
            );
        } else {
            $data = array(
                'name'		    => $name,
                'phone'		    => $phone,
                'address'       => $address,
                'city_id'       => $city_id,
                'status'		=> $status,
                'contact_type'  => $contact_type,
                'created_date'  => date('Y-m-d'),
                'created_by'    => $_SESSION['login']['id'],
                'modified_date' => date('Y-m-d'),
                'modified_by'   => $_SESSION['login']['id']
            );
        }

		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'emergency');
		redirect('/emergency');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'emergency');
		echo json_encode($status);
	}
}