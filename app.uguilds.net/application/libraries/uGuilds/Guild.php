<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Guild
 *
 * @author Ben Argo <ben@benargo.com>
 */

class Guild extends \BattlenetArmory\Guild {

	private $_id;
	private $domainName;
	private $ranks;
	private $theme = 'default';
	private $locale = 'en_GB';
	private $features = array();

	/**
	 * __construct()
	 *
	 * @access public
	 * @return void
	 */
	function __construct() 
	{
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

			case "guildName": /* preferred */
			case "name":
				return ucwords($this->name);
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
					$this->locale = 'en_GB';
				}

				return $this->locale;
				break;

			case "features":
				return $this->getFeatures();
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
		return $ci->uguilds->guild;
	}

	/**
	 * findByDomain()
	 *
	 * @access public
	 * @return void
	 */
	public function findByDomain($domain)
	{	
		$ci = get_instance();

		/*
		if(file_exists(APPPATH . 'cache/uGuilds/guild_objects/'. $domain .'.json') 
			&& filemtime(APPPATH . 'cache/uGuilds/guild_objects/'. $domain .'.json') >= time() - $ci->battlenetarmory->config['GuildsTTL'])
		{
			$cache = json_decode(file_get_contents(APPPATH . 'cache/uGuilds/guild_objects/'. $domain .'.json'));
			dump()
		}
		*/

		$query = $ci->db->query("SELECT 	`_id`,
								`region`,
								`realm`,
								`name`,
								'faction',
								`domainName`,
								`theme`,
								`locale`
						FROM `ug_Guilds`
						WHERE `domainName` = '$domain'
						LIMIT 0, 1");

		// Check we got a result
		if ($query->num_rows() > 0)
		{
			// Loop through the rows (there should only be one)
  			foreach ($query->result() as $row)
   			{
   				// Loop through the columns
    			foreach($row as $key => $value)
    			{
    				$this->$key = $value;
    			}

    			$ci->session->set_userdata('guild_id', $this->_id);

    			$this->_load(strtolower($this->region), $this->realm, $this->name);

    			// We only want to do this once!
    			break;
   			}
		} 
		else
		{
			return false;
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
		return $this;
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
		$this->showEmblem($showlevel, $width);
		$this->saveEmblem(FCPATH . 'media/BattlenetArmory/emblem_'. $this->region .'_'. preg_replace('/\ /', '_', $this->realm) .'_'. preg_replace('/\ /', '_', $this->guildName) .'_'. $width .'.png');

		return '/media/BattlenetArmory/emblem_'. $this->region .'_'. preg_replace('/\ /', '_', $this->realm) .'_'. preg_replace('/\ /', '_', $this->guildName) .'_'. $width .'.png';
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
	 * getLowestLevelMember()
	 *
	 * @access public
	 * @return int
	 */
	public function getLowestLevelMember()
	{
		// Theoretical lowest level is 95
		$level = 95;

		foreach($this->getData()['members'] as $member)
		{
			if($member['character']['level'] < $level)
			{
				$level = $member['character']['level'];
			}
		}

		return (int) $level;
	}

	/**
	 * getHighestLevelMember()
	 *
	 * @access public
	 * @return int
	 */
	public function getHighestLevelMember()
	{
		// Theoretical highest level is 1
		$level = 1;

		foreach($this->getData()['members'] as $member)
		{
			if($member['character']['level'] > $level)
			{
				$level = $member['character']['level'];
			}
		}

		return (int) $level;
	}
}