<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('strformat'))
{
	function strformat($str, $replace = NULL) 
	{
		return strtolower(str_replace(' ', $replace, $str));
	}
}