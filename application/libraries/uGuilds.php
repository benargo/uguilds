<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

require_once APPPATH . 'libraries/uGuilds/Account.php';
require_once APPPATH . 'libraries/uGuilds/Guild.php';
require_once APPPATH . 'libraries/uGuilds/Races.php';
require_once APPPATH . 'libraries/uGuilds/Classes.php';
require_once APPPATH . 'libraries/uGuilds/Theme.php';
require_once APPPATH . 'libraries/uGuilds/ThemeData.php';

class uGuilds {

	/**
	 * vars
	 */
	private $domain;
	private $guild;
	private $theme;
	private $locale;
	private $controller_map = array("applications"	=> "#",
									"roster"		=> "roster");

	/**
	 * __construct()
	 * 
	 * @access public
	 */
	function __construct() 
	{
		// Find the guild
		$this->_findGuild();

		// Set the theme & locale
		$this->_setLocale(true);
		$this->_setTheme(true);
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

			case "theme":
				if(!$this->theme instanceof uGuilds\Theme)
				{
					$this->theme = $this->_setTheme();
				}
				return $this->theme;
				break;

			case "locale":
				if(is_null($this->locale))
				{
					$this->_setLocale();
				}
				return $this->locale;
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
			$this->guild = unserialize(file_get_contents(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt'));
		}
		else // No cache file, generate one from the database
		{
			$this->guild = new uGuilds\Guild($this->domain);
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
			exit;
		}
	}

	/**
	 * _setLocale()
	 *
	 * @access private
	 * @var bool $override
	 * @return void
	 */
	private function _setLocale($override = false) 
	{
		if(is_null($this->locale) || $override == true)
		{
			$this->locale = $this->guild->locale;
		}
	}

	/**
	 * _setTheme()
	 *
	 * @access private
	 * @var bool $override
	 * @return void
	 */
	private function _setTheme($override = false) 
	{
		if(empty($this->theme) || !$this->theme instanceof uGuilds\Theme || $override == true) 
		{
			$this->theme = new uGuilds\Theme;
			$this->theme->findByID($this->guild->theme);
		}
	}

	/**
	 * getController()
	 *
	 * @access public
	 * @var string $feature
	 * @return string
	 */
	public function getController($feature)
	{
		if(isset($this->controller_map[$feature]))
		{
			return $this->controller_map[$feature];
		}
	}

	/**
	 * getPageTitle()
	 *
	 * @access public
	 * @return string
	 */
	public function getPageTitle()
	{
		$ci =& get_instance();
		return $ci->getPageTitle();
	}
}