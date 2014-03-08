<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Css extends UG_Controller {

	private $css = '';

	/**
	 * __construct()
	 *
	 * Calls the parent's constructor, then sets the content type
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		header('Content-Type: text/css');
		parent::__construct();
	}

	/**
	 * index()
	 *
	 * Get all the themes core CSS files
	 * @access public
	 * @return string
	 */
	public function index()
	{

		if($this->uri->uri_string == 'theme/css')
		{
			if(isset(apache_request_headers()['Referer']))
			{
				foreach($this->router->routes as $key => $value)
				{
					if(preg_match('/'. $key .'/', str_replace($this->config->item('base_url'), '', apache_request_headers()['Referer'])))
					{
						self::$controller_name = $value;

						$this->theme->set_controller_name(self::$controller_name);	
					}
				}
			}
		}

		foreach($this->theme->getCssFiles() as $file)
		{
			$this->css .= preg_replace('/\s\s+/', '', file_get_contents($file));
		}

		echo $this->css;
	}
}