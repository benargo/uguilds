<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends \BattlenetArmory\Character {

	/**
	 * __construct()
	 *
	 * @param string $name
	 */
	function __construct($name)
	{
		$ci =& get_instance();
		parent::__construct(strtolower($ci->guild->region), $ci->guild->realm, $name, false);
	}

	/**
	 * __get()
	 *
	 * @access public
	 * @param string $param
	 * @return mixed
	 */
	function __get($param)
	{
		switch($param)
		{
			case 'name':
				return ucwords($this->name);
				break;

			case 'region':
				return strtoupper($this->region);
				break;

			case 'realm':
				return ucwords($this->realm);
				break;

		}
	}

}