<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}

	public function get_index() {
		$this -> pdo -> where(array('reports.enable' => TRUE));
		
		$query = $this -> pdo -> get('reports');
		$result['list'] = $query -> result_array();
		return $result;
	}

	public function get_content($order_id) {
		$this -> pdo -> where(array('reports.order_id' => $order_id,'reports.enable' => TRUE,'genes_related_actions.enable'=>TRUE,'report_categories.enable'=>TRUE));	
		$this -> pdo -> join('genes_related_actions','reports.genes_related_action_id = genes_related_actions.id');
		$this -> pdo -> join('report_categories','reports.id = report_categories.id');
		
		$result['total'] = $this -> pdo -> count_all_results('reports');
	
		$this -> pdo -> select('related_actions.title as gene_category_title,report_categories.id as report_category_id,report_categories.title as report_category_title,reports.*');
		$this -> pdo -> where(array('reports.order_id' => $order_id,'reports.enable' => TRUE,'gene_categories.enable'=>TRUE,'report_categories.enable'=>TRUE));
		$this -> pdo -> join('genes_related_actions','reports.genes_related_action_id = genes_related_actions.id');
		$this -> pdo -> join('report_categories','reports.report_category_id = report_categories.id');
		
		$query = $this -> pdo -> get('reports');
		$result['list'] = $query -> result_array();
		
		return $result;
	}
}
