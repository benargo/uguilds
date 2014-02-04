<?php

/**
 * 'uGuilds' class unit test
 * 
 * Tests all the functionality of the major uGuilds master class
 *
 * @author Ben Argo <ben@benargo.com>
 * @copyright Copyright 2013 Ben Argo
 * @license MIT
 * @version 1.0
 *
 ** Table of Contents
 * 1. __construct()
 * 2. test_domain()
 */

class Master extends UG_Controller
{
	private $uGuilds;

	/**
	 * __construct()
	 *
	 * Loads the Unit Test library from CodeIgniter 
	 * and checks that we're on the development environment only
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		if(ENVIRONMENT !== 'development' || !defined(ENVIRONMENT))
		{
			// Show a 404 error if we're not in the development environment
			show_404($this->uri->uri_string());
		}

		$this->load->library('unit_test');
	}

	/**
	 * test_domain()
	 *
	 * Tests uGuilds->domain
	 *
	 * @access public
	 * @return echo true of false
	 */
	public function test_domain()
	{
		echo $this->unit->run($this->uGuilds->domain, 'is_string');
	}
}

