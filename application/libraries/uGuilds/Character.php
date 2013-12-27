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
		parent::__construct();
	}

}