<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'libraries/BattlenetArmory/Guild.php');

/**
 * 'Guild' model
 *
 * This model implements a specific guild. 
 * At present it's linked to World of Warcraft, in the future it will become game independant
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 * 
 ** Table of Contents
 * 1. Constants
 * 2. Variables
 *
 * 3. Magic Variables
 * 3.1. __construct()
 * 3.2. __get()
 */
class Guild extends CI_Model 
{
	// Constants
	const MINLEVEL = 1;
	const MAXLEVEL = 100;

	// Variables
	private $_id;
	private $domain_name;
	private $theme;
	private $locale = 'en_GB';
	private $faction;
	private $ranks = array();
	private $features = array();
	private $levelRange = array('min' => self::MAXLEVEL,
								'max' => self::MINLEVEL);

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

		// Determine the domain name from SERVER_NAME
		$this->domain_name = $_SERVER['SERVER_NAME'];

		$query = $this->db->query(
			"SELECT 	
				`_id`,
				`region`,
				`realm`,
				`name`,
				`domain_name`,
				`theme`,
				`locale`
			FROM `ug_Guilds`
			WHERE `domain_name` = '". $this->domain_name ."'
			LIMIT 0, 1");

		// Check we got a result
		if($query->num_rows() > 0)
		{
			$row = $query->row();
	   			
	   		// Loop through the columns
    		foreach($row as $key => $value)
    		{
    			$this->$key = $value;
    		}

    		// Load the full guild from battle.net
    		$guild = BattlenetArmory\Guild(strtolower($this->region), $this->realm, $this->name);

    		// Load the levels and ranks
    		$this->_setLowestLevelMember();
    		$this->_setHighestLevelMember();
    		$this->_setRanks();
		}
	}
}

