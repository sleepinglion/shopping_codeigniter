<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    protected $CI;

    public function __construct() {
        parent::__construct();
            // reference to the CodeIgniter super object
        $this->CI =& get_instance();
    }

    public function check_date($date) {    
    	$birthday=explode('-', $date);
		
		if(count($birthday)<3)
			return false;
		
		return checkdate($birthday[1],$birthday[2], $birthday[0]);
    }
}