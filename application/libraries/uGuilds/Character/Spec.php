<?php namespace uGuilds\Character;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Spec 
{
	// Spec data
	private $name;
	private $role;
	private $backgroundImage;
	private $icon;
	private $description;
	private $order; // 0, 1 or 2

	private $primary;
	private $selected;

	// Talent Calculator data
	private $calcTalent;
	private $calcSpec;
	private $calcGlyph;

	// Talents & Glyphs
	private $talents;
	private $glyphs;

	/**
	 * __construct()
	 *
	 * Initialise the class
	 *
	 * @access public
	 * @param array $data
	 * @param bool $primary
	 * @return void
	 */
	function __construct(array $data, $primary = false)
	{
		// Is this spec the primary spec?
		$this->primary = (bool) $primary;

		// Construct the data
		foreach($data as $key => $datum)
		{
			switch($key)
			{
				case 'spec': // Spec data
					foreach($datum as $key => $value)
					{
						$this->$key = $value;
					}
					break;

				case 'selected':
					$this->selected = (bool) $datum;

				default:
					$this->$key = $datum;
			}
		}
	}

	/**
	 * __get()
	 *
	 * Gets params
	 *
	 * @access public
	 * @param string $param
	 * @return mixed
	 */
	function __get($param)
	{
		if(property_exists($this, $param))
		{
			return $this->$param;
		}
	}
}