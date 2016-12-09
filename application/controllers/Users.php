<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Users extends SL_Controller {
	protected $model = 'User';

	public function index() {
		$this -> return_data['common_data']['title'] = _('My Page');
		$this -> layout -> render('users/index', $this -> return_data);
	}

	public function view($id) {
		$this -> layout -> render('users/view', $this -> return_data);
	}

	public function add() {
		$this -> load -> library('form_validation');
		$this -> set_message();

		$this -> form_validation -> set_rules('email', _('Email'), 'required|is_unique[users.email]|trim|valid_email');
		$this -> form_validation -> set_rules('password', _('Password'), 'required|trim|min_length[5]|max_length[40]|matches[password_confirm]');
		$this -> form_validation -> set_rules('password_confirm', _('Password Confirmation'), 'required|trim|min_length[5]|max_length[40]|matches[password]');
		$this -> form_validation -> set_rules('name', _('Name'), 'required|trim|min_length[2]|max_length[10]');
		$this -> form_validation -> set_rules('phone', _('Phone'), 'required|trim|min_length[5]');
		$this -> form_validation -> set_rules('birthday', _('Birthday'), 'required|trim');
		$this -> form_validation -> set_rules('birthday', _('Birthday'), 'required|trim|check_date', array('check_date' => _('%s Must Validate Date ex) Y-m-d')));
		$this -> form_validation -> set_rules('sex', _('Sex'), 'required|trim');

		$this -> form_validation -> set_rules('height', _('Height'), 'numeric|greater_than[20]|less_than[300]');
		$this -> form_validation -> set_rules('weight', _('Weight'), 'numeric|greater_than[20]|less_than[300]');

		$this -> form_validation -> set_rules('agree[over_age_18]', _('OverAge18 Agreement'), 'required', array('required' => _('Please Agree %s')));
		$this -> form_validation -> set_rules('agree[service]', _('Service Agreement'), 'required', array('required' => _('Please Agree %s')));
		$this -> form_validation -> set_rules('agree[information]', _('Personal Information Agreement'), 'required', array('required' => _('Please Agree %s')));

		$this -> return_data['common_data']['title'] = _('User Add');

		if ($this -> form_validation -> run() == FALSE) {
			$this -> layout -> add_css(base_url() . 'css/bootstrap-datepicker3.min.css');
			$this -> layout -> add_js(base_url() . 'js/validate.min.js');
			$this -> layout -> add_js(base_url() . 'js/bootstrap-datepicker.min.js');
			$this -> layout -> add_js(base_url() . 'js/users/add.js');
			$this -> layout -> render('users/add', $this -> return_data);
		} else {
			$data = $this -> input -> post(NULL, TRUE);

			$this -> load -> model('User');
			if ($id = $this -> User -> insert($data)) {
				redirect('users/complete');
			} else {
				//$this -> session -> set_flashdata('error', array('type' => 'alert', 'message' => 'gg'));
				$this -> layout -> render('users/add', $this -> return_data);

			}
		}
	}

	public function edit($id = null) {
		$this -> load -> library('form_validation');
		$this -> set_message();

		if (!empty($_POST['password'])) {
			$this -> form_validation -> set_rules('password', _('Password'), 'trim|min_length[5]|max_length[40]|matches[password_confirm]');
			$this -> form_validation -> set_rules('password_confirm', _('Password Confirmation'), 'trim|min_length[5]|max_length[40]|matches[password]');
		}

		$this -> form_validation -> set_rules('name', _('Name'), 'required|trim|min_length[2]|max_length[10]');
		$this -> form_validation -> set_rules('phone', _('Phone'), 'required|trim|min_length[5]');
		$this -> form_validation -> set_rules('birthday', _('Birthday'), 'required|trim|check_date', array('check_date' => _('%s Must Validate Date')));
		$this -> form_validation -> set_rules('sex', _('Sex'), 'required|trim');

		$this -> form_validation -> set_rules('height', _('Height'), 'numeric|greater_than[20]|less_than[300]');
		$this -> form_validation -> set_rules('weight', _('Weight'), 'numeric|greater_than[20]|less_than[300]');

		$this -> load -> model('User');
		$this -> return_data['common_data']['title'] = _('Edit User');
		$this -> return_data['data']['content'] = $this -> User -> get_content($this -> session -> userdata('user_id'));

		if ($this -> form_validation -> run() == FALSE) {
			$this -> layout -> add_css(base_url() . 'css/bootstrap-datepicker3.min.css');
			$this -> layout -> add_js(base_url() . 'js/validate.min.js');
			$this -> layout -> add_js(base_url() . 'js/bootstrap-datepicker.min.js');
			$this -> layout -> add_js(base_url() . 'js/users/edit.js');
			$this -> layout -> render('users/edit', $this -> return_data);
		} else {
			$data = $this -> input -> post(NULL, TRUE);

			$this -> load -> model('User');
			if ($id = $this -> User -> update($data)) {
				$this -> session -> set_flashdata('message', array('type' => 'success', 'message' => _('Successfully Updated User')));
				redirect('users/edit');
			} else {
				//$this -> session -> set_flashdata('error', array('type' => 'alert', 'message' => 'gg'));
				$this -> layout -> render('users/edit', $this -> return_data);

			}
		}
	}

	public function see_agree_service() {
		$this -> load -> view('users/see_agree_service');
	}
	
	public function see_agree_information() {
		$this -> load -> view('users/see_agree_information');
	}

	public function complete() {
		$this -> layout -> render('users/complete', $this -> return_data);
	}

	public function login() {
		$this -> load -> library('form_validation');
		$this -> set_message();

		$this -> form_validation -> set_rules('email', _('Email'), 'required|valid_email|max_length[40]');
		$this -> form_validation -> set_rules('password', _('Password'), 'required|min_length[5]|max_length[40]');

		if ($this -> form_validation -> run() == TRUE) {
			$this -> load -> model('User');

			$crypt = false;
			if ($this -> input -> post('crypt'))
				$crypt = true;

			if ($user = $this -> User -> login($this -> input -> post('email'), $this -> input -> post('password'), $crypt)) {
				$this -> session -> set_userdata(array('user_id' => $user['id'], 'name' => $user['name']));

				$this -> load -> model('UserLoginLog');
				$this -> UserLoginLog -> insert();

				if ($this -> input -> post('json')) {
					echo json_encode(array('result' => 'success'));
					return TRUE;
				} else {
					redirect(base_url());
				}
			} else {
				if ($this -> input -> post('json')) {
					echo json_encode(array('result' => 'error', 'message' => _('Not Match ID OR Password')));
					return TRUE;
				} else {
					$this -> session -> set_flashdata('message', array('type' => 'danger', 'message' => _('Not Match ID OR Password')));
					redirect(base_url() . 'login');
				}
			}
		} else {
			if ($this -> input -> post('json')) {
				$message = $this -> form_validation -> error_array();
				echo json_encode(array('result' => 'error', 'message' => $message));
				return TRUE;
			}
		}

		$this -> return_data['common_data']['title'] = _('Login');
		$this -> layout -> add_js(base_url() . 'js/jquery-2.1.1.min.js');
		$this -> layout -> add_js(base_url() . 'js/sha1.js');
		$this -> layout -> add_js(base_url() . 'js/validate.min.js');
		$this -> layout -> add_js(base_url() . 'js/users/login.js');
		
		$this -> layout -> render('users/login', $this -> return_data);
	}

	public function logout() {
		$this -> session -> sess_destroy();
		redirect(base_url());
	}

	public function check_email() {
		$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

		if (empty($email))
			return false;

		$this -> load -> model('User');

		if ($this -> User -> check_exists_email($email)) {
			if ($this -> input -> get('json')) {
				echo json_encode(array('result' => 'success', 'exists' => true, 'message' => _('Already Exists Email')));
				return TRUE;
			} else {
				$this -> session -> set_flashdata('message', array('type' => 'warning', 'message' => '아이디 또는 비밀번호가 맞지 않습니다.'));
				redirect(base_url());
			}
		} else {
			if ($this -> input -> get('json')) {
				echo json_encode(array('result' => 'success', 'exists' => false));
				return TRUE;
			} else {
				redirect(base_url());
			}

		}
	}

	private function _photo_upload() {
		$config['upload_path'] = './uploads/users/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = true;
		//$config['max_size'] = '100';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';

		$this -> load -> library('upload', $config);
		$this -> upload -> initialize($config);

		if ($this -> upload -> do_upload()) {
			$data = $this -> upload -> data();

			$config['image_library'] = 'gd2';
			$config['source_image'] = $data['full_path'];
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 100;
			$config['height'] = 100;
			$config['thumb_marker'] = '';
			$config['new_image'] = 'thumb_' . $data['file_name'];

			$this -> load -> library('image_lib', $config);
			$this -> image_lib -> resize();
			$this -> session -> set_flashdata('message', array('type' => 'success', 'message' => '회원가입 되었습니다.'));

			return $data;
		} else {
			return false;
		}
	}

}
