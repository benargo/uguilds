<?php namespace uGuilds\WoW;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'uGuilds\WoW\Battlenet' class
 *
 * Master abstract class for the uGuilds library
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1. __get()
 * 2. config()
 * 3. get_icon()
 * 4. getIcon()
 */
abstract class Battlenet 
{
	private static $battlenet_config;

	/**
	 * __get()
	 *
	 * Gets specific properties
	 *
	 * @access public
	 * @param (string) $name - the name of the propety to get
	 * @return (mixed) - the value requested
	 */
	function __get($name)
	{
		// Check if the default property exists
		if(property_exists($this, $name))
		{
			return $this->$name;
		}
	}

	/**
	 * config()
	 * 
	 * @access public
	 * @return self::$config
	 */
	public function config()
	{
		if(is_null(self::$battlenet_config))
		{
			$ci =& get_instance();
			$ci->config->load('battle.net');
			self::$battlenet_config =& $ci->config->item('battle.net');
		}

		return self::$battlenet_config;
	}

	/**
	 * get_icon()
	 *
	 * Gets a specific icon by name, and returns the string for it
	 *
	 * @access public
	 * @param (string) $string - the name of the icon according to Blizzard's icon database
	 * @param (int) $size - the size of the icon, either 18 or 56 (default is 56)
	 * @return image/jpeg
	 */
	public function get_icon($string, $size = 56)
	{
		if(!file_exists(FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg'))
		{
			if($image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/'. (int) $size .'/'. strtolower($string) .'.jpg'))
			{	
				save_jpeg($image, FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg', 100);
			}
			else
			{
				return false;
			}
		}

		return '/media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg';
	}
}

