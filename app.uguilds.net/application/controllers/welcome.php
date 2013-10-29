<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $data = array();
	private $content = array();

	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->_setPageTitle();
		$this->_setPageAuthor();


		// Load the header
		$this->load->view('includes/head', $this->data);
		$this->uguilds->theme->loadHeader();
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

		

	//	$this->uguilds->theme->loadFooter();
	}

	/**
	 * _setPageTitle()
	 *
	 * Create a function that will set the page title to a variable,
	 * which we can inject into the view
	 * 
	 * @access private
	 */
	private function _setPageTitle()
	{
		$this->data['page_title'] = $this->uguilds->guild->guildName .' ('. $this->uguilds->guild->realm .')';
	}

	/**
	 * _setPageAuthor()
	 * 
	 * Sets the page author based on the leading article. 
	 * If, for whatever reason, we can't find a leading article, then fall back on the guild name.
	 *
	 * @access private
	 */
	private function _setPageAuthor()
	{
		$this->data['page_author'] = $this->uguilds->guild->guildName;
	}

	/**
	 * _getContent()
	 *
	 * Gets the content for this controller from couchdb
	 *
	 * @access private
	 * @return void
	 */
	private function _getContent()
	{
//		$this->couchdb->key(array($this->uguilds->guild->))
	}


	/**
	 * _getLeadingArticle()
	 * 
	 * Gets the leading article from the database
	 * 
	 * @access private
	 * @return \CouchDocument $article
	 */
	private function _getLeadingArticle()
	{

	}
}

