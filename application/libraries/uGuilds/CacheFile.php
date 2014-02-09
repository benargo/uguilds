<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'uGuilds\CacheFile' class
 *
 * This class handles the storage, reading and operation of a cache file
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1. __construct()
 * 2. __get()
 * 3. set_contents()
 * 4. set_last_modified()
 * 5. set_path()
 */
class CacheFile
{
	protected $last_modified;
	protected $name;
	protected $path;
	protected $contents;

	/**
	 * __construct()
	 *
	 * Initialises the class, by loading the Cache model if it's not already loaded, and then opening the file
	 *
	 * @access public
	 * @param (string) $path - The path to the cache file
	 * @return void
	 */
	function __construct($path)
	{
		if($this->set_path($path))
		{
			$this->name = basename($path);
			$this->set_contents();
			$this->set_last_modified();
		}
	}

	/**
	 * __get()
	 *
	 * Gets a specific parameter
	 *
	 * @access public
	 * @param (string) $param - the name of the parameter
	 * @return mixed
	 */
	function __get($param)
	{
		if(property_exists($this, $param))
		{
			return $this->$param;
		}
	}

	/**
	 * __set()
	 *
	 * Sets a specific parameter
	 *
	 * @access public
	 * @param (string) $name - the name of the parameter
	 * @param (mixed) $value - the value to set it to
	 * @return void
	 */
	function __set($name, $value)
	{
		if(property_exists($this, $name) && method_exists($this, 'set_'. $name))
		{
			$this->set_{$name}($value);
		}
	}

	/**
	 * set_contents()
	 *
	 * Unserialises the file if it's possible, and sets the contents accordingly
	 *
	 * @access protected
	 * @return (string) the contents
	 */
	protected function set_contents()
	{
		$this->contents = file_get_contents($this->path);

		if(unserialize($this->contents))
		{
			$this->contents = unserialize($this->contents);
		}

		return $this->contents;
	}

	/**
	 * set_last_modified()
	 *
	 * Calculates the true last modified time
	 *
	 * @access protected
	 * @return (int) last modified, in unix time
	 */
	protected function set_last_modified($override = NULL)
	{	
		$this->last_modified = filemtime($this->path);

		// We have to work this out
		if(is_object($this->contents))
		{
			if(is_int($this->contents->last_modified))
			{
				$this->last_modified = $this->contents->last_modified;
			}
			elseif(is_int($this->contents->lastModified))
			{
				$this->last_modified = $this->contents->lastModified;
			}
		}

		return $this->last_modified;
	}

	/**
	 * set_path()
	 *
	 * Sets the path by detecting whether or not the given path is fully qualified or not
	 *
	 * @access protected
	 * @param (string) $path
	 * @return (string) the fully qualified path
	 */
	protected function set_path($path)
	{
		if(!strstr($path, APPPATH .'cache/'))
		{
			$path = APPPATH .'cache/'. $path;
		}

		if(is_readable($path))
		{
			$this->path = $path;
			return $this->path;
		}

		return false;
	}


}

