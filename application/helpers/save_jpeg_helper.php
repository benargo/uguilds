<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( !function_exists( 'save_jpeg' ) )
{
	function save_jpeg( $image, $destination, $quality = 100 )
	{
		// Default vars
		$check = true;

		// Determine the true destination
		$directory = explode( '/', $destination );
		$directory = implode( '/', array_slice( $directory, 0, -1 ) );

		// Make the directory
		if( !is_dir( $directory ) )
		{
			$check = mkdir( $directory, 0777, true );
		}
		
		// Save the file
		if( $check )
		{
			$check = imagejpeg( $image, $destination, $quality );
		}
				
		return $check;
	}
}