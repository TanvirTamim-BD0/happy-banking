<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\Account;
use App\Models\Bank;
use App\Models\MobileWallet;
use App\Models\IncomeExpense;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CurrentUser;
use App\Helpers\CardType;
use Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Models\FrontendNote;

class IncomeController extends Controller
{
    //To get all the income data...
    public function createIncome(Request $request)
    {

        //To decript...
        $incomeType = Crypt::decrypt($request->income_type);

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check income type...
        if($incomeType == 'Banking Income'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Income Bank')->get('description')->first();
            //To get all the bank list...
            $getBankIds = Account::where('user_id', $userId)->select('bank_id')->where('bank_id', '!=', null)
            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
                
            //To get all the bank data...
            $bankData = Bank::orderBy('bank_name', 'asc')->whereIn('id', $getBankIds)->get();
            $transactionCategoryData = TransactionCategory::where('category_type', 'Income')->get();

            return view('frontend.income.bankingIncome.create', compact('bankData','incomeType','transactionCategoryData','getNote'));
        }else if($incomeType == 'Mobile Wallet Income'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Income MFS')->get('description')->first();

            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $userId)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
                
            //To get all the mobile wallet data...
            $mobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();
            $transactionCategoryData = TransactionCategory::where('category_type', 'Income')->get();

            return view('frontend.income.mfsIncome.create', compact('mobileWalletData','incomeType','transactionCategoryData', 'getNote'));
        }else if($incomeType == 'Pocket Wallet Income'){
            //To get note & transaction category data... 
            $getNote = FrontendNote::where('description_type', 'Income Wallet')->get('description')->first();
            $transactionCategoryData = TransactionCategory::where('category_type', 'Income')->get();

            return view('frontend.income.pocketIncome.create', compact('incomeType','transactionCategoryData', 'getNote'));
        }
    }


    //get account data bank data wise ..........
    public function getAccountBankWise(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $accountData = Account::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
                        ->where('is_inactive', false)->get();
                        
        return response()->json($accountData);
    }


    //get account data mobile wallet data wise ..........
    public function getAccountMobileWalletWise(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'mobile_wallet_id'=> 'required',
        ]);

        $accountData = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                        ->where('is_inactive', false)->get();
        
        return response()->json($accountData);
    }

    //To add income...
    public function saveIncome(Request $request,$incomeType)
    {

       $validator = Validator::make($request->all(), [
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
        $incomeType = $incomeType;

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
            $data['pocket_wallet_id'] = $fromAccountData->id;
        }

        if($result = IncomeExpense::create($data)){
            //To save fromAccountData...
            $fromAccountData->save();

            if ($result->income_expense_type == 'Banking Income') {
                
                $resultData = Crypt::encrypt($result);
                Toastr::success('Transaction/Income added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.income-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id]));
            }
            elseif($result->income_expense_type == 'Mobile Wallet Income'){

                $resultData = Crypt::encrypt($result);
                Toastr::success('Transaction/Income added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.income-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id]));
                
            }
            else{
                
                $resultData = Crypt::encrypt($result);
                $from_account = Auth::user()->id;
                Toastr::success('Transaction/Income added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.income-wallet-invoice',['result'=>$resultData , 'from_account'=>$from_account]));
            }


        }else{
            Toastr::error('Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();

        }



    }


}
