<?php
namespace App\Helpers;

class PaymentType{

    public static function getPaymentTypeData()
    {
        //To get account payment type...
        $paymentTypeData = array('Account To Account','Account To MFS','MFS To Account','MFS To MFS','Account To Wallet','Wallet To Account','MFS To Wallet','Wallet To MFS','Card To Account','Card To MFS','Account To Card','MFS To Card','Wallet To Card');

        $arrayDataOfPaymentType = [];
        foreach($paymentTypeData as $key => $item){
            if($item != null){
                $arrayDataOfPaymentType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfPaymentType;
    }
}