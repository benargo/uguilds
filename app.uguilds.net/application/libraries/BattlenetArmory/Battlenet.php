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
			$ci =& get_instance();
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
	public function getIcon($string, $size = 56)
	{
		if(!file_exists(FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string)))
		{
			if($image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/'. (int) $size .'/'. strtolower($string) .'.jpg'))
			{	
				imagejpeg($image, FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg', 100);
			}
			else
			{
				throw new \Exception('Sorry, Blizzard doesn\'t have an icon of name '. strtolower($string) .' or size '. (int) $size .'px');
			}
		}

		return '/media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg';
	}
}