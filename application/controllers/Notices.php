<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'SL.php';

class Notices extends SL_Controller {
	protected $model='Notice';
	protected $comment=false;
}
