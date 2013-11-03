<?php
namespace BattlenetArmory;

class Classes {
	
	private $datas;

   	function __construct($region) {
   		$jsonConnect = new jsonConnect();
   		$data = $jsonConnect->getClasses($region);
   		$count = count($data['classes']);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$data['classes'][$i]['id']] = $data['classes'][$i]; 
   		}
   		$data = null;
   		$jsonConnect = null;
   	}

   	public function getClass($id,$field){
   		return $this->datas[$id][$field];
   	}

      public function getIcon($id) 
      {
         if(!file_exists(FCPATH .'media/images/classes/class_'. $id . '.jpg'))
         {
            $image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/class_'. $id .'.jpg');
            imagejpeg($image, FCPATH .'media/images/classes/class_'. $id .'.jpg', 100);
         }

         return '/media/images/classes/class_'. $id .'.jpg';
      }
}
