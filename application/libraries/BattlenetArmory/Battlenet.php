<?php namespace BattlenetArmory;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

abstract class Battlenet {

	private static $config;

	/**
	 * config()
	 * 
	 * @access public
	 * @return $this->config
	 */
	public function config()
	{
		if(is_null(self::$config))
		{
			$ci =& get_instance();
			$ci->config->load('battle.net');
			self::$config =& $ci->config->item('battle.net');
		}

		return self::$config;
	}

	/**
	 * get_icon
	 *
	 * @access public
	 * @param string $name
	 * @param int $size
	 * @return string
	 */
	public function getIcon($name, $size = 56)
	{
		$ci =& get_instance();
		$ci->load->helper('battlenet');

		return get_icon($name, $size);
	}
}