<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Model {
	protected $table = 'orders';
	protected $accepted_attributes = array('user_id', 'shipping_id', 'payment_id', 'same_order', 'enable', 'updated_at', 'created_at');

	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
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
