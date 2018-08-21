<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GenerateReport extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mgeneratereport');
	}

	public function Index()
	{
		$date	= '2017-09-04';
		$phone	= '081280452698';
		$data 	= $this->mgeneratereport->getDataDriver($phone, $date);
		$insertData = json_decode($data);
		echo $data;
	}
}