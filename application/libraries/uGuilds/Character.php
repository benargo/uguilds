<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends \BattlenetArmory\Character 
{
	protected $id;
	protected $name;
	protected $class;
	protected $currentTitle;
	protected $guild_rank;
	protected $professions = array();
	protected $realm;
	protected $race;
	protected $region;
	protected $specialisations = array();

	// Other data
	protected $characterData;

	// Referenced information
	protected $guild;

	/**
	 * __construct()
	 *
	 * @param string $name
	 */
	function __construct($name, $realm = false, $region = false)
	{
		// Load some models
		$ci =& get_instance();
		$ci->load->model('Classes');
		$ci->load->model('Races');

		if(!$realm)
		{
			$realm = $ci->guild->realm;
		}

		if(!$region)
		{
			$region = $ci->guild->region;
		}

		// Construct the character
		parent::__construct(strtolower($region), $realm, $name);

		if(empty($this->characterData['class']))
		{
			return;
		}
		
		// Construct some additional data
		$this->guild =& $ci->guild;

		$this->race  =& $ci->Races->getRace($this->characterData['race']);
		$this->class =& $ci->Classes->getClass($this->characterData['class']);

		$result = $ci->db->query(
			"SELECT id
			FROM ug_Characters
			WHERE region = '". $this->region ."'
				AND realm = '". $this->realm ."'
				AND `name` = '". $this->name ."'
			LIMIT 0, 1");

		if($result->num_rows() === 0)
		{
			$ci->db->query(
				"INSERT INTO ug_Characters
				(region, realm, `name`, guild_id)
				VALUES ('". strtoupper($this->region) ."', '". $this->realm ."', '". $this->name ."', ". $ci->guild->id .")");

			$this->id = $ci->db->insert_id();
		}
		else
		{
			$row = $result->row();
			$this->id = $row->id;
		}
	}

	/**
	 * __get()
	 *
	 * @access public
	 * @param string $param
	 * @return mixed
	 */
	function __get($param)
	{
		switch( $param )
		{
			case 'class':
				return $this->class;
				break;

			case 'guild_rank':
				return $this->getGuildRank();
				break;

			case 'name':
				return ucwords( $this->name );
				break;

			case 'professions':
				return $this->get_professions();

			case 'race':
				return $this->race;
				break;

			case 'realm':
				return ucwords( $this->realm );
				break;

			case 'region':
				return strtoupper( $this->region );
				break;


			default:
				if( property_exists( $this, $param ) )
				{
					return $this->$param;
				}
				elseif( in_array( $param, Character\Profession::keys() ) )
				{
					return $this->get_profession( $param );
				}
				elseif( array_key_exists( $param, $this->characterData ) )
				{
					return $this->characterData[$param];
				}

				break;
		}
	}

	/**
	 * get_achievements_position()
	 *
	 * Gets the character's position in the achievement rankings
	 *
	 * @access public
	 * @return string ordinal (e.g. 1st, 2nd, 3rd)
	 */
	public function get_achievements_position()
	{
		foreach($this->guild->getMembers('achievementPoints', 'desc') as $position => $member)
		{
			if($member->name == $this->name)
			{
				$ci =& get_instance();
				$ci->load->helper('ordinal');

				return ordinal($position + 1);
			}
		}
	}

	/**
	 * getCurrentTitle()
	 *
	 * Returns the users current title, and if we don't know it yet, finds it.
	 *
	 * @access public
	 * @param Boolean $withName: Set to FALSE if you want to use the %s instead of name
   	 * @return A string with the title and name
	 */
	public function getCurrentTitle($withName = true)
	{
		if(empty($this->current_title))
		{
			$this->setTitles();
		}

		return parent::getCurrentTitle($withName);
	}

	/**
	 * getGuildRank()
	 *
	 * Sets the guild rank if we don't know it
	 * and then returns it
	 *
	 * @access protected
	 * @return \uGuilds\Character\Rank object
	 */
	protected function getGuildRank()
	{
		if( is_null( $this->guild_rank ) )
		{
			foreach( $this->guild->getMembers() as $member )
			{
				if( $member->name === $this->name )
				{
					$this->guild_rank = new Character\Rank;
					$this->guild_rank->rank = $member->rank;

					if( isset( $member->rankname ) )
					{
						$this->guild_rank->rank_name = $member->rankname;
					}
				}
			}

			// Failsafe
			if(is_null($this->guild_rank))
			{
				$this->guild_rank = new Character\Rank;
				$this->guild_rank->rank = '?';
			}
		}

		return $this->guild_rank;
	}

	/**
	 * getImageURL
	 *
	 * Returns the picture of the character, before caching it and storing it ready for display
	 *
	 * @access public
	 * @param string $type: one of 'thumbnail' (default), 'pic' or 'inset'
	 * @return string: url of the cached image
	 */
	public function getImageURL($type = 'thumbnail')
	{
		$dest_file = FCPATH .'media/images/characters/'
					. strformat($this->region) .'/'
					. strformat($this->realm, '_') .'/'
					. strformat($this->guild->name, '_') .'/'
					. strformat($this->name) .'_'. $type .'.jpg';

		if( !file_exists( $dest_file )
			|| @filemtime( $dest_file ) >= $this->config()['CharactersTTL'])
		{
			// Generate the image
			switch( $type )
			{
				case 'thumbnail':
				default:
					$image = parent::getThumbnailURL();
					break;

				case 'picture':
					$image = parent::getProfilePicURL();
					break;

				case 'inset';
					$image = parent::getProfileInsetURL();
					break;
			}

			$image = imagecreatefromjpeg( $image );

			// Save the image
			$ci =& get_instance();
			$ci->load->helper( 'save_jpeg' );
			save_jpeg( $image, $dest_file );
		}

		$dest_file = str_replace( FCPATH, '/', $dest_file );

		return $dest_file;
	}

	/**
	 * get_professions()
	 *
	 * Gets an array containing all the Character's professions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function get_professions()
	{
		if( empty( $this->professions ) )
		{
			$this->_set_professions();
		}

		return $this->professions;
	}

	/**
	 * get_profession()
	 *
	 * Gets a single profession
	 *
	 * @access public
	 * @param string $key
	 * @return \uGuilds\Character\Profession object
	 */
	public function get_profession( $key )
	{
		if( empty( $this->professions ) )
		{
			$this->_set_professions();
		}

		if( array_key_exists( strformat( $key ), $this->professions ) )
		{
			return $this->professions[ strformat( $key ) ];
		}
	}

	/**
	 * _set_professions()
	 *
	 * @access protected
	 * @return void
	 */
	private function _set_professions()
	{
		if( empty( $this->professions ) )
		{
			if( is_array( $this->characterData['professions']['primary'] ) )
			{
				foreach( $this->characterData['professions']['primary'] as $profession )
				{
					$this->professions[ strformat( $profession['name'] ) ] = new Character\Profession( $profession );
				}
			}

			if( is_array( $this->characterData['professions']['secondary'] ) )
			{
				foreach( $this->characterData['professions']['secondary'] as $profession )
				{
					$this->professions[ strformat( $profession['name'] ) ] = new Character\Profession( $profession );
				}	
			}
		}
	}

	/**
	 * get_spec()
	 *
	 * Gets the Character's specialisation. A choice of 'active' (default), 'passive,' 'primary,' or 'secondary.'
	 *
	 * @access public
	 * @param string $type: 'active'/'primary'/'secondary'
	 * @return Spec object
	 */ 
	public function get_spec( $type = 'active' )
	{
		if( empty( $this->specialisations ) )
		{
			if(!empty($this->characterData['talents'][0]['spec']))
			{
				$this->specialisations['primary'] = new Character\Spec( $this->characterData['talents'][0], true );
			}
			if(!empty($this->characterData['talents'][1]['spec']))
			{
				$this->specialisations['secondary'] = new Character\Spec( $this->characterData['talents'][1], false );
			}		
		}

		switch($type)
		{
			case 'active':
			default:
				foreach( $this->specialisations as $spec )
				{
					if( $spec->selected )
					{
						return $spec;
					}
				}
				break;

			case 'passive':
				foreach( $this->specialisations as $spec )
				{
					if( !$spec->selected )
					{
						return $spec;
					}
				}
				break;

			case 'primary':
				return (isset($this->specialisations['primary']) ? $this->specialisations['primary'] : false);
				break;

			case 'secondary':
				return (isset($this->specialisations['secondary']) ? $this->specialisations['secondary'] : false);
				break;
		}
	}

	/**
	 * get_talent_calculator_url()
	 *
	 * Gets the Character's talent calculator URL, 
	 * for either their 'active' (default), 'primary,' or 'secondary' spec
	 *
	 * @access public
	 * @param string $type: : 'active'/'primary'/'secondary'
	 * @return string
	 */
	public function get_talent_calculator_url( $type = 'active' )
	{
		return $this->class->talent_calculator_id . $this->get_spec( $type )->get_talent_calculator_url();
	}
}
