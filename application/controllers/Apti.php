<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apti extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('apti/apti');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('apti/add');
			} else {
				$id 	= (isset($id)) ? intval($id) : 0;
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mapi->getData("ta_users", $where)->result();
				$this->load->view('apti/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save() {
		$id				= 0;
		$display_name	= strip_tags(trim($this->input->post('display_name')));
		$user_email		= trim($this->input->post('user_email'));
		$mobile_phone	= strip_tags(trim($this->input->post('mobile_phone')));
		$username		= strip_tags(trim($this->input->post('username')));
		$password		= trim(md5($this->input->post('password')));
		$user_status	= trim($this->input->post('user_status'));

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
				$config2['new_image']		= './assets/images/photo/apti/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				$data = array(
					'ta_name'		=> $display_name,
					'ta_email'		=> $user_email,
					'ta_phone'		=> $mobile_phone,
					'ta_username'	=> $username,
					'ta_password'	=> $password,
					'ta_image'		=> 'assets/images/photo/apti/'.$gbr['file_name'],
					'ta_status'		=> $user_status,
					'group_user'	=> 3,
					'created_by'	=> $_SESSION['login']['id']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'ta_name'		=> $display_name,
				'ta_email'		=> $user_email,
				'ta_phone'		=> $mobile_phone,
				'ta_username'	=> $username,
				'ta_password'	=> $password,
				'ta_status'		=> $user_status,
				'group_user'	=> 3,
				'created_by'	=> $_SESSION['login']['id']
			);
		}

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'ta_users');
		redirect('/apti');
	}

	public function Edit() {
		$id				= intval($this->input->post('id'));
		$display_name	= strip_tags(trim($this->input->post('display_name')));
		$user_email		= trim($this->input->post('user_email'));
		$mobile_phone	= strip_tags(trim($this->input->post('mobile_phone')));
		$password		= trim(md5($this->input->post('password')));
		$user_status	= trim($this->input->post('user_status'));

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
				$config2['new_image']		= './assets/images/photo/apti/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				if ($this->input->post('password') != '') {
					$data = array(
						'ta_name'		=> $display_name,
						'ta_email'		=> $user_email,
						'ta_phone'		=> $mobile_phone,
						'ta_password'	=> $password,
						'ta_image'		=> 'assets/images/photo/apti/'.$gbr['file_name'],
						'ta_status'		=> $user_status,
						'last_updated'	=> date('Y-m-d H:i:s'),
						'updated_by'	=> $_SESSION['login']['id']
					);
				} else {
					$data = array(
						'ta_name'		=> $display_name,
						'ta_email'		=> $user_email,
						'ta_phone'		=> $mobile_phone,
						'ta_image'		=> 'assets/images/photo/apti/'.$gbr['file_name'],
						'ta_status'		=> $user_status,
						'last_updated'	=> date('Y-m-d H:i:s'),
						'updated_by'	=> $_SESSION['login']['id']
					);					
				}
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			if ($this->input->post('password') != '') {
				$data = array(
					'ta_name'		=> $display_name,
					'ta_email'		=> $user_email,
					'ta_phone'		=> $mobile_phone,
					'ta_password'	=> $password,
					'ta_status'		=> $user_status,
					'last_updated'	=> date('Y-m-d H:i:s'),
					'updated_by'	=> $_SESSION['login']['id']
				);
			} else {
				$data = array(
					'ta_name'		=> $display_name,
					'ta_email'		=> $user_email,
					'ta_phone'		=> $mobile_phone,
					'ta_status'		=> $user_status,
					'last_updated'	=> date('Y-m-d H:i:s'),
					'updated_by'	=> $_SESSION['login']['id']
				);
			}
		}

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'ta_users');
		redirect('/apti');
	}
}