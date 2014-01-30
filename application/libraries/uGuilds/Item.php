<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends \BattlenetArmory\Item
{
	protected $id;
	protected $description;
	protected $name;
	protected $icon;
	protected $stackable;
	protected $allowableClasses = array();

	// Inherited
	protected $itemData;
	protected $statID;
	protected $cache;
	protected $iconbaseURL = '.media.blizzard.com/wow/icons/';
	protected $region;
	protected $icon_extension = '.jpg';

	/**
	 * __construct()
	 * 
	 * Creates a spell based on the data provided
	 *
	 * @access public
	 * @param int $id
	 * @return void
	 */
	function __construct( $id )
	{
		$ci =& get_instance();

		parent::__construct( strtolower( $ci->guild->region ), $id );

		foreach( $this->itemData as $key => $datum )
		{
			$this->$key = $datum;
		}

		unset( $this->itemData );

		foreach( $this->allowableClasses as $key => $class_id )
		{
			$this->allowableClasses[ $key ];
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