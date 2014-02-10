<?php namespace uGuilds\WoW\Character;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 'uGuilds\WoW\Character\Profession' class
 *
 * This class handles a Character's professions
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1.  Constants
 * 2.  Profession Variables
 * 3.  Keys
 *
 * 4.  __construct()
 * 5.  keys()
 * 6.  get_icon()
 * 7.  getIcon()
 * 8.  get_percentage()
 * 9.  get_recipe()
 * 10. get_recipes()
 * 11. has_recipes()
 */
class Profession extends \uGuilds\WoW\Battlenet
{
	// Constants
	const SKILL_MAX = 600;

	// Profession Variables
	protected $id;
	protected $name;
	protected $icon;
	protected $rank;
	protected $max;
	protected $recipes = array();

	// Keys
	private static $keys = array(

		// Primary
		'alchemy',
		'blacksmithing',
		'enchanting',
		'engineering',
		'herbalism',
		'inscription',
		'jewelcrafting',
		'leatherworking',
		'mining',
		'skinning',
		'tailoring',

		// Secondary
		'archaeology',
		'cooking',
		'first_aid',
		'fishing'
	);

	/**
	 * __construct()
	 * 
	 * Initialise the class
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	function __construct(array $data)
	{
		foreach($data as $key => $datum)
		{
			$this->$key = $datum;
		}
	}

	/**
	 * keys()
	 *
	 * Returns the array of profession keys
	 *
	 * @access public
	 * @static true
	 * @return array
	 */
	public static function keys()
	{
		return self::$keys;
	}

	/**
	 * get_icon()
	 *
	 * Gets the Profession's icon and returns the URL
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
	 * get_percentage()
	 * 
	 * Gets the Profession's skill as a percentage of the maximum
	 *
	 * @access public
	 * @return integer
	 */
	public function get_percentage()
	{
		$percentage = $this->rank / self::SKILL_MAX * 100;

		if($percentage > 100)
		{
			$percentage = 100;
		}

		return $percentage;
	}

	/**
	 * get_recipe()
	 *
	 * Gets a specified recipe, and returns a Profession\Recipe object
	 *
	 * @access public
	 * @param int $id
	 * @return Profession\Recipe object
	 */
	public function get_recipe($id)
	{
		if( in_array((int) $id, $this->recipes))
		{
			// Get the key
			$key = array_shift(array_keys($this->recipes, (int) $id));

			$this->recipes[(int) $id] = new Profession\Recipe((int) $id);

			unset($this->recipes[$key]);
		}

		return $this->recipes[(int) $id];
	}

	/**
	 * get_recipes()
	 * 
	 * Gets all the recipes, and returns a Profession\Recipe object for each
	 *
	 * @access public
	 * @return array
	 */
	public function get_recipes()
	{
		foreach($this->recipes as $key => $recipe)
		{
			if(is_int($recipe))
			{
				$this->recipes[$recipe] = new Profession\Recipe($recipe);

				unset($this->recipes[$key]);
			}
		}

		return $this->recipes;
	}

	/**
	 * has_recipes()
	 *
	 * Determines if the profession has recipes attached to it
	 *
	 * @access public
	 * @return bool
	 */
	public function has_recipes()
	{
		return (bool) !empty($this->recipes);
	}
}
