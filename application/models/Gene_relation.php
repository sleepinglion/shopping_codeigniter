<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gene_relation extends CI_Model {
	public function __construct() {
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}

	public function get_count($id = NULL) {
		if (isset($id)) {
			$this -> pdo -> where(array('gene_relations.id' => $id));
		}

		return $this -> pdo -> count_all_results('gene_relations');
	}

	public function get_index($gene_list) {
		if (empty($gene_list['total']))
			return $gene_list;

		foreach ($gene_list['list'] as $index => $value) {
			$this -> pdo -> join('genes_gene_relations', 'gene_relations.id=genes_gene_relations.gene_relation_id');
			$this -> pdo -> where(array('genes_gene_relations.gene_id' => $value['id'], 'gene_relations.enable' => TRUE));

			$result = array();
			$gene_list['list'][$index]['gene_relations']['total'] = $this -> pdo -> count_all_results('gene_relations');
			
			if(empty($gene_list['list'][$index]['gene_relations']['total']))
				continue;

			$this -> pdo -> select('gene_relations.*');
			$this -> pdo -> join('genes_gene_relations', 'gene_relations.id=genes_gene_relations.gene_relation_id');
			$this -> pdo -> where(array('genes_gene_relations.gene_id' => $value['id'], 'gene_relations.enable' => TRUE));

			$query = $this -> pdo -> get('gene_relations');
			$gene_list['list'][$index]['gene_relations']['list'] = $query -> result_array();
		}

		return $gene_list;

	}

}
