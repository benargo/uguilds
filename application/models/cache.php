<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * '\Cache' Model class
 *
 * This class handles the Creation, Read, Updating and Deletion (CRUD) of uGuild's caches.
 * Caches are used all over the place, particularly for saving large chunks of data needed for the application.
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1. __construct()
 * 2. create()
 * 3. read()
 * 4. update()
 * 5. delete()
 */
class Cache extends CI_Model 
{
	protected static $path;
	protected static $cache_file;

	/**
	 * __construct()
	 *
	 * Initialises the class
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Load the save_file helper
		get_instance()->load->helper('save_file');
	}

	/**
	 * create()
	 *
	 * Creates a cache file specified by a given path, writing the contents of a given string to it.
	 *
	 * @access public
	 * @param (string) $path - the path to the file;
	 *		can either be fully qualified (starting from '/') or relative to the APPPATH/cache folder.
	 * @param (mixed) $contents - the contents of the file to save.
	 * @return (bool) whether or not the operation was a success.
	 */
	public function create($path, $contents)
	{
		if(!strstr($path, APPPATH .'cache/'))
		{
			$path = APPPATH .'cache/'. $path;
		}

		return save_file($path, $contents);
	}


	/**
	 * read()
	 *
	 * Loads a file from the cache, given a path.
	 *
	 * @access public
	 * @param (string) $path - The path to the file.
	 *		can either be fully qualified (starting from '/') or relative to the APPPATH/cache folder.
	 * @return (string) the contents of the cached file.
	 */
	public function read($path)
	{
		// Check if our static cache is fine to be returned
		if(self::$path == $path && self::$cache_file instanceof uGuilds\CacheFile)
		{
			return self::$cache_file;
		}

		//
		self::$path = $path
		self::$cache_file = new uGuilds\CacheFile($path);

		if($cache_file->path)
		{
			return self::$cache_file;
		}

		unset(self::$path, self::$cache_file);

		return false;
	}

	/**
	 * update()
	 *
	 * Loads a cache file specified by a given path, opens it using the specified mode and writes the contents to it
	 *
	 * @access public
	 * @param (string) $path - the name of the file.
	 * @param (string) $mode - the mode which we should open the file.
	 * @param (mixed) $contents - the contents which we should write.
	 * @return (bool) whether or not the operation was a success.
	 */
	public function update($path, $mode = 'w+', $contents)
	{
		if(!strstr($path, APPPATH .'cache/'))
		{
			$path = APPPATH .'cache/'. $path;
		}

		$file = fopen($path, $mode);
		
		if(fwrite($file, $contents))
		{
			fclose($file);
			return true;
		}

		fclose($file);
		return false;
	}

	/**
	 * delete()
	 *
	 * Deletes a cache file given the path. This will normally be run from a CRON job.
	 *
	 * @access public
	 * @param (string) $path - the path to the file to delete.
	 * @return (bool) whether or not the operation was a success
	 */
	public function delete($path)
	{
		if(!strstr($path, APPPATH .'cache/'))
		{
			$path = APPPATH .'cache/'. $path;
		}

		if(unlink($path))
		{
			return true;
		}

		return false;
	}
}