<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends CI_Model {
	protected $order='id';
	protected $desc=true;	
	protected $table = 'shippings';
	protected $accepted_attributes = array('user_id','same_order', 'name', 'zip_code', 'address_default', 'address_detail', 'email', 'phone', 'message','enable','updated_at', 'created_at');

	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}
	
	public function get_index($per_page = 0, $page = 0, $user_id = NULL, $order = NULL, $desc = NULL, $enable = TRUE) {
		if (isset($user_id)) {
			$this -> pdo -> where(array($this -> table . '.user_id' => $user_id,$this -> table . '.enable' => true));
		}
		
		$result['total'] = $this -> pdo -> count_all_results($this -> table);
		
		if (!$result['total'])
			return $result;

		if (empty($order)) {
			if (empty($this -> order)) {
				$order = $this -> order;
			} else {
				$order = 'id';
			}
		}

		if (empty($desc)) {
			if (empty($this -> desc)) {
				$desc = $this -> desc;
			} else {
				$desc = TRUE;
			}
		}
		
		if(!is_bool($desc))
			throw new Exception("Error Processing Request", 1);
		
		if($desc) {
			$desc='desc';
		}	else {
			$desc='asc';
		}
		
		$this -> pdo -> where(array($this -> table . '.enable' => TRUE));
		$this -> pdo -> order_by($order, $desc);
		$query = $this -> pdo -> get($this -> table, $per_page, $page);
		$result['list'] = $query -> result_array();
		return $result;
	}	

	public function insert(Array $data) {
		$data['user_id'] = $this -> session -> userdata('user_id');
		$date = date('Y-m-d H:i:s');
		$data['enable'] = true;
		$data['updated_at'] = $date;
		$data['created_at'] = $date;

		foreach ($data as $key => $value) {
			if (in_array($key, $this -> accepted_attributes))
				$filtered_data[$key] = $value;
		}

		if ($this -> pdo -> insert($this -> table, $filtered_data)) {
			$id = $this -> pdo -> insert_id();

			return $id;
		} else {
			return false;
		}
	}

}
