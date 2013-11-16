<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Theme
 *
 * @author Ben Argo <ben@benargo.com>
 */

class ThemeData {

	/**
	 * Variables
	 */
	private $page_title;
	private $page_author;
	private $custom_params = array();
	
	/**
	 * __construct()
	 *
	 * @access public
	 */
	function __construct()
	{

	}

	/**
	 * __get()
	 * 
	 * @param string $var
	 * @access public
	 * @see get()
	 */
	function __get($var)
	{
		return $this->get($var);
	}

	/**
	 * get()
	 *
	 * @param string $vars
	 * @access private
	 */
	private function get($var)
	{
		// Check if the property exists already
		if(property_exists($this, $var))
		{
			return $this->$var;
		}

		// Check if it exists in the custom_params property
		if(isset($this->custom_params[$var]))
		{
			return $this->custom_params[$var];
		}

		return false;
	}

	/**
	 * __set()
	 *
	 * @param string/array $key
	 * @param mixed $value
	 * @access public
	 * @see set()
	 */
	function __set($key, $value = NULL)
	{
		$this->set($key, $value);
	}

	/**
	 * set()
	 *
	 * @param string $key
	 * @param mixed $value
	 * @access private
	 */
	private function set($key, $value = NULL)
	{
		if(property_exists($this, $key))
		{
			$this->$key = $value;
			return;
		}

		$this->custom_params[$key] = $value;
	}

	/**
	 * getController()
	 * 
	 * @param string $name
	 * @access public
	 * @return string
	 * @see uGuilds->getController()
	 */
	public function getController($name)
	{
		$ci =& get_instance();
		return $ci->uguilds->getController($name);
	}

}