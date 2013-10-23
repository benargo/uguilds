<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Guild
 *
 * @author Ben Argo <ben@benargo.com>
 */

class Guild {

	private $dbFields = array(
		"_id",
		"_rev",
		"region",
		"realm",
		"guildName",
		"domainName",
		"ranks",
		"theme");

	private $_id;
	private $_rev;
	public $region;
	public $realm;
	public $guildName;
	private $domainName;
	public $ranks;
	public $theme;

	/**
	 * __construct
	 *
	 * @param \couchDocument
	 * @access public
	 * @return void
	 */
	public function __construct(\couchDocument $guild_doc) 
	{
		foreach($guild_doc->getFields() as $key => $value) {
			// Check if the guild is valid
			if(!in_array($key,$this->dbFields)) {
				throw new \Exception("Invalid guild! Field missing was: ".$key."\n".dump($guild_doc,false));
			}
			// Set the variables
			$this->$key = $guild_doc->$key;
		}
	}
}