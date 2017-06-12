<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_home extends Admin_Controller
{
    public function index()
    {
        $this->load->view('admin/admin_home/index');
    }
}
