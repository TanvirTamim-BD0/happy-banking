<?php
namespace App\Helpers;

class ExpenseType{

    public static function getExpenseTypeData()
    {
        //To get account transfer type...
        $expenseTypeData = array('Banking Expense','Mobile Wallet Expense','Pocket Wallet Expense','Credit Card Expense');

        $arrayDataOfExpenseType = [];
        foreach($expenseTypeData as $key => $item){
            if($item != null){
                $arrayDataOfExpenseType[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return $arrayDataOfExpenseType;
    }
}