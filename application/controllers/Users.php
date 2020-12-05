<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'SL.php';

class Users extends SL_Controller
{
    protected $model = 'User';

    public function index()
    {
        $this -> return_data['common_data']['title'] = _('My Page');
        $this -> layout -> render('users/index', $this -> return_data);
    }

    public function view($id)
    {
        $this -> layout -> render('users/view', $this -> return_data);
    }

    public function add()
    {
        $this -> load -> library('form_validation');
        $this -> set_message();

        // require user data
        $this -> form_validation -> set_rules('email', _('Email'), 'required|trim|valid_email');
        $this -> form_validation -> set_rules('password', _('Password'), 'required|trim|min_length[5]|max_length[40]|matches[password_confirm]');
        $this -> form_validation -> set_rules('password_confirm', _('Password Confirmation'), 'required|trim|min_length[5]|max_length[40]|matches[password]');
        $this -> form_validation -> set_rules('name', _('Name'), 'required|trim|min_length[2]|max_length[10]');
        $this -> form_validation -> set_rules('phone', _('Phone'), 'required|trim|min_length[5]');
        $this -> form_validation -> set_rules('birthday', _('Birthday'), 'required|trim');
        $this -> form_validation -> set_rules('sex', _('Sex'), 'required|trim');

        // user agreement
        $this -> form_validation -> set_rules('agree[service]', _('Service Agreement'), 'required', array('required' => _('Please Agree %s')));
        $this -> form_validation -> set_rules('agree[information]', _('Personal Information Agreement'), 'required', array('required' => _('Please Agree %s')));

        $this -> return_data['common_data']['title'] = _('User Add');

        if ($this -> form_validation -> run() == false) {
            $this -> layout -> add_js('users/add.js');
            $this -> layout -> render('users/add', $this -> return_data);
        } else {
            $data = $this -> input -> post(null, true);

            $this -> load -> model('User');
            if ($id = $this -> User -> insert($data)) {
                redirect('users/complete');
            } else {
                //$this -> session -> set_flashdata('error', array('type' => 'alert', 'message' => 'gg'));
                $this -> layout -> render('users/add', $this -> return_data);
            }
        }
    }

    public function edit($id = null)
    {
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

        $this -> load -> model('User');
        $this -> return_data['common_data']['title'] = _('Edit User');
        $this -> return_data['data']['content'] = $this -> User -> get_content($this -> session -> userdata('user_id'));

        if ($this -> form_validation -> run() == false) {
            $this -> layout -> render('users/edit', $this -> return_data);
        } else {
            $data = $this -> input -> post(null, true);

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

    public function see_agree_service()
    {
        $this -> load -> view('users/see_agree_service');
    }

    public function see_agree_information()
    {
        $this -> load -> view('users/see_agree_information');
    }

    public function complete()
    {
        $this -> layout -> render('users/complete', $this -> return_data);
    }

    public function check_email()
    {
        $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

        if (empty($email)) {
            return false;
        }

        $this -> load -> model('User');

        if ($this -> User -> check_exists_email($email)) {
            if ($this -> input -> get('json')) {
                echo json_encode(array('result' => 'success', 'exists' => true, 'message' => _('Already Exists Email')));
                return true;
            } else {
                $this -> session -> set_flashdata('message', array('type' => 'warning', 'message' => '아이디 또는 비밀번호가 맞지 않습니다.'));
                redirect(base_url());
            }
        } else {
            if ($this -> input -> get('json')) {
                echo json_encode(array('result' => 'success', 'exists' => false));
                return true;
            } else {
                redirect(base_url());
            }
        }
    }

    private function _photo_upload()
    {
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
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
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
