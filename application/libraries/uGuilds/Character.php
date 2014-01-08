<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends \BattlenetArmory\Character {

	protected $currentTitle;
	protected $guild;

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
			case 'currentTitle':
				return $this->getCurrentTitle();
				break;

			case 'name':
				return ucwords($this->name);
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
	 * get_current_title()
	 *
	 * Returns the users current title, and if we don't know it yet, finds it.
	 *
	 * @access private
	 * @return string
	 */
	public function getCurrentTitle()
	{
		if(empty($this->currentTitle))
		{
			$this->setTitles();
		}

		$this->currentTitle = (object) $this->currentTitle;

		return $this->currentTitle;
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
