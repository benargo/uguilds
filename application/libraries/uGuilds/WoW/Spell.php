<?php namespace uGuilds\WoW;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Spell extends Battlenet
{
	protected $id;
	protected $name;
	protected $icon;

	// Additional (optional) data
	protected $description;
	protected $power_cost;
	protected $cast_time;
	protected $cooldown;
	protected $range;

	/**
	 * __construct()
	 * 
	 * Creates a spell based on the data provided
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	function __construct( array $data )
	{
		foreach( $data as $key => $datum )
		{
			$key = strtolower( preg_replace( '/([A-Z])/', '_$1', $key ) );
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
	function __get( $param )
	{
		if( property_exists( $this, $param ) )
		{
			return $this->$param;
		}
	}

	/**
	 * getIcon()
	 *
	 * Gets the icon in the specified size and returns the URL
	 *
	 * @access public
	 * @param int $size
	 * @return string
	 */
	public function getIcon( $size = 18 )
	{
		return parent::getIcon( $this->icon, $size );
	}
}
