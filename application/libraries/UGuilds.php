<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Master class for the uGuilds application
 * 
 * The class doesn't really do much, and may be phased out altogether at a later date. 
 * However, for the time being it's the master class that auto loads, finds the guild in question
 * and autoloads the rest of the library.
 *
 * @author Ben Argo <ben@benargo.com>
 * @copyright Copyright 2013 Ben Argo
 * @version 1.0
 *
 ** Table of Contents
 * 1. Variables
 * 2. __construct()
 * 3. __get($var)
 * 4. _find_guild()
 * 5. _set_domain()
 * 6. override_domain()
 */

// Remember library class names need to be capitalised.
class UGuilds 
{
	/**
	 * Variables
	 */
	private $domain;

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
			if( substr( $file->getFileName(), -4 ) == '.php' )
			{
				require_once( $file->getPathName() );
			}
		}

		$ci =& get_instance();

		if(ENVIRONMENT === 'production' && $ci->input->is_cli_request())
		{
			$this->override_domain();
		}

		// Find the guild
		$this->_find_guild();
	}

	/**
	 * __get()
	 *
	 * Magic getter :)
	 * 
	 * @access public
	 * @var string $var
	 * @return mixed
	 */
	function __get($var)
	{
		switch($var)
		{
			case "domain":
				if(is_null($this->domain))
				{
					$this->_set_domain();
				}

				return $this->domain;
				break;
		}
	}

	/**
	 *_find_guild()
	 *
	 * Sets the domain if it's not been set yet,
	 * then loads a guild, either from the cache or by creating a new one
	 * 
	 * @access private
	 * @return Reference to uGuilds\Guild object
	 */
	private function _find_guild() 
	{
		$ci =& get_instance();

		// Look up the domain name
		if(is_null($this->domain)) 
		{
			$this->_set_domain();
		}

		// Check if there's a cache file for this guild and it's valid
		if( file_exists(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') 
			&& filemtime(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') >= time() - $ci->config->item('battle.net')['GuildsTTL'] )
		{
			$ci->guild = unserialize(file_get_contents(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt'));
		}
		else // No cache file, generate one from the database
		{
			$ci->guild = new uGuilds\Guild($this->domain);
		}

		return $ci->guild;
	}

	/** 
	 * _set_domain()
	 *
	 * If the domain is null, set it, simple.
	 *
	 * @access private
	 * @return void
	 */
	private function _set_domain() 
	{
		// Determine the domain name from SERVER_NAME
		$this->domain = $_SERVER['SERVER_NAME'];

		// If they're running on the application domain name, then return a 403 Forbidden error
		if($this->domain === "app.uguilds.net") 
		{
			show_error("The application cannot be run on 'app.uguilds.net'", 403);
		}
	}

	/**
	 * override_domain()
	 *
	 * If we're running via the CLI, we won't have a domain name set. 
	 * Therefore we need to set one manually, which we can use for testing via the command line
	 */
	public function override_domain()
	{
		$this->domain = 'mercenariesinc.uguilds.vhost';
	}
}

