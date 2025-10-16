<?php
namespace App\Helpers;

class CardType{

    public static function getCardTypeData()
    {
        //To get account transfer type...
        $cardTypeData = array('Amex','Discover','JCB','Master Card','NEXUS','Visa Card','Union Pay');
        return $cardTypeData;
    }
    
}