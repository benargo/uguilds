<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

	protected static $controller_name;
	protected $account;
	protected $data;

	/**
	 * __construct()
	 *
	 * Initialise the class
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		self::$controller_name = get_class($this);

		if($this->session->userdata('user_id'))
		{
			$this->account = new uGuilds\Account($this->session->userdata('user_id'));
			$this->theme->data(array('account' => $this->account));
		}

		$this->get_includes();
	}

	/**
	 * data()
	 *
	 * Merges additional data into the $this->data array,
	 * and then returns the global data array
	 *
	 * @access protected
	 * @param array $data
	 * @return views
	 */
	protected function data(array $data = array())
	{
		$this->data = array_merge($this->data, $data);

		return $this->data;
	}

	/**
	 * render()
	 *
	 * Renders the final page
	 *
	 * @access protected
	 * @return text/html
	 */
	protected function render()
	{
		$this->theme->view('page');
	}

	/**
	 * getIncludes()
	 * 
	 * @access public
	 * @return void
	 */
	private function get_includes()
	{
		$ci =& get_instance();
		$dir = scandir(APPPATH .'views/includes');
		foreach($dir as $file)
		{
			if(preg_match('/.*\.php$/', $file))
			{
				$file = str_replace('.php', '', $file);
				$this->data[$file] = $ci->load->view('includes/'. $file, $this->data, true);
			}
		}
	} 
}