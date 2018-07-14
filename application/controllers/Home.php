<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'SL.php';

class Home extends SL_Controller
{
    protected $model = 'Home';
    protected $ad = false;
    protected $script='index.js';

    public function index($page = 0)
    {
        $this->render_format();
    }

    public function get_json_error_message()
    {
        echo json_encode(array('result' => 'success', 'message' => $this -> get_error_messages()));
    }
}
