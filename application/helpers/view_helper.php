<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds -> Helpers -> View
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 */

if(!function_exists('get_include'))
{
	/**
	 * get_include()
	 *
	 * Gets a specific include file, based on one of the following keys:
	 * 1. 'head'	-> HTML Head
	 * 2. 'nav' 	-> Primary Navigation
	 * 3. 'footer' 	-> Footer
	 *
	 * @param $key - one of the above keys
	 * @return void
	 */
	function get_include($key)
	{
		if(file_exists(APPPATH .'views/includes/'. $key .'.php'))
		{
			$ci =& get_instance();

			$ci->load->view('includes/'. $key, $ci->data);
		}
	}
}

if(!function_exists('get_subview'))
{
	/**
	 * get_subview()
	 *
	 * Loads the subview
	 *
	 * @return void
	 */
	function get_subview()
	{
		$ci =& get_instance();
		
		if($ci->data['subview'] !== NULL && file_exists(APPPATH .'views/'. @$ci->data['subview'] .'.php'))
		{
			$ci->load->view($ci->data['subview'], $ci->data);
		}
	}
}