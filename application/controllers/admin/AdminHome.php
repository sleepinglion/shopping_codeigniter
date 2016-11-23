<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class AdminHome extends Admin_Controller
{
 
  function __construct()
  {
    parent::__construct();
  }
 
  public function index()
  {
    $this->load->view('admin/dashboard_view');
  }
}