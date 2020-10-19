<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class Product extends SL_Model
{
    protected $table = 'products';
    protected $table_content = 'product_contents';
    protected $table_content_required = false;
    protected $table_id_name = 'product_id';
    protected $accepted_attributes = array('product_category_id', 'branch_id', 'title', 'price', 'gender', 'enroll_display', 'enable', 'created_at', 'updated_at');
    protected $enroll_display_only = false;
    protected $branch_id;

    public function __construct()
    {
        parent::__construct();
        $this->branch_id = $this->session->userdata('branch_id');
    }

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('p.*,p.id as product_id,pc.title as category_name,IF(psp.price,psp.price,p.price) as price,GROUP_CONCAT(CONCAT(pp.id,"::",pp.picture_url)) as picture_url');
        $this->pdo->join('product_pictures as pp', 'pp.product_id=p.id', 'left');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');
        $this->pdo->join('product_sub_prices as psp', 'psp.product_id=p.id', 'left');

        if (!empty($this->category_id)) {
            $this->pdo->where(array('p.product_category_id' => $this->category_id));
        }

        if (!empty($this->category_type)) {
            $this->pdo->where(array('pc.type' => $this->category_type));
        }

        if (empty($this->type)) {
            $this->pdo->where(array('p.enable' => true, 'pc.enable' => true));
        } else {
            if ($this->type == 'admin') {
                $this->pdo->where(array('p.enable' => true));
            } else {
                if (is_array($this->type)) {
                    $this->pdo->where_in('pc.type', $this->type);
                } else {
                    $this->pdo->where(array('pc.type' => $this->type));
                }
                $this->pdo->where(array('p.enable' => true, 'pc.enable' => true));
            }
        }

        if (isset($this->id)) {
            if (is_array($this->id)) {
                $this->pdo->where_in('p.id', $this->id);
            } else {
                $this->pdo->where(array('p.id' => $this->id));
            }
        }

        $this->pdo->group_by('p.id');
        $this->pdo->order_by($order, $desc);
        $query = $this->pdo->get($this->table.' as p', $per_page, $page);

        return $query->result_array();
    }

    public function get_count($id = null)
    {
        $this->pdo->select('count(*)');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');

        if (!empty($this->category_id)) {
            $this->pdo->where(array('p.product_category_id' => $this->category_id, 'p.enable' => true));
        }

        if (!empty($this->category_type)) {
            $this->pdo->where(array('pc.type' => $this->category_type));
        }

        if (isset($this->id)) {
            if (is_array($this->id)) {
                $this->pdo->where_in('p.id', $this->id);
            } else {
                $this->pdo->where(array('p.id' => $this->id));
            }
        }

        if (isset($id)) {
            if (is_array($id)) {
                $this->pdo->where_in('p.id', $id);
            } else {
                $this->pdo->where(array('p.id' => $id));
            }
        } else {
            if (empty($this->type)) {
                $this->pdo->where(array('p.enable' => true, 'pc.enable' => true));
            } else {
                if ($this->type == 'admin') {
                    $this->pdo->where(array('p.enable' => true));
                } else {
                    if (is_array($this->type)) {
                        $this->pdo->where_in('pc.type', $this->type);
                    } else {
                        $this->pdo->where(array('pc.type' => $this->type));
                    }
                    $this->pdo->where(array('p.enable' => true, 'pc.enable' => true));
                }
            }
        }

        $this->pdo->group_by('p.id');

        return $this->pdo->count_all_results($this->table.' as p');
    }

    protected function get_content_data($id)
    {
        $this->pdo->select('p.id,p.id as product_id,p.title,p.product_category_id,IF(psp.price,psp.price,p.price) as price,pc.title as category_name,GROUP_CONCAT(CONCAT(pp.id,"::",pp.picture_url)) as picture_url');
        $this->pdo->join('product_pictures as pp', 'pp.product_id=p.id', 'left');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');
        $this->pdo->join('product_sub_prices as psp', 'psp.product_id=p.id', 'left');
        $this->pdo->where(array('p.id' => $id, 'p.enable' => 1));
        $query = $this->pdo->get($this->table.' as p');

        return $query->row_array();
    }

    // 상품은 실제로는 지우지 않도록 한다.
    public function delete($id)
    {
        $content = $this->get_content_data($id);

        return $this->pdo->update('products', array('enable' => 0, 'updated_at' => $this->now), array('id' => $content['id']));
    }
}
