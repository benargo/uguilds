<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

require_once APPPATH . 'libraries/uGuilds/Guild.php';
require_once APPPATH . 'libraries/uGuilds/Theme.php';
require_once APPPATH . 'libraries/uGuilds/Account.php';

class uGuilds {

	/**
	 * vars
	 */
	protected $domain;
	public $guild;
	public $theme;
	public $locale;
	private $controller_map = array("applications"	=> "#",
									"roster"		=> "roster");

	/**
	 * __construct
	 * 
	 * @access public
	 */
	public function __construct() 
	{
		// Find the guild
		$this->findGuildByDomain();	

		// Set the theme & locale
		$this->setLocale(true);
		$this->setTheme(true);
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

		// Set up the database;
		$ci = get_instance();
		$db = $ci->couchdb;

		// Find the guild ID
		$guild_id = $db->key($this->domain)->limit(1)->getView('find_guild','by_domain')->rows[0]->value;

		// Create the new guild
		$this->guild = new uGuilds\Guild($db->asCouchDocuments()->getDoc($guild_id));

		// Set the region and realm
		$ci->battlenetarmory->setRegion($this->guild->region);
		$ci->battlenetarmory->setRealm($this->guild->realm);
	}

	/**
	 * getDomain()
	 *
	 * @access public
	 * @return string
	 */
	public function getDomain()
	{
		if(is_null($this->domain))
		{
			$this->calculateDomain();
		}

		return $this->domain;
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
		if(empty($this->locale) || $override == true)
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
		if(empty($this->theme) || $override == true) 
		{
			$this->theme = new uGuilds\Theme;
			$this->theme->findByName($this->guild->theme);
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