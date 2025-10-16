<?php
namespace App\Helpers;

class FrontendNoteType{

    public static function getNoteTypeData()
    {

        //To get account payment type...
        $noteTypeData = array('Income Wallet','Income MFS','Income Bank','Expense Wallet','Expense MFS','Expense Bank','Expense Credit Card', 'Wallet To MFS', 'Wallet To Account', 'Wallet To Credit Card', 'MFS To MFS', 'MFS To Bank', 'MFS To Wallet', 'Bank To Bank', 'Bank To MFS', 'Bank To Wallet', 'Credit Card To Bank', 'Credit Card To MFS', 'Bill Payment From Bank', 'Bill Payment From MFS', 'Bill Payment From Pocket');

        $arrayDataOfNoteType = [];
        foreach($noteTypeData as $key => $item){
            if($item != null){
                $arrayDataOfNoteType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfNoteType;
    }
}