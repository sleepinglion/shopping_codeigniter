<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'SL_Model.php';

class Faq extends SL_Model {
	protected $table = 'faqs';
	protected $table_content ='faq_contents';
}
