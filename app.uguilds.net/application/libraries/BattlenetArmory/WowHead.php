<?php
namespace BattlenetArmory;

<<<<<<< HEAD
class WowHead extends Battlenet {
=======
class WowHead {
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
	
   	private $cacheEnabled = TRUE;
   	private $cache;
   	private $type = 'WowHead';
	
	private $rawdata;
	
	function __construct() {
<<<<<<< HEAD
		$this->cacheEnabled = $this->config()['cachestatus'];
=======
		$this->cacheEnabled = $GLOBALS['wowarmory']['cachestatus'];
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
		if ($this->cacheEnabled){
	   		$this->cache = new CacheControl();
		}
   	}
   
	public function getData($url,$value) {
	   	$objectID = md5($url.$value);
		if ($this->cache->checkCache($objectID,$this->type, NULL)){
			#print "FOUND:".$url."<br />\n";
			$returnvalue = $this->cache->getData($objectID, $this->type);
   		} else {
   			#print "NOT FOUND:".$url."<br />\n";
			$powerdata = @file_get_contents($url."&power");
			preg_match('/name_enus: \'(.*?)\'\,/', $powerdata, $value);
			$returnvalue = $value[1];
			if ($this->cacheEnabled){
 				$this->cache->genericInsert($objectID, $returnvalue , $this->type);
			}
   		}
		return stripslashes($returnvalue);
	}
}
