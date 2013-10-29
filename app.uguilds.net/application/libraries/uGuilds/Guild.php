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
		"type",
		"region",
		"realm",
		"guildName",
		"domainName",
		"ranks",
		"theme",
		"locale",
		"features");

	private $_id;
	private $region;
	private $realm;
	private $guildName;
	private $domainName;
	private $ranks;
	private $theme = 'default';
	private $locale = 'en_GB';
	private $features = array();

	/**
	 * @access private
	 * @var \BattlenetArmory\Guild;
	 */
	private $_BattlenetArmoryGuild;

	/**
	 * __construct()
	 *
	 * @param \couchDocument
	 * @access public
	 * @return void
	 */
	function __construct(\couchDocument $guild_doc) 
	{
		foreach($guild_doc->getFields() as $key => $value) 
		{
			// Check if the guild is valid
			if(!in_array($key,$this->dbFields)) 
			{
				throw new \Exception("Invalid guild! Field missing was: ".$key);
			}
			
			// Set the variables
			if(property_exists($this, $key))
			{
				$this->$key = $guild_doc->$key;
			}
		}
	}

	/**
	 * __get()
	 *
	 * @param $var
	 * @access public
	 * @return mixed
	 */
	function __get($var)
	{
		switch($var)
		{
			case "id":
				return $this->_id;
				break;

			case "region":
				return strtoupper($this->region);
				break;

			case "realm":
				return ucwords($this->realm);
				break;

			case "name":
			case "guildName":
				return ucwords($this->guildName);
				break;

			case "domainName":
				return strtolower(preg_replace("![^a-z0-9\-\.]+!i", "-", $this->domainName));
				break;

			case "ranks":
				return $this->ranks;
				break;

			case "theme":
				return $this->theme;
				break;

			case "locale":
				if(!preg_match("/([a-z]+)_([A-Z]+)/", $this->locale))
				{
					$this->locale = "en_GB";
				}

				return $this->locale;
				break;

			case "features":
				return $this->getFeatures();
				break;
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

	/**
	 * showEmblem()
	 *
	 * @access public
	 * @var bool $showlevel
	 * @var int $width
	 * @return string $url
	 */
	public function getEmblem($showlevel=TRUE, $width=215)
	{
		$this->getBattlenetGuild()->showEmblem($showlevel, $width);
		$this->getBattlenetGuild()->saveEmblem(FCPATH . 'media/BattlenetArmory/emblem_'. $this->_id .'_'. $width .'.png');

		return '/media/BattlenetArmory/emblem_'. $this->_id .'_'. $width .'.png';
	}

	/**
	 * _getFeatures()
	 * 
	 * @access private
	 * @return array
	 */
	private function _getFeatures()
	{
		$return = array();

		foreach($this->features as $key => $value)
		{
			if($value)
			{
				$return[$key] = (bool) $value;
			}
		}

		return $return;
	}

	/**
	 * hasFeature()
	 *
	 * @access public
	 * @var string $feature
	 * @return bool
	 */
	public function hasFeature($feature)
	{
		return array_key_exists($feature, $this->_getFeatures());
	}

	/**
	 * areApplicationsEnabled()
	 *
	 * @return bool
	 * @access public
	 */
	public function areApplicationsEnabled()
	{
		return (bool) $this->features->applications;
	}
}