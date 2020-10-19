<?php

class SL_Model extends CI_Model
{
    public $category_id_name = null;
    protected $category_model;
    protected $category_id;
    protected $table;
    protected $table_content = false;
    protected $table_content_required = true;
    protected $table_id_name = false;
    protected $p_id = 'id'; // primary key
    protected $order = 'id';
    protected $desc = true;
    protected $accepted_attributes = array('title', 'branch_id', 'created_at', 'updated_at');
    protected $timezone;
    protected $today;
    protected $now;

    public function __construct()
    {
        if (isset($this->category_model)) {
            if (empty($this->category_id_name)) {
                $this->category_id_name = singular($this->table).'_category_id';
            }
        }

        $this->pdo = $this->load->database('pdo', true);
        $this->timezone = new DateTimeZone($this->config->item('time_reference'));

        $date_time_obj = new DateTime('now', $this->timezone);
        $this->now = $date_time_obj->format('Y-m-d H:i:s');
        $this->today = $date_time_obj->format('Y-m-d');
    }

    public function __set($key, $value)
    {
        if ($key == 'category_id') {
            if (!empty($value)) {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    throw new Exception('카테고리 ID는 숫자만 사용가능합니다.', 1);
                }
            }
        }

        if ($key == 'branch_id') {
            if (!empty($value)) {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    throw new Exception('지점 ID는 숫자만 사용가능합니다.', 1);
                }
            }
        }

        if ($key == 'user_id') {
            if (is_array($value)) {
                foreach ($value as $user_id) {
                    if (!empty($value)) {
                        if (!filter_var($user_id, FILTER_VALIDATE_INT)) {
                            throw new Exception('회원 ID는 숫자만 사용가능합니다.', 1);
                        }
                    }
                }
            } else {
                if (empty($value)) {
                    //  throw new Exception('회원 ID가 입력되지 않았습니다.', 1);
                } else {
                    if (!filter_var($value, FILTER_VALIDATE_INT)) {
                        throw new Exception('회원 ID는 숫자만 사용가능합니다.', 1);
                    }
                }
            }
        }

        $this->$key = $value;
    }

    public function get_count($id = null)
    {
        if (isset($id)) {
            $this->pdo->where(array($this->table.'.'.$this->p_id => $id));
        }

        return $this->pdo->count_all_results($this->table);
    }

    public function __get($name)
    {
        if ($name == 'table') {
            return $this->$name;
        }

        return parent::__get($name);
    }

    public function get_index($per_page = 0, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $result = array();
        $result['total'] = $this->get_count();

        if (empty($result['total'])) {
            return $result;
        }

        if (empty($order)) {
            if (empty($this->order)) {
                $order = $this->p_id;
            } else {
                $order = $this->order;
            }
        }

        if (isset($desc)) {
            if (empty($desc)) {
                $desc = false;
            } else {
                $desc = true;
            }
        } else {
            $desc = $this->desc;
        }

        if (!is_bool($desc)) {
            throw new Exception('Error Processing Request', 1);
        }

        if ($desc) {
            $desc = 'desc';
        } else {
            $desc = 'asc';
        }

        $result['list'] = $this->get_index_data($per_page, $page, $order, $desc, $enable);

        return $result;
    }

    protected function get_index_data($per_page = 1000, $page = 0, $order = null, $desc = null, $enable = true)
    {
        $this->pdo->select($this->table.'.*');
        $this->pdo->order_by($order, $desc);
        $query = $this->pdo->get($this->table, $per_page, $page);

        return $query->result_array();
    }

    final public function get_content($id)
    {
        $result = $this->get_content_data($id);

        if (!is_array($result)) {
            return false;
        }

        if (!count($result)) {
            return false;
        }

        return $result;
    }

    protected function get_content_data($id)
    {
        if ($this->table_content) {
            if ($this->table_content_required) {
                $this->pdo->select($this->table.'.*,'.$this->table_content.'.content');
                $this->pdo->join($this->table_content, $this->table.'.id='.$this->table_content.'.id');
            } else {
                $this->pdo->select($this->table.'.*,'.$this->table_content.'.content');
                $this->pdo->join($this->table_content, $this->table.'.id='.$this->table_content.'.'.$this->table_id_name, 'left');
            }
        } else {
            $this->pdo->select($this->table.'.*');
        }
        $this->pdo->where(array($this->table.'.'.$this->p_id => $id));
        $query = $this->pdo->get($this->table);

        return $query->row_array();
    }

    protected function get_count_no_required_content($id)
    {
        $this->pdo->where(array($this->table_content.'.'.$this->table_id_name => $id));

        return $this->pdo->count_all_results($this->table_content);
    }

    public function insert(array $data)
    {
        $data = array_merge($this->get_default_data($data), $data);
        $filtered_data = array();

        foreach ($data as $key => $value) {
            if (in_array($key, $this->accepted_attributes)) {
                $filtered_data[$key] = $value;
            }
        }

        if ($this->pdo->insert($this->table, $filtered_data)) {
            $id = $this->pdo->insert_id();

            if (!empty($this->table_content)) {
                if (empty($data['content'])) {
                    $data['content'] = $this->input->post('content');
                }

                if ($this->table_content_required) {
                    $this->pdo->insert($this->table_content, array('id' => $id, 'content' => $data['content']));
                } else {
                    if (!empty(trim($data['content']))) {
                        $this->pdo->insert($this->table_content, array($this->table_id_name => $id, 'content' => $data['content'], 'created_at' => $data['created_at'], 'updated_at' => $data['updated_at']));
                    }
                }
            }

            return $id;
        } else {
            return false;
        }
    }

    public function update(array $data)
    {
        $data['updated_at'] = $this->now;

        foreach ($data as $key => $value) {
            if (in_array($key, $this->accepted_attributes)) {
                $d_value = trim($value);

                if ($d_value == '') {
                    $filtered_data[$key] = null;
                }

                if ($value == '0000-00-00') {
                    $filtered_data[$key] = null;
                }

                $filtered_data[$key] = $value;
            }
        }

        if ($this->pdo->update($this->table, $filtered_data, array($this->p_id => $data[$this->p_id]))) {
            if (!empty($this->table_content) and !empty($data['content'])) {
                if ($this->table_content_required) {
                    $this->pdo->update($this->table_content, array('content' => $data['content']), array($this->p_id => $data[$this->p_id]));
                } else {
                    if (empty(trim($data['content']))) {
                        if ($this->get_count_no_required_content($data[$this->p_id])) {
                            $this->pdo->delete($this->table_content, array($this->table_id_name => $data[$this->p_id]));
                        }
                    } else {
                        if ($this->get_count_no_required_content($data[$this->p_id])) {
                            $this->pdo->update($this->table_content, array('content' => $data['content'], 'updated_at' => $data['updated_at']), array($this->table_id_name => $data['id']));
                        } else {
                            $this->pdo->insert($this->table_content, array($this->table_id_name => $data[$this->p_id], 'content' => $data['content'], 'created_at' => $data['updated_at'], 'updated_at' => $data['updated_at']));
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        return $this->pdo->delete($this->table, array($this->table.'.'.$this->p_id => $id));
    }

    public function check_exists($id)
    {
        $this->pdo->where(array($this->table.'.'.$this->p_id => $id));

        return $this->pdo->count_all_results($this->table);
    }

    protected function get_default_data()
    {
        if (!$this->input->is_cli_request()) {
            $data['admin_id'] = $this->session->userdata('admin_id');
            $data['branch_id'] = $this->session->userdata('branch_id');
        }

        $data['created_at'] = $this->now;
        $data['updated_at'] = $this->now;

        return $data;
    }

    public function update_fie_count($id, $count)
    {
        return $this->pdo->update($this->table, array('file_count' => $count), array('id' => $id));
    }

    public function update_count_plus($id)
    {
        $this->pdo->set('count', 'count+1', false);
        $this->pdo->where('id', $id);
        $this->pdo->update($this->table);
    }

    public function update_count_minus($id)
    {
        $this->pdo->set('count', 'count-1', false);
        $this->pdo->where('id', $id);
        $this->pdo->update($this->table);
    }
}
