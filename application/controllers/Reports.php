<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Reports extends SL_Controller {
	protected $model = 'Product';
	
	public function index($page = 0) {
		//$this -> load -> model('Reports');
		//$reports=$this -> Reports -> get_index();
		
		//$this -> return_data['data']=$reports;
		$this -> layout -> render('reports/index', $this -> return_data);
	}
}
