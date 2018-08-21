<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
	}

	public function manageData($group)
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'ta_name';

		echo $this->mapi->listManage($group, $limit, $offset, $order, $search, $sort);
	}

	public function status($id, $user_status)
	{
		$id	= intval($id);
		if( $user_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'ta_status'		=> $status
		);

		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->updStatus($id, $data, $where, 'ta_users');
		echo json_encode($status);
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'ta_users');
		echo json_encode($status);
	}

	public function getCity($province_id)
	{
		$id 	= intval($province_id);
		$where 	= array(
			'province_id'	=> $id,
			'status'		=> 0
		);
		$status	= $this->mapi->getCity($where, 'city');
		echo $status;
	}

	public function getMerk($brand)
	{
		$id 	= $brand;
		$where 	= array(
			'brand_name'	=> $id,
			'status'	=> 0
		);
		$status	= $this->mapi->getMerk($where, 'merk_car');
		echo $status;
	}

	public function requestCar()
	{
		$where 	= array(
			'status'	=> '0'
		);
		$status	= $this->mapi->getRequest($where, 'temp_request_car');
		echo json_encode($status);
	}

	public function requestWithdraw()
	{
		$where 	= array(
			'status_withdraw'	=> '0'
		);
		$status	= $this->mapi->requestWithdraw($where, 'driver_withdraw');
		echo json_encode($status);
	}

	public function getPhone($phone)
	{
		$where 	= array(
			'mobile_phone'	=> $phone
		);
		$status	= $this->mapi->getPhone($where, 'member');
		echo $status;
	}

	public function getPhones($phone)
	{
		$where 	= array(
			'mobile_communication'	=> $phone
		);
		$status	= $this->mapi->getPhone($where, 'member');
		echo $status;
	}

}