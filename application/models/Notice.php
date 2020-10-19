<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'SL_Model.php';

class Notice extends SL_Model {
	protected $table = 'notices';
	protected $table_content ='notice_contents';
}
