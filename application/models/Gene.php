<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gene extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}
	
	
	public function get_count($id = NULL) {
		if (isset($id)) {
			$this -> pdo -> where(array('genes.id' => $id));
		}

		return $this -> pdo -> count_all_results('genes');
	}
	
	public function get_index($category_id) {
		$this -> pdo -> join('gene_categories_genes','genes.id=gene_categories_genes.gene_id');
		$this -> pdo -> where(array('gene_categories_genes.gene_category_id' =>$category_id,'genes.enable' => TRUE));
		
		$result=array();
		$result['total'] = $this -> pdo -> count_all_results('genes');
		
		$this -> pdo -> select('genes.*');
		$this -> pdo -> join('gene_categories_genes','genes.id=gene_categories_genes.gene_id');
		$this -> pdo -> where(array('gene_categories_genes.gene_category_id' =>$category_id,'genes.enable' => TRUE));
		$query = $this -> pdo -> get('genes');
		$result['list'] = $query -> result_array();
		
		return $result;
		
	}	
}