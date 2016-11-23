<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

//인자로 들어오는 값을 2번 쓰는 잉여함수
function bar_color($index) {
	$dd = $index % 4;

	switch($dd) {
		case 0 :
			return 'success';
			break;
		case 1 :
			return 'info';
			break;
		case 2 :
			return 'warning';
			break;
		case 3 :
			return 'danger';
			break;
	}
}

function sl_get_thumb($url,$type='origin') {
	switch($type) {
		case 'large' :
			$prefix='large_thumb';
			break;
		case 'medium' :
			$prefix='medium_thumb';
			break;
		case 'small' :
			$prefix='small_thumb';
			break;
		default : 
			break;
	}	
	
	if(isset($prefix)) {
		$pathinfo=pathinfo($url);
		$url=$pathinfo['dirname'].'/'.$prefix.'_'.$pathinfo['basename'];
	}

	return $url;
} 

function product_price($price) {
	if($price) {
		return number_format($price);
	} else {
		return _('Free');
	} 
}

function sl_active_class($className, $classonly = false) {
	$return = false;

	$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $_SERVER['REQUEST_URI_PATH']);

	if (is_array($className)) {
		if (in_array($segments[1], $className)) {
			$return = true;
		}
	} else {
		if (!strcmp($segments[1], $className)) {
			$return = true;
		}
	}

	if ($return) {
		if ($classonly) {
			return ' active';
		} else {
			return 'class="active"';
		}
	} else {
		return false;
	}
}
?>