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
		$this->_setPageTitle('Guild Roster');
		$this->_setPageAuthor($this->uguilds->guild->guildName);

		// Load the header
		$this->_loadHeader();

		$races = new uGuilds\Races(strtolower($this->uguilds->guild->region));
		$classes = new uGuilds\Classes(strtolower($this->uguilds->guild->region));	

		$custom_data = array("races"   => $races,
							 "classes" => $classes,
							 "members" => $this->uguilds->guild->getMembers('rank'));

		// Load the roster table header and filter system
		$this->load->view('controllers/Roster/header.php', $this->data($custom_data));

		// Load the roster list
		$this->load->view('controllers/Roster/list.php', $this->data($custom_data));
		unset($races, $classes, $custom_data);

		// Load the footer
		$this->_loadFooter();
	}

}