<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('mclient');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('client/client');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Form($id = null)
	{
		$id 	= (isset($id)) ? intval($id) : 0;

		$where1 = array(
			'status'	=> 0
		);
        
		$data['getCity']	= $this->mapi->getCityDetail();
        
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('client/add', $data);
			} else {
				$where = array( 'id' => $id );
				$data['getData'] = $this->mapi->getData("ta_users", $where)->result();

				$this->load->view('client/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$ta_username	= strip_tags(trim($this->input->post('username')));
		$ta_password	= md5($this->input->post('password'));
		$ta_name		= strip_tags(trim($this->input->post('display_name')));
		$ta_phone		= strip_tags(trim($this->input->post('mobile_phone')));
		$ta_email		= trim($this->input->post('user_email'));
		$city_id		= trim($this->input->post('city_id'));

		$config['upload_path']		= './assets/images/';
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= 1000;
		$config['max_width']		= 1024;
		$config['max_height']		= 768;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if($_FILES['file']['name'])
		{
			if ($this->upload->do_upload('file'))
			{
				$gbr = $this->upload->data();
				$config2['image_library']	= 'gd2'; 
				$config2['source_image']	= $this->upload->upload_path.$this->upload->file_name;
				$config2['new_image']		= './assets/images/photo/client/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				$data = array(
					'ta_username'	=> $ta_username,
					'ta_password'	=> $ta_password,
					'ta_name'		=> $ta_name,
					'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
					'ta_phone'		=> $ta_phone,
					'ta_email'		=> $ta_email,
					'city_id'		=> $city_id,
					'ta_status'	    => 0,
					'group_user'	=> 2,
					'created_date'	=> date('Y-m-d H:i:s'),
					'created_by'	=> $_SESSION['login']['id'],
					'modified_date'	=> date('Y-m-d H:i:s'),
					'modified_by'	=> $_SESSION['login']['id']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'ta_username'	=> $ta_username,
				'ta_password'	=> $ta_password,
				'ta_name'		=> $ta_name,
				'ta_phone'		=> $ta_phone,
				'ta_email'		=> $ta_email,
				'city_id'		=> $city_id,
				'ta_status'	    => 0,
				'group_user'	=> 2,
				'created_date'	=> date('Y-m-d H:i:s'),
				'created_by'	=> $_SESSION['login']['id'],
				'modified_date'	=> date('Y-m-d H:i:s'),
				'modified_by'	=> $_SESSION['login']['id']
			);
		}

		$where = array(
			'id'	=> $id
		);
        
		$this->mapi->save($id, $data, $where, 'ta_users');
		
		redirect('/client');
	}

	public function Edit()
	{
		$id				= intval($this->input->post('id'));
		$ta_password	= md5($this->input->post('password'));
		$ta_name		= strip_tags(trim($this->input->post('display_name')));
		$ta_phone		= strip_tags(trim($this->input->post('mobile_phone')));
		$ta_email		= trim($this->input->post('user_email'));
		$city_id		= trim($this->input->post('city_id'));
		$ta_status		= trim($this->input->post('ta_status'));

		$config['upload_path']		= './assets/images/';
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= 100;
		$config['max_width']		= 1024;
		$config['max_height']		= 768;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if($_FILES['file']['name'])
		{
			if ($this->upload->do_upload('file'))
			{
				$gbr = $this->upload->data();
				$config2['image_library']	= 'gd2'; 
				$config2['source_image']	= $this->upload->upload_path.$this->upload->file_name;
				$config2['new_image']		= './assets/images/photo/client/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				if($this->input->post('password') == '') {
					$data = array(
						'ta_password'	=> $ta_password,
						'ta_name'		=> $ta_name,
						'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
						'ta_phone'		=> $ta_phone,
						'ta_email'		=> $ta_email,
						'city_id'		=> $city_id,
						'ta_status'	    => $ta_status,
						'modified_date'	=> date('Y-m-d H:i:s'),
						'modified_by'	=> $_SESSION['login']['id']
					);
				} else {
					$data = array(
						'ta_name'		=> $ta_name,
						'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
						'ta_phone'		=> $ta_phone,
						'ta_email'		=> $ta_email,
						'city_id'		=> $city_id,
						'ta_status'	    => $ta_status,
						'modified_date'	=> date('Y-m-d H:i:s'),
						'modified_by'	=> $_SESSION['login']['id']
					);					
				}
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			if($this->input->post('password') != '') {
				$data = array(
					'ta_password'	=> $ta_password,
                    'ta_name'		=> $ta_name,
                    'ta_phone'		=> $ta_phone,
                    'ta_email'		=> $ta_email,
                    'city_id'		=> $city_id,
                    'ta_status'	    => $ta_status,
                    'modified_date'	=> date('Y-m-d H:i:s'),
                    'modified_by'	=> $_SESSION['login']['id']
				);
			} else {
				$data = array(
                    'ta_name'		=> $ta_name,
                    'ta_phone'		=> $ta_phone,
                    'ta_email'		=> $ta_email,
                    'city_id'		=> $city_id,
                    'ta_status'	    => $ta_status,
                    'modified_date'	=> date('Y-m-d H:i:s'),
                    'modified_by'	=> $_SESSION['login']['id']
				);
			}
		}

		$where = array(
			'id'	=> $id
		);
		

		$this->mapi->save($id, $data, $where, 'ta_users');
		
		redirect('/client');
	}

	public function manageData($group)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'ta_name';

		echo $this->mclient->listManage($group, $limit, $offset, $order, $search, $sort);
	}

	public function withdraw_status($id, $status)
	{
		$id = intval($id);
		$data = array('status' => intval($status));
		$where = array('client_id'	=> $id);
		
		$data = array(
					'client_id' => $id,
					'status' => $status == 0 ? 1 : 0,
					'modified_date' => date('Y-m-d'),
		);
		
		if($this->mapi->getValue('app_withdraw_button', 'status', $id, 'client_id') === false){
			$query = $this->db->insert('app_withdraw_button', $data);
		} else {
			$this->db->where($where);
			$query = $this->db->update('app_withdraw_button', $data);
		}
		
		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "success";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		echo json_encode($msg);
	}
}