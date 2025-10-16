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

class BankingAccountController extends Controller
{
    //To get single bank account details page...
    public function getSingleBankAccountPage($id)
    {
        //To get single bank account data...
        $singleAccountData = Account::where('id', $id)->first();
        return view('frontend.bankingWalletAccount.bankAccountDetails', compact('singleAccountData'));
    }

    //To get payment type page for bank account transfer...
    public function paymentTypeForBankingWalletAccountTransfer($id)
    {
        $accountId = $id;
        return view('frontend.bankingWalletAccount.paymentTypeForBankingAccountTransfer',compact('accountId'));
    }
    
    //To get transfer page with accountId & transferType...
    public function transferBankingWalletAccount(Request $request)
    {   
        $accountId = $request->account_id;
        $paymentType = Crypt::decrypt($request->payment_type);
        $accountData = Account::where('id',$accountId)->first();
        
        //To get single account data...
        $singleAccountData = Account::where('id',$accountId)->first();

        //To get total account number...
        $getTotalAccountNumber = Account::getTotalAccountNumber($singleAccountData->id);
        if($getTotalAccountNumber > 1){
            //To get all the bank list...
            $getBankIds = Account::where('user_id', $singleAccountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                                    ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
        }else{
            //To get all the bank list...
            $getBankIds = Account::where('user_id', $singleAccountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                                    ->whereNotIn('bank_id', [$singleAccountData->bank_id])->where('is_inactive', false)
                                    ->groupBy('bank_id')->pluck('bank_id')->toArray();
        }
        
        //To get all the bank data...
        $bankData = Bank::whereIn('id', $getBankIds)->get();
        
        //To check payment type...
        if($paymentType == 'Account To Account'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bank To Bank')->get('description')->first();

            return view('frontend.bankingWalletAccount.bankToBankAccountTransfer',compact('accountData','bankData','paymentType', 'getNote'));
        }else if($paymentType == 'Account To MFS'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bank To MFS')->get('description')->first();

            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $singleAccountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                                    ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            //To get all the mobile wallet data...
            $mobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();


            return view('frontend.bankingWalletAccount.bankToMFSAccountTransfer',compact('accountData','mobileWalletData','paymentType', 'getNote'));
        }else if($paymentType == 'Account To Wallet'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bank To Wallet')->get('description')->first();

            return view('frontend.bankingWalletAccount.bankToPocketAccountTransfer',compact('accountData','bankData','paymentType', 'getNote'));
        }
    }

    //To get bank wise account data.....
    public function getBankWiseAccountData(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
            'transfer_type'=> 'required',
            'account_id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $accountData = Account::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
                            ->where('is_inactive', false)->whereNotIn('id', [$request->account_id])->get();
        }elseif(($request->transfer_type == 'Beneficiary Account')){
            $accountData = Beneficiary::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)->get();
        }

        return response()->json($accountData);
    }

    //To get beneficiary bank list data ..
    public function getBeneficiaryBankListData()
    {
        $getBankIds = Beneficiary::where('bank_id', '!=', null)->where('user_id', Auth::user()->id)
                        ->select('bank_id')->groupBy('bank_id')->pluck('bank_id')->toArray();
        $getBankData = Bank::whereIn('id', $getBankIds)->get();

        return response()->json($getBankData);
    }

    //To bank to bank account transfer...
    public function bankToBankTransfer(Request $request,$id)
    {   
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'pay_amount'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $validator = Validator::make($request->all(), [
                'to_account_id'=> 'required',
                'transfer_channel'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'to_beneficiary_account_id'=> 'required',
                'transfer_channel'=> 'required',
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
        $payFeeAmount = $processingFee;
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
        $checkStatus = $this->calculateBankToBankAccount($id, $request->to_account_id, $request->pay_amount, $totalPayAmount, $request->transfer_type);
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
                return redirect(route('webuser.banking-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account'));
        }
    }

    //To calculate account payment with fromAccount & toAccount...
    private function calculateBankToBankAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType)
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


    //get banefiaciary mobile wallet data ...
    public function getBeneficiaryMobileWalletData()
    {
        $getMobileWalletIds = Beneficiary::where('mobile_wallet_id', '!=', null)->where('user_id', Auth::user()->id)
                                ->select('mobile_wallet_id')->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
        $getMobileWalletData = MobileWallet::whereIn('id', $getMobileWalletIds)->get();

        return response()->json($getMobileWalletData);
    }


    //To bank to mfs account transfer...
    public function bankToMFSTransfer(Request $request,$id)
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
        $selectedAccountData = Account::where('id',$id)->first();

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
        $checkStatus = $this->calculateBankToMFSAccount($id, $request->to_account_id, $request->pay_amount, $request->pay_amount, $request->transfer_type);
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
                return redirect(route('webuser.banking-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account'));
        }

    }



    //To calculate account payment with fromAccount & toAccount...
    private function calculateBankToMFSAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType)
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

    //To bank to pocket account transfer...
    public function bankToPocketTransfer(Request $request,$id)
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
        $selectedAccountData = Account::where('id',$id)->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_account_id'] = $selectedAccountData->id;
        $data['total_pay_amount'] = $request->pay_amount;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payAmount = $request->pay_amount;
        $payFeeAmount = $processingFee;
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
        $checkStatus = $this->calculateBankToPocketAccount($id, $toAccountId, $request->pay_amount, $request->pay_amount);
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
                return redirect(route('webuser.banking-wallet-account'));
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account'));
        }

    }


    //To calculate account payment with fromAccount & toAccount...
    private function calculateBankToPocketAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount)
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


}
