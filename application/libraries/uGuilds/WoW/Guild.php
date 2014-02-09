<?php namespace uGuilds\WoW;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'uGuilds\WoW\Guild' class
 *
 * This class handles an individual guild
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1.  Constants
 * 2.  Variables
 * 3.  Static Variables
 *
 * 4.  __construct()
 * 5.  __get()
 * 6.  get_emblem()
 * 7.  getEmblem()
 * 8.  get_faction()
 * 9.  getFaction()
 * 10.  _get_features()
 * 11. has_feature()
 * 12. get_members()
 * 13. getMembers()
 * 14. filter()
 * 15. _filter()
 */
class Guild extends \BattlenetArmory\Guild 
{
	// Constants, duh
	const MINLEVEL = 1;
	const MAXLEVEL = 100;

	// Variables
	protected $_id; // Primary key
	protected $domainName;
	protected $locale = 'en_GB';
	protected $faction;
	protected $ranks = array();
	protected $features = array();
	protected $levelRange = array('min' => self::MAXLEVEL,
								  'max' => self::MINLEVEL);

	// Static Variables
	private static $params;

	/**
	 * __construct()
	 *
	 * @access public
	 * @param string $domain
	 * @return void
	 */
	function __construct($domain = NULL) 
	{
		$query = $this->db->query("SELECT 	
								`_id`,
								`region`,
								`realm`,
								`name`,
								`domainName`,
								`theme`,
								`locale`
						FROM `ug_Guilds`
						WHERE `domainName` = '$domain'
						LIMIT 0, 1");

		// Check we got a result
		if($query->num_rows() > 0)
		{
			// Loop through the rows (there should only be one)
  			foreach ($query->result() as $row)
   			{
	   			// Loop through the columns
	    		foreach($row as $key => $value)
	    		{
	    			$this->$key = $value;
	    		}

	    		// Set the session
	    		$this->session->set_userdata('guild_id', $this->_id);

	    		// Load the full guild from battle.net
	    		parent::_load(strtolower($this->region), $this->realm, $this->name);

	    		// Load the levels and ranks
	    		$this->_setLowestLevelMember();
	    		$this->_setHighestLevelMember();
	    		$this->_setRanks();
   			}

   			// Encode this object and store it in the cache
   			file_put_contents(APPPATH .'cache/uGuilds/guild_objects/'. $this->domainName .'.txt', serialize($this));
		}
		else // No result from the database, this guild must not exist
		{
			throw new \Exception('This guild does not exist');
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
			// Primary key
			case "id":
				return $this->_id;
				break;

			case "data":
				return $this->getData();
				break;

			case "domain_name":
			case "domainName":
				return strtolower(preg_replace("![^a-z0-9\-\.]+!i", "-", $this->domainName));
				break;

			case "features":
				return $this->get_features();
				break;

			case "guildName": 
			case "name": // preferred
				return ucwords($this->name);
				break;

			case "locale":
				if(!preg_match("/([a-z]+)_([A-Z]+)/", $this->locale))
				{
					$this->locale = 'en_GB';
				}

				return $this->locale;
				break;

			case "ranks":
				if(empty($this->ranks))
				{
					$this->_setRanks();
				}

				return $this->ranks;
				break;

			case "realm":
				return ucwords($this->realm);
				break;

			case "region":
				return strtoupper($this->region);
				break;

			default:
				return parent::__get($var);
				break;
		}
	}

	/**
	 * get_emblem()
	 *
	 * Creates the emblem if it doesn't exist
	 * and return the string to it's URL
	 *
	 * @access public
	 * @var bool $showlevel
	 * @var int $width
	 * @return string $url
	 */
	public function get_emblem($show_level = TRUE, $width = 215)
	{
		$emblem_path = '/media/images/guild_emblems/'. strtolower($this->region) .'/'. strformat($this->realm, '_') .'/'. strformat($this->guildName, '_') .'_'. $width .'px.png';
		$fq_emblem_path = rtrim(FCPATH, "/") . $emblem_path;

		if(!file_exists($fq_emblem_path))
		{
			// Determine the true destination
			$directory = explode('/', $fq_emblem_path);
			$directory = array_slice($directory, 0, -1);

			// Make the directory
			if(!isdir($directory)) mkdir(implode('/', $directory), 0777, true);

			$this->showEmblem($show_level, $width);
			$this->saveEmblem($fq_emblem_path);
		}
		
		return $emblem_path;
	}

	/**
	 * getEmblem()
	 *
	 * @see get_emblem()
	 *
	 * @access public
	 * @var bool $showlevel
	 * @var int $width
	 * @return string $url
	 */
	public function getEmblem($show_level = TRUE, $width = 215)
	{
		return $this->get_emblem($show_level, $width);
	}

	/**
	 * get_faction()
	 *
	 * Works out whether the guild is a member of the Horde or the Alliance
	 *
	 * @access public
	 * @return string
	 */
	public function get_faction()
	{
		if(is_null($this->faction))
		{
			$this->faction = $this->getData()['side'];
		}

		switch($this->faction)
		{
			case 0:
				return 'alliance';
				break;
			case 1:
				return 'horde';
				break;
		}
	}

	/**
	 * getFaction()
	 *
	 * @see get_faction()
	 *
	 * @access public
	 * @return text
	 */
	public function getFaction()
	{
		return $this->get_faction();
	}

	/** FEATURES **/

	/**
	 * _get_features()
	 * 
	 * @access private
	 * @return array
	 */
	private function _get_features()
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
	 * has_feature()
	 *
	 * @access public
	 * @var string $feature
	 * @return bool
	 */
	public function has_feature($feature)
	{
		return array_key_exists($feature, $this->_getFeatures());
	}

	/** MEMBERS **/

	/**
	 * get_members()
	 *
	 * Gets the list of members
	 * 
	 * @access public
	 * @param string $sort
	 * @param string $sortFlag
	 * @return array
	 */
	public function get_members($sort = FALSE, $sortFlag = 'asc')
	{
		$members = parent::getMembers($sort, $sortFlag);

		foreach($members as $key => $member)
		{
			$members[$key] = (object) $member;

			foreach($member['character'] as $trait => $value)
			{
				if($trait == 'spec')
				{
					$value = (object) $value;
				}

				$members[$key]->$trait = $value;
			}

			unset($members[$key]->character);
		}

		return $members;
	}

	/**
	 * getMembers()
	 *
	 * @see get_members()
	 *
	 * @access public
	 * @param string $sort
	 * @param string $sortFlag
	 * @return array
	 */
	public function getMembers($sort = FALSE, $sortFlag = 'asc')
	{
		return $this->get_members($sort, $sortFlag);
	}

	/**
	 * filter()
	 *
	 * @access public
	 * @param array $params
	 * @return array $members
	 */
	public function filter(array $params = array())
	{
		self::$params = $params;
		$members = array_values(array_filter($this->get_members(), array($this, '_filter')));
		unset(self::$params);

		return $members;
	}

	/**
	 * _filter()
	 * 
	 * @access private
	 * @param array $member
	 * @return array
	 */
	private function _filter($member)
	{
		foreach(self::$params as $key => $value)
		{
			if($member->$key == $value)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * get_lowest_level_member()
	 *
	 * @access public
	 * @return int
	 */
	public function getLowestLevelMember()
	{
		return (int) $this->levelRange['min'];
	}

	/**
	 * _setLowestLevelMember()
	 *
	 * @access private
	 * @return void
	 */
	private function _setLowestLevelMember()
	{
		// Loop through each of the members
		foreach( $this->getData()['members'] as $member )
		{
			// If this member has a lower level than the max level
			if( $member['character']['level'] < $this->levelRange['min'] )
			{
				$this->levelRange['min'] = $member['character']['level'];
			}
		}
	}

	/**
	 * getHighestLevelMember()
	 * 
	 * @access public
	 * @return int
	 */
	public function getHighestLevelMember()
	{
		return (int) $this->levelRange['max'];
	}

	/**
	 * _setHighestLevelMember()
	 *
	 * @access private
	 * @return int
	 */
	private function _setHighestLevelMember()
	{
		// Loop through each of the members
		foreach( $this->getData()['members'] as $member )
		{
			// If the member has a higher level than the minimum level
			if( $member['character']['level'] > $this->levelRange['max'] )
			{
				$this->levelRange['max'] = $member['character']['level'];
			}
		}
	}

	/**
	 * _setRanks()
	 *
	 * @access private
	 * @return void
	 */
	private function _setRanks()
	{
		$highestRank = $this->getMembers('rank','desc')[0]->rank;

		for( $i = 0; $i <= $highestRank; $i++ )
		{
			$this->ranks[ $i ] = $i;
		}

		$this->setGuildRankTitles();
	}

	/**
	 * _setGuildRankTitles()
	 *
	 * @access protected
	 * @return void
	 */
	protected function setGuildRankTitles()
	{
		$ci =& get_instance();

		$query = $ci->db->query( "SELECT
								`position`,
								`title`
								FROM `ug_GuildRanks`
								WHERE `guild_id` = '". $this->_id ."'
								ORDER BY `position`" );

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$this->ranks[ $row->position ] = $row->title;
			}

			parent::setGuildRankTitles( $this->ranks );
		}
	}
}


