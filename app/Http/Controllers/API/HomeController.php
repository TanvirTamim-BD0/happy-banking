<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceTransfer;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;
use App\Models\AccountPayment;
use App\Models\User;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\IncomeExpense;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();
        return response()->json([
            'message'   =>  'Successfully loaded ata.',
            'status_code'   => 201
        ], 201);
    }



    /*------------ Transfer Revert--------------*/
    public function transferDataFilter(Request $request){

         $validator = Validator::make($request->all(), [
            'transaction_id'=> 'required',
        ]);

        $data = AccountPayment::where('transaction_id',$request->transaction_id)->first();

        if (isset($data) && $data->from_pocket_account_id != null) {
            $fromAccountNumber = $data->fromPocketAccountData->mobile;
            $accountBalance = $data->fromPocketAccountData->wallet;
        }elseif(isset($data) && $data->from_account_id != null){
            $fromAccountNumber = $data->fromAccountData->account_number;
            $accountBalance = $data->fromAccountData->current_balance;
        }elseif(isset($data) && $data->from_credit_card_id != null){
            $fromAccountNumber = $data->creditCardData->card_number;
            $accountBalance = $data->creditCardData->total_limit;
        }

        if (isset($data) && $data->to_pocket_account_id != null) {
            $toAccountNumber = $data->toPocketAccountData->mobile;
        }elseif(isset($data) && $data->to_account_id != null){
            $toAccountNumber = $data->toAccountData->account_number;
        }elseif(isset($data) && $data->to_credit_card_id != null){
            $toAccountNumber = $data->toCreditCardData->card_number;
        }elseif(isset($data) && $data->to_beneficiary_account_id != null){
            $toAccountNumber = $data->toBeneficiaryAccountData->account_number;
        }


        if(isset($data) && !empty($data)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountPaymentData' => $data,
                'fromAccountNumber' => $fromAccountNumber,
                'accountBalance'   =>  $accountBalance,
                'toAccountNumber'   => $toAccountNumber,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }


    public function transferRevert(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'account_payment_id'=> 'required',
        ]);

        $data = AccountPayment::where('id',$request->account_payment_id)->first();


        //from acount balance update.........
        if ($data->from_pocket_account_id != null) {
            $fromAccountData = User::where('id', $data->from_pocket_account_id)->first();
            $balance = $data->total_pay_amount;
            $fromAccountData->wallet += $balance;
            $fromAccountData->save();
               
        }elseif($data->from_account_id != null){
            
            $fromAccountData = Account::where('id', $data->from_account_id)->first();
            $balance = $data->total_pay_amount;
            $fromAccountData->current_balance += $balance;
            $fromAccountData->save();

        }elseif($data->from_credit_card_id != null){
           
            $fromAccountData = CreditCard::where('id', $data->from_credit_card_id)->first();
            $balance = $data->total_pay_amount;
            $fromAccountData->total_limit += $balance;
            $fromAccountData->save();
        }


        //to amount balance update.....
        if ($data->to_pocket_account_id != null) {
            $toAccountData = User::where('id', $data->to_pocket_account_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->wallet -= $balance;
            $toAccountData->save();

        }elseif($data->to_account_id != null){
            
            $toAccountData = Account::where('id', $data->to_account_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->current_balance -= $balance;
            $toAccountData->save();

        }elseif($data->to_credit_card_id != null){
            
            $toAccountData = CreditCard::where('id', $data->to_credit_card_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->total_limit -= $balance;
            $toAccountData->save();
        }


        //delete account data .......
        $deleteAccountPayment = AccountPayment::where('id',$request->account_payment_id)->delete();


        return response()->json([
            'message'   =>  'Successfully Transfer Revert.',
            'status_code'   => 201
        ], 201);
        

    }



    /*------------ Income Expense Revert--------------*/
    public function incomeExpenseDataFilter(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'transaction_id'=> 'required',
        ]);

        $data = IncomeExpense::where('transaction_id',$request->transaction_id)->first();

        if (isset($data) && $data->pocket_wallet_id != null) {
            $fromAccountNumber = $data->pocketWalletData->mobile;
            $accountBalance = $data->pocketWalletData->wallet;
        }elseif(isset($data) && $data->from_account_id != null){
            $fromAccountNumber = $data->fromAccountData->account_number;
            $accountBalance = $data->fromAccountData->current_balance;
        }elseif(isset($data) && $data->from_credit_card_id != null){
            $fromAccountNumber = $data->fromCreditCardData->card_number;
            $accountBalance = $data->fromCreditCardData->total_limit;
        }

        if(isset($data) && !empty($data)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'incomeExpenseData' => $data,
                'fromAccountNumber' => $fromAccountNumber,
                'accountBalance'   =>  $accountBalance,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }

    public function incomeExpenseRevert(Request $request)
    {
         $data = IncomeExpense::where('id',$request->income_expense_id)->first();

        //status true income and status false expesne.....
        if ($data->status == true) {
            
            if ($data->pocket_wallet_id != null) {
                $account = User::where('id', $data->pocket_wallet_id)->first();
                $balance = $data->amount;
                $account->wallet -= $balance;
                $account->save();

            }elseif($data->from_account_id != null){
                $account = Account::where('id', $data->from_account_id)->first();
                $balance = $data->amount;
                $account->current_balance -= $balance;
                $account->save();

            }elseif($data->from_credit_card_id != null){
                $account = CreditCard::where('id', $data->from_credit_card_id)->first();
                $balance = $data->amount;
                $account->total_limit -= $balance;
                $account->save();
            }

        }else{

          if ($data->pocket_wallet_id != null) {
                $account = User::where('id', $data->pocket_wallet_id)->first();
                $balance = $data->amount;
                $account->wallet += $balance;
                $account->save();

            }elseif($data->from_account_id != null){
                $account = Account::where('id', $data->from_account_id)->first();
                $balance = $data->amount;
                $account->current_balance += $balance;
                $account->save();
                
            }elseif($data->from_credit_card_id != null){
                $account = CreditCard::where('id', $data->from_credit_card_id)->first();
                $balance = $data->amount;
                $account->total_limit += $balance;
                $account->save();

            }
        }

        //delete data .......
        $deleteAccountPayment = IncomeExpense::where('id',$request->income_expense_id)->delete();

        return response()->json([
            'message'   =>  'Successfully Income Expense Revert.',
            'status_code'   => 201
        ], 201);

    }



}
