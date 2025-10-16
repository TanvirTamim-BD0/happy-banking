<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionCategory;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\User;
use App\Models\Income;
use App\Models\IncomeExpense;
use App\Helpers\CurrentUser;
use App\Helpers\IncomeType;
use Carbon\Carbon;
use Validator;
use Auth;

class IncomeController extends Controller
{
    //To get all the income data...
    public function getIncomeData()
    {
        ///To get income data...
        $incomeData = IncomeExpense::orderBy('id','desc')->where('user_id', Auth::user()->id)->where('status', true)
                        ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData','transactionCategoryData'])->get();

        if(!empty($incomeData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'incomeData'   =>  $incomeData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get all the account to mfs payment data...
    public function getIncomeTypeData()
    {
        ///To get income type data...
        $incomeTypeData = IncomeType::getIncomeTypeData();

        if(!empty($incomeTypeData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'incomeTypeData'   =>  $incomeTypeData,
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
    public function getBankMFSDataWithIncomeType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user & income type...
        $userId = CurrentUser::getUserId();
        $incomeType = $request->name;

        //To check account type...
        if($incomeType == 'Banking Income'){
            //To get all the bank list...
            $getBankIds = Account::where('user_id', $userId)->select('bank_id')->where('bank_id', '!=', null)
            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
                
            //To get all the bank data...
            $data = Bank::orderBy('bank_name', 'asc')->whereIn('id', $getBankIds)->get();
        }else if($incomeType == 'Mobile Wallet Income'){
            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $userId)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
                
            //To get all the mobile wallet data...
            $data = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();
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
    public function getAccountDataWithBankOrMFS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $incomeType = $request->name;

        //To check account type...
        if($incomeType == 'Banking Income'){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            $accountData = Account::orderBy('id', 'desc')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->where('bank_id', $request->bank_id)
                            ->with(['bankData','mobileWalletData'])->get();
        }else if($incomeType == 'Mobile Wallet Income'){
             $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            $accountData = Account::orderBy('id', 'desc')->where('mobile_wallet_id', '!=', null)
                            ->where('is_inactive', false)->where('mobile_wallet_id', $request->mobile_wallet_id)
                            ->with(['bankData','mobileWalletData'])->get();
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

    //To get income source data...
    public function getIncomeSourceData()
    {
        //To fetch all the transactionCategory data with incom category...
        $transactionCategoryData = TransactionCategory::where('category_type', 'Income')->get();

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

    //To save income data...
    public function addIncomeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'income_expense_type'=> 'required',
            'from_account_id'=> 'nullable',
            'transaction_category_id'=> 'required',
            'amount'=> 'required',
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
        $incomeType = $request->income_expense_type;
        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To check account type...
        if($incomeType == 'Banking Income'){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else if($incomeType == 'Mobile Wallet Income'){
             $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To check from account id...
        $fromAccountId = $request->from_account_id;
        if($fromAccountId != null){
            $fromAccountData = Account::where('id', $request->from_account_id)->first();
            //To credit income amount with from account balance...
            $fromAccountData->current_balance += $request->amount;
        }else{
            $fromAccountData = User::where('id', Auth::user()->id)->first();
            //To credit income amount with from account balance...
            $fromAccountData->wallet += $request->amount;
        }

        if($result = IncomeExpense::create($data)){
            //To save fromAccountData...
            $fromAccountData->save();

            return response()->json([
                'message' => 'Income added successfully.',
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
}
