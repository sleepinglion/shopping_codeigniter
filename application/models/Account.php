<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'SL_Model.php';

class Account extends SL_Model
{
    protected $table = 'accounts';
    protected $accepted_attributes = array('account_category_id', 'branch_id', 'user_id', 'type', 'transaction_date', 'cash', 'apt_charge', 'point', 'outstanding', 'credit', 'enable', 'created_at', 'updated_at');

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select('a.transaction_date,ufc.fc_id,fc.name as fc_name,oo.title as other_title,p.title as product_title,
            (a.cash+a.credit+a.apt_charge) as total_account,
            o.price,
            o.original_price,            
            p.id as product_id,
            if(p.id,p.title,null) as product_name,
            if(pc.id,pc.title,null) as product_category,
            IF(r.id,"Rent",IF(e.id,"Enroll","Product")) as order_type,
            if(e.start_date,e.start_date,if(r.id,if(r.start_datetime,date(r.start_datetime),""),"")) as start_date,
            if(e.end_date,e.end_date,if(r.id,if(r.end_datetime,date(r.end_datetime),""),"")) as end_date,
            if(e.id,(select SUM(cash) FROM accounts INNER JOIN account_commissions ON account_commissions.account_id=accounts.id WHERE account_commissions.enroll_id=e.id),0) as commission,
            u.name,uac.card_no,a.*,e.quantity,e.use_quantity,ac.title as category_name,u.address_detail,
            r.no,r.start_datetime,r.end_datetime,r.type as rent_type
            ', false);

        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');
        $this->pdo->join('user_access_cards as uac', 'uac.user_id = u.id', 'left');
        $this->pdo->join('user_fcs AS ufc', 'ufc.user_id=u.id', 'left');
        $this->pdo->join('admins as fc', 'ufc.fc_id=fc.id', 'left');

        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id', 'left');
        $this->pdo->join('orders as o', 'ao.order_id=o.id', 'left');
        $this->pdo->join('order_products as op', 'op.order_id=o.id', 'left');
        $this->pdo->join('products as p', 'op.product_id=p.id', 'left');
        $this->pdo->join('product_categories as pc', 'p.product_category_id=pc.id', 'left');

        $this->pdo->join('enrolls as e', 'e.order_id=o.id', 'left');
        $this->pdo->join('rents as r', 'r.order_id=o.id', 'left');
        $this->pdo->join('others as oo', 'oo.order_id=o.id', 'left');

        /* if (isset($this->employee_id)) {
            $this -> pdo -> where(array('ae.trainer_id'=>$this->employee_id));
            //$this -> pdo -> where(array('cm.trainer_id'=>$this->employee_id));
        } */

        if (isset($this->date)) {
            $this->pdo->where(array('a.transaction_date' => $this->date));
        }

        if (isset($this->user_id)) {
            $this->pdo->where(array('a.user_id' => $this->user_id));
        }

        if (isset($this->order_id)) {
            $this->pdo->select('od.dc_rate,od.dc_price');
            $this->pdo->join('order_discounts as od', 'od.order_id=o.id', 'left');
            $this->pdo->where(array('o.id' => $this->order_id));
        }

        if (!isset($this->user_id) and !isset($this->order_id)) {
            $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id')));
        }

        if (isset($this->start_date)) {
            $this->pdo->where('a.transaction_date >=', $this->start_date);
        }

        if (isset($this->end_date)) {
            $this->pdo->where('a.transaction_date <=', $this->end_date);
        }

        if (isset($this->dongho)) {
            $this->pdo->where(array('u.address_detail' => $this->dongho));
        }

        if (isset($this->no_commission)) {
            $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION));
        }

        if (isset($this->no_branch_transfer)) {
            $this->pdo->where_not_in('a.account_category_id', array(BRANCH_TRANSFER));
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->order_by('a.id', 'desc');
        $this->pdo->group_by('a.id');

        $query = $this->pdo->get($this->table.' as a', $per_page, $page);

        return $query->result_array();
    }

    public function get_count($id = null)
    {
        $this->pdo->select('count(*) as count');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');

        /* if (isset($this->employee_id)) {
            $this -> pdo -> join('account_trainers as ae', 'ae.account_id=a.id');
            $this -> pdo -> where(array('ae.trainer_id'=>$this->employee_id));
        } */

        if (isset($id)) {
            $this->pdo->where(array('a.id' => $id));

            return $this->pdo->count_all_results($this->table.' as a');
        }

        if (isset($this->date)) {
            $this->pdo->where(array('a.transaction_date' => $this->date));
        }

        if (isset($this->user_id)) {
            $this->pdo->where(array('a.user_id' => $this->user_id));
        }

        if (isset($this->order_id)) {
            $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
            $this->pdo->where(array('ao.order_id' => $this->order_id));
        }

        if (!isset($this->user_id) and !isset($this->order_id)) {
            $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id')));
        }

        if (isset($this->start_date)) {
            $this->pdo->where('a.transaction_date >=', $this->start_date);
        }

        if (isset($this->end_date)) {
            $this->pdo->where('a.transaction_date <=', $this->end_date);
        }

        if (isset($this->dongho)) {
            $this->pdo->join('users as u', 'a.user_id=u.id');
            $this->pdo->where(array('u.address_detail' => $this->dongho));
        }

        if (isset($this->no_commission)) {
            $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION));
        }

        if (isset($this->no_branch_transfer)) {
            $this->pdo->where_not_in('a.account_category_id', array(BRANCH_TRANSFER));
        }

        if (isset($this->client_id)) {
            if ($this->client_id != 'all') {
                $this->pdo->where(array('a.client_id' => $this->client_id));
            }
        }

        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));

        if (isset($this->group)) {
            $this->pdo->join('users as u', 'a.user_id=u.id');
            $this->pdo->group_by(array('u.address_detail', 'u.id'));
        } else {
            $this->pdo->group_by('a.id');
        }

        return $this->pdo->count_all_results($this->table.' as a');
    }

    public function insert(array $data)
    {
        if (empty($data['user_id'])) {
            throw new Exception('user id not insert');
        }

        $cash = 0;
        $credit = 0;
        $apt_charge = 0;
        $outstanding = 0;
        $point = 0;

        if (!empty($data['cash'])) {
            $cash = $data['cash'];
        }

        if (!empty($data['credit'])) {
            $credit = $data['credit'];
        }

        if (!empty($data['apt_charge'])) {
            $apt_charge = $data['apt_charge'];
        }

        if (!empty($data['point'])) {
            $point = $data['point'];
        }

        if (!empty($data['outstanding'])) {
            $outstanding = $data['outstanding'];
        }

        if (empty($data['transaction_date'])) {
            $data['transaction_date'] = $this->today;
        }

        if (isset($data['type'])) {
            if ($data['type'] != 'O') {
                $data['type'] = 'I';
            }
        } else {
            $data['type'] = 'I';
        }

        $data['payment'] = $cash + $credit + $apt_charge + $point;
        $id = parent::insert($data);

        $this->pdo->insert('account_orders', array('account_id' => $id, 'order_id' => $data['order_id']));

        if (empty($data['product_id'])) {
            log_message('error', 'Some variable did not contain a value.'.basename($_SERVER['SCRIPT_FILENAME']));
        } else {
            $this->pdo->insert('account_products', array('account_id' => $id, 'product_id' => $data['product_id']));
        }

        if (!empty($data['group_id'])) {
            if (is_array($data['group_id'])) {
                foreach ($data['group_id'] as $group_id) {
                    $this->pdo->insert('account_groups', array('account_id' => $id, 'group_id' => $group_id));
                }
            } else {
                $this->pdo->insert('account_groups', array('account_id' => $id, 'group_id' => $data['group_id']));
            }
        }

        if ($data['account_category_id'] == ADD_COMMISSION) {
            $this->pdo->insert('account_commissions', array('account_id' => $id, 'course_id' => $data['course_id'], 'enroll_id' => $data['enroll_id'], 'employee_id' => $data['employee_id']));
        }

        return $id;
    }

    public function get_refund($per_page, $page)
    {
        $result = array();
        $this->pdo->where_in('a.account_category_id', array(3, 4, 7, 8, 20, 22, 24));
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.type' => 'O', 'a.enable' => 1));

        if (isset($this->start_date)) {
            $this->pdo->where('a.transaction_date >=', $this->start_date);
        }

        if (isset($this->end_date)) {
            $this->pdo->where('a.transaction_date <=', $this->end_date);
        }

        $result['total'] = $this->pdo->count_all_results($this->table.' as a');

        if (!$result['total']) {
            return $result;
        }

        $this->pdo->select('a.*,u.name as user_name,u.address_detail,ac.title as account_category_name');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id', 'left');
        $this->pdo->where_in('a.account_category_id', array(3, 4, 7, 8, 20, 22, 24));
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.type' => 'O', 'a.enable' => 1));

        if (isset($this->start_date)) {
            $this->pdo->where('a.transaction_date >=', $this->start_date);
        }

        if (isset($this->end_date)) {
            $this->pdo->where('a.transaction_date <=', $this->end_date);
        }

        $this->pdo->order_by('a.id', 'desc');
        $query = $this->pdo->get($this->table.' as a', $per_page, $page);
        $result['list'] = $query->result_array();

        return $result;
    }

    public function get_fc_total_sales($start_date, $end_date)
    {
        $this->pdo->join('users as u', 'a.user_id=u.id');
        $this->pdo->where(array('u.fc_id' => $this->session->userdata('admin_id'), 'a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$start_date.'" AND a.transaction_date<="'.$end_date.'"');
        $count = $this->pdo->count_all_results($this->table.' as a');

        if (!$count) {
            return 0;
        }

        $this->pdo->select('sum(a.cash)+sum(a.credit)+sum(a.apt_charge) as total');
        $this->pdo->join('users as u', 'a.user_id=u.id');
        $this->pdo->where(array('u.fc_id' => $this->session->userdata('admin_id'), 'a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->where('a.transaction_date>="'.$start_date.'" AND a.transaction_date<="'.$end_date.'"');
        $query = $this->pdo->get($this->table.' as a');
        $result = $query->result_array();

        return $result[0]['total'];
    }

    public function get_product_content($id = null, $per_page, $page)
    {
        $result = array();
        $this->pdo->select('count(*)');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');

        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'p.id' => $id, 'a.enable' => 1));

        $result['total'] = $this->pdo->count_all_results($this->table.' as a');

        if (!$result['total']) {
            return $result;
        }

        $this->pdo->select('a.*,u.name as user_name,u.address_detail,ac.title as account_category_name');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');
        $this->pdo->join('admins as fc', 'u.fc_id=fc.id', 'left');

        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id', 'left');
        $this->pdo->join('orders as o', 'ao.order_id=o.id', 'left');
        $this->pdo->join('order_products as op', 'op.order_id=o.id', 'left');
        $this->pdo->join('products as p', 'op.product_id=p.id', 'left');

        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'p.id' => $id, 'a.enable' => 1));
        $this->pdo->order_by('a.id desc');
        $query = $this->pdo->get($this->table.' as a', $per_page, $page);
        $result['list'] = $query->result_array();

        return $result;
    }

    protected function get_content_data($id)
    {
        $this->pdo->select('a.*,u.name as user_name,u.address_detail');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');
        $this->pdo->where(array('a.id' => $id));
        $query = $this->pdo->get($this->table.' as a');

        return $query->row_array();
    }

    public function get_product_content_other($type, $per_page, $page)
    {
        $result = array();
        $this->pdo->select('count(*)');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->join('orders as o', 'ao.order_id=o.id');
        $this->pdo->join('others as oo', 'oo.other_id=o.id');
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));

        $result['total'] = $this->pdo->count_all_results($this->table.' as a');

        if (!$result['total']) {
            return $result;
        }

        $this->pdo->select('a.*,u.name as user_name,ac.title as account_category_name,oo.title as product_name');
        $this->pdo->join('account_categories as ac', 'a.account_category_id=ac.id');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->join('orders as o', 'ao.order_id=o.id');
        $this->pdo->join('others as oo', 'oo.other_id=o.id');
        $this->pdo->join('users as u', 'a.user_id=u.id', 'left');
        $this->pdo->where(array('a.branch_id' => $this->session->userdata('branch_id'), 'a.enable' => 1));
        $this->pdo->order_by('a.id desc');
        $query = $this->pdo->get($this->table.' as a', $per_page, $page);
        $result['list'] = $query->result_array();

        return $result;
    }

    public function get_content_by_category_id($category_id, $p_id)
    {
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->join('orders as o', 'ao.order_id=o.id');
        $this->pdo->where(array('account_category_id' => $category_id, 'o.id' => $p_id));
        $count = $this->pdo->count_all_results($this->table.' as a');

        if (!$count) {
            return false;
        }

        $this->pdo->select('a.*,ao.order_id');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->join('orders as o', 'ao.order_id=o.id');
        $this->pdo->where(array('account_category_id' => $category_id, 'o.id' => $p_id));
        $query = $this->pdo->get($this->table.' as a');

        return $query->row_array();
    }

    public function get_list_by_order_id($order_id)
    {
        $result = array();
        $this->pdo->select('count(*) as count');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('ao.order_id' => $order_id));
        $result['total'] = $this->pdo->count_all_results($this->table.' as a');

        if (!$result['total']) {
            return $result;
        }

        $this->pdo->select('a.id,a.transaction_date,a.user_id,a.cash,a.credit,a.apt_charge,ao.order_id');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('ao.order_id' => $order_id));
        $this->pdo->order_by('a.id desc');
        $query = $this->pdo->get($this->table.' as a');
        $result['list'] = $query->result_array();

        return $result;
    }

    public function get_content_by_order_id($order_id)
    {
        $result = array();
        $this->pdo->select('count(*) as count');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('ao.order_id' => $order_id));
        $this->pdo->group_by('ao.order_id');
        $total = $this->pdo->count_all_results($this->table.' as a');

        if ($total) {
            $result['total'] = $total;
        } else {
            return false;
        }

        $this->pdo->select('a.id,a.transaction_date,a.user_id,SUM(if(STRCMP(a.type,"I")>0,-(a.cash),a.cash)) as cash,SUM(if(STRCMP(a.type,"I")>0,-(a.credit),a.credit)) as credit,SUM(if(STRCMP(a.type,"I")>0,-(a.apt_charge),a.apt_charge)) as apt_charge,ao.order_id');
        $this->pdo->join('account_orders as ao', 'ao.account_id=a.id');
        $this->pdo->where_not_in('a.account_category_id', array(ADD_COMMISSION, BRANCH_TRANSFER));
        $this->pdo->where(array('ao.order_id' => $order_id));
        $this->pdo->order_by('a.id desc');
        $this->pdo->group_by('ao.order_id');
        $query = $this->pdo->get($this->table.' as a');

        return $query->row_array();
    }

    // 회계 기록은 실제로는 지우지 않도록 한다.
    public function delete($id)
    {
        return $this->pdo->update($this->table, array('enable' => 0, 'updated_at' => $this->now), array('id' => $id));
    }
}
