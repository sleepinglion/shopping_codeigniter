<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'SL.php';

class Products extends SL_Controller
{
    protected $model = 'Product';

    public function index($page = 0)
    {
        $this -> load -> model('Product');
        $products = $this -> Product -> get_index();

        $this -> return_data['data'] = $products;
        $this->render_format();
    }

    public function add()
    {
        $this->render_format();
    }
}
