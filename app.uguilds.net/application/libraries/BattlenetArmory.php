<?php
/**
 * Master class for the battle.net WoW armory
 * @author Thomas Andersen <acoon@acoon.dk>
 * @copyright Copyright (c) 2011, Thomas Andersen, http://sourceforge.net/projects/wowarmoryapi
 * @version 3.5.1
 */

require_once APPPATH . 'libraries/BattlenetArmory/Battlenet.php';
require_once APPPATH . 'libraries/BattlenetArmory/CacheControl.php';
require_once APPPATH . 'libraries/BattlenetArmory/SafePDO.php';
require_once APPPATH . 'libraries/BattlenetArmory/Guild.php';
require_once APPPATH . 'libraries/BattlenetArmory/Character.php';
require_once APPPATH . 'libraries/BattlenetArmory/Item.php';
require_once APPPATH . 'libraries/BattlenetArmory/jsonConnect.php';
require_once APPPATH . 'libraries/BattlenetArmory/AuctionHouse.php';
require_once APPPATH . 'libraries/BattlenetArmory/Realms.php';
require_once APPPATH . 'libraries/BattlenetArmory/Achievements.php';
require_once APPPATH . 'libraries/BattlenetArmory/ArenaTeam.php';
require_once APPPATH . 'libraries/BattlenetArmory/Races.php';
require_once APPPATH . 'libraries/BattlenetArmory/Classes.php';
require_once APPPATH . 'libraries/BattlenetArmory/Perks.php';
require_once APPPATH . 'libraries/BattlenetArmory/Quest.php';
require_once APPPATH . 'libraries/BattlenetArmory/WowHead.php';

class BattlenetArmory {

	private $region;
	private $realm;
	private $cacheEnabled = TRUE;
	private $characterExcludeFields = FALSE;
   private $config;

	/**
	 * This will load the main armory class but only the realm and region will be set and no connections will be made until a get function is called.
	 * @param String $region Region can be EU|US|RU|TW|CN etc.
	 * @param String $realm Name of the realm. E.g.: 'Defias Brotherhood'
	 */
   	function __construct($region='EU', $realm=FALSE) {
      	
         $this->region = strtolower($region);
      	$this->realm  = $realm;

         $ci = get_instance();
         $this->config = $ci->config->item('battle.net');
   	}
   	
   	/**
   	 * Enter description here ...
   	 * @param Integer $locale The locale you want to use. Set to FALSE to reset back to default
   	 */
   	public function setLocale($locale = FALSE){
   		$this->config['locale'] = $locale;
   	}
   	
   	/**
   	 * Retrieve the arena team from armory - Returns an ArenaTeam object
   	 * @param String $teamsize Can be 2v2 | 3v3 | 5v5
   	 * @param String $teamname The name of the team
   	 * @param String $realm Realm name is not if you have already defined it in the construct or with setRegion()
   	 * @return object $team The arena team object
   	 */
   	public function getArenaTeam($teamsize, $teamname,$realm = FALSE){
   		if (!$realm) {
   			$realm = $this->realm;
   		} 
   		$team = new ArenaTeam($this->region, $realm, $teamsize, $teamname);
   		return $team;
   	}
   	
   	/**
   	 * Load an item based on the item ID - not possible to use item names.
   	 * @param Integer $itemID The ID of the item requested
   	 * @return Returns the Item object.
   	 */
   	public function getItem($itemID){
   		$item = new Item($this->region, $itemID);
   		return $item;
   	}
   	
   	/**
   	 * Load a quest based on the quest ID.
   	 * @param Integer $quest_id The ID of the quest requested
   	 * @return Returns the Quest object.
   	 */
   	public function getQuest($quest_id){
   		$quest = new Quest($this->region, $quest_id);
   		return $quest;
   	}
   	
   	
   	/**
   	 * Retrieve the character from armory - Returns a character object
   	 * @param String $name Name of the character
   	 * @param String $realm Realmname is not needed if you have already defined it in the construct or with setRegion()
   	 * @return object $character The character object from the character class.
   	 */
   	public function getCharacter($name,$realm = ''){
   		if (strlen($realm) == 0) {
   			$realm = $this->realm;
   		} 
   		$character = new Character($this->region, $realm , $name, $this->characterExcludeFields);
   		return $character;
   	}

   	/**
   	 * Enter description here ...
   	 * @param Array $fieldsArray An array with all the fields that should not be loaded or FALSE to reset to dafeult - Main consumers are quests and achievements - array('quests','achievements')
   	 */
   	public function characterExcludeFields($fieldsArray){
   		$this->characterExcludeFields = $fieldsArray;
   	}
   	
   	/**
   	 * Retrieve the guild from armory.
   	 * @param String $name Name of the guild
   	 * @param String $realm Realmname is not needed if you have already defined it in the construct or with setRegion()
   	 * @return object $guild The guild object from the guild class.
   	 */
   	public function getGuild($name,$realm = ''){
   		if (strlen($realm) == 0) {
   			$realm = $this->realm;
   		} 
   		$guild = new \BattlenetArmory\Guild($this->region, $realm , $name);
   		return $guild;
   	}
   	
   	
   	/**
   	 * Switch the region.
   	 * @param String $newRegion Can be EU/US/CH etc.
   	 * @return void
   	 */
   	public function setRegion($newRegion){
   		$this->region = strtolower($newRegion);
   	}

      /**
       * Switch the default realm.
       * @param String $newRealm
       * @return void
       */
      public function setRealm($newRealm){
         $this->realm = strtolower($newRealm);
      }
   	
   	public function getAuctionHouse($realm = ''){
   		if (strlen($realm) == 0) {
   			$realm = $this->realm;
   		} 
   		$auctionhouse = new AuctionHouse($this->region, $realm);
   		return $auctionhouse;
   	}

   	public function getRealms(){
   		$realmO = new Realms($this->region);
   		return $realmO;
   	}
   	
   	
   	/**
   	 * Turn the cache on/off
   	 * @param Boolean $boolean Can be TRUE or FALSE
   	 */
   	public function useCache($boolean){
		$this->cacheEnabled = $boolean;
		$this->config['cachestatus'] = $boolean;
   	}
   	
   	/**
   	 * Enter description here ...
   	 * @param $enabled
   	 */
   	public function UTF8($enabled=TRUE){
   		$this->config['UTF8'] = $enabled;
   	}

   	/**
   	 * Enable debug for a specific element. 
   	 * @param String $element Valid elements is: emblem
   	 * @param Boolean $value Can be set to TRUE/FALSE 
   	 */
   	public function debug($element,$value=TRUE){
   		$this->config['debug'][$element] = $value;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setCharactersCacheTTL($seconds){
   		$this->config['CharactersTTL'] = $seconds;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setGuildsCacheTTL($seconds){
   		$this->config['GuildsTTL'] = $seconds;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setAuctionHouseCacheTTL($seconds){
   		$this->config['AuctionHouseTTL'] = $seconds;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setItemsCacheTTL($seconds){
   		$this->config['ItemsTTL'] = $seconds;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setAchievementsCacheTTL($seconds){
   		$this->config['AchievementsTTL'] = $seconds;
   	}
   	
   	/**
   	 * Give cache a new time to live
   	 * @param Integer $seconds The amount of seconds the cache should be considered valid
   	 */
   	public function setArenaTeamsCacheTTL($seconds){
   		$this->config['ArenaTeamsTTL'] = $seconds;
   	}
   	
   	
}

?>
