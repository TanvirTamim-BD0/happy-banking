<?php
namespace App\Helpers;

class IncomeType{

    public static function getIncomeTypeData()
    {
        //To get account transfer type...
        $incomeTypeData = array('Banking Income','Mobile Wallet Income','Pocket Wallet Income');

        $arrayDataOfIncomeType = [];
        foreach($incomeTypeData as $key => $item){
            if($item != null){
                $arrayDataOfIncomeType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfIncomeType;
    }
}