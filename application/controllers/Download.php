<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

	public function Index()
	{
		$this->db->select('build');
		$this->db->from( "app_version" );
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		$result = $query->row();
		
		$File = "http://tempelad.mesinrusak.com/resources/apps/tempelad_".$result->build.".apk";
		
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-Disposition: attachment; filename=\"" . basename($File) . "\"");
		//header("Content-Type: application/force-download");
		//header("Content-Length: " . filesize($File));
		//header("Connection: close");
		ob_clean(); flush();
		readfile($File);
	}
	
}