<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'SL.php';

class Impression extends SL_Model {
	protected $table = 'impressions';
	protected $accepted_attributes=array('impressionable_type','controller_name','action_name','ip_address','request_hash','session_hash','referrer','impressionable_id','created_at','updated_at');
	
	
	public function get_count_impression($data) {
		$this -> pdo -> where(array($this -> table . '.impressionable_type' => $data['impressionable_type'],$this -> table . '.controller_name'=>$data['controller_name'],$this -> table . '.action_name'=>$data['action_name'],$this -> table . '.ip_address'=>$data['ip_address'],$this -> table . '.impressionable_id'=>$data['impressionable_id']));
		
		return $this -> pdo -> count_all_results($this -> table);
	}
}
