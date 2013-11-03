<?php
namespace BattlenetArmory;

<<<<<<< HEAD
class Achievements extends Battlenet {
=======
class Achievements {
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
	
	private $data;
	private $datas;

   	function __construct($id_list,$type,$region) {
   		$jsonConnect = new jsonConnect();
   		$this->data = $jsonConnect->getAchievements($region,$id_list,$type);
   		$count = count($this->data);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$this->data[$i]['id']] = $this->data[$i]; 
   		}
   		#print_r($this->datas);
   	}

   	public function getAchievement($id,$field){
   		return $this->datas[$id][$field];
   	}
}