<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrdersProduct extends CI_Model {
	protected $table = 'orders_products';

	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}

	public function insert($order_id, Array $products) {
		$date = date('Y-m-d H:i:s');
		$data['created_at'] = $date;

		foreach ($products as $index => $product) {
			$filtered_data['order_id']=$order_id;
			$filtered_data['product_id']=$product['id'];
			$filtered_data['quantity']=$product['quantity'];

			if ($this -> pdo -> insert($this -> table, $filtered_data)) {
				$id = $this -> pdo -> insert_id();

				$result[]=$id;
			} else {
				$result[]=false;
			}
			unset($filtered_data);
		}
		
		return $result;
	}

}
