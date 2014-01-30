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
		// If this is empty the likelihood is the character can't be found due to inactivity or out of date caching
		if(is_null($this->character->class))
		{
			$this->error_404();
			return;
		}

		$this->data(array(
			'breadcrumbs' => array(
				'/' => 'Home',
				'/roster' => 'Guild Roster',
				'/roster/rank='. (isset($this->character->guild_rank->rank_name) ? strformat($this->character->guild_rank->rank_name) : $this->character->guild_rank->rank) => (isset($this->character->guild_rank->rank_name) ? $this->character->guild_rank->rank_name : 'Rank '. $this->character->guild_rank->rank),
				'/roster/'. strtolower($this->character->name) => $this->character->name),
			'character' => $this->character,
			'inset_image' => $this->character->getImageURL('inset'),
			'faction' => $this->guild->getFaction()
		));

		$this->data(array(
			'content' => $this->load->view('controllers/Roster/Character/Index', $this->data(), true)
		));

		$this->render();
	}

	/**
	 * profession()
	 *
	 * Display a single profession for characters
	 * Profession 'name' is encoded in the URL, specifically:
	 * '$this->uri->segments[3]'
	 */
	public function profession()
	{
		// If this is empty the likelihood is the character can't be found due to inactivity or out of date caching
		if( is_null( $this->character->class ) )
		{
			$this->error_404();
			return;
		}

		$profession = $this->character->get_profession( $this->uri->segments[3] );

		// If this returns null then we've tried to view a profession which either this character does not have or the recipes are empty
		if( !$profession->has_recipes() )
		{
			show_404($this->uri->uri_string());	
		}

		$this->data(array(
			'breadcrumbs' => array(
				'/' => 'Home',
				'/roster' => 'Guild Roster',
				'/roster/rank='. (isset($this->character->guild_rank->rank_name) ? strformat($this->character->guild_rank->rank_name) : $this->character->guild_rank->rank) => (isset($this->character->guild_rank->rank_name) ? $this->character->guild_rank->rank_name : 'Rank '. $this->character->guild_rank->rank),
				'/roster/'. strtolower($this->character->name) => $this->character->name,
				'/roster/'. strtolower($this->character->name) .'/'. strformat($profession->name) => $profession->name),
			'character' => $this->character,
			'inset_image' => $this->character->getImageURL('inset'),
			'profession' => $profession
		));		

		$this->data(array(
			'content' => $this->load->view('controllers/Roster/Character/Profession', $this->data(), true)
		));

		$this->render();
	}

	/**
	 * profile_picture()
	 *
	 * Display a character's full profile picture
	 */
	public function profile_picture()
	{

	}

	/**
	 * error_404()
	 *
	 * The character can't be found!
	 */
	public function error_404()
	{
		$this->theme->data(array(
			'character' => $this->character
		));

		$this->theme->data(array(
			'content' => $this->load->view('controllers/Roster/Character/404', $this->theme->data(), true)	
		));

		http_response_code(404);

		$this->theme->view('page');
	}

	public function dump()
	{
		dump( $this->character->get_profession( 'inscription' )->get_recipes() );
	}

}
