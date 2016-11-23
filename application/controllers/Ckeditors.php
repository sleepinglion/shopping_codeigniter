<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL_Photo.php';

class Ckeditors extends SL_Photo_Controller {
	protected $model = 'Ckeditor';

	public function upload_test() {
		$this -> load -> library('form_validation');
		$this -> form_validation -> set_rules('upload', 'upload', 'required');

		if ($this -> form_validation -> run() == FALSE) {
			$this -> layout -> render('ckeditors/upload_test', $this -> return_data);
		}
	}

	public function add() {
		$this -> load -> model($this -> model);

		if (empty($_FILES['upload']))
			throw new Exception("Error Processing Request", 1);

		if ($_FILES['upload']['error'])
			throw new Exception("Error Processing Request", 1);

		$data = array('data_file_name' => $_FILES['upload']['name']);
		$id = $this -> {$this -> model} -> insert($data);

		if (!$id)
			show_error();

		try {
			$upload_data = $this -> photo_upload($id);
		} catch(Exception $e) {
			$error = $e -> getMessage();
			$upload_data = false;
		}
		
		if ($upload_data) {
			$data['id'] = $id;
			$data['data_content_type'] = $upload_data['file_type'];
			$data['width'] = $upload_data['image_width'];
			$data['height'] = $upload_data['image_height'];
			$data['type'] = 'Ckeditor::Picture';
			$data['assetable_type'] = 'User';
			$data['assetable_id'] = $this -> session -> userdata('user_id');
			$data['data_file_size'] = $_FILES['upload']['size'];

			$result = $this -> {$this -> model} -> update($data);
			$this -> load -> view('ckeditors/upload', array('data' => array('CKEditorFuncNum' => 1, 'url' => $upload_data['url'])));
		} else {
			$this -> layout -> render('ckeditors/upload_test', array('error' => $this -> upload -> display_errors()));
			return true;
		}
	}
}
