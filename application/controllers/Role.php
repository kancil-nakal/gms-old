<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('mrole');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('role/role');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function groupuser()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'group_name';

		echo $this->mrole->groupuser($limit, $offset, $order, $search, $sort);
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
		$status	= $this->mapi->updStatus($id, $data, $where, 'ta_users_group');
		echo json_encode($status);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');

			$id 	= (isset($id)) ? intval($id) : 0;
			if($id == 1 || $id == 5) {
				$parent = array(
					'status'	=> 0,
					'level'		=> 1,
					'id !='		=> 18
				);
			} else {
				$parent = array(
					'status'	=> 0,
					'level'		=> 1
				);
			}
			$data['parent']	= $this->mrole->role_menu($parent);

			$child = array(
				'status'	=> 0,
				'level'		=> 2
			);
			$data['child']	= $this->mrole->role_menu($child);

			$where = array(
				'T01.id'	=> $id
			);
			$data['getData'] = $this->mrole->getData($where);
			$this->load->view('role/edit', $data);
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id				= intval($this->input->post('id'));
		$module_id		= $this->input->post('module_id');
		$status			= trim($this->input->post('status'));
		$pers 			= '';
		for($i=0;$i<count($module_id);$i++) {
			if($i!=(count($module_id)-1)) {$delimiters=',';}
			else {$delimiters='';}
			$pers .= $module_id[$i].$delimiters;
		}
		$data = array(
			'menu_id'		=> $pers
		);

		$where = array(
			'group_user'	=> $id
		);
		$this->mrole->edit($data, $where, 'ta_group_menu');
		redirect('/role');
	}

	// public function Edit()
	// {
	// 	$id				= intval($this->input->post('id'));
	// 	$ta_name		= strip_tags(trim($this->input->post('display_name')));
	// 	$ta_email		= trim($this->input->post('user_email'));
	// 	$ta_phone		= strip_tags(trim($this->input->post('mobile_phone')));
	// 	$ta_username	= strip_tags(trim($this->input->post('username')));
	// 	$ta_password	= md5($this->input->post('password'));
	// 	$province_id	= intval($this->input->post('province_id'));
	// 	$city_id		= trim($this->input->post('city_id'));

	// 	$config['upload_path']		= './assets/images/';
	// 	$config['allowed_types']	= 'gif|jpg|png';
	// 	$config['max_size']			= 100;
	// 	$config['max_width']		= 1024;
	// 	$config['max_height']		= 768;

	// 	$this->load->library('upload', $config);
	// 	$this->upload->initialize($config);

	// 	if($_FILES['file']['name'])
	// 	{
	// 		if ($this->upload->do_upload('file'))
	// 		{
	// 			$gbr = $this->upload->data();
	// 			$config2['image_library']	= 'gd2'; 
	// 			$config2['source_image']	= $this->upload->upload_path.$this->upload->file_name;
	// 			$config2['new_image']		= './assets/images/photo/client/';
	// 			$config2['maintain_ratio']	= TRUE;
	// 			$config2['width']			= 100;
	// 			$this->load->library('image_lib',$config2); 

	// 			if ( !$this->image_lib->resize()){
	// 				$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
	// 			}
	// 			@unlink('./assets/images/'.$gbr['file_name']);
	// 			if($this->input->post('password') == '') {
	// 				$data = array(
	// 					'ta_name'		=> $ta_name,
	// 					'ta_email'		=> $ta_email,
	// 					'ta_phone'		=> $ta_phone,
	// 					'ta_username'	=> $ta_username,
	// 					'ta_password'	=> $ta_password,
	// 					'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
	// 					'province_id'	=> $province_id,
	// 					'city_id'		=> $city_id,
	// 					'last_updated'	=> date('Y-m-d H:i:s'),
	// 					'updated_by'	=> $_SESSION['login']['id']
	// 				);
	// 			} else {
	// 				$data = array(
	// 					'ta_name'		=> $ta_name,
	// 					'ta_email'		=> $ta_email,
	// 					'ta_phone'		=> $ta_phone,
	// 					'ta_username'	=> $ta_username,
	// 					'ta_image'		=> 'assets/images/photo/client/'.$gbr['file_name'],
	// 					'province_id'	=> $province_id,
	// 					'city_id'		=> $city_id,
	// 					'last_updated'	=> date('Y-m-d H:i:s'),
	// 					'updated_by'	=> $_SESSION['login']['id']
	// 				);					
	// 			}
	// 		} else {
	// 			echo $this->upload->display_errors('', '');
	// 		}
	// 	} else {
	// 		if($this->input->post('password') == '') {
	// 			$data = array(
	// 				'ta_name'		=> $ta_name,
	// 				'ta_email'		=> $ta_email,
	// 				'ta_phone'		=> $ta_phone,
	// 				'ta_username'	=> $ta_username,
	// 				'ta_password'	=> $ta_password,
	// 				'province_id'	=> $province_id,
	// 				'city_id'		=> $city_id,
	// 				'last_updated'	=> date('Y-m-d H:i:s'),
	// 				'updated_by'	=> $_SESSION['login']['id']
	// 			);
	// 		} else {
	// 			$data = array(
	// 				'ta_name'		=> $ta_name,
	// 				'ta_email'		=> $ta_email,
	// 				'ta_phone'		=> $ta_phone,
	// 				'ta_username'	=> $ta_username,
	// 				'province_id'	=> $province_id,
	// 				'city_id'		=> $city_id,
	// 				'last_updated'	=> date('Y-m-d H:i:s'),
	// 				'updated_by'	=> $_SESSION['login']['id']
	// 			);
	// 		}
	// 	}

	// 	$where = array(
	// 		'id'	=> $id
	// 	);

	// 	$this->mapi->save($id, $data, $where, 'ta_users');
	// 	redirect('/client');
	// }
}