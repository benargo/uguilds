<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Character extends UG_Controller {

	protected $character;

	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->character = new uGuilds\Character($this->uri->segments[2]);
		$this->theme->data(array(
			'page_title' => $this->character->name .' - '. $this->character->realm,
			'author' => $this->guild->name,
			'character_name' => $this->character->currentTitle->name_with_name,
			'inset_image' => $this->character->getImageURL('inset')
		));
	}

	/**
	 * index()
	 *
	 * Index page for the character
	 */
	public function index()
	{
		$this->theme->data(array(
			'content' => $this->load->view('controllers/Roster/character', $this->theme->data(), true)
		));
		$this->theme->view('page');
	}

	/**
	 * professions()
	 *
	 * Display a character's professions
	 */
	public function professions()
	{

	}

	public function dump()
	{
		dump($this->character);
	}

}
