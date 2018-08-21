<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->helper(array('form', 'url'));
//		$this->load->model('moperator');
		$this->load->model('mapi');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$group_user	= $_SESSION['login']['group_user'];
			$id			= intval($_SESSION['login']['id']);
			$where = array(
				'id'	=> $id
			);
			$data['getData'] = $this->mapi->getData("ta_users", $where)->result();

			$this->load->view('blocks/header');
			if($group_user == 1 OR $group_user == 5) {
				$this->load->view('profile/operator', $data);
			} else if ($user_type == '1') {
				$where1 = array(
					'status'	=> 0
				);
				$data['getProvince'] = $this->mapi->getProvince($where1);
				$province = $this->mapi->getProvinceDetail("member", $where);
				$whereProvince = array(
					'province_id' => $province->province
				);
				$data['getCity']	= $this->mapi->getCityDetail($whereProvince);

				$this->load->view('profile/client', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}
}