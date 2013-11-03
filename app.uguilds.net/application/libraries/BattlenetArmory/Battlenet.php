<?php namespace BattlenetArmory;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

abstract class Battlenet {

	private $config;

	public function config()
	{
		if(is_null($this->config))
		{
			$ci = get_instance();
			$ci->config->load('battle.net');
			$this->config = $ci->config->item('battle.net');
		}

		return $this->config;
	}
}