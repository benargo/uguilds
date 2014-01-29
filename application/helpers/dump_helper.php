<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists( 'dump' ) )
{

	/**
	 * dump()
	 *
	 * @access public
	 * @static true
	 * @return dumped contents
	 */
	function dump( $data, $exit = true ) 
	{
		print "<pre>";
		print_r( $data );
		print "</pre>";
		
		if( $exit ) 
		{
			exit;
		}
	}

}