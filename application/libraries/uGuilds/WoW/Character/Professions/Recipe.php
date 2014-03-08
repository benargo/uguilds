<?php namespace uGuilds\WoW\Character\Profession;

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'libraries/uGuilds/WoW/Spell.php');

class Recipe extends \uGuilds\WoW\Battlenet
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
		 * The battle.net library we're using doesn't provide a method
		 * of loading and caching recipes, so we have to do it.
		 */
		if( !file_exists( APPPATH .'cache/WoW/Recipes/'. (int) $id ) )
		{
			$curl = new \uGuilds\Curl( 'recipe/' . (int) $id );
			$recipe = $curl->fetch_json( true );

			file_put_contents( APPPATH .'cache/WoW/Recipes/'. (int) $id, serialize( $recipe ) );

			// Close the Curl
			$curl->close();
		}
		else
		{
			$recipe = unserialize(file_get_contents( APPPATH .'cache/WoW/Recipes/'. (int) $id ));
		}

		foreach($recipe as $key => $value)
		{
			$this->$key = $value;
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
	 * Gets the Profession's icon and returns the URL
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