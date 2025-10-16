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

class CardToMFSPaymentController extends Controller
{
    //To get all the card to mfs payment data...
    public function getCardToMFSPaymentData()
    {
        ///To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the account payment data...
        $accountPaymentData = AccountPayment::orderBy('id','DESC')->where('payment_type', 'Card To MFS')->where('user_id', $userId)
                                ->with(['fromAccountData','toAccountData','toBeneficiaryAccountData','creditCardData'])->get();

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
    public function getAccountDetailsWSA($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id',$id)->with(['bankData'])->first();

        //To get all the mobile wallet list...
        $getMFSIds = Account::where('user_id', $singleAccountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                        ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
        $getMFSData = MobileWallet::whereIn('id', $getMFSIds)->get();

        //To get account transfer & payment type data...
        $transferTypeData = TransferType::getTransferTypeData();
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
                'mobileWalletData'   =>  $getMFSData,
                'transferTypeData'   =>  $transferTypeData,
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

    //To get mobile wallet list data with transfer type...
    public function getMobileWalletListData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transfer_type'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get single account data...
        $singleAccountData = CreditCard::where('id',$id)->with(['bankData'])->first();

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $singleAccountData->user_id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
        }else{
            return response()->json([
				'message'   =>  'Sorry transfer type will be always [Own account].',
                'status_code'   => 500
			], 500);
        }

        //To get all the mobile wallet data...
        $getMobileWalletData = MobileWallet::whereIn('id', $getMobileWalletIds)->get();

        if(!empty($getMobileWalletData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'mobileWalletData'   =>  $getMobileWalletData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }

    }
    
    //To get account data with mobile wallet id...
    public function getAccountDataWithMobileWallet(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mobile_wallet_id'=> 'required',
            'transfer_type'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $accountData = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                            ->where('is_inactive', false)->whereNotIn('id', [$id])->with(['bankData','mobileWalletData'])->get();
        }else{
            return response()->json([
				'message'   =>  'Sorry transfer type will be always [Own account].',
                'status_code'   => 500
			], 500);
        }
        

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

    // To card to mfs payment...
    public function cardToMFSPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_fee'=> 'required',
            'pay_fee_amount'=> 'required',
            'total_pay_amount'=> 'required',
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
            $request->validate([
                'currency_type'=> 'required',
            ]);

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

        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To check transfer type...
        if($request->transfer_type != 'Own Account'){
            return response()->json([
                'message'   =>  'Sorry transfer type will be always [Own account].',
                'status_code'   => 500
			], 500);
        }

        //To check payment type...
        if($request->payment_type != 'Card To MFS'){
            return response()->json([
                'message'   =>  'Sorry payment type will be always [Card To MFS].',
                'status_code'   => 500
			], 500);
        }

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccount($id, $request->to_account_id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd, $request->pay_fee);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = CreditCard::where('id', $id)->with(['bankData'])->first();
                $toAccountDetails = Account::where('id', $request->to_account_id)->with(['bankData','mobileWalletData'])->first();
                
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
}
