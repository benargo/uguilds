<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo
 *	@version 1.0
 */

require_once APPPATH . 'libraries/uGuilds/Account.php';
require_once APPPATH . 'libraries/uGuilds/Guild.php';
require_once APPPATH . 'libraries/uGuilds/Races.php';
require_once APPPATH . 'libraries/uGuilds/Classes.php';
require_once APPPATH . 'libraries/uGuilds/Theme.php';
require_once APPPATH . 'libraries/uGuilds/ThemeData.php';

// Remember library class names need to be capitalised.
class UGuilds {

	/**
	 * vars
	 */
	private $domain;

	/**
	 * __construct()
	 * 
	 * @access public
	 */
	function __construct() 
	{
		// Find the guild
		$this->_findGuild();
	}

	/**
	 * __get()
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
					$this->_setDomain();
				}
				return $this->domain;
				break;

			case "guild":
				if(!$this->guild instanceof uGuilds\Guild)
				{
					$this->guild = $this->_findGuild();
				}
				return $this->guild;
				break;
		}
	}

	/**
	 * getGuildByDomain
	 * 
	 * @access private
	 */
	private function _findGuild() 
	{
		$ci =& get_instance();

		// Look up the domain name
		if(is_null($this->domain)) 
		{
			$this->_setDomain();
		}

		// Check if there's a cache file for this guild and it's valid
		if(file_exists(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') 
			&& filemtime(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') >= time() - $ci->config->item('battle.net')['GuildsTTL'])
		{
			$ci->guild = unserialize(file_get_contents(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt'));
		}
		else // No cache file, generate one from the database
		{
			$ci->guild = new uGuilds\Guild($this->domain);
		}
	}

	/** 
	 * _setDomain()
	 *
	 * @access private
	 * @return void
	 */
	private function _setDomain() 
	{
		// Determine the domain name from SERVER_NAME
		$this->domain = $_SERVER['SERVER_NAME'];

		// If they're running on the application domain name, then return a 403 Forbidden error
		if($this->domain === "app.uguilds.net") 
		{
			header('HTTP/1.0 403 Forbidden');
		}
	}
}

