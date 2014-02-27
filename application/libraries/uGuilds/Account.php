<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Libraries -> uGuilds -> Account
 *
 * This class handles the operations of accounts for the uGuilds application.
 * For now this account will handle it all, in future editions it will be an abstract class 
 * extended by each game uGuilds supports.
 *
 * It is largely based on the Account class for the Ashkandari project.
 * @link https://github.com/benargo/Ashkandari/blob/master/framework/account.class.php
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 ** Table of Contents
 * 1.  Variables
 *
 * 2.  __construct()
 * 3.  __get()
 * 4.  factory()
 *
 * 5.  set_email()
 * 6.  set_password()
 * 7.  authenticate()
 *
 * 8.  get_character()
 * 9.  get_active_character()
 * 10. get_all_characters()
 * 11. set_active_character()
 */
class Account
{
	// Variables
	protected $id;
	protected $email;
	protected $password;
	protected $activation_code;
	protected $is_active;
	protected $is_suspended;
	protected $battletag;

	// Characters
	protected $active_character;
	protected $characters = array();

	/**
	 * __construct()
	 *
	 * Initialises the class
	 *
	 * @access public
	 * @param (int) $id - the ID number of the account to load
	 * @return void
	 */
	function __construct($id)
	{
		// Load the database
		$ci =& get_instance();

		$result = $ci->db->query(
			"SELECT 
				a.id,
				a.email,
				a.password,
				a.activation_code,
				a.is_active,
				a.is_suspended,
				a.battletag,
				a.active_character,
				c.id AS character_id,
				c.name AS character_name
			FROM ug_Accounts a
			RIGHT OUTER JOIN ug_Characters c ON c.account_id = a.id
			WHERE a.id = '". $id ."'
			AND c.guild_id = ". $ci->guild->id ."
			LIMIT 0, 1");

		if($result->num_rows() > 0)
		{
			$this->id = $id;

			$row = $result->row();
			
			foreach($row as $key => $value)
			{
				// Account parameters
				if(property_exists($this, $key))
				{
					$this->$key = $value;
				}
			}

			$this->characters[$row->character_id] = $row->character_name;
	
		}
	}

	/**
	 * __get()
	 *
	 * Magic getter
	 *
	 * @access public
	 * @param (string) $param - The name of the parameter
	 * @return mixed
	 */
	function __get($param)
	{
		if(property_exists($this, $param))
		{
			return $this->$param;
		}
	}

	/**
	 * THIS FUNCTION IS NOT PRESENTLY IN USE
	 *
	 * factory()
	 *
	 * Loads the Account based on a character's name. 
	 * If the character doesn't exist in the database, 
	 * create it and initiate the registration protocol.
	 *
	 * @access public
	 * @static true
	 * @param (string) $character_name
	 * @return instance of uGuilds\Account OR FALSE
	 */
	/*
	public static function factory($character_name)
	{
		$ci =& get_instance();

		$result = $ci->db->query(
			"SELECT account_id
			FROM ug_Characters
			WHERE region = '". $ci->guild->region ."'
				AND realm = '". $ci->guild->realm ."'
				AND `name` = '$character_name'
				AND account_id IS NOT NULL
			LIMIT 0, 1");

		if($result->num_rows() > 0)
		{
			$row = $result->row();

			return new Account($row->account_id);
		}

		return false;
	}

	/**
	 * factory()
	 *
	 * Loads the Account based on an email address.
	 *
	 * @access public
	 * @static true
	 * @param (string) $email
	 * @return instance of uGuilds\Account OR FALSE
	 */
	public static function factory($email)
	{
		$ci =& get_instance();
		$ci->load->library('encrypt');

		$query = $ci->db->query(
			"SELECT id
			FROM ug_Accounts
			WHERE id = ". $ci->db->escape(sha1($email)) ."
			LIMIT 0, 1");
			
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			return new Account($row->id);
		}
		
		return false;
	}

	/**
	 * get_email()
	 *
	 * Returns the decrypted email address for us to use.
	 *
	 * @access public
	 * @return string
	 */
	public function get_email()
	{
		$ci =& get_instance();
		$ci->load->library('encrypt');

		return $ci->encrypt->decode($this->email);
	}

	/**
	 * set_email()
	 *
	 * Change an Account's email address and update it in the database.
	 *
	 * @access public
	 * @param (string) $value - The new email address to set.
	 * @return boolean
	 */
	public function set_email($value) 
	{
		$ci =& get_instance();
		$ci->load->library('encrypt');
		
		$this->email = $ci->encrypt->encode($value);
		if($ci->db->simple_query(
			"UPDATE `Accounts` 
			SET `email` = '". $ci->encrypt->encode($value) ."', 
			`active` = 0
			WHERE `id` = ". $this->id)) return true;
		
		return false;
	}
	
	/**
	 * set_password()
	 *
	 * Updates a user password, 
	 * and deactivates the account if this is either an attempt to hack the account 
	 * or reset the password on the account.
	 *
	 * @access public
	 * @param (string) $new_password - The new password to set.
	 * @return boolean
	 */
	public function set_password($new_password = NULL) 
	{
		$ci =& get_instance();
		 
		if($value === NULL) 
		{
			return $ci->db->simple_query(
				"UPDATE Accounts 
				SET password = NULL, 
				active = 0 WHERE 
				_id = ". $this->id);
		}

		return $ci->db->simple_query(
			"UPDATE Accounts
			 SET password = '". password_hash($new_password) ."' 
			 WHERE _id = ". $this->id);
	}

	/**
	 * authenticate()
	 *
	 * This function will authenticate users against the database and return one of three strings
	 * which can be switched through to determine the following action.
	 *
	 * @access public
	 * @param (string) $password - The raw, unhashed password to authenticate with
	 * @return boolean
	 */
	public function authenticate($password) 
	{
		return password_verify($password, $this->password);
	}
	 
	/**************
	 * CHARACTERS *
	 **************/

	/**
	 * get_character()
	 *
	 * Gets a specified character by name
	 *
	 * @access public
	 * @param (string) $name - the name of the character
	 * @return uGuilds\WoW\Character object OR FALSE
	 */
	public function get_character($name)
	{
		if(array_search($name, $this->characters) !== FALSE)
		{
			return new Character($name);
		}

		return false;
	}

	/**
	 * get_active_character()
	 *
	 * Returns an Account's active character
	 *
	 * @access public
	 * @return uGuilds\WoW\Character object OR FALSE
	 */
	public function get_active_character() 
	{
		if(isset($this->active_character))
		{
			return $this->get_character($this->characters[$this->active_character]);
		}

		return false;
	 }
	 
	/**
	 * get_all_characters()
	 *
	 * Gets all of the Account's characters
	 *
	 * @access public
	 * @return array containing uGuilds\WoW\Character objects
	 */
	public function get_all_characters() 
	{
		if($this->characters)
		{
			$response = array();

			foreach($this->characters as $character_name)
			{
				$response[strtolower($character_name)] = new Character($character_name, true);
			}

			return $response;
		}

		return false; 
	}
	 
	/**
	 * set_active_character()
	 *
	 * Sets an active character for the account, based on a given name
	 *
	 * @access public
	 * @param (string) $character_name
	 * @return boolean
	 */
	public function set_active_character($character_name) 
	{	 
		if(in_array($this->characters, $character_name))
		{
			$this->active_character = ucfirst($character_name);

			return true;
		}
		
		return false;	 
	}
}

