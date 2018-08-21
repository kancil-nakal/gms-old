<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Node extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mnode');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('node');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$mobile_phone	= trim($this->input->post('mobile_phone'));
		$lat			= trim($this->input->post('lat'));
		$long			= trim($this->input->post('long'));
		$tracking_id	= intval($this->input->post('tracking_id'));
		$is_moving		= trim($this->input->post('is_moving'));
		$accuracy		= trim($this->input->post('accuracy'));
		$altitude		= trim($this->input->post('altitude'));
		$heading		= trim($this->input->post('heading'));
		$odometer		= trim($this->input->post('odometer'));
		$speed			= trim($this->input->post('speed'));

		$data = array(
			'hpnumber'		=> $mobile_phone,
			'tracking_id'	=> $tracking_id,
			'is_moving'		=> $is_moving,
			'accuracy'		=> $accuracy,
			'altitude'		=> $altitude,
			'heading'		=> $heading,
			'odometer'		=> $odometer,
			'lat'			=> $lat,
			'long'			=> $long,
			'speed'			=> $speed
		);	

		echo json_encode($this->mnode->save($data, 'tracking_detail'));
	}
}