<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class Order extends SL_Model
{
    protected $table = 'orders';
    protected $table_content = 'order_contents';
    protected $table_content_required = false;
    protected $table_id_name = 'order_id';
    protected $accepted_attributes = array('user_id', 'shipping_id', 'payment_id', 'total_price', 're_order', 'stopped', 'enable', 'created_at', 'updated_at');

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('o.*');
        $this->pdo->join('order_products as op', 'op.order_id=o.id');
        $this->pdo->join('products as p', 'op.product_id=p.id');
        $this->pdo->join('users as u', 'o.user_id=u.id', 'left');

        if (!empty($this->user_id)) {
            $this->pdo->where(array('o.user_id' => $this->user_id));
        }

        if (!empty($this->product_id)) {
            if (is_array($this->product_id)) {
                $this->pdo->where_in('op.product_id', $this->product_id);
            } else {
                $this->pdo->where(array('op.product_id' => $this->product_id));
            }
        }

        if (isset($this->stopped)) {
            if (empty($this->stopped)) {
                $this->pdo->where(array('o.stopped' => 0));
            } else {
                $this->pdo->where(array('o.stopped' => 1));
            }
        }

        if (!empty($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('o.client_id' => $this->client_id));
            }
        }

        if (empty($this->enable)) {
            $this->pdo->where(array('o.enable' => 1));
        } else {
            if ($this->enable == 'account') {
                $this->pdo->where('(o.enable=1 OR EXISTS(SELECT order_id FROM account_orders INNER JOIN accounts ON account_orders.account_id=accounts.id WHERE account_orders.order_id=o.id AND accounts.enable=1))');
            } else {
                if ($this->enable != 'all') {
                    $this->pdo->where(array('o.enable' => 0));
                }
            }
        }

        $this->pdo->where(array('o.branch_id' => $this->session->userdata('branch_id')));
        $this->pdo->order_by($order, $desc);
        $this->pdo->group_by('o.id');
        $query = $this->pdo->get($this->table.' as o', $per_page, $page);

        return $query->result_array();
    }

    public function get_count($id = null)
    {
        $this->pdo->select('count(*) as count');
        $this->pdo->join('order_products as op', 'op.order_id=o.id');
        $this->pdo->join('products as p', 'op.product_id=p.id');

        if (isset($id)) {
            $this->pdo->where(array('o.id' => $id));

            return $this->pdo->count_all_results($this->table.' as o');
        }

        if (!empty($this->user_id)) {
            $this->pdo->where(array('o.user_id' => $this->user_id));
        }

        if (!empty($this->product_id)) {
            if (is_array($this->product_id)) {
                $this->pdo->where_in('op.product_id', $this->product_id);
            } else {
                $this->pdo->where(array('op.product_id' => $this->product_id));
            }
        }

        if (isset($this->stopped)) {
            if (empty($this->stopped)) {
                $this->pdo->where(array('o.stopped' => 0));
            } else {
                $this->pdo->where(array('o.stopped' => 1));
            }
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('o.client_id' => $this->client_id));
            }
        }

        if (empty($this->enable)) {
            $this->pdo->where(array('o.enable' => 1));
        } else {
            if ($this->enable == 'account') {
                $this->pdo->where('(o.enable=1 OR EXISTS(SELECT order_id FROM account_orders INNER JOIN accounts ON account_orders.account_id=accounts.id WHERE account_orders.order_id=o.id AND accounts.enable=1))');
            } else {
                if ($this->enable != 'all') {
                    $this->pdo->where(array('o.enable' => 0));
                }
            }
        }

        $this->pdo->where(array('o.branch_id' => $this->session->userdata('branch_id')));
        $this->pdo->group_by('o.id');

        return $this->pdo->count_all_results($this->table.' as o');
    }

    protected function get_content_data($id)
    {
        $this->pdo->select('o.*');
        $this->pdo->join('users as u', 'o.user_id=u.id');
        $this->pdo->join('order_products as op', 'op.order_id=o.id');
        $this->pdo->join('products as p', 'op.product_id=p.id');

        $this->pdo->join('user_access_cards as uac', 'uac.user_id=u.id', 'left');
        $this->pdo->join('enrolls as e', 'e.order_id=o.id', 'left');
        $this->pdo->join('rents as r', 'r.order_id=o.id', 'left');
        $this->pdo->join('rent_sws as sws', 'sws.order_id=o.id', 'left');

        $this->pdo->where(array('o.'.$this->p_id => $id));
        $query = $this->pdo->get($this->table.' as o');

        return $query->row_array();
    }

    // 주문 기록은 실제로는 지우지 않도록 한다.
    public function delete($id)
    {
        return $this->pdo->update($this->table, array('enable' => 0, 'updated_at' => $this->now), array('id' => $id));
    }
}
