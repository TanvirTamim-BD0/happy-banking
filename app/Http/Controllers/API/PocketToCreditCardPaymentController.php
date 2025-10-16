<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\AccountPayment;
use App\Models\Beneficiary;
use App\Models\User;
use App\Helpers\CurrentUser;
use App\Helpers\TransferType;
use App\Helpers\PaymentType;
use Carbon\Carbon;
use Validator;
use Auth;

class PocketToCreditCardPaymentController extends Controller
{
    //To get all the pocket to credit card payment data...
    public function getPocketToCreditCardPaymentData()
    {
        ///To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the account payment data...
        $accountPaymentData = AccountPayment::orderBy('id','DESC')->where('payment_type', 'Wallet To Card')->where('user_id', $userId)
                                ->with(['fromAccountData','toAccountData','toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'
                                ,'creditCardData','toCreditCardData'])->get();

        if(!empty($accountPaymentData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountPaymentData'   =>  $accountPaymentData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get account details with selected account id...
    public function getAccountDetailsWSA()
    {
        //To get single account data...
        $singleAccountData = User::where('id', Auth::user()->id)->first();

        //To get all the bank list...
        $getBankIds = CreditCard::where('user_id', $singleAccountData->id)->select('bank_id')->where('bank_id', '!=', null)
                        ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
        $getBankData = Bank::whereIn('id', $getBankIds)->get();

        //To get account payment type data...
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
                'bankData'   =>  $getBankData,
                'paymentTypeData'   =>  $paymentTypeData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get account data with bank id...
    public function getAccountDataWithBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get all the account data...
        $accountData = CreditCard::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
                        ->where('is_inactive', false)->with(['bankData'])->get();
        

        if(!empty($accountData)){
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

    //To Wallet To Card transfer...
    public function pocketToCreditCardPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'currency_type'=> 'required',
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
        $selectedAccountData = CreditCard::where('id',$request->to_credit_card_id)->first();

        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            if($request->currency_type == 'BDT Currency'){
                $validator = Validator::make($request->all(), [
                    'pay_amount_bdt'=> 'required',
                ]);

                if ($validator->fails()) {
                    return response(['errors'=>$validator->errors()->all()], 422);
                }

                $requestPayAmount = $request->pay_amount_bdt;
            }else{
                $validator = Validator::make($request->all(), [
                    'pay_amount_convert_bdt'=> 'required',
                    'pay_amount_usd'=> 'required',
                ]);

                if ($validator->fails()) {
                    return response(['errors'=>$validator->errors()->all()], 422);
                }

                $requestPayAmount = $request->pay_amount_convert_bdt;
            }
        }else{
            $validator = Validator::make($request->all(), [
                'pay_amount'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            $requestPayAmount = $request->pay_amount;
        }

        //To check transfer type...
        if($request->transfer_type != 'Own Account'){
            return response()->json([
                'message'   =>  'Sorry transfer type will be always [Own account].',
                'status_code'   => 500
			], 500);
        }

        //To check payment type...
        if($request->payment_type != 'Wallet To Card'){
            return response()->json([
                'message'   =>  'Sorry payment type will be always [Wallet To Card].',
                'status_code'   => 500
			], 500);
        }

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['from_pocket_account_id'] = Auth::user()->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['pay_amount'] = $requestPayAmount;
        $data['pay_fee'] = "0.00";
        $data['pay_fee_amount'] = "0.00";
        $data['total_pay_amount'] = $requestPayAmount;

        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $fromAccountId = Auth::user()->id;
        $checkStatus = $this->calculateFromAndToAccountForCardWithPocketId($fromAccountId, $selectedAccountData->id, $request->pay_amount, $request->total_pay_amount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = User::where('id', $fromAccountId)->first();
                $toAccountDetails = CreditCard::where('id', $selectedAccountData->id)->with(['bankData'])->first();

                return response()->json([
                    'message' => 'Your payment transfered successfully.',
                    'data'   =>  $result,
                    'fromAccountData' => $fromAccountDetails,
                    'toAccountData' => $toAccountDetails,
                    'status_code'   => 201
                ], 201);
            }else{
                return response()->json([
                    'message'   =>  'Something is wrong.',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no sufficient balance.',
                'status_code'   => 500
            ], 500);
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
}
