<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds -> Models -> Theme
 *
 * Handles the rendering of themes within the uGuilds application
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 * Table of Contents
 * 1. Constants
 * 2. Properties
 * 3. Files
 *
 * 4. __construct()
 * 5. __get()
 * 6. find_by_id()
 * 7. reset()
 */
class Theme extends CI_Model 
{
	// Constants
	const DEFAULT_THEME = 'a6e284d6c07328787bb817c6a0000b29';

	// Properties
	private $id;
	private $controller;
	private $path;

	// Files
	private $xml;
	private $css = array();
	private $javascript = array();
	private $images = array();
	private $views = array();

	/**
	 * __construct()
	 *
	 * Initialise the class:
	 * 1. Load the parent class (Models)
	 * 2. Load the guild and loader objects
	 * 
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * __get()
	 *
	 * Magic getter :)
	 *
	 * @access public
	 * @param string $var - The name of the parameter to get
	 * @return mixed
	 */
	function __get($param)
	{
		switch($param)
		{
			// Default: Get anything
			default: 
				if(property_exists($this, $param))
				{
					return $this->$param;
				}
				break;
		}
	}

	/**
	 * find_by_id()
	 *
	 * Search to find the theme based on a given ID number.
	 * 
	 * @access public
	 * @param string $id - The ID of the given theme
	 * @return void
	 */
	public function find_by_id($id = self::DEFAULT_THEME)
	{
		$this->id = $id;

		if(file_exists(FCPATH .'themes/'. $this->id .'/theme.xml'))
		{
			$ci = get_instance();
			$this->controller = $ci->controller();

			$this->xml = simplexml_load_file(FCPATH .'themes/'. $this->id .'/theme.xml');

			$this->get_css_files();
			$this->get_javascript_files();
			$this->get_images();
		}
	}

	/**
	 * reset()
	 *
	 * A function which we can call if we need to fall back on the default theme.
	 *
	 * @access private
	 * @return void
	 */
	private function reset()
	{
		$this->find_by_id(self::DEFAULT_THEME);
	}

	/**
	 * get_path()
	 *
	 * Determines the path of the theme, and returns it if as either: 
	 * 1. A web-browser understandable path; or
	 * 2. A fully-qualified path, from the root of the server.
	 *
	 * @access public
	 * @param bool $prepend
	 * @return string
	 */
	public function get_path($prepend = false)
	{
		$path = '/themes/'. $this->id;

		if($prepend)
		{
			$path = FCPATH .'themes/'. $this->id;
		}

		return $path;
	}

	/**
	 * get_css_files()
	 *
	 * Grab all of the CSS files for the theme and add them to $this->css.
	 * Finally, return the list of CSS files.
	 *
	 * @access public
	 * @return array
	 */
	public function get_css_files() 
	{
		// Grab all the CSS files
		if(empty($this->css))
		{
			// Iterate through the theme directory
			$iterator = new RecursiveDirectoryIterator($this->get_path(true));
			foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
			{
				if(substr($file->getFileName(), -4) == '.css')
				{
					$this->css[] = str_replace(FCPATH, '/', $file->getPathName());
				}
			}

			$this->css = array_merge($this->css, $this->get_controller_css());
		}

		// Return the list of CSS files
		return $this->css;
	}

	/**
	 * get_controller_css()
	 *
	 * Prints out any additional CSS files neccessary for the controller. 
	 * This is defined by uGuilds and applies to all themes.
	 * i.e. All themes have these basic standards for the controllers and they may be overwritten per theme.
	 *
	 * @access private
	 * @return string
	 */
	private function get_controller_css() 
	{	
		$files = array();

		if(is_dir(FCPATH .'media/css/controller/'. $this->controller))
		{
			$files = scandir(FCPATH .'media/css/controller/'. $this->controller .'/');
			$files = preg_grep('/\.css$/', $files);

			foreach($files as $key => $value)
			{
				$files[$key] = '/media/css/controller/'. $this->controller .'/'. $value;
			}
		}

		return $files;
	}

	/**
	 * get_javascript_files()
	 *
	 * Grab all of the CSS files for the theme and add them to $this->css.
	 * Finally, return the list of CSS files.
	 *
	 * @access public
	 * @return array
	 */
	public function get_javascript_files()
	{
		// Grab all the CSS files
		if(empty($this->javascript))
		{
			// Iterate through the theme directory
			$iterator = new RecursiveDirectoryIterator($this->get_path(true));
			foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
			{
				if(substr($file->getFileName(), -7) == '.min.js')
				{
					$this->javascript[] = str_replace(FCPATH, '/', $file->getPathName());
				}
			}
		}

		$this->javascript = array_merge($this->javascript, $this->get_controller_js());

		// Return a list of JavaScript files
		return $this->javascript;
	}

	/**
	 * get_controller_js()
	 *
	 * Prints out any additional JS files neccessary for the controller. 
	 * This is defined by uGuilds, applies to all themes and cannot be overwritten.
	 *
	 * @access private
	 * @return string
	 */
	private function get_controller_js()
	{
		$files = array();

		if(is_dir(FCPATH .'media/js/controller/'. $this->controller .'/'))
		{
			$files = scandir(FCPATH .'media/js/controller/'. $this->controller .'/');
			$files = preg_grep('/!(\.min\.js)/', $files);

			foreach($files as $key => $value)
			{
				$files[$key] = '/media/js/controller/'. $this->controller .'/'. $value;
			}
		}

		return $files;
	}

	/**
	 * get_images()
	 *
	 * Scans the images directory and returns acceptable image formats
	 * Acceptable file endings are: gif, jp(e)g, png, tiff, svg
	 *
	 * @access public
	 * @return array
	 */
	public function get_images()
	{
		// Grab all the images
		if(empty($this->images))
		{
			// Iterate through the theme directory
			$iterator = new RecursiveDirectoryIterator($this->get_path(true));
			foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
			{
				if(preg_match('/\.(gif|jpe?g|png|tiff|svg)$/', $file->getFileName()))
				{
					$this->images[] = str_replace(FCPATH, '', $file->getPathName());
				}
			}
		}

		// Return the list of images
		return $this->images;
	}

	/**
	 * get_page()
	 *
	 * Renders the final page
	 * 
	 * @access public
	 * @return string
	 */
	public function get_page()
	{
		if(!is_link(APPPATH .'views/themes/'. $this->id) && is_dir(FCPATH .'themes/'. $this->id .'/views'))
		{
			symlink(FCPATH .'themes/'. $this->id .'/views', APPPATH .'views/themes/'. $this->id);
		}

		if(file_exists(readlink(APPPATH .'views/themes/'. $this->id) .'/page.php'))
		{
			return 'themes/'. $this->id .'/page.php';
		}
	}
}

