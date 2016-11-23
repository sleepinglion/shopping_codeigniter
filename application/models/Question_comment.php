<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'SLComment.php';

class Question_comment extends SLComment_Model{
	protected $table = 'question_comments';
	protected $parent_table_id='question_id';
}
