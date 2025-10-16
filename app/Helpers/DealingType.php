<?php
namespace App\Helpers;

class DealingType{

    public static function getDealingTypeData()
    {
        //To get dealing transfer type...
        $dealingTypeData = array('Transfer','Transaction');

        $arrayDataOfDealingType = [];
        foreach($dealingTypeData as $key => $item){
            if($item != null){
                $arrayDataOfDealingType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfDealingType;
    }
}