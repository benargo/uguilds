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
		$this->findGuildByDomain();	

		// Set the theme & locale
		$this->setLocale(true);
		$this->setTheme(true);
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
					$this->calculateDomain();
				}
				return $this->domain;
				break;

			case "guild":
				if(!$this->guild instanceof uGuilds\Guild)
				{
					$this->guild = $this->findGuildByDomain();
				}
				return $this->guild;
				break;

			case "theme":
				if(!$this->theme instanceof uGuilds\Theme)
				{
					$this->theme = $this->setTheme();
				}
				return $this->theme;
				break;

			case "locale":
				if(is_null($this->locale))
				{
					$this->setLocale();
				}
				return $this->locale;
				break;
		}
	}

	/**
	 * load()
	 * 
	 * @access public
	 * @static true
	 * @return $this
	 */
	public static function load()
	{
		$ci = get_instance();
		return $ci->uguilds;
	}

	/**
	 * getGuildByDomain
	 * 
	 * @access private
	 */
	private function findGuildByDomain() 
	{
		// Look up the domain name
		if(is_null($this->domain)) {
			$this->calculateDomain();
		}

		// Create the guild object
		$this->guild = new uGuilds\Guild;

		if($this->guild->findByDomain($this->domain))
		{
			$ci = get_instance();

			// Set the region and realm
			$ci->battlenetarmory->setRegion($this->guild->region);
			$ci->battlenetarmory->setRealm($this->guild->realm);
		}
	}

	/** 
	 * calculateDomain
	 *
	 * @access private
	 * @return void
	 */
	private function calculateDomain() 
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
	 * setLocale
	 *
	 * @access private
	 * @var bool $override
	 * @return void
	 */
	private function setLocale($override=false) 
	{
		if(is_null($this->locale) || $override == true)
		{
			$this->locale = $this->guild->locale;
		}
	}

	/**
	 * setTheme
	 *
	 * @access private
	 * @var bool $override
	 * @return void
	 */
	private function setTheme($override=false) 
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
		$ci = get_instance();
		return $ci->getPageTitle();
	}
}