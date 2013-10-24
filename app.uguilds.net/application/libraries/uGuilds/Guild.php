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
		"theme",
		"locale");

	private $_id;
	private $_rev;
	public $region;
	public $realm;
	public $guildName;
	private $domainName;
	public $ranks;
	public $theme = 'default';
	public $locale = 'en_GB';

	/**
	 * @access private
	 * @var \BattlenetArmory\Guild;
	 */
	private $_BattlenetArmoryGuild;

	/**
	 * __construct
	 *
	 * @param \couchDocument
	 * @access public
	 * @return void
	 */
	public function __construct(\couchDocument $guild_doc) 
	{
		foreach($guild_doc->getFields() as $key => $value) 
		{
			// Check if the guild is valid
			if(!in_array($key,$this->dbFields)) 
			{
				throw new \Exception("Invalid guild! Field missing was: ".$key);
			}
			// Set the variables
			$this->$key = $guild_doc->$key;
		}
	}

	/**
	 * getBattlenetGuild
	 *
	 * @access public
	 * @return \BattlenetArmory\Guild object
	 */
	public function getBattlenetGuild() 
	{
		if(is_null($this->_BattlenetArmoryGuild)) 
		{
			$ci = get_instance();
			$this->_BattlenetArmoryGuild = $ci->battlenetarmory->getGuild($this->guildName,$this->realm);
		}
		return $this->_BattlenetArmoryGuild;
	}
}