<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\AccountPayment;
use App\Models\Beneficiary;
use App\Models\User;
use App\Helpers\CurrentUser;
use App\Helpers\TransferType;
use App\Helpers\PaymentType;
use Carbon\Carbon;
use Validator;
use Auth;


class PocketToMFSPaymentController extends Controller
{
    //To get all the Wallet To MFS payment data...
    public function getPocketToMFSPaymentData()
    {
        ///To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the account payment data...
        $accountPaymentData = AccountPayment::orderBy('id','DESC')->where('payment_type', 'Wallet To MFS')->where('user_id', $userId)
                                ->with(['fromAccountData','toAccountData','toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->get();

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

        //To get all the mobile wallet list...
        $getMFSIds = Account::where('user_id', $singleAccountData->id)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
                        ->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
        $getMFSData = MobileWallet::whereIn('id', $getMFSIds)->get();

        //To get account payment type data...
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
                'mobileWalletData'   =>  $getMFSData,
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

    //To get account data with mobile wallet id...
    public function getAccountDataWithMobileWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_wallet_id'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get all the account data...
        $accountData = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                        ->with(['bankData','mobileWalletData'])->get();
        

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

    // To Wallet To Account payment...
    public function pocketToAccountPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'to_account_id'=> 'required',
            'pay_amount'=> 'required',
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
        $selectedAccountData = User::where('id', Auth::user()->id)->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->id;
        $data['from_pocket_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccount($selectedAccountData->id, $request->to_account_id, $request->pay_amount, $request->total_pay_amount);
        if($checkStatus == true){
            //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = User::where('id', $selectedAccountData->id)->first();
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
}
