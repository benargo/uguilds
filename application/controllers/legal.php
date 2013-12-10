<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Legal extends UG_Controller {

	/**
	 * Construction function
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_setPageAuthor('uGuilds');
	}

	public function terms()
	{
		$this->_setPageTitle('Terms &amp; Conditions of Service');
		$this->_loadHeader();

		$this->load->view('controllers/Legal/Terms');
	}

	public function privacy()
	{
		$this->_setPageTitle('Privacy Policy');
		$this->_loadHeader();

		$this->load->view('controllers/Legal/Privacy');
	}

}