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
			'character_name' => $this->character->name,
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
			'breadcrumbs' => array(
				'/' => 'Home',
				'/roster' => 'Guild Roster',
				'/roster/rank='. (isset($this->character->guild_rank->rank_name) ? strformat($this->character->guild_rank->rank_name) : $this->character->guild_rank->rank) => (isset($this->character->guild_rank->rank_name) ? $this->character->guild_rank->rank_name : $this->character->guild_rank->rank),
				'/roster/'. strtolower($this->character->name) => $this->character->name),
			'character' => $this->character,
			'inset_image' => $this->character->getImageURL('inset'),
			'faction' => $this->guild->getFaction()
		));
		$this->theme->data(array(
			'content' => $this->load->view('controllers/Roster/character', $this->theme->data(), true)
		));
		$this->theme->view('page');
	}

	/**
	 * profile_picture()
	 *
	 * Display a character's full profile picture
	 */
	public function profile_picture()
	{

	}

	public function dump()
	{
		dump($this->character);
	}

}
