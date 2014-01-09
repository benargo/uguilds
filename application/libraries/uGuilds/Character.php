<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends \BattlenetArmory\Character 
{
	protected $currentTitle;
	protected $guild;
	protected $guildRank;
	protected $race;
	protected $class;

	/**
	 * __construct()
	 *
	 * @param string $name
	 */
	function __construct($name)
	{
		$ci =& get_instance();
		$this->guild =& $ci->guild;
		parent::__construct(strtolower($ci->guild->region), $ci->guild->realm, $name, false);
		$this->race = new Races;
		$this->class = new Classes;
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
			case 'name':
				return ucwords($this->name);
				break;

			case 'class':
				return $this->getClass();
				break;

			case 'guildRank':
				return $this->getGuildRank();
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
	 * getClass()
	 *
	 * Returns the characters class
	 *
	 * @access public
	 */
	public function getClass()
	{
		return $this->class->getClass($this->characterData['class']);
	}

	/**
	 * getCurrentTitle()
	 *
	 * Returns the users current title, and if we don't know it yet, finds it.
	 *
	 * @access private
	 * @param Boolean $withName Set to FALSE if you want to use the %s instead of name
   	 * @return A string with the title and name
	 */
	public function getCurrentTitle($withName = true)
	{
		if(empty($this->currentTitle))
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
	 */
	public function getGuildRank()
	{
		if(is_null($this->guildRank))
		{
			foreach($this->guild->getMembers() as $member)
			{
				if($member->name === $this->name)
				{
					$this->guildRank = new Character\Rank;
					$this->guildRank->rank = $member->rank;
					if(isset($member->rankname))
					{
						$this->guildRank->rankName = $member->rankname;
					}
				}
			}
		}

		return $this->guildRank;
	}

	/**
	 * getImageURL
	 *
	 * Returns the picture of the character, before caching it and storing it ready for display
	 *
	 * @access public
	 * @param string $type: one of 'avatar' (default), 'pic' or 'inset'
	 * @return string: url of the cached image
	 */
	public function getImageURL($type = 'avatar')
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
			$function_name = 'getProfile'. ucwords($type) .'URL';
			$image = parent::{$function_name}();
			$image = imagecreatefromjpeg($image);

			// Save the image
			$ci =& get_instance();
			$ci->load->helper('save_jpeg');
			save_jpeg($image, $dest_file);
		}

		$dest_file = str_replace(FCPATH, '/', $dest_file);
		return $dest_file;
	}

}
