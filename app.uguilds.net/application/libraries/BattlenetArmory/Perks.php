<?php
namespace BattlenetArmory;

<<<<<<< HEAD
class Perks extends Battlenet {
=======
class Perks {
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
	
	private $datas;
	private $guildlevel;

   	function __construct($region,$guildlevel) {
   		$jsonConnect = new jsonConnect();
   		$data = $jsonConnect->getPerks($region);
   		$count = count($data['perks']);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$data['perks'][$i]['guildLevel']] = $data['perks'][$i];
<<<<<<< HEAD
   			$this->datas[$data['perks'][$i]['guildLevel']]['wowhead'] = $this->config()['urls']['perk'].'='.$data['perks'][$i]['spell']['id']; 
=======
   			$this->datas[$data['perks'][$i]['guildLevel']]['wowhead'] = $GLOBALS['wowarmory']['urls']['perk'].'='.$data['perks'][$i]['spell']['id']; 
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
   		}
   		$this->guildlevel = $guildlevel;
   		$data = null;
   		$jsonConnect = null;
   	}

   	public function getPerks(){
   		$return = array();
   		foreach ($this->datas as $key => $data){
   			if ($key <= $this->guildlevel){
   				$return[$key] = $data; 
   			}
   		}
   		if (count($return)>0){
   			return $return;
   		} else {
   			return FALSE;
   		}
   	}
   	
   	public function getNextPerk(){
   		foreach ($this->datas as $key => $data){
   			if ($key > $this->guildlevel){
   				return $data; 
   			}
   		}
   		return FALSE;
   	}
}
