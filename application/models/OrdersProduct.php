<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class OrderProduct extends SL_Model
{
    protected $table = 'order_products';
    protected $accepted_attributes=array('order_id','product_id','total_price','quantity');

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('op.*,p.title,p.title as product_name,p.price,o.user_id,o.transaction_date');
        $this->pdo->join('orders as o', 'op.order_id = o.id');
        $this->pdo->join('products as p', 'op.product_id = p.id');

        if (!empty($this->product_id)) {
            $this->pdo->where(array('p.id' => $this->product_id));
        }

        if (!empty($this->order_id)) {
            $this->pdo->where(array('o.id' => $this->order_id));
        }

        $this->pdo->where(array('o.enable' => 1,'p.enable' => 1));
        $this->pdo->order_by($order, $desc);
        $query = $this->pdo->get($this->table.' as op');

        return $query->result_array();
    }

    public function get_count($id = null)
    {
        $this->pdo->join('orders as o', 'op.order_id = o.id');
        $this->pdo->join('products as p', 'op.product_id = p.id');

        if (isset($id)) {
            $this->pdo->where(array('os.id' => $id));

            return $this->pdo->count_all_results($this->table.' as os');
        }

        if (!empty($this->product_id)) {
            $this->pdo->where(array('p.id' => $this->product_id));
        }

        if (!empty($this->order_id)) {
            $this->pdo->where(array('o.id' => $this->order_id));
        }

        $this->pdo->where(array('o.enable' => 1,'p.enable' => 1));

        return $this->pdo->count_all_results($this->table.' as op');
    }
}
