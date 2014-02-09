<?php namespace uGuilds\WoW;

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
	function __construct($id)
	{
		$ci =& get_instance();

		parent::__construct(strtolower($ci->guild->region), $id);

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
	function __get($param)
	{
		switch($param)
		{
			case 'icon':
				return $this->get_icon(18);
				break;

			default:
				if(property_exists($this, $param) && $this->$param !== NULL)
				{
					return $this->$param;
				}
				break;
		}
	}

	/**
	 * get_icon()
	 *
	 * Gets the icon in the specified size and returns the URL
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
}

