<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds -> Helpers -> Battlenet
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 */

if(!function_exists('get_icon'))
{
	/**
	 * get_icon()
	 *
	 * Gets a specific icon file. If it doesn't exist, download it from Blizzard and cache it.
	 *
	 * @param $string - The key of the icon
	 * @param $size - The width of the icon, either 18 or 56.
	 * @return string
	 */
	function get_icon($string, $size = 56)
	{
		if(!file_exists(FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string).'.jpg'))
		{
			if($size != 18 && $size != 56)
        	{
           		$size = 56;
        	}

			if($image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/'. (int) $size .'/'. strtolower($string) .'.jpg'))
			{	
				imagejpeg($image, FCPATH .'media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg', 100);
			}
			else
			{
				show_error('Sorry, Blizzard doesn\'t have an icon of name '. strtolower($string) .' or size '. (int) $size .'px');
			}
		}

		return '/media/images/icons/'. (int) $size .'/'. strtolower($string) .'.jpg';
	}
}