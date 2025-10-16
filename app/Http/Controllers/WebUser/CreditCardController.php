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
use App\Models\CreditCardReminder;
use App\Models\ActiveSession;
use Carbon\Carbon;
use App\Models\FrontendNote;

class CreditCardController extends Controller
{
    //To get single credit card details page...
    public function getSingleCreditCardPage($id)
    {
        //To get single credit card data...
        $singleCreditCardData = CreditCard::where('id', $id)->first();
        return view('frontend.creditCardWalletAccount.creditCardDetails', compact('singleCreditCardData'));
    }

    //To get transfer page with accountId & transferType...
    public function transferCreditCardWalletAccount(Request $request)
    {   
        $accountId = $request->account_id;
        $paymentType = Crypt::decrypt($request->payment_type);
        $accountData = CreditCard::where('id',$accountId)->first();
        
        //To get single credit card data...
        $singleAccountData = CreditCard::where('id',$accountId)->first();
        
        //To check payment type...
        if($paymentType == 'Card To Account'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Credit Card To Bank')->get('description')->first();

            //To get all the bank list...
            $getBankIds = Account::where('user_id', $singleAccountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                                ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $bankData = Bank::whereIn('id', $getBankIds)->get();
            
            return view('frontend.creditCardWalletAccount.cardToBankAccountTransfer',compact('accountData','bankData','paymentType','getNote'));
        }else if($paymentType == 'Card To MFS'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Credit Card To MFS')->get('description')->first();

            //To get all the mobile wallet list...
            $getMobileWalletIds = Account::where('user_id', $singleAccountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                                ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            $getMobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();

            return view('frontend.creditCardWalletAccount.cardToMFSAccountTransfer',compact('accountData','getMobileWalletData','paymentType','getNote'));
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



    //To card to bank transfer...
    public function cardToBankTransfer(Request $request, $id)
    { 
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$id)->with(['bankData'])->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'pay_amount_bdt'=> 'required',
                ]);
                $data['transfer_currency_type'] = $request->currency_type;
                $requestPayAmount = $request->pay_amount_bdt;
            }else if($request->currency_type == 'USD Currency'){
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);
                $data['transfer_currency_type'] = $request->currency_type;
                $data['usd_in_bdt_rate'] = $request->usd_in_bdt_rate;
                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'pay_amount'=> 'required',
            ]);

            $requestPayAmount = $request->pay_amount;
        }

        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payFeeAmount = ($requestPayAmount / 100) * $processingFee;
        $totalPayAmount = $payFeeAmount+$requestPayAmount;
        $data['pay_amount'] = $requestPayAmount;
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
        $checkStatus = $this->calculateFromAndToAccount($id, $request->to_account_id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd, $request->pay_fee);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){

                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.card-tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_account_id]));

            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }




     //To card to mfs transfer...
    public function cardToMFSTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$id)->with(['bankData'])->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'pay_amount_bdt'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_bdt;
            }else{
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'pay_amount'=> 'required',
            ]);

            $requestPayAmount = $request->pay_amount;
        }

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payFeeAmount = ($requestPayAmount / 100) * $processingFee;
        $totalPayAmount = $payFeeAmount+$requestPayAmount;
        $data['pay_amount'] = $requestPayAmount;
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
        $checkStatus = $this->calculateFromAndToAccount($id, $request->to_account_id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd, $request->pay_fee);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                
                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.card-tranjection-invoice',['result'=>$resultData , 'from_account'=>$id ,'to_account'=>$request->to_account_id]));
                
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
    private function calculateFromAndToAccount($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType, $currencyType, $payAmountUSD, $processingFee)
    {
        //To get from & to bankData...
        $fromAccountData = CreditCard::where('id', $fromAccountId)->first();

        //To check transfer type...
        if($transferType == 'Own Account'){
            $toAccountData = Account::where('id', $toAccountId)->first();
        }else{
            $toAccountData = null;
        }

        //To check currency...
        if($fromAccountData->is_dual_currency == true){
            if($currencyType == 'BDT Currency'){
                //To check cuurent payment amount with account balance...
                $currentBalance = $fromAccountData->total_bdt_limit;
                $remainingBalance = $currentBalance-$totalPaymentAmount;

                if($remainingBalance >= 0){
                    //To debit...
                    $fromAccountData->total_bdt_limit -= $totalPaymentAmount;
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
            }else{

                //To calculate processing fee...
                $payFeeAmountUSD = ($payAmountUSD / 100) * $processingFee;;
                $totalPayAmountUSD = $payFeeAmountUSD+$payAmountUSD;
                
                //To check cuurent payment amount with account balance...
                $currentBalance = $fromAccountData->total_usd_limit;
                $remainingBalance = $currentBalance-$totalPayAmountUSD;

                if($remainingBalance >= 0){
                    //To debit...
                    $fromAccountData->total_usd_limit -= $totalPayAmountUSD;
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
        }else{
            //To check cuurent payment amount with account balance...
            $currentBalance = $fromAccountData->total_limit;
            $remainingBalance = $currentBalance-$totalPaymentAmount;
            if($remainingBalance >= 0){
                //To debit...
                $fromAccountData->total_limit -= $totalPaymentAmount;
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



    /*
        //////////////////////////////////////////////////
        For Bank, MFS, Wallet To Card Account Transfer...
        //////////////////////////////////////////////////
    */

    //To get payment type page for credit card account transfer...
    public function paymentTypeForBillPayment($id)
    {
        $accountId = $id;
        return view('frontend.creditCardWalletAccount.paymentTypeForBillPayment',compact('accountId'));
    }

    //To get transfer page with accountId & transferType...
    public function transferCreditCardAccount(Request $request)
    {   
        $accountId = $request->account_id;
        $paymentType = Crypt::decrypt($request->payment_type);
        $accountData = CreditCard::where('id',$accountId)->first();
        
        //To get single account data...
        $singleAccountData = CreditCard::where('id',$accountId)->first();
        
        //To check payment type...
        if($paymentType == 'Account To Card'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bill Payment From Bank')->get('description')->first();

            //To get all the bank list...
            $getBankIds = Account::where('user_id', $singleAccountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
            $bankData = Bank::whereIn('id', $getBankIds)->get();
            
            return view('frontend.creditCardWalletAccount.bankToCardAccountTransfer',compact('accountData','bankData','paymentType', 'getNote'));
        }else if($paymentType == 'MFS To Card'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bill Payment From MFS')->get('description')->first();

            //To get all the mobile wallet list...
            $getMobileWalletIds = Account::where('user_id', $singleAccountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                                    ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
            $getMobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();

            return view('frontend.creditCardWalletAccount.mfsToCardAccountTransfer',compact('accountData','getMobileWalletData','paymentType', 'getNote'));
        }else if($paymentType == 'Wallet To Card'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Bill Payment From Pocket')->get('description')->first();

            return view('frontend.creditCardWalletAccount.pocketToCardAccountTransfer',compact('accountData','paymentType', 'getNote'));
        }
    }

    //To bank to card transfer...
    public function bankToCardTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'from_account_id'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'pay_amount_bdt'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_bdt;
            }else{
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'pay_amount'=> 'required',
            ]);

            $requestPayAmount = $request->pay_amount;
        }

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payFeeAmount = ($requestPayAmount / 100) * $processingFee;
        $totalPayAmount = $payFeeAmount+$requestPayAmount;
        $data['pay_amount'] = $requestPayAmount;
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
        $checkStatus = $this->calculateFromAndToAccountForCard($request->from_account_id, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){

                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.card-bill-payment-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id ,'to_account'=>$id]));

            }else{
                Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Sorry you have no sufficient balance.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To mfs to card transfer...
    public function mfsToCardTransfer(Request $request, $id)
    {
        $request->validate([
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'from_account_id'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'pay_amount_bdt'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_bdt;
            }else{
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'pay_amount'=> 'required',
            ]);

            $requestPayAmount = $request->pay_amount;
        }

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payFeeAmount = ($requestPayAmount / 100) * $processingFee;
        $totalPayAmount = $payFeeAmount+$requestPayAmount;
        $data['pay_amount'] = $requestPayAmount;
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
        $checkStatus = $this->calculateFromAndToAccountForCard($request->from_account_id, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                
                $resultData = Crypt::encrypt($result);

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.card-bill-payment-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id ,'to_account'=>$id]));

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
    private function calculateFromAndToAccountForCard($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType, $currencyType, $payAmountUSD)
    {
        //To get from account data...
        $fromAccountData = Account::where('id', $fromAccountId)->first();
        
        //To check transfer type...
        if($transferType == 'Own Account'){
            $toAccountData = CreditCard::where('id', $toAccountId)->first();
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

    //To Wallet To Card transfer...
    public function pocketToCardTransfer(Request $request, $id)
    {
        $request->validate([
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'from_pocket_account_id'=> 'required',
            'pay_fee'=> 'required',
            'notes'=> 'nullable',
        ]);

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');
        
        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $request->validate([
                    'pay_amount_bdt'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_bdt;
            }else{
                $request->validate([
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $request->validate([
                'pay_amount'=> 'required',
            ]);

            $requestPayAmount = $request->pay_amount;
        }

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;

        //To calculate processing fee...
        $processingFee = $request->pay_fee;
        $payFeeAmount = ($requestPayAmount / 100) * $processingFee;
        $totalPayAmount = $payFeeAmount+$requestPayAmount;
        $data['pay_amount'] = $requestPayAmount;
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
        $checkStatus = $this->calculateFromAndToAccountForCardWithPocketId($request->from_pocket_account_id, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                
                $resultData = Crypt::encrypt($result);
                $from_account_id = Auth::user()->id;

                Toastr::success('Your payment transfered successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('webuser.card-wallet-bill-payment-invoice',['result'=>$resultData , 'from_account'=>$from_account_id ,'to_account'=>$id]));


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
    private function calculateFromAndToAccountForCardWithPocketId($fromAccountId, $toAccountId, $paymentAmount, $totalPaymentAmount, $transferType, $currencyType, $payAmountUSD)
    {
        //To get from account data...
        $fromAccountData = User::where('id', $fromAccountId)->first();
        
        //To check transfer type...
        if($transferType == 'Own Account'){
            $toAccountData = CreditCard::where('id', $toAccountId)->first();
        }else{
            $toAccountData = null;
        }

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


    //credit card reminder .....
    public function creditCardBillReminder($id)
    {   
        $creditCardId = $id;
        $cardReminderData = CreditCardReminder::where('credit_card_id',$id)->get();
        return view('frontend.creditCardWalletAccount.creditCardReminder.index', compact('cardReminderData','creditCardId'));
    }

    public function creditCardBillReminderCreate($id)
    {
        $creditCardId = $id;
        $activeSessionData = ActiveSession::where('status', true)->first();
        return view('frontend.creditCardWalletAccount.creditCardReminder.create', compact('activeSessionData','creditCardId'));
    } 

    //To add new credit card bill reminder
    public function storeCreditCardBillReminder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'credit_card_id'=> 'required',
            'active_session_id'=> 'required',
            'last_payment_date'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        $lastPaymentDate = Carbon::createFromFormat('d-m-Y', $request->last_payment_date)->format('Y-m-d');
        $data['last_payment_date'] = $lastPaymentDate;

        //To get selected account data...
        $selectedAccountData = CreditCard::where('id',$request->credit_card_id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            $request->validate([
                'total_bdt_due'=> 'required',
                'total_usd_due'=> 'required',
                'bdt_minimum_due'=> 'required',
                'usd_minimum_due'=> 'required',
            ]);

            if($request->total_bdt_due > $request->bdt_minimum_due){
                //
            }else{
                Toastr::error('Total bdt due always will be greater than minimum bdt due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
            
            if($request->total_usd_due > $request->usd_minimum_due){
                //
            }else{
                Toastr::error('Total usd due always will be greater than minimum usd due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }

        }else{
            $request->validate([
                'total_due'=> 'required',
                'minimum_due'=> 'required',
            ]);

            if($request->total_due > $request->minimum_due){
                //
            }else{
                Toastr::error('Total due always will be greater than minimum due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }


        if($result = CreditCardReminder::create($data)){
            Toastr::success('CreditCardReminder created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.credit-card-bill-reminder',$request->credit_card_id);
        }else{
           Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }  

    public function creditCardBillReminderEdit($creditCardId, $cardReminderId)
    {
        //To get single credit card reminder & active session data...
        $singleCardReminderData = CreditCardReminder::where('id', $cardReminderId)->first();
        $activeSessionData = ActiveSession::where('status', true)->first();
        $lastPaymentDate = Carbon::createFromFormat('Y-m-d', $singleCardReminderData->last_payment_date)->format('d-m-Y');

        return view('frontend.creditCardWalletAccount.creditCardReminder.edit', compact('activeSessionData','creditCardId','singleCardReminderData','lastPaymentDate'));
    }

    //To update new credit card bill reminder
    public function updateCreditCardBillReminder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credit_card_reminder_id'=> 'required',
            'credit_card_id'=> 'required',
            'active_session_id'=> 'required',
            'last_payment_date'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        $lastPaymentDate = Carbon::createFromFormat('d-m-Y', $request->last_payment_date)->format('Y-m-d');
        $data['last_payment_date'] = $lastPaymentDate;

        //To get selected account & single card reminder data...
        $selectedAccountData = CreditCard::where('id',$request->credit_card_id)->first();
        $singleCardReminderData = CreditCardReminder::where('id', $request->credit_card_reminder_id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            $request->validate([
                'total_bdt_due'=> 'required',
                'total_usd_due'=> 'required',
                'bdt_minimum_due'=> 'required',
                'usd_minimum_due'=> 'required',
            ]);

            if($request->total_bdt_due > $request->bdt_minimum_due){
                //
            }else{
                Toastr::error('Total bdt due always will be greater than minimum bdt due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
            
            if($request->total_usd_due > $request->usd_minimum_due){
                //
            }else{
                Toastr::error('Total usd due always will be greater than minimum usd due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{
            $request->validate([
                'total_due'=> 'required',
                'minimum_due'=> 'required',
            ]);

            if($request->total_due > $request->minimum_due){
                //
            }else{
                Toastr::error('Total due always will be greater than minimum due.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }

        if($singleCardReminderData->update($data)){
            Toastr::success('CreditCardReminder created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.credit-card-bill-reminder',$request->credit_card_id);
        }else{
           Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    public function changeStatusCreditCardBillReminder(Request $request)
    {
        //To get single credit card reminder & active session data...
        $singleCardReminderData = CreditCardReminder::where('id', $request->credit_card_reminder_id)->first();

        if($singleCardReminderData->status == true){
            $singleCardReminderData->status = false;
        }else{
            $singleCardReminderData->status = true;
        }

        if($singleCardReminderData->save()){
            Toastr::success('CreditCardReminder status changed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //For credit card currency chage...
    public function cardCurrency($id)
    {
        //To get single credit card data...
        $singleCreditCardData = CreditCard::where('id', $id)->first();
        return view('frontend.creditCardWalletAccount.creditCardCurrency', compact('singleCreditCardData'));
    }
    
    //For credit card currency enable...
    public function enableCurrency(Request $request, $id)
    {
        $request->validate([
            'total_bdt_limit'=> 'required',
            'total_usd_limit'=> 'required',
        ]);
        
        //To get single credit card data...
        $singleCreditCardData = CreditCard::where('id', $id)->first();
        $singleCreditCardData->total_bdt_limit = $request->total_bdt_limit;
        $singleCreditCardData->total_usd_limit = $request->total_usd_limit;
        $singleCreditCardData->is_dual_currency = true;
        if($singleCreditCardData->save()){
            Toastr::success('Card currency changed successfully.', 'Euccess', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //For credit card currency disable...
    public function disableCurrency(Request $request, $id)
    {
        $request->validate([
            'total_bdt_limit'=> 'nullable',
            'total_usd_limit'=> 'nullable',
        ]);
        
        //To get single credit card data...
        $singleCreditCardData = CreditCard::where('id', $id)->first();
        $singleCreditCardData->total_bdt_limit = $request->total_bdt_limit;
        $singleCreditCardData->total_usd_limit = $request->total_usd_limit;
        $singleCreditCardData->is_dual_currency = false;
        if($singleCreditCardData->save()){
            Toastr::success('Card currency changed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

}
