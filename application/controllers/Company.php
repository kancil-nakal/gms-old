<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {
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
			$this->load->view('company/company');
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
		$data['getProvince'] = $this->mapi->getProvince($where1);
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('company/add', $data);
			} else {
				$where = array( 'id' => $id );
				$data['getData'] = $this->mapi->getData("ta_users", $where)->result();

				$whereProvince = array(
					'province_id' 	=> $data['getData'][0]->province_id,
					'status'		=> 0
				);
				$data['getCity']	= $this->mapi->getCityDetail($whereProvince);
				$this->load->view('company/edit', $data);
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
		$ta_name		= strip_tags(trim($this->input->post('name')));
		$ta_email		= trim($this->input->post('email'));
		$ta_phone		= strip_tags(trim($this->input->post('phone')));
		$province_id	= intval($this->input->post('province_id'));
		$city_id		= trim($this->input->post('city_id'));
		$user_status	= 0;

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
				$config2['new_image']		= './assets/images/photo/company/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				$data = array(
					'ta_name'		=> $ta_name,
					'ta_email'		=> $ta_email,
					'ta_phone'		=> $ta_phone,
					'ta_image'		=> 'assets/images/photo/company/'.$gbr['file_name'],
					'group_user'	=> 4,
					'province_id'	=> $province_id,
					'city_id'		=> $city_id,
					'created_by'	=> $_SESSION['login']['id']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'ta_name'		=> $ta_name,
				'ta_email'		=> $ta_email,
				'ta_phone'		=> $ta_phone,
				'province_id'	=> $province_id,
				'city_id'		=> $city_id,
				'group_user'	=> 4,
				'created_by'	=> $_SESSION['login']['id']
			);
		}

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'ta_users');
		redirect('/company');
	}

	public function Edit()
	{
		$id				= intval($this->input->post('id'));
		$ta_name		= strip_tags(trim($this->input->post('name')));
		$ta_email		= trim($this->input->post('email'));
		$ta_phone		= strip_tags(trim($this->input->post('phone')));
		$province_id	= intval($this->input->post('province_id'));
		$city_id		= trim($this->input->post('city_id'));

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
				$config2['new_image']		= './assets/images/photo/company/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
				$data = array(
					'ta_name'		=> $ta_name,
					'ta_email'		=> $ta_email,
					'ta_phone'		=> $ta_phone,
					'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
					'province_id'	=> $province_id,
					'city_id'		=> $city_id,
					'last_updated'	=> date('Y-m-d H:i:s'),
					'updated_by'	=> $_SESSION['login']['id']
				);
			} else {
				echo $this->upload->display_errors('', '');
			}
		} else {
			$data = array(
				'ta_name'		=> $ta_name,
				'ta_email'		=> $ta_email,
				'ta_phone'		=> $ta_phone,
				'province_id'	=> $province_id,
				'city_id'		=> $city_id,
				'last_updated'	=> date('Y-m-d H:i:s'),
				'updated_by'	=> $_SESSION['login']['id']
			);
		}

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'ta_users');
		redirect('/company');
	}
}