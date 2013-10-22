<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Master class for the uGuilds application
 *	@author Ben Argo <ben@benargo.com>
 *	@copyright Copyright 2013 Ben Argo & University of the West of England
 *	@version 1.0
 */

require_once APPPATH . 'libraries/uGuilds/Guild.php';

class uGuilds {

	protected $domain;
	public $guild;

	function __construct() {
		$this->findGuildByDomain();
	}

	/**
	 * getGuildByDomain
	 * 
	 * @access public
	 */
	protected function findGuildByDomain() {

		// Look up the domain name
		if(!isset($this->domain)) {
			$this->calcDomain();
		}

		// Generate a new instance of a database
		$db = new couchdb();

		// Find the guild ID
		$guild_id = $db->key($this->domain)->limit(1)->getView('find_guild','by_domain')->rows[0]->value;

		// Create the new guild
		//$this->guild = new uGuilds\Guild($db->getDoc($guild_id));
		$this->guild = $db->getDoc($guild_id);

	}

	/** 
	 * calcDomain
	 *
	 * @access private
	 * @return void
	 */
	private function calcDomain() {

		// Determine the domain name from SERVER_NAME
		$this->domain = $_SERVER['SERVER_NAME'];

		// If they're running on the application domain name, then return a 403 Forbidden error
		if($this->domain === "app.uguilds.net") {
			header('HTTP/1.0 403 Forbidden');
			exit;
		}
	}

	/**
	 * dump
	 *
	 * @access public
	 */
	public function dump($data) {
		print "<pre>";
		print_r($data);
		print "</pre>";
		exit;
	}
}