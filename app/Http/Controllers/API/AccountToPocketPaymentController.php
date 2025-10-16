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

class AccountToPocketPaymentController extends Controller
{
    //To get all the Account To Wallet payment data...
    public function getAccountToPocketPaymentData()
    {
        ///To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the account payment data...
        $accountPaymentData = AccountPayment::orderBy('id','DESC')->where('payment_type', 'Account To Wallet')->where('user_id', $userId)
                                ->with(['fromAccountData','toAccountData','toBeneficiaryAccountData','toPocketAccountData'])->get();

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
        $singleAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        //To get account payment type data...
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
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

    // To Account To Wallet payment...
    public function accountToPocketPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
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
        $selectedAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['from_account_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $toAccountId = Auth::user()->id;
        $checkStatus = $this->calculateFromAndToAccount($id, $toAccountId, $request->pay_amount, $request->total_pay_amount);
        if($checkStatus == true){
            $data['to_pocket_account_id'] = $toAccountId;

            //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get to & from account data...
                $fromAccountDetails = Account::where('id', $selectedAccountData->id)->with(['bankData','mobileWalletData'])->first();
                $toAccountDetails = User::where('id', $toAccountId)->first();

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
