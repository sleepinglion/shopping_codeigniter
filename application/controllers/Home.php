<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Home extends SL_Controller {
	protected $model = 'Home';
	protected $ad = false;

	public function index($page = 0) {

		$this -> layout -> add_js(base_url() . 'js/plugin/jquery.tagcanvas.min.js');
		$this -> layout -> add_js(base_url() . 'js/index.js');

		if (ENVIRONMENT == 'production')
			$this -> output -> cache(1200);

		$this -> layout -> render('home/index', $this -> return_data);
	}

	public function get_json_error_message() {
		echo json_encode(array('result' => 'success', 'message' => $this -> get_error_messages()));
	}

}
