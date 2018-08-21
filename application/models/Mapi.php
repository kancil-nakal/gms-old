<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MApi extends CI_Model {
	function listManage($group, $limit, $offset, $order, $search, $sort)
	{
		if($group == 1 || $group == 5) {
			$rs 	= $this->db->query("SELECT id FROM ta_users WHERE (ta_name LIKE '%".$search."%' || ta_email LIKE '%".$search."%' || ta_phone LIKE '%".$search."%') AND group_user IN (1,5)");
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT id, ta_name, ta_email, ta_phone, ta_status, group_user FROM ta_users WHERE (ta_name LIKE '%".$search."%' || ta_email LIKE '%".$search."%' || ta_phone LIKE '%".$search."%') AND group_user IN (1,5) ORDER BY $sort $order LIMIT $offset,$limit");
		} elseif($group == 2) {
			$rs 	= $this->db->query("SELECT T01.id FROM ta_users T01 WHERE (T01.ta_name LIKE '%".$search."%' || T01.ta_email LIKE '%".$search."%' || T01.ta_phone LIKE '%".$search."%') AND T01.group_user = ".$group);
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT T01.id, T01.ta_name, T01.ta_email, T01.ta_phone, T01.ta_status FROM ta_users T01 WHERE (T01.ta_name LIKE '%".$search."%' || T01.ta_email LIKE '%".$search."%' || T01.ta_phone LIKE '%".$search."%') AND T01.group_user = ".$group." ORDER BY $sort $order LIMIT $offset,$limit");
		} else {
			$rs 	= $this->db->query("SELECT T01.id FROM ta_users T01 WHERE (T01.ta_name LIKE '%".$search."%' || T01.ta_email LIKE '%".$search."%' || T01.ta_phone LIKE '%".$search."%') AND T01.group_user = ".$group);
			$result["total"] = $rs->num_rows();

			$rs		= $this->db->query("SELECT T01.id, T01.ta_name, T01.ta_email, T01.ta_phone, T01.ta_status FROM ta_users T01 WHERE (T01.ta_name LIKE '%".$search."%' || T01.ta_email LIKE '%".$search."%' || T01.ta_phone LIKE '%".$search."%') AND T01.group_user = ".$group." ORDER BY $sort $order LIMIT $offset,$limit");
		}

		$items 	= array();
		while($row = $rs->unbuffered_row('object')) {
			array_push($items, $row);
		}
		$result["rows"] = $items;
		echo json_encode($result);
	}

	public function save($id, $data, $where, $table) {
		if($id == 0) {
			$this->db->insert($table, $data);
			$query = $this->db->insert_id();
		} else {
			$this->db->where($where);
			$query = $this->db->update($table, $data);
		}
		return $query;
	}

	public function updStatus($id, $data, $where, $table) {
		$this->db->where($where);
		$query = $this->db->update($table, $data);

		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "success";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}

	public function getData($table, $where, $order = null)
	{
		if($order !== null) $this->db->order_by($order, 'ASC');
		return $this->db->get_where($table, $where);
	}

	public function delete($where,$table)
	{
		$this->db->where($where);
		$query = $this->db->delete($table);

		if ($query) {
			$msg['success']	= true;
			$msg['msg']		= "success";
		} else {
			$msg['success']	= false;
			$msg['msg'] 	= $this->db->_error_message();
		}
		return $msg;
	}

	public function getCity($where, $table)
	{
		$this->db->select('id, city_name');
		$this->db->from($table);
		$this->db->where( $where );
		$this->db->order_by('city_name', 'ASC');
		$query = $this->db->get();

		return json_encode($query->result());
	}

	public function getCityDetail()
	{
		$this->db->select('id, city_name');
		$this->db->from('city');
		$this->db->order_by('city_name', 'ASC');
		$query = $this->db->get();

		$i = 0;
		foreach ($query->result() as $row)
		{
			$data[$i]['id']			= $row->id;
			$data[$i]['city_name']	= $row->city_name;
			$i = $i + 1;
		}
		return $data;
	}

	public function getRequest($where, $table)
	{
		$this->db->select('DISTINCT(site_id)');
		$this->db->from( $table );
		$this->db->where( $where );
		$this->db->group_by( 'site_id' );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function requestWithdraw($where, $table)
	{
		$this->db->select('id');
		$this->db->from( $table );
		$this->db->where( $where );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function cekData($table, $where)
	{
		return $this->db->get_where($table, $where);
	}

	public function getPosition()
	{
		$sql 		= "SELECT id, propinsi_name FROM province WHERE status = 0 ORDER BY propinsi_name ASC";
		$queries 	= $this->db->query($sql);
		$i 			= 0;
		foreach($queries->result() as $row)
		{
			$result['id'][$i] 				= $row->id; 
			$result['propinsi_name'][$i] 	= $row->propinsi_name;
			$i = $i + 1;
		}
		return $result;
	}

	public function getPhone($where, $table)
	{
		$this->db->select('id');
		$this->db->from( $table );
		$this->db->where( $where );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getClient()
	{
		$sql 	= "SELECT id, ta_name FROM ta_users WHERE ta_status = 0 AND group_user = 2 ORDER BY ta_name ASC";
		$query	= $this->db->query($sql);
		return $query->result();
	}

	public function getValue($table, $column, $id, $id_column = 'id')
	{
		$this->db->select($column);
		$this->db->from($table);
		$this->db->where(array($id_column => $id));
		$query = $this->db->get();
		$result = $query->result();
		$status = false;
		if($result) $status = isset($result[0]->status) ? $result[0]->status : $result; 
		return $status;
	}
}
