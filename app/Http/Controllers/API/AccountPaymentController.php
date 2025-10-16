<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionCategory;
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

class AccountPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ///To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the account payment data...
        $accountPaymentData = AccountPayment::orderBy('id','DESC')->where('payment_type', 'Account To Account')->where('user_id', $userId)
                                ->with(['fromAccountData','toAccountData','toBeneficiaryAccountData'])->get();

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
    ****************************************************
    Account Payment Start From Here...
    ****************************************************
    */

    //To get transaction category data...
    public function getTransactionCategory()
    {
        //To get all the account payment data...
        $transactionCategoryData = TransactionCategory::orderBy('id','DESC')->get();

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

    //To get account details with selected account id...
    public function getAccountDetailsWSA($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

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
        $getBankData = Bank::whereIn('id', $getBankIds)->get();

        //To get account transfer & payment type data...
        $transferTypeData = TransferType::getTransferTypeData();
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
                'bankData'   =>  $getBankData,
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

    //To get bank list data with transfer type...
    public function getBankListData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transfer_type'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get single account data...
        $singleAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
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
        }elseif(($request->transfer_type == 'Beneficiary Account')){
            //To get all the bank list...
            $getBankIds = Beneficiary::where('bank_id', '!=', null)->where('user_id', Auth::user()->id)->select('bank_id')
                        ->groupBy('bank_id')->pluck('bank_id')->toArray();
        }else{
            $getBankIds[] = null;
        }

        //To get all the bank data...
        $getBankData = Bank::whereIn('id', $getBankIds)->get();

        if(!empty($getBankData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'bankData'   =>  $getBankData,
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
    public function getAccountDataWithBank(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
            'transfer_type'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To check transfer type...
        if($request->transfer_type == 'Own Account'){
            $accountData = Account::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
                            ->where('is_inactive', false)->whereNotIn('id', [$id])->with(['bankData','mobileWalletData'])->get();
        }elseif(($request->transfer_type == 'Beneficiary Account')){
            $accountData = Beneficiary::where('bank_id', $request->bank_id)->with(['bankData','mobileWalletData'])->get();
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

    // To account to account payment...
    public function accountToAccountPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'pay_amount'=> 'required',
            'pay_fee'=> 'required',
            'pay_fee_amount'=> 'required',
            'total_pay_amount'=> 'required',
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


        //To generate transaction id...
        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccount($id, $request->to_account_id, $request->pay_amount, $totalPayAmount, $request->transfer_type);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = Account::where('id', $selectedAccountData->id)->with(['bankData','mobileWalletData'])->first();
                $toAccountDetails = Account::where('id', $request->to_account_id)->with(['bankData','mobileWalletData'])->first();
                if(isset($request->to_beneficiary_account_id) && $request->to_beneficiary_account_id != null){
                    $toBenefeciaryAccountDetails = Beneficiary::where('id', $request->to_beneficiary_account_id)->with(['bankData','mobileWalletData'])->first();
                }else{
                    $toBenefeciaryAccountDetails = null;
                }

                return response()->json([
                    'message' => 'Your payment transfered successfully.',
                    'data'   =>  $result,
                    'fromAccountData' => $fromAccountDetails,
                    'toAccountData' => $toAccountDetails,
                    'toBeneficiaryAccountData' => $toBenefeciaryAccountDetails,
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
}
