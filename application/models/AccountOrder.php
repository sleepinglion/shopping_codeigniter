<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class AccountOrder extends SL_Model
{
    protected $table = 'account_orders';
    protected $accepted_attributes = array('account_id', 'order_id');

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('ao.*,a.account_category_id,a.branch_id,a.client_id,a.user_id,a.type,a.transaction_date,a.cash,a.apt_charge,a.credit,a.point,a.outstanding,a.enable,a.created_at,a.updated_at');
        $this->pdo->join('accounts as a', 'ao.account_id=a.id');

        if (!empty($this->account_id)) {
            $this->pdo->where(array('ao.account_id' => $this->account_id));
        }

        if (!empty($this->order_id)) {
            $this->pdo->where(array('ao.order_id' => $this->order_id));
        }

        if (isset($this->no_commission)) {
            $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION));
        }

        if (isset($this->no_branch_transfer)) {
            $this->pdo->where_not_in('a.account_category_id', array(BRANCH_TRANSFER));
        }

        $query = $this->pdo->get($this->table.' as ao', $per_page, $page);

        return $query->result_array();
    }

    public function get_count($id = null)
    {
        $this->pdo->join('accounts as a', 'ao.account_id=a.id');

        if (isset($id)) {
            $this->pdo->where(array('ao.id' => $id));

            return $this->pdo->count_all_results($this->table.' as ao');
        }

        if (!empty($this->account_id)) {
            $this->pdo->where(array('ao.account_id' => $this->account_id));
        }

        if (!empty($this->order_id)) {
            $this->pdo->where(array('ao.order_id' => $this->order_id));
        }

        if (isset($this->no_commission)) {
            $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION));
        }

        if (isset($this->no_branch_transfer)) {
            $this->pdo->where_not_in('a.account_category_id', array(BRANCH_TRANSFER));
        }

        return $this->pdo->count_all_results($this->table.' as ao');
    }

    protected function get_content_data($id)
    {
        $this->pdo->select('ao.*,a.account_category_id,a.branch_id,a.client_id,a.user_id,a.type,a.transaction_date,a.cash,a.apt_charge,a.credit,a.point,a.outstanding,a.enable,a.created_at,a.updated_at');
        $this->pdo->join('accounts as a', 'ao.account_id=a.id');
        $this->pdo->where(array('ao.id' => $id));
        $query = $this->pdo->get($this->table.' as ao');

        return $query->row_array();
    }
}
