<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}	
	
	public function get_index() {
		$this -> pdo -> where(array('products.enable' => TRUE));
		$query = $this -> pdo -> get('products');
		$result['list'] = $query -> result_array();
		
		if(count($result['list'])) {
			foreach($result['list'] as $index=>$value) {
				$this -> pdo -> where(array('product_pictures.enable' => TRUE,'product_pictures.product_id'=>$value['id']));
				$query = $this -> pdo -> get('product_pictures');
				$result['list'][$index]['photo_list']= $query -> result_array();
			}
		}
		
		return $result;
	}
	
	public function get_count($id = NULL) {
		if (isset($id)) {
			$this -> pdo -> where(array('products.id' => $id));
		}

		return $this -> pdo -> count_all_results('products');
	}	
	
	public function get_content($id) {
		$this -> pdo -> from('products');
		$this -> pdo -> where(array('products.id' => $id));
		$query = $this -> pdo -> get();
		$result = $query -> result_array();
		
		$this -> pdo -> where(array('product_pictures.enable' => TRUE,'product_pictures.product_id'=>$id));
		$query = $this -> pdo -> get('product_pictures');
		$result[0]['photo_list']= $query -> result_array();

		return $result[0];
	}	
}