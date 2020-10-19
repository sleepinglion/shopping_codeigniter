<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class AccountProduct extends SL_Model
{
    protected $table = 'account_products';
    protected $accepted_attributes = array('account_id', 'product_id');

    public function get_count($id = null)
    {
        $this->pdo->select('count(distinct ap.id) as count,p.id as category_id');
        $this->pdo->join('accounts as a', 'ap.account_id=a.id');
        $this->pdo->join('products as p', 'ap.product_id=p.id', 'left');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$this->start_date.'" AND a.transaction_date<="'.$this->end_date.'"');

        $this->pdo->group_by('category_id');

        return $this->pdo->count_all_results($this->table.' as ap');
    }

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('
      p.price,
      p.id as category_id,
      if(p.id,p.title,null) as product_name,
      if(pc.id,pc.title,null) as product_category,
      SUM(if(a.type="I",a.cash,0)) as i_cash,
      SUM(if(a.type="I",a.credit,0)) as i_credit,
      SUM(if(a.type="I",a.apt_charge,0)) as i_apt_charge,
      SUM(if(a.type="O",a.cash,0)) AS o_cash,
      SUM(if(a.type="O",a.credit,0)) AS o_credit,
      SUM(if(a.type="O",a.apt_charge,0)) AS o_apt_charge,
      SUM(if(a.type="I",1,0)) as request_user,
      SUM(if(a.type="O",1,0)) as refund_user
      ', false);

        $this->pdo->join('accounts as a', 'ap.account_id=a.id');
        $this->pdo->join('products as p', 'ap.product_id=p.id', 'left');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$this->start_date.'" AND a.transaction_date<="'.$this->end_date.'"');

        $this->pdo->group_by('category_id');
        $this->pdo->order_by('category_id', 'desc');
        $query = $this->pdo->get($this->table.' as ap', $per_page, $page);

        return $query->result_array();
    }

    public function get_product_user_detail($id = null, $per_page = 10000, $page = 0)
    {
        $result = array();
        $result['list'] = $this->get_available_product_list();

        $total = count($result['list']);
        $result['total'] = $total;

        if (!$result['total']) {
            return $result;
        }

        foreach ($result['list'] as $index => $value) {
            if ($value['product_id'] == 'etc' or empty($value['product_id'])) {
                unset($result['list'][$index]);
                $result['total'] = $result['total'] - 1;
                continue;
            }

            if (!empty($id)) {
                if ($id == 'all' or $id == $value['product_id']) {
                    $result['list'][$index]['user_list']['total'] = $this->get_product_user_detail_count($value['product_id']);
                    $result['list'][$index]['user_list']['list'] = $this->get_product_user_detail_data($value['product_id'], $per_page, $page);
                    $result['view_total'] = $result['list'][$index]['user_list']['total'];
                }
            }
        }

        return $result;
    }

    private function get_available_product_list()
    {
        $this->pdo->select('if(c.id,"course",if(f.id,"facility",if(p.id,"product","etc"))) as product_type,p.id as product_id,p.title as product_name');

        $this->pdo->join('accounts as a', 'ap.account_id=a.id');
        $this->pdo->join('products as p', 'ap.product_id=p.id', 'left');
        $this->pdo->join('courses as c', 'c.product_id=p.id', 'left');
        $this->pdo->join('facilities as f', 'f.product_id=p.id', 'left');

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$this->start_date.'" AND a.transaction_date<="'.$this->end_date.'"');

        $this->pdo->group_by('product_id');
        $query = $this->pdo->get($this->table.' as ap');

        return $query->result_array();
    }

    private function get_product_user_detail_count($product_id)
    {
        $this->pdo->select('count(distinct a.id) as count,a.user_id');

        $this->pdo->join('accounts as a', 'ap.account_id=a.id');
        $this->pdo->join('products as p', 'ap.product_id=p.id', 'left');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');

        if (isset($this->type)) {
            if ($this->type == 'out') {
                $this->pdo->where(array('a.type' => 'O'));
            } else {
                if ($this->type == 'in') {
                    $this->pdo->where(array('a.type' => 'I'));
                }
            }
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'p.id' => $product_id, 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$this->start_date.'" AND a.transaction_date<="'.$this->end_date.'"');

        $this->pdo->order_by('u.address_detail', 'asc');
        $this->pdo->group_by('a.user_id');

        return $this->pdo->count_all_results($this->table.' as ap');
    }

    private function get_product_user_detail_data($product_id, $per_page = 10000, $page = 0)
    {
        $this->pdo->select('distinct a.id,a.user_id,a.transaction_date,p.title,group_concat(a.created_at ORDER BY a.id desc) as created_at,u.address_detail as dongho,u.name,count(u.id) as total_product,SUM(if(STRCMP(a.type,"I")>0,-(a.apt_charge),a.apt_charge)) as total_per_user', false);
        $this->pdo->join('accounts as a', 'ap.account_id=a.id');
        $this->pdo->join('products as p', 'ap.product_id=p.id', 'left');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');

        if (isset($this->type)) {
            if ($this->type == 'out') {
                $this->pdo->where(array('a.type' => 'O'));
            } else {
                if ($this->type == 'in') {
                    $this->pdo->where(array('a.type' => 'I'));
                }
            }
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'p.id' => $product_id, 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$this->start_date.'" AND a.transaction_date<="'.$this->end_date.'"');

        $this->pdo->order_by('u.address_detail', 'asc');
        $this->pdo->group_by('a.user_id');
        $query = $this->pdo->get($this->table.' as ap', $per_page, $page);

        return $query->result_array();
    }
}
