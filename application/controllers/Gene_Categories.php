<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Gene_categories extends SL_Controller {
	protected $model = 'Gene_category';

	public function index($page = 0) {
		$this -> load -> model('Product');
		$products = $this -> Product -> get_index();

		$this -> return_data['data'] = $products;

		$this -> layout -> render('products/index', $this -> return_data);
	}

	public function add() {
		$this -> layout -> render('products/add', $this -> return_data);
	}

	public function view($id) {
		$this -> load -> model($this -> model);

		if (!$this -> {$this -> model} -> get_count($id))
			show_404();
		
		$this -> load -> model('Order');
		$orders=$this -> Order -> get_index($this->session->userdata('user_id'));
		$order_id=$orders['list'][0]['id'];
		
		$this -> return_data['data']['content'] = $this -> {$this -> model} -> get_content($id,$order_id);
		
		$this -> load -> model('Gene');
		$this -> return_data['data']['genes']=$this->Gene->get_index($this -> return_data['data']['content']['id']);
		
		$this -> load -> model('Gene_relation');
		$this -> return_data['data']['genes']=$this->Gene_relation->get_index($this -> return_data['data']['genes']);
		
		$this -> layout -> render($this -> router -> fetch_class() . '/view', $this -> return_data);
	}
}
