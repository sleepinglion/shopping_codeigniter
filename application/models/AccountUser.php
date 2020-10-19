<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class AccountUser extends SL_Model
{
    protected $table = 'accounts';

    public function get_count($id = null)
    {
        $this->pdo->select('count(*)');
        $this->pdo->join('users as u', 'a.user_id=u.id');
        //$this->pdo->join('account_orders  as ao', 'ao.account_id=o.id', 'left');
        //$this->pdo->join('orders  as o', 'ao.order_id=o.id', 'left');

        if (isset($this->dongho)) {
            $this->pdo->where(array('u.address_detail' => $this->dongho));
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id')));
        $this->pdo->where('(a.transaction_date >= "'.$this->start_date.'" AND a.transaction_date <= "'.$this->end_date.'")  AND a.enable=1');
        $this->pdo->group_by('u.address_detail');

        return $this->pdo->count_all_results($this->table.' as a');
    }

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('
          u.address_detail as dongho,
          SUM(if(STRCMP(a.type,"I")>0,-(a.apt_charge+a.cash+a.credit),(a.apt_charge+a.cash+a.credit))) as total_account_per_unit,
          SUM(if(STRCMP(a.type,"I")>0,-(a.apt_charge),(a.apt_charge))) as total_apt_charge_per_unit
      ');

        $this->pdo->join('users as u', 'a.user_id=u.id');
        //$this->pdo->join('account_orders  as ao', 'ao.account_id=o.id', 'left');
        //$this->pdo->join('orders  as o', 'ao.order_id=o.id', 'left');

        if (isset($this->dongho)) {
            $this->pdo->where(array('u.address_detail' => $this->dongho));
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id')));
        $this->pdo->where('(a.transaction_date >= "'.$this->start_date.'" AND a.transaction_date <= "'.$this->end_date.'")  AND a.enable=1');

        $this->pdo->order_by('u.address_detail', false);
        $this->pdo->group_by('u.address_detail');

        $query = $this->pdo->get($this->table.' as a', $per_page, $page);

        return $query->result_array();
    }
}
