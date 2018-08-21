<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {
	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
		$this->load->model('mapi');
		$this->load->model('mteam');
        
		if(!$this->session->userdata('login')) {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Index()
	{
		if($this->session->userdata('login')) {
			if($_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 5) {
				$this->load->view('blocks/header');
				$this->load->view('team/team');
				$this->load->view('blocks/footer');
			} else {
				$this->output->set_status_header('404');
				$this->load->view('404');
			}
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function listTeam()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'desc';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'team_name';
		echo $this->mteam->listTeam($limit, $offset, $order, $search, $sort);
	}

	public function Form($id = null)
	{
		if($this->session->userdata('login')) {
			$id 	= (isset($id)) ? intval($id) : 0;
			$where1 = array(
				'status'	=> 0
			);
            
            $data['getCity']	    = @$this->mapi->getCityDetail();
            $data['getShift']	    = @$this->mapi->getData('master_shift' , null, 'id')->result_array();
            $data['getPosition']	= @$this->mapi->getData('master_position' , null, 'name')->result_array();

			$this->load->view('blocks/header');
			if ($id == 0) {
				$this->load->view('team/add', $data);
			} else {
				$where = array(
					'id'	=> $id
				);
				$data['getData'] = $this->mteam->getData($id);
               
                
                //var_dump($data['getPosition']);exit();
                
				$this->load->view('team/edit', $data);
			}
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function Save()
	{
		$id             = intval($this->input->post('id'));
		$site_id        = intval($this->input->post('site_id'));
		$shift_id       = intval($this->input->post('shift_id'));
		$position_id    = intval($this->input->post('position_id'));
		$team_name      = trim($this->input->post('team_name'));
		$address        = trim($this->input->post('address'));
		$city_id        = intval($this->input->post('city_id'));
		$mobile_phone   = trim($this->input->post('mobile_phone'));
		$join_date	    = trim($this->input->post('join_date'));
		$team_status    = trim($this->input->post('team_status'));
		$app_status     = trim($this->input->post('app_status'));
		$education      = trim($this->input->post('education'));
		$experience     = trim($this->input->post('experience'));
		$training       = trim($this->input->post('training'));

		$config['upload_path']		= './assets/images/';
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= 1000;
		$config['max_width']		= 1024;
		$config['max_height']		= 768;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        
        $avatar = '';
        $certificate = '';
        $file['uploaded'] = '0';
        $file['errors'] = '0';

		if($_FILES['file']['name'])
		{
			if ($this->upload->do_upload('file'))
			{
				$gbr = $this->upload->data();
				$config2['image_library']	= 'gd2'; 
				$config2['source_image']	= $this->upload->upload_path.$this->upload->file_name;
				$config2['new_image']		= './assets/images/photo/team/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
                $avatar= 'assets/images/photo/team/'.$gbr['file_name'];
			} else {
				echo $this->upload->display_errors('', '');
			}
		} 
        
        if ($_FILES['certificate']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            // Define the settings that will be used against all files.
            $myUploadSettings = array(
                'upload_path'   => dirname(BASEPATH) ."/public/uploads/",
                'allowed_types' => "jpg|png|pdf"
            );

            // Load the library with our settings.
            $this->load->library('uploads', $myUploadSettings);

            // Attempt to upload all files.
            $uploadedData = array();
            $uploadErrorsList = array();
            if (!$this->uploads->do_upload('certificate')) {
                // Retrieve all errors in a single string.
                $uploadErrorsString = $this->uploads->display_errors();
                // Retrieve an associative array containing all errors separated by the files in which their occurred  (as fileName => errMessage).
                $uploadErrorsList = $this->uploads->getErrorMessages();
                $file['uploadErrorsList'] = $uploadErrorsList;
                $file['uploadErrorsString'] = $uploadErrorsString;
            } else {
                // All files were uploaded successfully.
                $file['errorslist'] = '0';
            }

            // Retrieve an associative array containing some data from all files that were uploaded successfully (as fileName => fileData).
            $uploadedData = $this->uploads->data();

            // Check if any files were uploaded successfully.
            if (count($uploadedData) > 0) {
                // Yay, at least one file was uploaded!
                $certificate = $uploadedData;
                $file['uploaded'] = count($uploadedData);
            } else {
                // No files were uploaded.
                $file['uploaded'] = '0';
            }

            // Check and handle errors that may occurred.
            if (count($uploadErrorsList) > 0) {
                // Damn, let's handle these errors.
                $file['errors'] = '1';
            } else {
                // Yay, no errors!
                $file['errors'] = '0';
            }
        } else {
            // No files were selected.
                $file['selected'] = '0';
        }
        
        
        $data = array(
            'site_id'       => $site_id,
            'shift_id'      => $shift_id,
            'position_id'   => $position_id,
            'team_name'     => $team_name,
            'address'       => $address,
            'city_id'       => $city_id,
            'mobile_phone'  => $mobile_phone,
            'join_date'     => $join_date,
            'team_status'   => $team_status,
            'app_status'    => $app_status,
            'modified_date' => date('Y-m-d H:i:s'),
            'created_by'    =>  $_SESSION['login']['id'],
            'modified_by'   => date('Y-m-d H:i:s'),
            'modified_by'   =>  $_SESSION['login']['id'],
            'education'     => $education,
            'experience'    => $experience,
            'training'      => $training
        );	
        
        if($avatar) $data['avatar'] = $avatar;
        if($certificate) {
            $cert = array();
            
            //add new file
            foreach($certificate as $row) {
                $cert[] = $row['file_name'];
            }
        
            $data['certificate'] = json_encode($cert); 
        }

		$where = array(
			'id'	=> $id
		);

		$this->mapi->save($id, $data, $where, 'team');
		redirect('/team');
	}

	public function Edit()
	{
		$id             = intval($this->input->post('id'));
		$shift_id       = intval($this->input->post('shift_id'));
		$site_id        = intval($this->input->post('site_id'));
		$position_id    = intval($this->input->post('position_id'));
		$team_name      = trim($this->input->post('team_name'));
		$address        = trim($this->input->post('address'));
		$city_id        = intval($this->input->post('city_id'));
		$mobile_phone   = trim($this->input->post('mobile_phone'));
		//$jDate 			= explode('/', $this->input->post('join_date'));
		//$join_date		= $jDate[2].'-'.$jDate[0].'-'.$jDate[1];
		$join_date		= trim($this->input->post('join_date'));
		$team_status    = intval($this->input->post('team_status'));
		$app_status     = intval($this->input->post('app_status'));
		$education      = trim($this->input->post('education'));
		$experience     = trim($this->input->post('experience'));
		$training       = trim($this->input->post('training'));
        

		$config['upload_path']		= './assets/images/';
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= 1000;
		$config['max_width']		= 1024;
		$config['max_height']		= 768;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        
        $avatar = '';
        $certificate = '';
        $file['uploaded'] = '0';
        $file['errors'] = '0';

		if($_FILES['file']['name'])
		{
			if ($this->upload->do_upload('file'))
			{
				$gbr = $this->upload->data();
				$config2['image_library']	= 'gd2'; 
				$config2['source_image']	= $this->upload->upload_path.$this->upload->file_name;
				$config2['new_image']		= './assets/images/photo/team/';
				$config2['maintain_ratio']	= TRUE;
				$config2['width']			= 100;
				$this->load->library('image_lib',$config2); 

				if ( !$this->image_lib->resize()){
					$this->session->set_flashdata('errors', $this->image_lib->display_errors('', ''));   
				}
				@unlink('./assets/images/'.$gbr['file_name']);
                
                $avatar = 'assets/images/photo/team/'.$gbr['file_name'];
			} else {
				echo $this->upload->display_errors('', '');
			}
		}
        
        if ($_FILES['certificate']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            // Define the settings that will be used against all files.
            $myUploadSettings = array(
                'upload_path'   => dirname(BASEPATH) ."/public/uploads/",
                'allowed_types' => "jpg|png|pdf"
            );

            // Load the library with our settings.
            $this->load->library('uploads', $myUploadSettings);

            // Attempt to upload all files.
            $uploadedData = array();
            $uploadErrorsList = array();
            if (!$this->uploads->do_upload('certificate')) {
                // Retrieve all errors in a single string.
                $uploadErrorsString = $this->uploads->display_errors();
                // Retrieve an associative array containing all errors separated by the files in which their occurred  (as fileName => errMessage).
                $uploadErrorsList = $this->uploads->getErrorMessages();
                $file['uploadErrorsList'] = $uploadErrorsList;
                $file['uploadErrorsString'] = $uploadErrorsString;
            } else {
                // All files were uploaded successfully.
                $file['errorslist'] = '0';
            }

            // Retrieve an associative array containing some data from all files that were uploaded successfully (as fileName => fileData).
            $uploadedData = $this->uploads->data();

            // Check if any files were uploaded successfully.
            if (count($uploadedData) > 0) {
                // Yay, at least one file was uploaded!
                $certificate = $uploadedData;
                $file['uploaded'] = count($uploadedData);
            } else {
                // No files were uploaded.
                $file['uploaded'] = '0';
            }

            // Check and handle errors that may occurred.
            if (count($uploadErrorsList) > 0) {
                // Damn, let's handle these errors.
                $file['errors'] = '1';
            } else {
                // Yay, no errors!
                $file['errors'] = '0';
            }
        } else {
            // No files were selected.
                $file['selected'] = '0';
        }
        
        $data = array(
            'site_id'				=> $site_id,
            'shift_id'				=> $shift_id,
            'position_id'			=> $position_id,
            'team_name'	            => $team_name,
            'address'			    => $address,
            'city_id'				=> $city_id,
            'mobile_phone'			=> $mobile_phone,
            'join_date'				=> $join_date,
            'team_status'			=> $team_status,
            'app_status'			=> $app_status,
            'modified_date'			=> date('Y-m-d H:i:s'),
            'modified_by'			=> $_SESSION['login']['id'],
            'education'             => $education,
            'experience'            => $experience,
            'training'              => $training
        );

		$where = array(
			'id'	=> $id
		);
        
        if($avatar) $data['avatar'] = $avatar;
        if($certificate) {
            $cert = array();
            
            //get old file
            $this->db->select('certificate');
            $this->db->from('team');
            $this->db->where($where);
            $query = $this->db->get();
            
            if(count($query->result()) > 0) {
                foreach($query->result() as $row) {
                    $json = json_decode($row->certificate);
                    foreach($json as $value) {
                        $cert[] = $value;
                    }
                }
            }
            
            //add new file
            foreach($certificate as $row) {
                $cert[] = $row['file_name'];
            }
        
            $data['certificate'] = json_encode($cert); 
        }
        
		$this->mapi->save($id, $data, $where, 'team');
        
		redirect('/team');
	}

	public function delete($id)
	{
		$id	= intval($id);
		$where = array(
			'id' => $id
		);

		$status	= $this->mapi->delete($where, 'team');
		echo json_encode($status);
	}

	public function del_cert($id, $name)
	{
		$id	= intval($id);
		$where = array(
			'id' => $id
		);
        
        $this->db->select('certificate');
		$this->db->from('team');
		$this->db->where($where);
		$query = $this->db->get();
        
        foreach($query->result() as $row) {
            $json = json_decode($row->certificate);
            foreach($json as $k => $v) {
                if($name == $v) {
                    unlink(dirname(BASEPATH) ."/public/uploads/". $v);
                    unset($json[$k]);
                }
            }
        }
        
        $save['certificate'] = json_encode($json);
        
        $this->mapi->save($id, $save, $where, 'team');
        
        redirect('/team/form/' . $id);
        /*
        
        $data['getCity']	    = @$this->mapi->getCityDetail();
        $data['getShift']	    = @$this->mapi->getData('master_shift' , null, 'id')->result_array();
        $data['getPosition']	= @$this->mapi->getData('master_position' , null, 'name')->result_array();

        $this->load->view('blocks/header');
        $data['getData'] = $this->mteam->getData($id);  
        $this->load->view('team/edit', $data);
        $this->load->view('blocks/footer');
        */
	}

	public function app_status($id, $app_status)
	{
		$id	= intval($id);
		if( $app_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'app_status' => $status
		);

		$where = array(
			'id'	=> $id
		);
		$status	= $this->mapi->updStatus($id, $data, $where, 'team');
		echo json_encode($status);
	}

	public function team_status($id, $team_status)
	{
		$id	= intval($id);
		if( $team_status == 0 ) { $status = 1; }
		else { $status = 0; }

		$data = array(
			'team_status' => $status
		);

		$where = array(
			'id'	=> $id
		);
		$status	= $this->mapi->updStatus($id, $data, $where, 'team');
		echo json_encode($status);
	}

	public function uuid_status($id, $uuid_status)
	{
		$id	= intval($id);
		if( $uuid_status == 0 ) { 
			$data = array(
				'app_uuid' => ''
			);

			$where = array(
				'id'	=> $id
			);
			$status	= $this->mapi->updStatus($id, $data, $where, 'team');
		} else {
			$status = array('msg' => 'success');
		}
		echo json_encode($status);
	}
}