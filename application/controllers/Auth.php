<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//session_start();

class Auth extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('mauth');
	}

	public function index()
	{
		if($this->session->userdata('status')) {
			redirect('');
		} else {
			$this->load->helper(array('form'));
			$this->load->view('auth/login');
		}
	}

	public function validation() {
		$username = strip_tags($this->input->post('user_email'));
		$password = md5($this->input->post('user_pass'));

		$where = array(
			'ta_username'	=> $username,
			'ta_password'	=> $password,
			'ta_status'		=> 0
		);
		$cek = $this->mauth->cek_login("ta_users", $where)->num_rows();
		if($cek > 0) {
			$where1 = array(
				'ta_username'		=> $username,
				'ta_password'		=> $password
			);
			$getData = $this->mauth->getData("ta_users", $where1);
			$data_session = array(
				'id'			=> $getData->id,
				'ta_name'		=> $getData->ta_name,
				'ta_email'		=> $getData->ta_email,
				'group_user'	=> $getData->group_user,
				'ta_image'		=> $getData->ta_image,
				'province_id'	=> $getData->province_id,
				'status'		=> "login"
			);
			$this->session->set_userdata('login', $data_session);
			redirect(base_url(""));
		} else {
			redirect(base_url(""));
		}
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url(""));
	}

	public function forgot() {
		$this->load->helper(array('form'));
		$this->load->view('auth/forgot');
	}

	public function signup() {
		$this->load->helper(array('form'));
		$this->load->view('auth/signup');		
	}
	// function validation(){
	// 	$username = $this->input->post('username');
	// 	$password = $this->input->post('password');
	// 	$where = array(
	// 		'username' => $username,
	// 		'password' => md5($password)
	// 	);
	// 	$cek = $this->m_login->cek_login("m_user",$where)->num_rows();
	// 	if($cek > 0){
	// 		$data_session = array(
	// 			'nama' => $username,
	// 			'status' => "login"
	// 		);
	// 		$this->session->set_userdata('login',$data_session);
	// 		redirect(base_url(""));
	// 	}else {
	// 		echo "Username dan password salah !";
	// 	}
	// }
	
	// function logout(){
	// 	$this->session->sess_destroy();
	// 	redirect(base_url('login'));
	// }
}
