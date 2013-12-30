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
		$this->theme->data(array('page_title' => $this->guild->name .' ('. $this->guild->realm .')',
					  			 'author' => $this->guild->name));
		$this->theme->getIncludes();
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
		$this->theme->data(array('content' => $this->load->view('controllers/Welcome/leadingArticle',null,true)));
		$this->theme->view('page');
	}
}

