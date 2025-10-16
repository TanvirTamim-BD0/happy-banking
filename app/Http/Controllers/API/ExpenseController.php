<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionCategory;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use App\Models\IncomeExpense;
use App\Helpers\CurrentUser;
use App\Helpers\ExpenseType;
use Carbon\Carbon;
use Validator;
use Auth;

class ExpenseController extends Controller
{
    //To get all the expense or transaction data...
    public function getExpenseOrTransactionData()
    {
        ///To get expense data...
        $expenseData = IncomeExpense::orderBy('id','desc')->where('user_id', Auth::user()->id)->where('status', false)
                        ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','fromCreditCardData','transactionCategoryData'])->get();

        if(!empty($expenseData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'expenseData'   =>  $expenseData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get single credit card data...
    public function getSingleCreditCardData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_credit_card_id'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        ///To get single credit card data...
        $singleCreditCardData = CreditCard::where('user_id', Auth::user()->id)->where('id', $request->from_credit_card_id)
                        ->with(['bankData'])->get();

        if(!empty($singleCreditCardData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleCreditCardData'   =>  $singleCreditCardData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get bank, mfs data with type...
    public function getBankMFSDataWithExpenseType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $incomeType = $request->name;
        $singleAccountId = Auth::user()->id;

        //To check account type...
        if($incomeType == 'Banking Expense'){
            //To get all the bank list...
            $getBankIds = Account::where('user_id', $singleAccountId)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $data = Bank::whereIn('id', $getBankIds)->get();
        }else if($incomeType == 'Mobile Wallet Expense'){
            //To get all the bank list...
            $getMFSIds = Account::where('user_id', $singleAccountId)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            $data = MobileWallet::whereIn('id', $getMFSIds)->get();
        }else if($incomeType == 'Credit Card Expense'){
            //To get all the bank list...
            $getBankIds = CreditCard::where('user_id', $singleAccountId)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $data = Bank::whereIn('id', $getBankIds)->get();
        }

        if(isset($data) && !empty($data)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'data'   =>  $data,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }
    
    //To get account data with bank or mfs...
    public function getAccountDataWithBankOrMFSForExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $incomeType = $request->name;

        //To check account type...
        if($incomeType == 'Banking Expense'){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            $accountData = Account::orderBy('id', 'desc')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->where('bank_id', $request->bank_id)->with(['bankData','mobileWalletData'])->get();
        }else if($incomeType == 'Mobile Wallet Expense'){
             $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            $accountData = Account::orderBy('id', 'desc')->where('mobile_wallet_id', '!=', null)
                            ->where('is_inactive', false)->where('mobile_wallet_id', $request->mobile_wallet_id)->with(['bankData','mobileWalletData'])->get();
        }else if($incomeType == 'Credit Card Expense'){
            $accountData = CreditCard::orderBy('id', 'desc')->where('bank_id', $request->bank_id)
                            ->where('is_inactive', false)->with(['bankData'])->get();
        }

        if(isset($accountData) && !empty($accountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountData'   =>  $accountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To get all the expense type data...
    public function getExpenseTypeData()
    {
        ///To get expense type data...
        $expenseTypeData = ExpenseType::getExpenseTypeData();

        if(!empty($expenseTypeData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'expenseTypeData'   =>  $expenseTypeData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get expense source data...
    public function getExpenseSourceData()
    {
        //To fetch all the transactionCategory data with incom category...
        $transactionCategoryData = TransactionCategory::where('category_type', 'Expense')->get();

        if(!empty($transactionCategoryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'transactionCategoryData'   =>  $transactionCategoryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To save expense or transaction data...
    public function addExpenseOrTransactionData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'income_expense_type'=> 'required',
            'transaction_category_id'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');

        $data = $request->all();
        $data['user_id'] = $userId;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['status'] = false;
        $expenseType = $request->income_expense_type;
        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To check account type...
        if($expenseType == 'Banking Expense' || $expenseType == 'Mobile Wallet Expense'){
            $validator = Validator::make($request->all(), [
                'from_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else if($expenseType == 'Credit Card Expense'){
             $validator = Validator::make($request->all(), [
                'from_credit_card_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To check from account id & credit card id...
        $fromAccountId = $request->from_account_id;
        $fromCreditCardId = $request->from_credit_card_id;
        if($fromAccountId != null){
            $request->validate([
                'amount'=> 'required',
            ]);

            $fromAccountData = Account::where('id', $request->from_account_id)->first();

            //To check balance...
            $previousBalance = $fromAccountData->current_balance;
            $debitBalance = $previousBalance - $request->amount;
            if($debitBalance >= 0){
                //To debit expense amount with from account balance...
                $fromAccountData->current_balance -= $request->amount;
                $data['bank_id'] = $fromAccountData->bank_id;
                $data['mobile_wallet_id'] = $fromAccountData->mobile_wallet_id;
            }else{
                return response()->json([
                    'message'   =>  'Sorry, You have no sufficient balance.!',
                    'status_code'   => 500
                ], 500);
            }
        }else if($fromCreditCardId != null){
            $fromAccountData = CreditCard::where('id', $request->from_credit_card_id)->first();

            //To check currency...
            if($fromAccountData->is_dual_currency == true){
                if($request->currency_type == 'BDT Currency'){
                    $request->validate([
                        'amount_bdt'=> 'required',
                    ]);

                    //To check balance...
                    $previousBalanceBDT = $fromAccountData->total_bdt_limit;
                    $debitBalanceBDT = $previousBalanceBDT - $request->amount_bdt;
                    if($debitBalanceBDT >= 0){
                        //To debit expense amount with from credit card balance...
                        $fromAccountData->total_bdt_limit -= $request->amount_bdt;
                        $data['bank_id'] = $fromAccountData->bank_id;
                        $data['amount'] = $request->amount_bdt;
                    }else{
                        return response()->json([
                            'message'   =>  'Sorry, You have no sufficient balance.!',
                            'status_code'   => 500
                        ], 500);
                    }
                }else{
                    $request->validate([
                        'amount_usd'=> 'required',
                    ]);

                    //To check balance...
                    $previousBalanceUSD = $fromAccountData->total_usd_limit;
                    $debitBalanceUSD = $previousBalanceUSD - $request->amount_usd;
                    if($debitBalanceUSD >= 0){
                        //To debit expense amount with from credit card balance...
                        $fromAccountData->total_usd_limit -= $request->amount_usd;
                        $data['bank_id'] = $fromAccountData->bank_id;
                        $data['amount'] = $request->amount_usd;
                    }else{
                        return response()->json([
                            'message'   =>  'Sorry, You have no sufficient balance.!',
                            'status_code'   => 500
                        ], 500);
                    }
                }
            }else{
                $request->validate([
                    'amount'=> 'required',
                ]);

                //To check balance...
                $previousBalance = $fromAccountData->total_limit;
                $debitBalance = $previousBalance - $request->amount;
                if($debitBalance >= 0){
                    //To debit expense amount with from credit card balance...
                    $fromAccountData->total_limit -= $request->amount;
                    $data['bank_id'] = $fromAccountData->bank_id;
                }else{
                    return response()->json([
                        'message'   =>  'Sorry, You have no sufficient balance.!',
                        'status_code'   => 500
                    ], 500);
                }
            }
            
        }else{
            $request->validate([
                'amount'=> 'required',
            ]);

            $fromAccountData = User::where('id', Auth::user()->id)->first();

            //To check balance...
            $previousBalance = $fromAccountData->wallet;
            $debitBalance = $previousBalance - $request->amount;
            if($debitBalance >= 0){
                //To debit expense amount with from account balance...
                $fromAccountData->wallet -= $request->amount;
                $data['pocket_wallet_id'] = $fromAccountData->id;
            }else{
                return response()->json([
                    'message'   =>  'Sorry, You have no sufficient balance.!',
                    'status_code'   => 500
                ], 500);
            }
        }

        if($result = IncomeExpense::create($data)){
            //To save fromAccountData...
            $fromAccountData->save();

            return response()->json([
                'message' => 'Transaction/Expense added successfully.',
                'data'   =>  $result,
                'accountData' => $fromAccountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }


    }


    /*
    //////////////////////////////////////////////////////////////////////
    For Credit Card, Account & Pocket Income, Expense & All Data To Load...
    //////////////////////////////////////////////////////////////////////
    */

    //To get all the credit data with account id...
    public function getCreditDataWithAccountId($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To check account type...
        if($singleAccountData->bank_id != null){
            ///To get debit data...
            $creditData = IncomeExpense::orderBy('id','desc')->where('status', true)->where('bank_id', $singleAccountData->bank_id)
                            ->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Banking Income')
                            ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','transactionCategoryData'])->get();
        }else{
            ///To get debit data...
            $creditData = IncomeExpense::orderBy('id','desc')->where('status', true)->where('mobile_wallet_id', $singleAccountData->mobile_wallet_id)
                            ->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Mobile Wallet Income')
                            ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','transactionCategoryData'])->get();
        }
        

        if(!empty($creditData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditData'   =>  $creditData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }
    
    //To get all the debit data with account id...
    public function getDebitDataWithAccountId($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To check account type...
        if($singleAccountData->bank_id != null){
            ///To get debit data...
            $debitData = IncomeExpense::orderBy('id','desc')->where('status', false)->where('bank_id', $singleAccountData->bank_id)
                        ->where('from_account_id', $singleAccountData->id)
                        ->where('income_expense_type','Banking Expense')->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','fromCreditCardData','transactionCategoryData'])->get();
        }else{
            ///To get debit data...
            $debitData = IncomeExpense::orderBy('id','desc')->where('status', false)->where('mobile_wallet_id', $singleAccountData->mobile_wallet_id)
                        ->where('from_account_id', $singleAccountData->id)
                        ->where('income_expense_type','Mobile Wallet Expense')->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','fromCreditCardData','transactionCategoryData'])->get();
        }

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get all the credit & debit data with account id...
    public function getCreditDebitDataWithAccountId($id)
    {
        ///To get credit & debit data...
        $creditDebitData = IncomeExpense::orderBy('id','desc')->where('from_account_id', $id)
                            ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','fromCreditCardData','transactionCategoryData'])->get();
        

        if(!empty($creditDebitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditDebitData'   =>  $creditDebitData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }


     //To get all the debit data with credit card account id...
    public function getDebitDataWithCreditCardAccountId($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id', $id)->first();

        if(isset($singleAccountData) && $singleAccountData != null){
            ///To get debit data...
            $debitData = IncomeExpense::orderBy('id','desc')->where('status', false)->where('from_credit_card_id', $singleAccountData->id)
                        ->where('income_expense_type','Credit Card Expense')->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','fromCreditCardData','transactionCategoryData'])->get();
        }

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }


}
