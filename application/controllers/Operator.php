<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller {
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
			$this->load->view('operator/operator');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth', 'refresh');
		}
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('operator/add');
			} else {
				$id 	= (isset($id)) ? intval($id) : 0;
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mapi->getData("ta_users", $where)->result();
				$this->load->view('operator/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth', 'refresh');
		}
	}

	public function Save() {
		$id				= 0;
		$ta_username	= strip_tags(trim($this->input->post('username')));
		$ta_password	= trim(md5($this->input->post('password')));
		$ta_name		= strip_tags(trim($this->input->post('display_name')));
		$ta_phone		= trim($this->input->post('mobile_phone'));
		$ta_email		= trim($this->input->post('user_email'));

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
				$config2['new_image']		= './assets/images/photo/operator/';
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
					'user_image'	=> 'assets/images/photo/operator'.$gbr['file_name'],
					'ta_phone'		=> $ta_phone,
					'ta_email'		=> $ta_email,
					'group_user'	=> 5,
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
				'group_user'	=> 5,
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
		redirect('/operator', 'refresh');
	}

	public function Edit() {
		$id				= intval($this->input->post('id'));
		$ta_password	= trim(md5($this->input->post('password')));
		$ta_name		= strip_tags(trim($this->input->post('display_name')));
		$ta_phone		= trim($this->input->post('mobile_phone'));
		$ta_email		= trim($this->input->post('user_email'));
		$ta_status		= trim($this->input->post('user_status'));

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
				$config2['new_image']		= './assets/images/photo/operator/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				if( $this->input->post('password') != '') {
					$data = array(
						'ta_password'	=> $ta_password,
						'ta_name'		=> $ta_name,
						'user_image'	=> 'assets/images/photo/operator'.$gbr['file_name'],
						'ta_phone'		=> $ta_phone,
						'ta_email'		=> $ta_email,
						'ta_status'		=> $ta_status,
                        'modified_date'	=> date('Y-m-d H:i:s'),
                        'modified_by'	=> $_SESSION['login']['id']
					);
				} else {
					$data = array(
						'ta_name'		=> $ta_name,
						'user_image'	=> 'assets/images/photo/operator'.$gbr['file_name'],
						'ta_phone'		=> $ta_phone,
						'ta_email'		=> $ta_email,
						'ta_status'		=> $ta_status,
                        'modified_date'	=> date('Y-m-d H:i:s'),
                        'modified_by'	=> $_SESSION['login']['id']
					);					
				}
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			if( $this->input->post('password') != '') {
				$data = array(
					'ta_password'	=> $ta_password,
					'ta_name'		=> $ta_name,
					'ta_phone'		=> $ta_phone,
					'ta_email'		=> $ta_email,
					'ta_status'		=> $ta_status,
                    'modified_date'	=> date('Y-m-d H:i:s'),
                    'modified_by'	=> $_SESSION['login']['id']
				);
			} else {
				$data = array(
					'ta_name'		=> $ta_name,
					'ta_phone'		=> $ta_phone,
					'ta_email'		=> $ta_email,
					'ta_status'		=> $ta_status,
                    'modified_date'	=> date('Y-m-d H:i:s'),
                    'modified_by'	=> $_SESSION['login']['id']
				);
			}
		}

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'ta_users');
		redirect('/operator', 'refresh');
	}
}