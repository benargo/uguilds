<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manifest extends UG_Controller {

	private $cache = array('');
	private $network = array('');

	/**
	 * Construction function
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if(ENVIRONMENT == 'production')
		{
			header('Content-type: text/cache-manifest');
		
			$this->_getEmblems();
			$this->_getSystemMedia();
			$this->_getThemeFiles();

			$this->load->view('controllers/Manifest/manifest', 
				array('network' => $this->network,
					  'cache' => $this->cache));
		}
	}

	/**
	 * _getEmblems()
	 *
	 * @access private
	 * @return void
	 */
	private function _getEmblems()
	{
		$files = scandir(FCPATH.'media/BattlenetArmory');
		$files = preg_grep('/emblem_'.
				strtoupper($this->uguilds->guild->region).'_'.
				str_replace(' ', '_', ucwords($this->uguilds->guild->realm)) .'_'.
				str_replace(' ', '_', ucwords($this->uguilds->guild->name)) .'_/', $files);
	
		foreach($files as $file)
		{
			$this->cache[] = 'http://static.uguilds.net/media/BattlenetArmory/'.$file;
		}
	}

	/**
	 * _getSystemCSS()
	 *
	 * @access private
	 * @return void
	 */
	private function _getSystemMedia()
	{
		$this->cache[] = 'http://code.jquery.com/jquery-1.10.2.min.js';
		$this->cache[] = 'http://code.jquery.com/jquery-1.10.2.min.map';
		$this->cache[] = 'http://code.jquery.com/jquery-migrate-1.2.1.min.js';
		$iterator = new RecursiveDirectoryIterator(FCPATH.'media');
		foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
		{
			if(preg_match_all('/\.css|\.eot|\.svg|\.ttf|\.woff|\.jpe?g|\.gif|\.png|\.tiff|\.min\.js$/', $file->getFileName())
				&& !preg_match_all('/\/BattlenetArmory\//', $file->getPathname()))
			{
				$this->cache[] = 'http://static.uguilds.net'.preg_replace('/'.str_replace('/','\/',FCPATH).'/','/',$file->getPathname());
			}
		}
	}

	/**
	 * _getThemeFiles()
	 *
	 * @access private
	 * @return void
	 */
	private function _getThemeFiles() 
	{
		$files = simplexml_load_file(FCPATH.'themes/'.$this->uguilds->theme->id .'/theme.xml')->files;
		foreach($files->file as $file)
		{
			$this->cache[] = '/themes/'. $this->uguilds->theme->id .'/'. (string) $file;
		}
	}
}