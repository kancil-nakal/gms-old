<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mmessage');
		$this->load->model('mapi');
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('message/message');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listMessageAll()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'active_date';

		echo $this->mmessage->listMessage('N', $limit, $offset, $order, $search, $sort);
	}

	public function listMessagePersonal()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'active_date';

		echo $this->mmessage->listMessage('Y', $limit, $offset, $order, $search, $sort);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0; ;

			$this->load->view('blocks/header');
			if($id == 0) {
				$this->load->view('message/add');
			} else {
				$where = array(
					'id'	=> $id
				);
				if ($id != 0) {
					$data['getData'] = $this->mmessage->getMessage("notification", $where)->result();
					$data['client'] = $data['getData'][0]->recipient == 'client' ? array('client_id' => $data['getData'][0]->recipient_id, 'client_name' => $this->mapi->getValue('ta_users', 'ta_name', $data['getData'][0]->recipient_id)[0]->ta_name) : array('client_id' => '', 'client_name' => '');
					$data['team'] = $data['getData'][0]->recipient == 'team' ? array('team_id' => $data['getData'][0]->recipient_id, 'team_name' => $this->mapi->getValue('team', 'team_name', $data['getData'][0]->recipient_id)[0]->team_name) : array('team_id' => '', 'team_name' => '');
				}
				$this->load->view('message/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}		
	}

	public function view($id)
	{
		if($this->session->userdata('login')) {
			$id 	= intval($id);

			$this->load->view('blocks/header');
			$where = array('id'	=> $id);
			$data['getData'] = $this->mmessage->getMessage("notification", $where)->result();	
			$this->load->view('message/view', $data);
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}		
	}

	public function save()
	{
		$session 		= $this->session->userdata('login');
		$id				= intval($this->input->post('id'));
		$message_name	= trim($this->input->post('message_name'));
		$message		= trim($this->input->post('message'));
		$recipient		= trim($this->input->post('recipient'));
		$client_id		= trim($this->input->post('client_id'));
		$team_id		= trim($this->input->post('team_id'));
		$recipient_id	= $recipient == 'client' ? $client_id : ($recipient == 'team' ? $team_id : null);
		$created_date	= date('Y-m-d');
		$created_by		= $session['id'];

		if($id == 0) {
			$data = array(
				'message_name'	=> $message_name,
				'message'		=> $message,
				'recipient'		=> $recipient,
				'recipient_id'	=> $recipient_id,
				'active_date'	=> date('Y-m-d'),
				'created_date'	=> date('Y-m-d'),
				'created_by'	=> $_SESSION['login']['id']
			);
		} else {
			$data = array(
				'message_name'	=> $message_name,
				'message'		=> $message,
				'recipient'		=> $recipient,
				'recipient_id'	=> $recipient_id,
				'active_date'	=> date('Y-m-d'),
				'created_date'	=> date('Y-m-d'),
				'created_by'	=> $_SESSION['login']['id']
			);			
		}
		$where = array(
			'id'	=> $id
		);

		$this->mmessage->save($id, $data, $where, 'notification');
		
		redirect('/message');
	}

	public function publish($id)
	{
		$id	= intval($id);
		
		$result['success']	= true;
		$result['msg']		= "success";
		
		$notif = $this->db->get_where('notification', array('id' => $id))->result();
		
		if($notif[0]->recipient == 'client') {
			$where = array(
					'team_status' => '0',
					'app_status' => '0',
					'app_token <>' => '',
					'client_id' => $notif[0]->recipient_id
				);
		} elseif($notif[0]->recipient == 'team') {
			$where = array(
					'team_status' => '0',
					'app_status' => '0',
					'app_token <>' => '',
					'id' => $notif[0]->recipient_id
				);
		} else {
			$where = array(
					'team_status' => '0',
					'app_status' => '0',
					'app_token <>' => ''
				);
		}
		
		$this->db->select('mobile_phone,app_token');
		$this->db->from('team');
		$this->db->where( $where );
		$query = $this->db->get();
		$team = $query->result_array();
		foreach($team as $row){
			$reg_token[] = $row['app_token'];
			$data = array(
					'notif_id'		=> $id,
					'notif_mobile'	=> $row['mobile_phone'],
					'notif_status'	=> '0',
				);
			$query = $this->db->insert('notification_detail', $data);
			if (!$query) {
				$result['success']	= false;
				$result['msg'] 	= $this->db->_error_message();
			}
		}
		
		$this->db->where( array('id' => $id) );
		$query = $this->db->update('notification', array('status' => '0', 'active_date' => date('Y-m-d')));
		
		
		//send notification
		$api_key = 'AIzaSyAJDC4jGvDYrwE_f9p4s9nJ4t21V0iWIKM'; 
		
        if(isset($reg_token)) {
            $msg = array
             (
                 'message' => $notif[0]->message_name,
                 'body' 	=> 'Body',
                 'title' => 'Admin',
                 'subtitle' => 'subtitle',
                 'tickerText' => 'Ada pesan baru dari Admin',
                 'vibrate' => 1,
                 'sound' => 1,
                 'largeIcon' => 'large_icon',
                 'smallIcon' => 'small_icon'
             );
             
             $fields = array(
                         'registration_ids' => $reg_token,
                         'data' => $msg
                         );
             
             $headers = array(
                         'Authorization: key=' . $api_key,
                         'Content-Type: application/json'
                         );
            
             //Using curl to perform http request 
             $ch = curl_init();
             curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
             curl_setopt( $ch,CURLOPT_POST, true );
             curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
             curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
             curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
             curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
             
             //Getting the result 
             $results = curl_exec($ch );
             curl_close( $ch );

            echo json_encode($result);
        }
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id'	=> $id
		);

		$status	= $this->mapi->delete($where, 'notification');
		echo json_encode($status);
	}
}