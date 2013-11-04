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

	/**
	 * config()
	 * 
	 * @access public
	 * @return $this->config
	 */
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

	/**
	 * getIcon()
	 *
	 * @access public
	 * @return image/jpeg
	 */
	public function getIcon($string)
	{
		if(!file_exists(FCPATH .'media/images/icons/'. strtolower($string)))
		{
			$image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/56/'. strtolower($string) .'.jpg');
			imagejpeg($image, FCPATH .'media/images/icons/'. strtolower($string) .'.jpg', 100);
		}

		return '/media/images/icons/'. strtolower($string) .'.jpg';
	}
}