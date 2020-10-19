<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'SL_Model.php';

class Question extends SL_Model {
	protected $table = 'questions';
	protected $table_content = 'question_contents';
	protected $accepted_attributes=array('title','name','encrypted_password','salt','secret','user_id','created_at','updated_at');
}
