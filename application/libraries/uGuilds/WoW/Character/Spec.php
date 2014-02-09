<?php namespace uGuilds\WoW\Character;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'uGuilds\WoW\Character\Spec' class
 *
 * This class handles a Character's specialisation
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1.  Spec variables
 * 2.  Talent calculator variables
 * 3.  Talents & Glyphs
 *
 * 4.  __construct()
 * 5.  get_icon()
 * 6.  getIcon()
 * 7.  get_talent()
 * 8.  get_talent_calculator_url()
 * 9.  is_active()
 * 10. _sort_glyphs()
 * 11. _sort_talents()
 */
class Spec extends \uGuilds\WoW\Battlenet
{
	// Spec variables
	protected $name;
	protected $role;
	protected $backgroundImage;
	protected $icon;
	protected $description;
	protected $order; // 0, 1 or 2

	protected $primary;
	protected $selected = false;

	// Talent Calculator variables
	protected $calcSpec;
	protected $calcTalent;
	protected $calcGlyph;

	// Talents & Glyphs
	protected $talents = array();
	protected $glyphs = array();

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
				case 'glyphs':
					$this->_sort_glyphs($datum);
					break;

				case 'spec': // Spec data
					foreach($datum as $key => $value)
					{
						$this->$key = $value;
					}
					break;

				case 'selected':
					$this->selected = (bool) $datum;
					break;

				case 'talents':
					$this->_sort_talents($datum);
					break;

				default:
					$this->$key = $datum;
			}
		}
	}

	/**
	 * get_icon()
	 *
	 * Gets the Spec's icon and returns the URL
	 *
	 * @access public
	 * @param int $size
	 * @return string
	 */
	public function get_icon($size = 18)
	{
		return parent::get_icon($this->icon, $size);
	}

	/**
	 * getIcon()
	 *
	 * @see get_icon()
	 *
	 * @access public
	 * @param int $size
	 * @return string
	 */
	public function getIcon($size = 18)
	{
		return $this->get_icon($size);
	}

	/**
	 * get_talent()
	 *
	 * Gets a specific talent based on the tier provided
	 * and returns it as an array
	 * 
	 * @access public
	 * @param int $tier
	 * @return array
	 */
	public function get_talent($tier)
	{
		return $this->talents[$tier];
	}

	/**
	 * get_talent_calculator_url()
	 *
	 * Concatenates the Talent Calculator URL and returns it.
	 *
	 * @access public
	 * @return string
	 */
	public function get_talent_calculator_url()
	{
		return $this->calcSpec .'!'. $this->calcTalent .'!'. $this->calcGlyph;
	}

	/**
	 * is_active()
	 *
	 * Returns whether this spec is the active one
	 *
	 * @access public
	 * @return bool
	 */
	public function is_active()
	{
		return (bool) $this->selected;
	}

	/**
	 * _sort_glyphs()
	 * 
	 * Sorts an array of glyphs and populates this class accordingly
	 *
	 * @access private
	 * @param array $data
	 * @return void
	 */
	private function _sort_glyphs(array $data)
	{
		foreach($data as $type => $glyphs)
		{
			$this->glyphs[$type] = array();

			foreach($glyphs as $glyph)
			{
				$this->glyphs[$type][] = new Spec\Glyph($glyph);
			}
		}
	}

	/**
	 * _sort_talents()
	 *
	 * Sorts an array of talents and populates this class accordingly
	 *
	 * @access private
	 * @param array $data
	 * @return void
	 */
	private function _sort_talents(array $data)
	{
		foreach($data as $datum)
		{
			if($datum)
			{
				$this->talents[$datum['tier']] = new Spec\Talent($datum);
			}
		}

		ksort($this->talents);
	}
}
