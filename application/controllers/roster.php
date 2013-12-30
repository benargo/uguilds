<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roster extends UG_Controller {

	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->theme->data(array('page_title' => 'Guild Roster',
					  			 'author' => $this->guild->name));
		$this->theme->getIncludes();
	}

	/**
	 * index()
	 *
	 * @access public
	 * @see all()
	 */
	public function index()
	{
		$this->all();
	}

	/**
	 * all()
	 *
	 * @access public
	 */
	public function all()
	{
		$races = new uGuilds\Races(strtolower($this->guild->region));
		$classes = new uGuilds\Classes(strtolower($this->guild->region));	

		$this->theme->data(array("races"   => $races,
							 "classes" => $classes,
							 "members" => $this->guild->getMembers('rank'),
							 "ranks"   => $this->guild->ranks));

		$this->theme->data(array("content" => $this->load->view('controllers/Roster/header', $this->theme->data(), true)
							 			. $this->load->view('controllers/Roster/list', $this->theme->data(), true)));

		$this->theme->view('page');
	}

}