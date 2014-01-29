<?php namespace uGuilds\Character\Profession;

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'libraries/uGuilds/Spell.php');

class Recipe extends \uGuilds\Spell
{
	// Recipe data
	protected $id;
	protected $icon;
	protected $name;
	protected $profession;

	/**
	 * __construct()
	 *
	 * Initialise the class
	 *
	 * @access public
	 * @param int $id
	 * @return void
	 */
	function __construct( $id )
	{
		/**
		 * The battle net library we're using doesn't provide a method
		 * of loading and caching recipes, so we have to do it.
		 */
		if( !file_exists( APPPATH .'cache/WoW/Recipes/'. (int) $id ) )
		{
			
		}
	}	
}