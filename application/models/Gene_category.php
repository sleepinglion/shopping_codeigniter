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
	
	public function get_content($id,$order_id) {
		$this -> pdo -> select('gene_categories.*,report_categories.sub_title as report_category_sub_title');
		$this -> pdo -> join('reports','reports.gene_category_id = gene_categories.id');
		$this -> pdo -> join('report_categories','reports.report_category_id = report_categories.id');
		$this -> pdo -> where(array('reports.order_id'=>$order_id,'gene_categories.id' =>$id,'gene_categories.enable' => TRUE));
		$query = $this -> pdo -> get('gene_categories');
		$result = $query -> result_array();
		$content=$result[0];
		
		return $content;
	}	
}