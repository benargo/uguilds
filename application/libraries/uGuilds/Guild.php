<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Guild
 *
 * @author Ben Argo <ben@benargo.com>
 */

class Guild extends \BattlenetArmory\Guild {

	const MINLEVEL = 1;
	const MAXLEVEL = 100;

	private $_id;
	private $domainName;
	private $theme;
	private $locale = 'en_GB';
	private $faction;
	private $ranks = array();
	private $features = array();
	private $levelRange = array( 'min' => self::MAXLEVEL,
								 'max' => self::MINLEVEL );

	/**
	 * __construct()
	 *
	 * @access public
	 * @param string $domain
	 * @return void
	 */
	function __construct($domain = NULL) 
	{
		if($domain)
		{
			$this->_load($domain);
		}
	}

	/**
	 * __get()
	 *
	 * @param $var
	 * @access public
	 * @return mixed
	 */
	function __get( $var )
	{
		switch($var)
		{
			case "id":
				return $this->_id;
				break;

			case "region":
				return strtoupper( $this->region );
				break;

			case "realm":
				return ucwords( $this->realm );
				break;

			case "guildName": /* preferred */
			case "name":
				return ucwords( $this->name );
				break;

			case "domainName":
				return strtolower( preg_replace( "![^a-z0-9\-\.]+!i", "-", $this->domainName ) );
				break;

			case "ranks":
				if( empty( $this->ranks ) )
				{
					$this->_setRanks();
				}

				return $this->ranks;
				break;

			case "theme":
				return $this->theme;
				break;

			case "locale":
				if( !preg_match( "/([a-z]+)_([A-Z]+)/", $this->locale ) )
				{
					$this->locale = 'en_GB';
				}

				return $this->locale;
				break;

			case "features":
				return $this->getFeatures();
				break;

			case "data":
				return $this->getData();
				break;
		}
	}

	/**
	 * instance()
	 * 
	 * @access public
	 * @static true
	 * @return \uGuilds\Guild object
	 */
	public static function instance()
	{
		$ci =& get_instance();
		return $ci->uguilds->guild;
	}

	/**
	 * _load()
	 *
	 * @access private
	 * @param string $domain
	 * @return uGuilds\Guild object
	 */
	protected function _load($domain)
	{
		$ci =& get_instance();

		$query = $ci->db->query( "SELECT 	
								`_id`,
								`region`,
								`realm`,
								`name`,
								`domain_name`,
								`theme`,
								`locale`
						FROM `ug_Guilds`
						WHERE `domain_name` = '$domain'
						LIMIT 0, 1" );

		// Check we got a result
		if( $query->num_rows() > 0 )
		{
			// Loop through the rows (there should only be one)
  			foreach ( $query->result() as $row )
   			{
	   			// Loop through the columns
	    		foreach( $row as $key => $value )
	    		{
	    			$this->$key = $value;
	    		}

	    		// Set the session
	    		$ci->session->set_userdata( 'guild_id', $this->_id );

	    		// Load the full guild from battle.net
	    		parent::_load( strtolower( $this->region ), $this->realm, $this->name );

	    		// Load the levels and ranks
	    		$this->_setLowestLevelMember();
	    		$this->_setHighestLevelMember();
	    		$this->_setRanks();
   			}

   			// Encode this object and store it in the cache
   			file_put_contents( APPPATH .'cache/uGuilds/guild_objects/'. $this->domainName .'.txt', serialize( $this ) );
		}
		else // No result from the database, this guild must not exist
		{
			throw new \Exception( 'This guild does not exist' );
		}
	}

	/**
	 * showEmblem()
	 *
	 * @access public
	 * @var bool $showlevel
	 * @var int $width
	 * @return string $url
	 */
	public function getEmblem( $showlevel = TRUE, $width = 215 )
	{
		if( !file_exists( FCPATH .'media/BattlenetArmory/emblem_'. strtoupper( $this->region ) .'_'. preg_replace( '/\ /', '_', $this->realm ) .'_'. preg_replace( '/\ /', '_', $this->guildName ) .'_'. $width .'.png' ) )
		{
			$this->showEmblem( $showlevel, $width );
			$this->saveEmblem( FCPATH . 'media/BattlenetArmory/emblem_'. strtoupper( $this->region ) .'_'. preg_replace( '/\ /', '_', $this->realm ) .'_'. preg_replace( '/\ /', '_', $this->guildName ) .'_'. $width .'.png' );
		}
		
		return '/media/BattlenetArmory/emblem_'. strtoupper( $this->region ) .'_'. preg_replace( '/\ /', '_', $this->realm ) .'_'. preg_replace( '/\ /', '_', $this->guildName ) .'_'. $width .'.png';
	}

	/**
	 * getFaction()
	 *
	 * @access public
	 * @return text
	 */
	public function getFaction()
	{
		if( is_null( $this->faction ) )
		{
			$this->faction = $this->getData()[ 'side' ];
		}

		switch( $this->faction )
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
	 * _getFeatures()
	 * 
	 * @access private
	 * @return array
	 */
	private function _getFeatures()
	{
		$return = array();

		foreach( $this->features as $key => $value )
		{
			if( $value )
			{
				$return[ $key ] = (bool) $value;
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
		return array_key_exists( $feature, $this->_getFeatures() );
	}

	/**
	 * getMembers
	 * 
	 * @access public
	 * @param string $sort
	 * @param string $sortFlag
	 * @return array
	 */
	public function getMembers($sort = FALSE, $sortFlag = 'asc')
	{
		$members = parent::getMembers( $sort, $sortFlag );

		foreach( $members as $key => $member )
		{
			$members[ $key ] = (object) $member;
			foreach( $member['character'] as $trait => $value)
			{
				if( $trait == 'spec' )
				{
					$value = (object) $value;
				}

				$members[ $key ]->$trait = $value;
			}
			unset( $members[ $key ]->character );
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
	public function filter( array $params = array() )
	{
		$this->params = $params;
		$members = array_values( array_filter( $this->getMembers(), array( $this, '_filter' ) ) );
		unset( $this->params );
		return $members;
	}

	/**
	 * _filter()
	 * 
	 * @access private
	 * @param array $member
	 * @return array
	 */
	private function _filter( $member )
	{
		foreach( $this->params as $key => $value )
		{
			if( $member->$key == $value )
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * getLowestLevelMember()
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
