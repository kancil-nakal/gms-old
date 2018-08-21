<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  

class RoleMenu {
	function __construct()
	{
		$this->ci =& get_instance();
	}

	public function build_menu($type)
	{
		$menu 	= array();
		$html_out = '';
		$role 	= $this->ci->db->query("SELECT menu_id FROM ta_group_menu WHERE group_user = ".$type."");
		$res 	= $role->result();
		$split	= explode(',', $res[0]->menu_id);
		$query	= $this->ci->db->query("SELECT id, menu_name, menu_link, icon FROM ta_menu WHERE status = '0' AND reference_id = 0 ORDER BY menu_name ASC");

		foreach ($query->result() as $row)
		{
			$id 		= $row->id;
			if(in_array($id, $split)) {		
				$title 		= $row->menu_name;
				$url 		= $row->menu_link;
				$icon 		= $row->icon;
				{
					if ($url != '')
					{
						$html_out .= '<li><a href="'.base_url($url).'"><span class="nav-icon"><i class="material-icons">'.$icon.'</i></span><span class="nav-text">'.$title.'</span></a></li>';
					} else {
						$html_out .= '<li><a><span class="nav-caret"><i class="fa fa-caret-down"></i></span><span class="nav-icon"><i class="material-icons">'.$icon.'</i></span><span class="nav-text">'.$title.'</span></a>';
					}
				}
				$html_out .= $this->get_childs($id, $split);
			}
		}
		$html_out .= '</li>';
		return $html_out;
	}

	function get_childs($id, $split)
	{
		$html_out = '<ul class="nav-sub">';

		$query = $this->ci->db->query("SELECT id, menu_name, menu_link, icon FROM ta_menu WHERE status = '0' AND reference_id = $id ORDER BY ordering ASC");

		foreach ($query->result() as $row)
		{
			$id 		= $row->id;
			if(in_array($id, $split)) {	
				$title 		= $row->menu_name;
				$url 		= $row->menu_link;
				$icon 		= $row->icon;

				$html_out .= '<li><a href="'.base_url($url).'" ><span class="nav-text">'.$title.'</span></a></li>';
			}
		}
		$html_out .= '</ul>';
		return $html_out;
	}
}