<?php
namespace BattlenetArmory;

<<<<<<< HEAD
class Quest extends Battlenet {
=======
class Quest {
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
	
	private $datas;

   	function __construct($region,$id) {
   		$jsonConnect = new jsonConnect();
   		$this->datas = $jsonConnect->getQuest($region,$id);
   		$jsonConnect = null;
   	}

   	public function getTitle(){
   		return $this->datas['title'];
   	}
   	
   	public function getData(){
   		return $this->datas;
	}
}
