<?php

class SL_Model extends CI_Model {
	public $category_model = NULL;
	public $category_id_name= NULL;
	protected $table;
	protected $table_content;
	protected $table_user = 'users';
	protected $order='id';
	protected $desc=true;
	protected $accepted_attributes=array('title','user_id','created_at','updated_at');

	public function __construct() {
		if(isset($this->category_model)) {
			if(empty($this->category_id_name)) {
				$this->category_id_name=singular($this->table).'_category_id';
			}						
		}
		
		$this -> pdo = $this -> load -> database('pdo', TRUE);
	}
	
	public function get_menu($menu=nulls) {
		if(empty($menu))
			$menu=$this ->table;
		
		$this -> pdo -> from($this -> table);
		$this -> pdo -> where(array($this -> table . '.controller' => $menu));
		$query = $this -> pdo -> get();
		$result = $query -> result_array();
		return $result[0];
	}

	public function get_count($id = NULL) {
		if (isset($id)) {
			$this -> pdo -> where(array($this -> table . '.id' => $id));
		}

		return $this -> pdo -> count_all_results($this -> table);
	}

	public function __get($name) {
		if ($name == 'table')
			return $this -> $name;

		return parent::__get($name);
	}

	public function get_index($per_page = 0, $page = 0, $category_id = NULL, $order = NULL, $desc = NULL, $enable = TRUE) {
		if (isset($category_id)) {
			$this -> pdo -> where(array($this -> table . '.' . singular($this -> table) . '_category_id' => $category_id));
			
			if($this -> input -> get('search_type'))
				$result=$this->get_search($this -> input -> get('search_type'),$this -> input -> get('search_word'));
			
			$result['total'] = $this -> pdo -> count_all_results($this -> table);
		} else {			
			if($this -> input -> get('search_type'))
				$result=$this->get_search($this -> input -> get('search_type'),$this -> input -> get('search_word'));
			
			$result['total'] = $this -> pdo -> count_all_results($this -> table);
		}

		if (!$result['total'])
			return $result;		

		if (empty($order)) {
			if (empty($this -> order)) {
				$order = $this -> order;
			} else {
				$order = 'id';
			}
		}

		if (empty($desc)) {
			if (empty($this -> desc)) {
				$desc = $this -> desc;
			} else {
				$desc = TRUE;
			}
		}
		
		if(!is_bool($desc))
			throw new Exception("Error Processing Request", 1);
		
		if($desc) {
			$desc='desc';
		}	else {
			$desc='asc';
		}

		$this -> pdo -> select($this -> table . '.*,' . $this -> table_user . '.name');
		$this -> pdo -> join($this -> table_user, $this -> table . '.user_id = ' . $this -> table_user . '.id', 'left');
		if (isset($category_id))
			$this -> pdo -> where(array($this -> table . '.' . singular($this -> table) . '_category_id' => $category_id));
		
		// if(empty($this->session->))
		$this -> pdo -> where(array($this -> table . '.enable' => TRUE));
		
		if($this -> input -> get('search_type'))
			$this->get_search($this -> input -> get('search_type'),$this -> input -> get('search_word'));
		
		$this -> pdo -> order_by($order, $desc);
		$query = $this -> pdo -> get($this -> table, $per_page, $page);
		$result['list'] = $query -> result_array();
		return $result;
	}

	protected function get_search($search_type,$search_word=null) {
		$search_type_title=array('title'=>_('Title'),'content'=>_('Content'),'titlencontent'=>_('Title+Content'));
		
		if(array_key_exists($search_type,$search_type_title)) {
			$result['search_type_title'] = $search_type_title[$search_type];
		} else {
			$result['search_type_title'] = _('Title');
			return $result;
		}
		
		if (empty($search_word)) {
			return $result;
		}
		
		switch($search_type) {
			case 'title' :
				$this -> pdo -> like($this -> table . '.title', $search_word);
				break;
			case 'content' :
				$this -> pdo -> join($this -> table_content, $this->table.'.id='.$this->table_content.'.id');
				$this -> pdo -> like($this -> table_content . '.content',$search_word);
				break;
			case 'titlencontent' :
				$this -> pdo -> join($this -> table_content, $this->table.'.id='.$this->table_content.'.id');
				$this -> pdo -> like($this -> table . '.title', $search_word);
				$this -> pdo -> or_like($this -> content_table.'.content', $search_word);
				$query_where = 'WHERE (b.title LIKE CONCAT("%",:title,"%") OR bc.content LIKE CONCAT("%",:content,"%"))';
				break;
			/*	case 'nickname' :
			 if($this -> input -> get('search_word')) {
			 $this -> pdo -> join('users', 'poll_communities.user_id=users.id');
			 $this -> pdo -> like('users.nickname',$this -> input -> get('search_word'));
			 }
			 $result['search_type_title'] = _('label_nickname');
			 break; */
		}
		return $result;
	}

	public function get_content($id) {
		if($this->table_content) {
			$this -> pdo -> select($this -> table . '.*,' . $this -> table_content . '.content,' . $this -> table_user . '.nickname');
		} else {
			$this -> pdo -> select($this -> table . '.*,' . $this -> table_user . '.nickname');
		}
		$this -> pdo -> from($this -> table);
		
		if($this->table_content)
			$this -> pdo -> join($this -> table_content, $this -> table . '.id = ' . $this -> table_content . '.id');
		
		$this -> pdo -> join($this -> table_user, $this -> table . '.user_id = ' . $this -> table_user . '.id','left');
		$this -> pdo -> where(array($this -> table . '.id' => $id));
		$query = $this -> pdo -> get();
		$result = $query -> result_array();
		return $result[0];
	}

	public function insert(Array $data) {
		$data['user_id'] = $this -> session -> userdata('user_id');
		$date=date('Y-m-d H:i:s');
		$data['created_at'] = $date;
		$data['updated_at'] = $date;
		
		foreach($data as $key=>$value) {
			if(in_array($key,$this->accepted_attributes))
				$filtered_data[$key]=$value;
		}
		
		if ($this -> pdo -> insert($this -> table,$filtered_data)) {
			$id = $this -> pdo -> insert_id();
			if (!empty($this -> table_content))
				$this -> pdo -> insert($this -> table_content, array('id' => $id, 'content' => $_POST['content']));
			
			return $id;
		} else {
			return false;
		}
	}

	public function update(Array $data) {
		if(!$this->session->userdata('admin')) {
			unset($data['user_id']);
		}
		
		$date=date('Y-m-d H:i:s');
		$data['updated_at'] = $date;
		
		foreach($data as $key=>$value) {
			if(in_array($key,$this->accepted_attributes))
				$filtered_data[$key]=$value;
		}
		
		if ($this -> pdo -> update($this -> table, $filtered_data, array('id' => $data['id']))) {
			if(isset($this->table_content) and isset($data['content']))
				$this -> pdo -> update($this -> table_content, array('content' => $data['content']), array('id' => $data['id']));
			return true;
		} else {
			return false;
		}
	}

	public function delete($id) {
		if ($this -> pdo -> delete($this -> table, array('id' => $id))) {
			return true;
		} else {
			return false;
		}
	}

	public function update_fie_count($id, $count) {
		return $this -> pdo -> update($this -> table, array('file_count' => $count), array('id' => $id));
	}

	public function update_count_plus($id) {
		$this -> pdo -> set('count', 'count+1', FALSE);
		$this -> pdo -> where('id', $id);
		$this -> pdo -> update($this -> table);
	}

	public function update_count_minus($id) {
		$this -> pdo -> set('count', 'count-1', FALSE);
		$this -> pdo -> where('id', $id);
		$this -> pdo -> update($this -> table);
	}

}
