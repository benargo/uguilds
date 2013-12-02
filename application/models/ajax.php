<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Model {

	protected $data;

	/**
	 * __construct()
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * asJSON()
	 * 
	 * Returns the data requested as JSON
	 * @access public
	 * @param $data
	 * @return string $json
	 */
	public function asJson($data = NULL)
	{
		if(is_null($data))
		{
			$data = $this->data;
		}

		return json_encode($data);
	}
}