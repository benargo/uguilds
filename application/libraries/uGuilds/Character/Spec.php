<?php namespace uGuilds\Character;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Spec extends \BattlenetArmory\Battlenet
{
	// Spec data
	private $name;
	private $role;
	private $backgroundImage;
	private $icon;
	private $description;
	private $order; // 0, 1 or 2

	private $primary;
	private $selected = 02;

	// Talent Calculator data
	private $calcSpec;
	private $calcTalent;
	private $calcGlyph;

	// Talents & Glyphs
	private $talents = array();
	private $glyphs = array();

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
	function __construct( array $data, $primary = false )
	{
		// Is this spec the primary spec?
		$this->primary = (bool) $primary;

		// Construct the data
		foreach( $data as $key => $datum )
		{
			switch( $key )
			{
				case 'glyphs':
					$this->_sort_glyphs( $datum );
					break;

				case 'spec': // Spec data
					foreach( $datum as $key => $value )
					{
						$this->$key = $value;
					}
					break;

				case 'selected':
					$this->selected = (bool) $datum;
					break;

				case 'talents':
					$this->_sort_talents( $datum );
					break;

				default:
					$this->$key = $datum;
			}
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
	 * Gets the Spec's icon and returns the URL
	 *
	 * @access public
	 * @param int $size
	 * @return string
	 */
	public function getIcon( $size = 18 )
	{
		return parent::getIcon( $this->icon, $size );
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
	public function get_talent( $tier )
	{
		return $this->talents[ $tier ];
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
	private function _sort_glyphs( array $data )
	{
		foreach( $data as $type => $glyphs )
		{
			$this->glyphs[ $type ] = array();

			foreach( $glyphs as $glyph )
			{
				$this->glyphs[ $type ][] = new Spec\Glyph( $glyph );
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
	private function _sort_talents( array $data )
	{
		foreach( $data as $datum )
		{
			if($datum)
			{
				$this->talents[ $datum['tier'] ] = new Spec\Talent( $datum );
			}
		}

		ksort( $this->talents );
	}
}
