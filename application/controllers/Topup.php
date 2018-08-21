<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topup extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mtopup');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('topup/topup');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save() {
		$id			= intval($this->input->post('id'));
		if($id === 0) {
			redirect('/topup');
		} else {
			$split		= explode(',', $this->input->post('topup'));
			$cur 		= '';
			for($i=0; $i<count($split); $i++) {
				$cur .= $split[$i];
			}
			$newSaldo	= explode('.', $cur);

			$cek 	= $this->mtopup->cek_topup($id);
			$saldo 	= $cek + $newSaldo[0];

			$data = array(
				'beginning_saldo'	=> $saldo,
				'remain_saldo'		=> $saldo,
				'status'			=> 'Y'
			);

			$data1 = array(
				'client_id'		=> $id,
				'top_up'		=> $newSaldo[0],
				'createdBy'		=> $_SESSION['login']['id'],
				'createdDate'	=> date('Y-m-d')
			);

			$where = array(
				'client_id'	=> $id
			);

			$this->mapi->save($id, $data, $where, 'client_saldo');
			$this->mapi->save(0, $data1, $where, 'history_topup');
			redirect('/client');
		}
	}
}