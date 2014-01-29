<?php namespace uGuilds\Character;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profession extends \BattlenetArmory\Battlenet
{
	// Constants
	const SKILL_MAX = 600;

	// Profession Data
	protected $id;
	protected $name;
	protected $icon;
	protected $rank;
	protected $max;
	protected $recipes = array();

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

	/**
	 * getIcon()
	 *
	 * Gets the Profession's icon and returns the URL
	 *
	 * @access public
	 * @param int $size
	 * @return string
	 */
	public function getIcon($size = 18)
	{
		return parent::getIcon($this->icon, $size);
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
		if(in_array((int) $id, $this->recipes))
		{
			// Get the key
			$key = array_shift(array_keys($this->recipes, (int) $id));

			$this->recipes[$key] = new Profession\Recipe((int) $id);
		}
	}
}