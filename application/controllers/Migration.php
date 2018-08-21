<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmigration');
	}

	public function index()
	{
		$where = array(
			'group_user'	=> 2
		);
		echo $this->mmigration->summaryDetail($where);
	}
}