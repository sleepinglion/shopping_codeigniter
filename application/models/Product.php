<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL.php';

class Product extends SL_Model
{
    public function get_index($per_page = 0, $page = 0, $category_id = null, $order = null, $desc = null, $enable = true)
    {
        $this -> pdo -> where(array('products.enable' => true));
        $query = $this -> pdo -> get('products');
        $result['list'] = $query -> result_array();

        if (count($result['list'])) {
            foreach ($result['list'] as $index => $value) {
                $this -> pdo -> where(array('product_pictures.enable' => true, 'product_pictures.product_id' => $value['id']));
                $query = $this -> pdo -> get('product_pictures');
                $result['list'][$index]['photo_list'] = $query -> result_array();
            }
        }

        return $result;
    }

    public function get_count($id = null)
    {
        if (isset($id)) {
            $this -> pdo -> where(array('products.id' => $id));
        }

        return $this -> pdo -> count_all_results('products');
    }

    public function get_content($id)
    {
        $this -> pdo -> from('products');
        $this -> pdo -> where(array('products.id' => $id));
        $query = $this -> pdo -> get();
        $result = $query -> result_array();

        $this -> pdo -> where(array('product_pictures.enable' => true, 'product_pictures.product_id' => $id));
        $query = $this -> pdo -> get('product_pictures');
        $result[0]['photo_list'] = $query -> result_array();

        return $result[0];
    }
}
