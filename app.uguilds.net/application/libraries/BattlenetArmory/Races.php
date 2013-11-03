<?php
namespace BattlenetArmory;

<<<<<<< HEAD
class Races extends Battlenet {
=======
class Races {
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
	
	private $datas;

   	function __construct($region)
      {
   		$jsonConnect = new jsonConnect();
   		$data = $jsonConnect->getRaces($region);
   		$count = count($data['races']);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$data['races'][$i]['id']] = $data['races'][$i]; 
   		}
   		$data = null;
   	}

   	public function getRace($id,$field)
      {
   		return $this->datas[$id][$field];
   	}

      public function getIcon($id, $gender) 
      {
         if(!file_exists(FCPATH .'media/images/races/race_'. $id . '_'. $gender .'.jpg'))
         {
            $image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/race_'. $id .'_'. $gender .'.jpg');
            imagejpeg($image, FCPATH .'media/images/races/race_'. $id .'_'. $gender .'.jpg', 100);
         }

         return '/media/images/races/race_'. $id .'_'. $gender .'.jpg';
      }
}
