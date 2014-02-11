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
 * 1. Variables
 * 1.1. Constants
 * 1.2. Variables
 * 1.3. Static Variables
 * 1.4. Public Variables
 *
 * 2. Magic Methods
 * 2.1. __construct()
 * 2.2. __get()
 *
 * 3. Emblem Methods 
 * 3.1. get_emblem()
 * 3.2. delete_emblem()
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
	protected $domain_name;
	protected $locale = 'en_GB';
	protected $faction;
	protected $ranks = array();
	protected $features = array();
	protected $levelRange = array('min' => self::MAXLEVEL,
								  'max' => self::MINLEVEL);
	protected $theme;

	// Emblem Variables
	protected $emblem_path;

	// Static Variables
	private static $params;

	// Public Variables
	private $public_variables = array(
		'locale',
		'faction',
	);


	/**
	 * __construct()
	 *
	 * @access public
	 * @param string $domain
	 * @return void
	 */
	function __construct($domain) 
	{
		$ci =& get_instance();
		$query = $ci->db->query("SELECT 	
								`_id`,
								`region`,
								`realm`,
								`name`,
								`domain_name`,
								`theme`,
								`locale`
						FROM `ug_Guilds`
						WHERE `domain_name` = '$domain'
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

				// Set the emblem path
				$this->emblem_path = APPPATH .'cache/WoW/Guild_emblems/'. strtolower($this->region) .'/'. strformat($this->realm, '_') .'/'. strformat($this->guildName, '_') .'.png'; 

				// Load the full guild from battle.net
				parent::_load(strtolower($this->region), $this->realm, $this->name);

				// Load the levels and ranks
				$this->_set_lowest_level_member();
				$this->_setHighestLevelMember();
				$this->_setRanks();
			}

			// If the data loaded correctly, then save the cache
			if($this->guildData)
			{
				$ci->load->helper('save_file');

				// Encode this object and store it in the cache
				save_file(APPPATH .'cache/uGuilds/WoW/guild_objects/'. $this->domain_name, serialize($this));
			}
		}
		else // No result from the database, this guild must not exist
		{
			show_404('This guild does not exist');
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
				if(isset($this->guildData[$var]))
				{
					return (object) $this->guildData[$var];
				}

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
	 * @var bool $show_level
	 * @var int $width
	 * @return string $url
	 */
	public function get_emblem($show_level = TRUE, $width = 215)
	{
		$emblem_path = '/media/images/guild_emblems/'. strtolower($this->region) .'/'. strformat($this->realm, '_') .'/'. strformat($this->guildName, '_') .'_'. $width .'px.png';
		$fq_emblem_path = rtrim(FCPATH, "/") . $emblem_path;

		if(!file_exists($fq_emblem_path) 
			|| filemtime($fq_emblem_path) + $this->config->item('emblem_ttl', 'battle.net') > time())
		{
			// Determine the true destination
			$directory = explode('/', $fq_emblem_path);
			$directory = array_slice($directory, 0, -1);
			$directory = implode('/', $directory);

			// Make the directory
			if(!is_dir($directory)) mkdir($directory, 0777, true);

			$this->showEmblem($show_level, $width);
			$this->saveEmblem($fq_emblem_path);
		}
		
		return $emblem_path;
	}

	/**
	 * delete_emblem()
	 *
	 * Deletes the cached emblem file and all of the copied emblem files
	 *
	 * @access public
	 * @return void
	 */
	public function delete_emblem()
	{
		if(is_file($this->emblem_path)) unlink($this->emblem_path);

		$files = glob(FCPATH .'media/images/guild_emblems/'. strtolower($this->region) .'/'. strformat($this->realm, '_') .'/'. strformat($this->guildName, '_') .'_*px.png');
		foreach($files as $file)
		{
			unlink($file);
		}
	}

	/**
	 * save_emblem()
	 *
	 * Saves the emblem of a specified size to the public media directory
	 *
	 * @access public
	 * @param (string) $dest - the destination to save to
	 * @return void or error
	 */
	public function save_emblem($dest)
	{
		if(!strstr($dest, FCPATH))
		{
			$dest = FCPATH . $dest;
		}

		return copy($this->emblem_path, $dest);
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
	 * _set_lowest_level_member()
	 *
	 * @access private
	 * @return void
	 */
	private function _set_lowest_level_member()
	{
		// Loop through each of the members
		foreach($this->members as $member)
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
		$highestRank = $this->get_members('rank','desc')[0]->rank;

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


