<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Theme
 *
 * @author Ben Argo <ben@benargo.com>
 */
class Theme {

	/**
	 * variables
	 */
	private $name;

	/**
	 * __construct
	 *
	 * @param string $theme_id
	 * @return void
	 */
	public function __construct($theme_name)
	{
		$this->name = $theme_name;
	}

	/**
	 * validateXML
	 *
	 * @return bool
	 */
	private function validateXML() {

	}

	/**
	 * parseXML
	 *
	 * @param \SimpleXMLElement $xml
	 * @return void
	 */
	private function parseXML(\SimpleXMLElement $xml) {

	}
}