<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Products extends SL_Controller {
	protected $model = 'Product';	

	public function index($page = 0) {
		$this -> load -> model('Product');
		$products = $this -> Product -> get_index();

		$this -> return_data['data'] = $products;

		$this -> layout -> render('products/index', $this -> return_data);
	}

	public function add() {
		$this -> layout -> render('products/add', $this -> return_data);
	}

}
