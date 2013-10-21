<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Guild Model Class
 *
 * Provides a model for mapping guilds to domain names,
 * and mapping guild models to guild objects.
 *
 * @author Ben Argo
 */

class Guild_Model extends CI_Model {
	
	private $_id;			/* primary key */
	private $region;
	private $realm;
	private $guildName;
	private $domainName;	/* key */
	private $ranks;
	private $officers;
	private $moderators;

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		// Call the model constructor
		parent::__construct();
	}

	/**
	 * loadByDomain
	 *
	 * Load a guild by their domain name.
	 *
	 * @param string
	 * @access public
	 */
	function loadByDomain($domain)
	{
		// Get the guild we're refering to
		$this->db->query("SELECT 
				`guilds`.`_id`,
				`realms`.`region`,
				`realms`.`name`,
				`guilds`.`guildName`,
				`guilds`.`domainName`,
				`guilds`.`ranks`
			FROM `guilds`
			LEFT OUTER JOIN `realms` ON (`guilds`.`realm` = `realms`.`_id`)
			WHERE `domainName` = '$domain'
			LIMIT 0, 1");

		print_r($this->db->row());
	}
}

/* End of file Guild.php */
/* Location: ./application/models/Guild.php */