<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'libraries/BattlenetArmory/Guild.php');

/**
 * 'Guild' model
 *
 * This model implements a specific guild. 
 * At present it's linked to World of Warcraft, in the future it will become game independant
 *
 * @author Ben Argo <ben@benargo.com>
 * 
 * 
 ** Table of Contents
 * 1. Constants
 * 2. Variables
 * 
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


	}
}

