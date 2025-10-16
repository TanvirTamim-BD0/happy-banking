<?php
namespace App\Helpers;

class TransferType{

    public static function getTransferTypeData()
    {
        //To get account transfer type...
        $transferTypeData = array('Own Account','Beneficiary Account');

        $arrayDataOfTransferType = [];
        foreach($transferTypeData as $key => $item){
            if($item != null){
                $arrayDataOfTransferType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfTransferType;
    }
}