<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

require_once APPPATH . 'libraries/uGuilds/Guild.php';

class uGuilds {

	/**
	 * vars
	 */
	protected $domain;
	public $guild;
	public $theme;

	/**
	 * __construct
	 * 
	 * @access public
	 */
	public function __construct() 
	{
		$this->findGuildByDomain();
		$ci = get_instance();
		$ci->battlenetarmory->setRegion($this->guild->region);
		$ci->battlenetarmory->setRealm($this->guild->realm);
	}

	/**
	 * getGuildByDomain
	 * 
	 * @access private
	 */
	private function findGuildByDomain() 
	{
		// Look up the domain name
		if(!isset($this->domain)) {
			$this->calculateDomain();
		}

		// Set up the database;
		$ci = get_instance();
		$db = $ci->couchdb;

		// Find the guild ID
		$guild_id = $db->key($this->domain)->limit(1)->getView('find_guild','by_domain')->rows[0]->value;

		// Create the new guild
		//$this->guild = $db->asCouchDocuments()->getDoc($guild_id);
		$this->guild = new uGuilds\Guild($db->asCouchDocuments()->getDoc($guild_id));

		$this->setTheme();
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
		if($this->domain === "app.uguilds.net") {
			header('HTTP/1.0 403 Forbidden');
			exit;
		}
	}

	/**
	 * setTheme
	 *
	 * @access private
	 * @return void
	 */
	/**
	 * setTheme
	 *
	 * @access private
	 * @return void
	 */
	private function setTheme() 
	{
		if(!$this->guild->theme) {
			$this->theme = 'default';
			return;
		}
		$this->theme = $this->guild->theme;
	}
}