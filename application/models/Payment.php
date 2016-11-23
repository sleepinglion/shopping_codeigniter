<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}

	public function get_index() {
		$this -> pdo -> where(array('payments.enable' => TRUE));
		$query = $this -> pdo -> get('payments');
		$result['list'] = $query -> result_array();
		return $result;
	}

}
