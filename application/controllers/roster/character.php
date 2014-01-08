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
		$this->theme->data(array('page_title' => $this->character->name .' - '. $this->character->realm,
                                 'author' => $this->guild->name));
		dump($this->character);
	}

	public function index()
	{
		$this->theme->view('page');
	}

}
