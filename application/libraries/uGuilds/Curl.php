<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * OOCurl
 *
 * Provides an Object-Oriented interface to the PHP cURL
 * functions and clean up some of the curl_setopt() calls.
 *
 * @package OOCurl
 * @author James Socol <me@jamessocol.com>
 * @version 0.3.0
 * @copyright Copyright (c) 2008-2013, James Socol
 * @license MIT
 */
require_once(APPPATH .'libraries/oocurl/OOCurl.php');

/**
 * Curl
 *
 * Extends OOCurl and adapts it for use with Battle.net's armory
 */
class Curl extends \Curl
{
	private $regions = array(
		'us'=>'us.battle.net',
		'eu'=>'eu.battle.net',
		'kr'=>'kr.battle.net',
		'tw'=>'tw.battle.net',
		'cn'=>'battlenet.com.cn');
	/**
	 * __construct()
	 *
	 * Initialises the class, and sets options to include the API key
	 *
	 * @access public
	 * @param string $url
	 * @return void
	 */
	function __construct( $url )
	{
		$ci =& get_instance();

		// Set the host
		$host = 'https://'. $this->regions[ strtolower( $ci->guild->region ) ] .'/api/wow/';

		parent::__construct( $host . $url );

		// Set the API keys
		$ci->load->config('battle.net');
		$config =& $ci->config->item('battle.net');

		$pubkey = $config['keys']['public'];
		$privkey = $config['keys']['private'];

		$date = date('D, d M Y G:i:s T',time());

		$stringtosign = "GET\n".$date."\n".$url."\n";

		$signature = base64_encode( hash_hmac( 'sha1', $stringtosign, $privkey, true ) );

		$header = array(
			"Host: ". $this->regions[ strtolower( $ci->guild->region ) ], 
			"Date: ". $date, 
			"\nAuthorization: BNET ". $pubkey .":". base64_encode( hash_hmac( 'sha1', "GET\n". $date ."\n". $this->url ."\n", $privkey, true ) )."\n" );

		$this->HTTPHEADER = $header;

		// Set other CURLOPTs
		$this->RETURNTRANSFER = true;
		$this->VERIFYHOST = false;
		$this->SSL_VERIFYPEER = false;
		$this->FOLLOWLOCATION = true;
		$this->TIMEOUT = 10;
		$this->VERBOSE = true;

		return $this;
	}

	/**
	 * init()
	 *
	 * Initialises the Curl again with a different URL
	 *
	 * @access public
	 * @param string $url
	 * @return void
	 */
	public function init( $url )
	{
		$ci =& get_instance();
		
		// Set the host
		$host = 'https://'. $this->regions[ strtolower( $ci->guild->region ) ] .'/api/wow/';

		return parent::init( $host . $url );
	}
}

