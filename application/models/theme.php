<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * \uGuilds\models\Theme
 * 
 * @author Ben Argo
 * @version 1.1
 */

class Theme extends CI_Model {

	// Variables & Constants
	const DEFAULT_THEME = 'a6e284d6c07328787bb817c6a0000b29';

	// Properties
	private $_id;

	// Files
	private $xml;
	private $css;
	private $javascript;
	private $images;
	private $views = array();
	private $data = array();

	/**
	 * __construct()
	 * 
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$ci = get_instance();

		$this->_findById($ci->guild->theme);

		$this->data['theme_path'] = $this->getPath();
		$this->data['locale'] = $ci->guild->locale;
		$this->data['guild'] = $ci->guild;
	}

	/**
	 * __get()
	 *
	 * @access public
	 * @param string $var
	 * @return mixed
	 */
	function __get($var)
	{
		switch($var)
		{
			case "_id": // Private
			case "id": // Public
				if(is_null($this->_id))
				{
					$this->_reset();
				}
				return $this->_id;
				break;

			case "name":
				return $this->name;
				break;

			case "data": // Prefered
			case "themeData":
				return $this->data;
				break;
		}
	}

	/** 
	 * instance()
	 *
	 * @access public
	 * @static true
	 * @return instanceof \uGuilds\models\Theme
	 */
	public static function &instance()
	{
		$ci = &get_instance();
		return $ci->theme;
	}

	/**
	 * _findById()
	 * 
	 * @access private
	 * @return void
	 */
	private function _findById($id = self::DEFAULT_THEME)
	{
		$this->_id = $id;

		if(file_exists(FCPATH .'themes/'. $this->_id .'/theme.xml'))
		{
			$this->xml = simplexml_load_file(FCPATH .'themes/'. $this->_id .'/theme.xml');
			$this->getCssFiles();
			$this->getJavaScriptFiles();
			$this->getImages();
		}
	}

	/**
	 * _reset()
	 *
	 * A function which we can call if we need to fall back on the default theme	
	 * @access private
	 * @return void
	 */
	private function _reset()
	{
		$this->_findByID(self::DEFAULT_THEME);
	}

	/**
	 * getPath()
	 *
	 * @access public
	 * @param bool $prepend
	 * @return string
	 */
	public function getPath($prepend = false)
	{
		$path = '/themes/'. $this->_id;

		if($prepend)
		{
			$path = FCPATH .'themes/'. $this->_id;
		}
		return $path;
	}

	/**
	 * getCssFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getCssFiles() 
	{
		if(empty($this->css))
		{
			$files = scandir(FCPATH .'themes/'. $this->_id .'/css');
			$this->css = preg_grep('/\.css$/', $files);
		}
		return $this->css;
	}

	/**
	 * getJavaScriptFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getJavaScriptFiles()
	{
		if(empty($this->javascript))
		{
			$files = scandir(FCPATH .'themes/'. $this->_id .'/js');
			$this->javascript = preg_grep('/\.min\.js$/', $files);
		}
		return $this->javascript;
	}

	/**
	 * getImages()
	 *
	 * Scans the images directory and returns acceptable image formats
	 * Acceptable file endings are: gif, jp(e)g, png, tiff, svg  
	 * @access public
	 * @return array
	 */
	public function getImages()
	{
		if(empty($this->images))
		{
			$files = scandir(FCPATH .'themes/'. $this->_id .'/images');
			$this->images = preg_grep('/\.(gif|jpe?g|png|tiff|svg)$/', $files);
		}
		return $this->images;
	}

	/**
	 * getIncludes()
	 * 
	 * @access public
	 * @return void
	 */
	public function getIncludes()
	{
		$this->data['head'] = $this->view('includes/head', $this->data);
		$this->data['nav'] = $this->view('includes/nav', $this->data);
		$this->data['footer'] = $this->view('includes/footer', $this->data);
	}

	/**
	 * data()
	 *
	 * @access public
	 * @param array $data
	 * @return views
	 */
	public function data(array $data = NULL)
	{
		$this->data = array_merge($this->data, $data);
		return $this->data;
	}

	/**
	 * view()
	 *
	 * Gets the requested view and returns it as data
	 * @see http://ellislab.com/codeigniter/user-guide/general/views.html
	 * @access public
	 * @param string $name
	 * @param array $data
	 * @return \CodeIgniter\View
	 */
	public function view($name, array $data = array())
	{
		$data = array_merge($this->data, $data);

		if(file_exists(APPPATH .'views/themes/'. $this->_id .'/'. $name .'.php'))
		{
			if(!array_key_exists($name, $this->views))
			{
				$this->views[$name] = 'themes/'. $this->_id .'/'. $name;
			}
			$ci =& get_instance();
			return $ci->load->view($this->views[$name], $data, true);
		}
	}

}