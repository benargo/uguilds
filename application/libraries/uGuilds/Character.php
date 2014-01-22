<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends \BattlenetArmory\Character 
{
	protected $name;
	protected $class;
	protected $currentTitle;
	protected $guild_rank;
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
	function __construct($name)
	{
		$ci =& get_instance();
		parent::__construct(strtolower($ci->guild->region), $ci->guild->realm, $name, false);
		$this->guild =& $ci->guild;

		$races = new Races;
		$this->race = $races->getRace($this->characterData['race']);

		$classes = new Classes;
		$this->class = $classes->getClass($this->characterData['class']);
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
		switch($param)
		{
			case 'class':
				return $this->class;
				break;

			case 'guild_rank':
				return $this->getGuildRank();
				break;

			case 'name':
				return ucwords($this->name);
				break;

			case 'race':
				return $this->race;
				break;

			case 'realm':
				return ucwords($this->realm);
				break;

			case 'region':
				return strtoupper($this->region);
				break;

			default:
				if(property_exists($this, $param))
				{
					return $this->$param;
				}
				elseif(array_key_exists($param, $this->characterData))
				{
					return $this->characterData[$param];
				}
				break;

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
		if(is_null($this->guild_rank))
		{
			foreach($this->guild->getMembers() as $member)
			{
				if($member->name === $this->name)
				{
					$this->guild_rank = new Character\Rank;
					$this->guild_rank->rank = $member->rank;
					if(isset($member->rankname))
					{
						$this->guild_rank->rank_name = $member->rankname;
					}
				}
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

		if(!file_exists($dest_file)
			|| @filemtime($dest_file) >= $this->config()['CharactersTTL'])
		{
			// Generate the image
			switch($type)
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

			$image = imagecreatefromjpeg($image);

			// Save the image
			$ci =& get_instance();
			$ci->load->helper('save_jpeg');
			save_jpeg($image, $dest_file);
		}

		$dest_file = str_replace(FCPATH, '/', $dest_file);
		return $dest_file;
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
	public function get_spec($type = 'active')
	{
		if(empty($this->specialisations))
		{
			$this->specialisations['primary'] = new Character\Spec($this->characterData['talents'][0], true);
			$this->specialisations['secondary'] = new Character\Spec($this->characterData['talents'][1], false);
		}

		switch($type)
		{
			case 'active':
			default:
				foreach($this->specialisations as $spec)
				{
					if($spec->selected)
					{
						return $spec;
					}
				}
				break;

			case 'passive':
				foreach($this->specialisations as $spec)
				{
					if(!$spec->selected)
					{
						return $spec;
					}
				}
				break;

			case 'primary':
				return $this->specialisations['primary'];
				break;

			case 'secondary':
				return $this->specialisations['secondary'];
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
	public function get_talent_calculator_url($type = 'active')
	{
		return $this->class->talent_calculator_id . $this->get_spec($type)->get_talent_calculator_url();
	}
}
