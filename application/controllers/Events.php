<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('mevents');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('events/events');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listEvents()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'created_date';
		echo $this->mevents->listEvents($limit, $offset, $order, $search, $sort);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('events/add');
			} else {
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mapi->getData('client_events', $where)->result();
				$this->load->view('events/edit', $data);
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
		$client_id		= intval($_SESSION['login']['id']);
		$event_name		= trim($this->input->post('event_name'));
		$status 		= intval($this->input->post('status'));
		$dateS 			= explode('/', $this->input->post('date_start'));
		$date_start		= $dateS[2].'-'.$dateS[0].'-'.$dateS[1];
		$dateE 			= explode('/', $this->input->post('date_end'));
		$date_end		= $dateE[2].'-'.$dateE[0].'-'.$dateE[1];
		$search_name	= trim($this->input->post('search_name'));
		$radius_meter	= trim($this->input->post('radius_meter'));
		$lat			= trim($this->input->post('lat'));
		$lng			= trim($this->input->post('lng'));
		$south			= trim($this->input->post('south'));
		$west			= trim($this->input->post('west'));
		$north			= trim($this->input->post('north'));
		$east			= trim($this->input->post('east'));

		$data = array(
			'client_id'		=> $client_id,
			'event_name'	=> $event_name,
			'date_start'	=> $date_start,
			'date_end'		=> $date_end,
			'lat'			=> $lat,
			'lng'			=> $lng,
			'radius_meter'	=> $radius_meter,
			'south'			=> $south,
			'west'			=> $west,
			'north'			=> $north,
			'east'			=> $east,
			'status'		=> $status
		);

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'client_events');
		redirect('/events');
	}

	public function status($id, $events_status)
	{
		$id	= intval($id);
		if( $events_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'status'		=> $status
		);

		$where = array(
			'id'	=> $id
		);
		$status	= $this->mapi->updStatus($id, $data, $where, 'client_events');
		echo json_encode($status);
	}

	public function delete($id)
	{
		$id	= intval($id);
		$data = array(
			'status'	=> 2
		);

		$where = array(
			'id'	=> $id
		);
		$status	= $this->mapi->updStatus($id, $data, $where, 'client_events');
		echo json_encode($status);
	}
	
	public function getone($id) 
	{
		$where = array(
			'id' => $id
		);
		$result = $this->mapi->getData('client_events', $where)->result();
		
		
		echo json_encode($result);
		
	}
	
	public function getone_byroute($phone,$date) 
	{
		
		$query = $this->db->query("SELECT * FROM client_events WHERE id IN (SELECT event_id FROM driver_route_events WHERE driver_phone = '".$phone."' AND route_date = '".$date."' GROUP BY event_id)");
		
		if($query->num_rows() > 0) $result =  $query->result(); //result_array
		else $result = false;
			
		echo json_encode($result);
	}
}