<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends UG_Controller {

	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->_setPageTitle($this->uguilds->guild->guildName .' ('. $this->uguilds->guild->realm .')');
		$this->_setPageAuthor($this->uguilds->guild->guildName);

		$this->_loadHeader();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('controllers/Welcome/leadingArticle');
		$this->_loadFooter();
	}
}
