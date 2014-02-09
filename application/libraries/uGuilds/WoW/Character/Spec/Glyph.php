<?php namespace uGuilds\WoW\Character\Spec;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Glyph extends \uGuilds\WoW\Battlenet
{
	private $id;
	private $name;
	private $icon;
	private $item;

	/**
	 * __construct()
	 * 
	 * Constructs the glyph using data provided as an array
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	function __construct(array $data)
	{
		// Loop through the data
		foreach($data as $key => $datum)
		{
			if($key == 'glyph')
			{
				$key = 'id';
			}

			$this->$key = $datum;
		}

		$this->item = new \uGuilds\WoW\Item((int) $this->item);
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
