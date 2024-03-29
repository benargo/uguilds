<?php
namespace BattlenetArmory;

class Perks extends Battlenet {
	
	private $datas;
	private $guildlevel;

   	function __construct($region,$guildlevel) {
   		$jsonConnect = new jsonConnect();
   		$data = $jsonConnect->getPerks($region);
   		$count = count($data['perks']);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$data['perks'][$i]['guildLevel']] = $data['perks'][$i];
   			$this->datas[$data['perks'][$i]['guildLevel']]['wowhead'] = $this->config()['urls']['perk'].'='.$data['perks'][$i]['spell']['id']; 
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
