<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MRole extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	function groupuser($limit, $offset, $order, $search, $sort)
	{
		$rs 	= $this->db->query("SELECT id FROM ta_users_group WHERE group_name LIKE '%".$search."%'");
		$result["total"] = $rs->num_rows();

		$rs		= $this->db->query("SELECT id, group_name, status FROM ta_users_group WHERE group_name LIKE '%".$search."%' ORDER BY $sort $order LIMIT $offset,$limit");

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function role_menu($where)
	{
		$this->db->select("id, menu_name, reference_id");
		$this->db->from("ta_menu");
		$this->db->where($where);
		$this->db->order_by("menu_name", "asc");
		$query = $this->db->get();

		return $query->result();
	}

	public function getData($where)
	{
		$this->db->select("T01.id, T01.group_name, T02.menu_id, T02.id AS group_id");
		$this->db->from("ta_users_group T01");
		$this->db->join('ta_group_menu T02', 'T02.group_user = T01.id', 'left');
		$this->db->where($where);
		$query = $this->db->get();

		return $query->result();
	}

	public function edit($data, $where, $table)
	{
		$this->db->where($where);
		$query = $this->db->update($table, $data);
		return $query;
	}
}