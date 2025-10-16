<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\Account;
use App\Models\User;
use App\Models\Bank;
use App\Models\MobileWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CurrentUser;
use App\Helpers\CardType;
use Auth;
use App\Models\AccountPayment;
use App\Models\Beneficiary;
use App\Models\TransactionCategory;
use App\Models\IncomeExpense;
use Illuminate\Support\Facades\Crypt;
use App\Models\FrontendNote;

class PocketAccountController extends Controller
{
    //To get transfer page with accountId & transferType...
    public function transferPocketWalletAccount(Request $request)
    {
        $accountId = Auth::user()->id;
        $paymentType = Crypt::decrypt($request->payment_type);
        $accountData = User::where('id',$accountId)->first();

        //To get single account data...
        $singleAccountData = User::where('id',$accountId)->first();

        //To check income type...
        if($paymentType == 'Wallet To Account'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Wallet To Account')->get('description')->first();

            //To get all the bank & transaction category data list...
            $getBankIds = Account::where('user_id', $singleAccountData->id)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $bankData = Bank::whereIn('id', $getBankIds)->get();

            return view('frontend.pocketWalletAccount.pocketToBankAccountTransfer', compact('bankData','paymentType','getNote'));
        }else if($paymentType == 'Wallet To MFS'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Wallet To MFS')->get('description')->first();

            //To get all the bank & transaction category data list...
            $getMFSIds = Account::where('user_id', $singleAccountData->id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            $mobileWalletData = MobileWallet::whereIn('id', $getMFSIds)->get();

            return view('frontend.pocketWalletAccount.pocketToMFSAccountTransfer', compact('mobileWalletData','paymentType','getNote'));
        }
        else if($paymentType == 'Wallet To Card'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Wallet To Credit Card')->get('description')->first();

            //To get all the bank list...
            $getBankIds = CreditCard::where('user_id', $singleAccountData->id)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $bankData = Bank::whereIn('id', $getBankIds)->get();
            
            return view('frontend.pocketWalletAccount.pocketToCardAccountTransfer', compact('bankData','paymentType', 'getNote'));
        }
        else{
            return view('frontend.income.pocketIncome.pocketToBankAccountTransfer', compact('paymentType'));
        }
    }

    //To get account data bank data wise ..........
    public function getAccountWithBankWise(Request $request)
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

    //To get account data mobile wallet data wise ..........
    public function getAccountWithMobileWalletWise(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'mobile_wallet_id'=> 'required',
        ]);

        $accountData = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                        ->where('is_inactive', false)->get();
                        
        return response()->json($accountData);
    }

    //To pocket to bank account transfer...
    public function pocketToBankTransfer(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_amount'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = User::where('id', Auth::user()->id)->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->id;
        $data['from_pocket_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['pay_fee'] = 0.00;
        $data['pay_fee_amount'] = 0.00;
        $data['total_pay_amount'] = $request->pay_amount;

        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $totalPayAmount = $request->pay_amount;
        $checkStatus = $this->calculateFromAndToAccount($selectedAccountData->id, $request->to_account_id, $request->pay_amount, $totalPayAmount);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){

                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.wallet-tranjection-invoice',['result'=>$resultData , 'from_account'=>$selectedAccountData->id ,'to_account'=>$request->to_account_id]));

            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To Wallet To MFS account transfer...
    public function pocketToMFSTransfer(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_amount'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = User::where('id', Auth::user()->id)->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->id;
        $data['from_pocket_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['pay_fee'] = 0.00;
        $data['pay_fee_amount'] = 0.00;
        $data['total_pay_amount'] = $request->pay_amount;

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $totalPayAmount = $request->pay_amount;
        $checkStatus = $this->calculateFromAndToAccount($selectedAccountData->id, $request->to_account_id, $request->pay_amount, $totalPayAmount);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){

                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.wallet-tranjection-invoice',['result'=>$resultData , 'from_account'=>$selectedAccountData->id ,'to_account'=>$request->to_account_id]));

            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To calculate account payment with fromAccount & toAccount...
    private function calculateFromAndToAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount)
    {
        //To get from & to account data...
        $fromAccountData = User::where('id', $fromAccountId)->first();
        $toAccountData = Account::where('id', $toAccountId)->first();

        //To check cuurent payment amount with account balance...
        $currentBalance = $fromAccountData->wallet;
        $remainingBalance = $currentBalance-$totalPaymentAmount;
        if($remainingBalance >= 0){
            //To debit...
            $fromAccountData->wallet -= $totalPaymentAmount;
            if($fromAccountData->save()){
                //To check to account data is null or not...
                if($toAccountData != null){
                    //To credit...
                    $toAccountData->current_balance += $paymentAmount;
                    $toAccountData->save();
                }

                return true;
            }
        }else{
            return false;
        }
        
    }


    //get bank wise credit card data ........
    public function getBankWiseCreditCardData(Request $request)
    {
        $creditCardData = CreditCard::where('bank_id',$request->bank_id)->where('user_id', Auth::user()->id)
                            ->where('is_inactive', false)->get();
        return response()->json($creditCardData);
    }


    public function pocketToCardTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'to_credit_card_id'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');

        //To get selected account data...
        $selectedCardAccountData = CreditCard::where('id',$request->to_credit_card_id)->first();

        //To check currency...
        if($selectedCardAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'amount_bdt'=> 'required',
                ]);

                $requestPayAmount = $request->amount_bdt;
            }else{
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'amount_usd'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'amount'=> 'required',
            ]);

            $requestPayAmount = $request->amount;
        }
        
        //To get selected account data...
        $selectedAccountData = User::where('id', Auth::user()->id)->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->id;
        $data['from_pocket_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['pay_fee'] = 0.00;
        $data['pay_fee_amount'] = 0.00;
        $data['pay_amount'] = $requestPayAmount;
        $data['total_pay_amount'] = $requestPayAmount;

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $totalPayAmount = $requestPayAmount;
        $checkStatus = $this->calculateFromPocketToCardAccount($selectedAccountData->id, $request->to_credit_card_id, $requestPayAmount, $totalPayAmount, $request->currency_type, $request->amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){

                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.wallet-tranjection-invoice',['result'=>$resultData , 'from_account'=>$selectedAccountData->id ,'to_account'=>$request->to_credit_card_id]));
                
            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

    }


     //To calculate account payment with fromAccount & toAccount...
    private function calculateFromPocketToCardAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $currencyType, $payAmountUSD)
    {
        //To get from & to account data...
        $fromAccountData = User::where('id', $fromAccountId)->first();
        $toAccountData = CreditCard::where('id', $toAccountId)->first();

        //To check cuurent payment amount with account balance...
        $currentBalance = $fromAccountData->wallet;
        $remainingBalance = $currentBalance-$totalPaymentAmount;
        if($remainingBalance >= 0){
            //To debit...
            $fromAccountData->wallet -= $totalPaymentAmount;
            if($fromAccountData->save()){
                //To check to account data is null or not...
                if($toAccountData != null){

                    //To check currency...
                    if($toAccountData->is_dual_currency == true){
                        if($currencyType == 'BDT Currency'){
                            //To credit...
                            $toAccountData->total_bdt_limit += $paymentAmount;
                            $toAccountData->save();
                        }else{
                            //To credit...
                            $toAccountData->total_usd_limit += $payAmountUSD;
                            $toAccountData->save();
                        }
                    }else{
                        //To credit...
                        $toAccountData->total_limit += $paymentAmount;
                        $toAccountData->save();
                    }
                }

                return true;
            }
        }else{
            return false;
        }
        
    }


}
