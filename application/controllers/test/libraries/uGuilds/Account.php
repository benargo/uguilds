<?php

/**
 * 'uGuilds\Account' class unit test
 * 
 * Tests all the functionality of the uGuilds\Account class
 *
 * This library uses CodeIgniter's Unit Testing: 
 * @link http://ellislab.com/codeigniter/user-guide/libraries/unit_testing.html
 *
 * @author Ben Argo <ben@benargo.com>
 * @copyright Copyright 2013 Ben Argo
 * @license MIT
 * @version 1.0
 *
 ** Table of Contents
 * 1. __construct()
 * 2. index()
 */

class Account extends UG_Controller
{
	/* Variables */
	private $account;

	/**
	 * __construct()
	 *
	 * Initialises the class with some basic parameters and settings
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

		// Load the unit test library
		$this->load->library('unit_test');
	}

	/**
	 * index()
	 *
	 * Runs through all the tests
	 *
	 * @access public
	 * @return echoed output
	 */
	public function index()
	{

	}

	/**
	 * test_load()
	 *
	 * Tests the static function load()
	 *
	 * @access private
	 * @return true or false
	 */
	private function test_load()
	{
		/**
		 * Find account using email address 'test@uguilds.net'
		 * Expected result: true
		 */
		$this->unit->run(uGuilds\Account::load('test@uguilds.net', true) instanceof uGuilds\Account, true, 'Find account using email address \'test@uguilds.net\'');
		
		/**
		 * Find account using fake email address 'test@example.org'
		 * Expected result: false
		 */
		$this->unit->run(uGuilds\Account::load('test@example.org', true), false, 'Find account using fake email address \'test@example.org\'');

		/**
		 * Find account using email address 'test@uguilds.net' but the function is expecting an account ID integer
		 * Expected result: false
		 */
		$this->unit->run(uGuilds\Account::load('test@uguilds.net', false), false, 'Find account using email address \'test@uguilds.net\' but the function is expecting an account ID integer');

		/**
		 * Find account using ID number for the test account
		 * Expected result: true
		 */
		$this->unit->run(uGuilds\Account::load(uGuilds\Account::load('test@uguilds.net', true)->id, false) instanceof uGuilds\Account, true, 'Find account using ID number for the test account');

		/**
		 * Find account using an ID number that doesn't exist
		 * Expected result: false
		 */
		$this->unit->run(uGuilds\Account::load(0, false), false, 'Find account using an ID number that doesn\'t exist');

		// Any others?
	}

	/**
	 * test_change_email()
	 *
	 * Tests that we can change the email address of the test account
	 *
	 * @access private
	 * @return true or false
	 */
	private function test_change_email()
	{

	}
}

