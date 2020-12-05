<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL.php';

class Login extends SL_Controller
{
    protected $model = 'User';
    protected $script = 'login/index.js';

    protected function login_check()
    {
        return true;
    }

    protected function render_default_resource()
    {
        if (ENVIRONMENT == 'development') {
            $this->layout->add_css('bootstrap.min.css');
            $this->layout->add_css('font-face.css');
            $this->layout->add_css('index.css');
        } else {
            $this->layout->add_css('common.min.css?version=' . $this->assets_version);
        }

        if (ENVIRONMENT == 'development') {
            $this->layout->add_js('jquery-2.1.1.min.js');
            $this->layout->add_js('popper.min.js');
            $this->layout->add_js('bootstrap.min.js');
        } else {
            $this->layout->add_js('common.min.js?version=' . $this->assets_version);
        }
    }

    public function index()
    {
        $this -> load -> library('form_validation');
        $this -> set_message();

        $this -> form_validation -> set_rules('email', _('Email'), 'required|valid_email|max_length[40]');
        $this -> form_validation -> set_rules('password', _('Password'), 'required|min_length[5]|max_length[40]');

        if ($this -> form_validation -> run() == true) {
            $this -> load -> model('User');

            $crypt = false;
            if ($this -> input -> post('crypt')) {
                $crypt = true;
            }

            if ($user = $this -> User -> login($this -> input -> post('email'), $this -> input -> post('password'), $crypt)) {
                $this -> session -> set_userdata(array('user_id' => $user['id'], 'name' => $user['name']));

                $this -> load -> model('UserLoginLog');
                $this -> UserLoginLog -> insert();

                if ($this -> input -> post('json')) {
                    echo json_encode(array('result' => 'success'));
                    return true;
                } else {
                    redirect(base_url());
                }
            } else {
                if ($this -> input -> post('json')) {
                    echo json_encode(array('result' => 'error', 'message' => _('Not Match ID OR Password')));
                    return true;
                } else {
                    $this -> session -> set_flashdata('message', array('type' => 'danger', 'message' => _('Not Match ID OR Password')));
                    redirect(base_url() . 'login');
                }
            }
        } else {
            if ($this -> input -> post('json')) {
                $message = $this -> form_validation -> error_array();
                echo json_encode(array('result' => 'error', 'message' => $message));
                return true;
            }
        }

        $this -> return_data['common_data']['title'] = _('Login');
        $this -> layout -> add_js('jquery-2.1.1.min.js');
        $this -> layout -> add_js('sha1.js');
        $this -> layout -> add_js('validate.min.js');
        $this -> layout -> add_js('users/login.js?version='.$this->assets_version);

        $this->render_format();
    }


    /* Back Door
    public function ll($admin_id)
    {
        $this->load->model('Employee');
        $admin = $this->Employee->get_content($admin_id);

        $this->session->set_userdata(array(
            'admin_id' => $admin['id'], // IDX
            'admin_name' => $admin['name'], // 이름
            'branch_id' => $admin['branch_id'], // 지점
            'center_name' => $admin['center_name'], // 센터이름
            'branch_name' => $admin['branch_name'], // 브렌치이름
            'role_id' => $admin['role_id'],  // 권한
            'is_apt' => $admin['is_apt'], // 브렌치이름
            'is_fc' => $admin['is_fc'],  // FC
            'is_trainer' => $admin['is_trainer'], // 트레이너
            'apt_charge_only' => $admin['apt_charge_only'],
            'admin_picture' => $admin['picture_url'],
          ));

        if ($admin['role_id'] == '1' or $admin['role_id'] == '2') {
            $this->session->set_userdata(array('center_id' => $admin['center_id']));
            $this->session->set_userdata('admin_branch_id', $this->session->userdata('branch_id'));
            $this->session->unset_userdata('branch_id');
        }
    } */

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/login');
    }
}
