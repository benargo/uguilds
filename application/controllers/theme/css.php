<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* \uGuilds\controllers\theme\css */

class Css extends UG_Controller {

	/**
	 * __construct()
	 *
	 * Calls the parent's constructor, then sets the content type
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		header('Content-Type: text/css');
		parent::__construct();
	}

	/**
	 * index()
	 *
	 * Get all the themes core CSS files
	 * @access public
	 * @return string
	 */
	public function index()
	{
		foreach($this->theme->getCssFiles() as $file)
		{
			echo file_get_contents($this->theme->getPath(true).'/css/'.$file);
		}
	}

}