<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\Account;
use App\Models\Bank;
use App\Models\MobileWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CurrentUser;
use App\Helpers\CardType;
use Auth;
use App\Models\AccountPayment;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\IncomeExpense;
use App\Models\FrontendNote;

class MfsAccountController extends Controller
{
    //To get single mobile account details page...
    public function getSingleMobileAccountPage($id)
    {
        //To get single mobile account data...
        $singleAccountData = Account::where('id', $id)->first();
        return view('frontend.mobileWalletAccount.mobileAccountDetails', compact('singleAccountData'));
    }

    //To get payment type page for bank account transfer...
    public function paymentTypeForMobileWalletAccountTransfer($id)
    {
        $accountId = $id;
        return view('frontend.mobileWalletAccount.paymentTypeForMobileAccountTransfer',compact('accountId'));
    }


    //To get transfer page with accountId & transferType...
    public function transferMobileWalletAccount(Request $request)
    {   
        $accountId = $request->account_id;
        $paymentType = Crypt::decrypt($request->payment_type);
        $accountData = Account::where('id',$accountId)->first();
        
        //To get single account data...
        $singleAccountData = Account::where('id',$accountId)->first();

        //To get total account number...
        $getTotalAccountNumber = Account::getTotalAccountNumber($accountData->id);
        if($getTotalAccountNumber > 1){
            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $accountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                                    ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            
            //To get all the mobile wallet data...
            $getMobileWalletData = MobileWallet::whereIn('id', $getMobileWalletIds)->get();
        }else{
            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $accountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                                    ->whereNotIn('mobile_wallet_id', [$accountData->mobile_wallet_id])->where('is_inactive', false)
                                    ->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            
            //To get all the mobile wallet data...
            $getMobileWalletData = MobileWallet::whereIn('id', $getMobileWalletIds)->get();
        }
        
        //To check payment type...
        if($paymentType == 'MFS To MFS'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'MFS To MFS')->get('description')->first();

            return view('frontend.mobileWalletAccount.mfsToMFSAccountTransfer',compact('accountData','getMobileWalletData','paymentType', 'getNote'));
        }else if($paymentType == 'MFS To Account'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'MFS To Bank')->get('description')->first();

            //To get all the bank list...
            $getBankIds = Account::where('user_id', $accountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
                
            //To get all the bank data...
            $bankData = Bank::orderBy('bank_name', 'asc')->whereIn('id', $getBankIds)->get();
                
            return view('frontend.mobileWalletAccount.mfsToBankAccountTransfer',compact('accountData','paymentType','bankData', 'getNote'));
        }else if($paymentType == 'MFS To Wallet'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'MFS To Wallet')->get('description')->first();

            return view('frontend.mobileWalletAccount.mfsToPocketAccountTransfer',compact('accountData','paymentType', 'getNote'));
        }
    }


     //get data mobile wallet wise account data....
     public function getMobileWalletWiseAccountData(Request $request)
     {
        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $accountData = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                            ->where('is_inactive', false)->whereNotIn('id', [$request->accountId])->get();
        }elseif(($request->transfer_type == 'Beneficiary Account')){
            $accountData = Beneficiary::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)->get();
        }

        return response()->json($accountData);
     }


    //To get beneficiary  mobile account data.........
    public function getBeneficiaryMobileWalletAccountData()
    {
        $getMobileWalletIds = Beneficiary::where('mobile_wallet_id', '!=', null)->where('user_id', Auth::user()->id)
                                ->select('mobile_wallet_id')->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();

        //To get all the mobile wallet data...
        $getMobileWalletData = MobileWallet::whereIn('id', $getMobileWalletIds)->get();
        return response()->json($getMobileWalletData);
    }

    //To mfs to mfs account transfer...
    public function mfsToMFSTransfer(Request $request,$id)
    {   
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'pay_fee'=> 'required',
            'pay_amount'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $validator = Validator::make($request->all(), [
                'to_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'to_beneficiary_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();


        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_account_id'] = $selectedAccountData->id;
        $data['total_pay_amount'] = $request->pay_amount;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payAmount = $request->pay_amount;
        $payFeeAmount = $processingFee;;
        $totalPayAmount = $payFeeAmount+$request->pay_amount;
        if (is_numeric($payFeeAmount) && strpos($payFeeAmount, '.') !== false) {
            $data['pay_fee_amount'] = number_format($payFeeAmount, 2);
        }else{
            $data['pay_fee_amount'] = $payFeeAmount;
        };
        if (is_numeric($totalPayAmount) && strpos($totalPayAmount, '.') !== false) {
            $data['total_pay_amount'] = number_format($totalPayAmount, 2);
        }else{
            $data['total_pay_amount'] = $totalPayAmount;
        };

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;
        

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccount($id, $request->to_account_id, $request->pay_amount, $totalPayAmount, $request->transfer_type);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                if($request->transfer_type == 'Own Account'){
                    return redirect(route('webuser.tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_account_id]));
                }else{
                    return redirect(route('webuser.tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_beneficiary_account_id]));
                }
            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect(route('webuser.mobile-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.mobile-wallet-account'));
        }
    }

    //To calculate account payment with fromAccount & toAccount...
    private function calculateFromAndToAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType)
    {
        //To get from & to bankData...
        $fromAccountData = Account::where('id', $fromAccountId)->first();

        //To check transfer type...
        if($transferType == 'Own Account'){
            $toAccountData = Account::where('id', $toAccountId)->first();
        }else{
            $toAccountData = null;
        }

        //To check cuurent payment amount with account balance...
        $currentBalance = $fromAccountData->current_balance;
        $remainingBalance = $currentBalance-$totalPaymentAmount;
        if($remainingBalance >= 0){
            //To debit...
            $fromAccountData->current_balance -= $totalPaymentAmount;
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

    //To MFS To Wallet transfer .......
    public function mfsToPocketTransfer(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'pay_amount'=> 'required',
            'transfer_type'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payAmount = $request->pay_amount;
        $payFeeAmount = ($payAmount / 100) * $processingFee;;
        $totalPayAmount = $payFeeAmount+$request->pay_amount;
        if (is_numeric($payFeeAmount) && strpos($payFeeAmount, '.') !== false) {
            $data['pay_fee_amount'] = number_format($payFeeAmount, 2);
        }else{
            $data['pay_fee_amount'] = $payFeeAmount;
        };
        if (is_numeric($totalPayAmount) && strpos($totalPayAmount, '.') !== false) {
            $data['total_pay_amount'] = number_format($totalPayAmount, 2);
        }else{
            $data['total_pay_amount'] = $totalPayAmount;
        };

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $toAccountId = Auth::user()->id;
        $checkStatus = $this->calculateMFSToPocketAccount($id, $toAccountId, $request->pay_amount, $request->pay_amount);
        if($checkStatus == true){
            $data['to_pocket_account_id'] = $toAccountId;

            //To save account payment data...
            if($result = AccountPayment::create($data)){
                $resultData = Crypt::encrypt($result);
                $to_account_id = Auth::user()->id;
                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.tranjections-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$to_account_id]));
            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect(route('webuser.mobile-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.mobile-wallet-account'));
        }

    }

    //To calculate account payment with fromAccount & toAccount...
    private function calculateMFSToPocketAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount)
    {
        //To get from & to account data...
        $fromAccountData = Account::where('id', $fromAccountId)->first();
        $toAccountData = User::where('id', $toAccountId)->first();

        //To check cuurent payment amount with account balance...
        $currentBalance = $fromAccountData->current_balance;
        $remainingBalance = $currentBalance-$totalPaymentAmount;
        if($remainingBalance >= 0){
            //To debit...
            $fromAccountData->current_balance -= $totalPaymentAmount;
            if($fromAccountData->save()){
                //To check to account data is null or not...
                if($toAccountData != null){
                    //To credit...
                    $toAccountData->wallet += $paymentAmount;
                    $toAccountData->save();
                }

                return true;
            }
        }else{
            return false;
        }
        
    }

    //To get account bank wise........
    public function getAccountDataBankWise(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
            'transfer_type'=> 'required',
        ]);

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $accountData = Account::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)->get();
        }elseif(($request->transfer_type == 'Beneficiary Account')){
            $accountData = Beneficiary::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)->get();
        }

        return response()->json($accountData);
    }

    //To mfs to bank transfer .......
    public function mfsToBankTransfer(Request $request,$id)
    {   
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'pay_amount'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $validator = Validator::make($request->all(), [
                'to_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'to_beneficiary_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payAmount = $request->pay_amount;
        $payFeeAmount = ($payAmount / 100) * $processingFee;;
        $totalPayAmount = $payFeeAmount+$request->pay_amount;
        if (is_numeric($payFeeAmount) && strpos($payFeeAmount, '.') !== false) {
            $data['pay_fee_amount'] = number_format($payFeeAmount, 2);
        }else{
            $data['pay_fee_amount'] = $payFeeAmount;
        };
        if (is_numeric($totalPayAmount) && strpos($totalPayAmount, '.') !== false) {
            $data['total_pay_amount'] = number_format($totalPayAmount, 2);
        }else{
            $data['total_pay_amount'] = $totalPayAmount;
        };

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;


        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateMFSToBankAccount($id, $request->to_account_id, $request->pay_amount, $totalPayAmount, $request->transfer_type);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                if($request->transfer_type == 'Own Account'){
                    return redirect(route('webuser.tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_account_id]));
                }else{
                    return redirect(route('webuser.tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_beneficiary_account_id]));
                }
            }else{
               Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect(route('webuser.mobile-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.mobile-wallet-account'));
        }

    }

    //To calculate account payment with fromAccount & toAccount...
    private function calculateMFSToBankAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType)
    {
        //To get from & to bankData...
        $fromAccountData = Account::where('id', $fromAccountId)->first();

        //To check transfer type...
        if($transferType == 'Own Account'){
            $toAccountData = Account::where('id', $toAccountId)->first();
        }else{
            $toAccountData = null;
        }

        //To check cuurent payment amount with account balance...
        $currentBalance = $fromAccountData->current_balance;
        $remainingBalance = $currentBalance-$totalPaymentAmount;
        if($remainingBalance >= 0){
            //To debit...
            $fromAccountData->current_balance -= $totalPaymentAmount;
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



}

