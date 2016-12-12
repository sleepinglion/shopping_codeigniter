<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Reports extends SL_Controller {
	protected $model = 'Report';
	
	public function index($page = 0) {
		$this -> load -> model('Order');
		$orders=$this -> Order -> get_index($this->session->userdata('user_id'));
		
		if($orders['total']) {		
			$this -> view($orders['list'][0]['id']);
		} else {
			$this -> return_data['data']=$orders;
			$this -> layout -> render('reports/view', $this -> return_data);
		}
	}
	
	public function view($id) {
		$this -> load -> model('Report');
		$reports=$this -> Report -> get_content($id);
		
		$this -> return_data['data']=$reports;
		$this -> layout -> render('reports/view', $this -> return_data);
	}
}
