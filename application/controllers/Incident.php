<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incident extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mincident');
		$this->load->model('mapi');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('incident/incident');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listincident()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 8;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'I.incident_date';
		echo $this->mincident->listincident($limit, $offset, $order, $search, $sort);
	}

	public function form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
            
            $data['getShift']	= @$this->mapi->getData('master_shift' , null, 'id')->result_array();
            $data['getStatus']	= @$this->mapi->getData('master_incidentstatus' , null, 'id')->result_array();
            
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('incident/add', $data);
			} else {
				$where = array(
					'I.id'	=> $id
				);
                
                $query	= $this->db->query("SELECT * FROM files WHERE attachment_id = '".$id."' AND attachment_type = 'incident' ORDER BY id ASC");
                $data['getFile']	= $query->result();
                
				$data['getData'] = $this->mincident->getData($where);
                
				$this->load->view('incident/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getincident($name)
	{
		$name = str_replace('%20', ' ', $name);
		echo json_encode($this->mincident->cek_incident($name));
	}

	public function Save()
	{
		$id                     = intval($this->input->post('id'));
		$site_id                = intval($this->input->post('site_id'));
		$team_id                = intval($this->input->post('team_id'));
		$incident_no            = trim($this->input->post('incident_no'));
		$subject                = trim($this->input->post('subject'));
		$location               = trim($this->input->post('location'));
		$incident_date          = trim($this->input->post('incident_date'));
		$incident_time          = trim($this->input->post('incident_time'));
		$parties                = trim($this->input->post('parties'));
		$description            = trim($this->input->post('description'));
		$action                 = trim($this->input->post('action'));
		$analysis               = trim($this->input->post('analysis'));
		$advice                 = trim($this->input->post('advice'));
		$photo                  = trim($this->input->post('photo'));
		$status                 = trim($this->input->post('status'));
		$att_date               = trim($this->input->post('att_date'));
		$att_shift              = intval($this->input->post('att_shift'));
		$modified_by            = $this->input->post('modified_by') ? trim($this->input->post('modified_by')) : $_SESSION['login']['ta_name'];
		$modified_alias         = trim($this->input->post('modified_alias'));
		$modified_department    = trim($this->input->post('modified_department'));
		$modified_position      = trim($this->input->post('modified_position'));
		$knowing_by             = trim($this->input->post('knowing_by'));
		$knowing_position       = trim($this->input->post('knowing_position'));
		$accepted_by            = trim($this->input->post('accepted_by'));
		$accepted_position      = trim($this->input->post('accepted_position'));
		
        if($id> 0) {
            $data = array(
                'site_id'               => $site_id,
                'team_id'               => $team_id,
                'incident_no'           => $incident_no,
                'subject'               => $subject,
                'location'              => $location,
                'incident_date'         => $incident_date,
                'incident_time'         => $incident_time,
                'parties'               => $parties,
                'description'           => $description,
                'action'                => $action,
                'analysis'              => $analysis,
                'advice'                => $advice,
                'photo'                 => $photo,
                'status'                => $status,
                'att_date'              => $att_date,
                'att_shift'             => $att_shift,
                'modified_alias'        => $modified_alias,
                'modified_department'   => $modified_department,
                'modified_position'     => $modified_position,
                'knowing_by'            => $knowing_by,
                'knowing_position'      => $knowing_position,
                'accepted_by'           => $accepted_by,
                'accepted_position'     => $accepted_position,
                'modified_date'         => date('Y-m-d'),
                'modified_by'           => $modified_by
            );
        } else {
            $data = array(
                'site_id'               => $site_id,
                'team_id'               => $team_id,
                'incident_no'           => $incident_no,
                'subject'               => $subject,
                'location'              => $location,
                'incident_date'         => $incident_date,
                'incident_time'         => $incident_time,
                'parties'               => $parties,
                'description'           => $description,
                'action'                => $action,
                'analysis'              => $analysis,
                'advice'                => $advice,
                'photo'                 => $photo,
                'status'                => $status,
                'att_date'              => $att_date,
                'att_shift'             => $att_shift,
                'modified_alias'        => $modified_alias,
                'modified_department'   => $modified_department,
                'modified_position'     => $modified_position,
                'knowing_by'            => $knowing_by,
                'knowing_position'      => $knowing_position,
                'accepted_by'           => $accepted_by,
                'accepted_position'     => $accepted_position,
                'created_date'          => date('Y-m-d'),
                'created_by'            => $_SESSION['login']['ta_name'],
                'modified_date'         => date('Y-m-d'),
                'modified_by'           => $modified_by
            );
        }
		$where = array('id'=> $id);

		$this->mapi->save($id, $data, $where, 'incident');
		redirect('/incident');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'incident');
		echo json_encode($status);
	}

	public function del_file($id, $file_id)
	{
		$id	= intval($id);
		$file_id	= intval($file_id);
        
        $this->load->library('curl');
        $url = 'http://gmsapi.mesinrusak.com/del_files/' .$_SESSION['login']['id'].'/'.$file_id;
        $result = $this->curl->simple_get($url);
        //var_dump($url . ' | ' . $result);
        
        redirect('/incident/form/' . $id);
	}
}