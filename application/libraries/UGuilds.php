<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Master class for the uGuilds application
 * 
 * The class doesn't really do much, and may be phased out altogether at a later date. 
 * However, for the time being it's the master class that auto loads, finds the guild in question
 * and autoloads the rest of the library.
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1. __construct()
 */

// Remember library class names need to be capitalised.
class UGuilds 
{
	/**
	 * __construct()
	 *
	 * Initialise the class
	 * Includes all neccessary files for the rest of this class
	 * and then finds the guild.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct() 
	{
		// Include all this library's files
		$iterator = new RecursiveDirectoryIterator(APPPATH .'libraries/uGuilds');
		foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
		{
			if(substr($file->getFileName(), -4) == '.php')
			{
				require_once($file->getPathName());
			}
		}
	}
}

