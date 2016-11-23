<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Questions extends SL_Controller {
	protected $model='Question';
	protected $comment_model='Question_comment';
}
