<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gene_category extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}
	
	
	public function get_count($id = NULL) {
		if (isset($id)) {
			$this -> pdo -> where(array('gene_categories.id' => $id));
		}

		return $this -> pdo -> count_all_results('gene_categories');
	}
	
	public function get_content($id) {
		$this -> pdo -> where(array('gene_categories.id' =>$id,'gene_categories.enable' => TRUE));
		$query = $this -> pdo -> get('gene_categories');
		$result = $query -> result_array();
		$content=$result[0];
		
		return $content;
	}	
}