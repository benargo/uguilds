<?php namespace uGuilds\Character\Spec;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Talent extends \BattlenetArmory\Battlenet
{
	// Talent data
	private $tier;
	private $level;
	private $column;
	private $spell;

	// Other talent data
	private $talent_levels = array(
		// Tier => Level
		0 => 15,
		1 => 30,
		2 => 45,
		3 => 60,
		4 => 75,
		5 => 90,
		6 => 100);

	/**
	 * __construct()
	 * 
	 * Constructs the talent using data provided as an array
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	function __construct( array $data )
	{
		// Loop through the data
		foreach( $data as $key => $datum )
		{
			$this->$key = $datum;
		}

		$this->level = $this->talent_levels[ $this->tier ];

		// Handle the spell
		$this->spell = new \uGuilds\Spell( $this->spell );
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
	function __get( $param )
	{
		if( property_exists( $this, $param ) )
		{
			return $this->$param;
		}
	}
}
