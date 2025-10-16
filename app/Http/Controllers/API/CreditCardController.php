<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\User;
use App\Helpers\CurrentUser;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\AccountPayment;
use App\Models\Beneficiary;
use App\Helpers\TransferType;
use App\Helpers\PaymentType;
use Carbon\Carbon;
use Validator;
use Auth;

class CreditCardController extends Controller
{
    //To get credit card type...
    public function getCreditCardType()
    {
        $data = array('Amex','Discover','Master Card','Visa Card','Union Pay','JCB','NEXUS');

        $arrayData = [];
        foreach($data as $key => $item){
            if($item != null){
                $arrayData[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return response()->json([
            'message'   =>  'Successfully loaded data.',
            'creditCardType'   =>  $arrayData,
            'status_code'   => 201
        ], 201);
    }
    
    //To get credit card data...
    public function getCreditCardData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $creditCardData = CreditCard::orderBy('id','DESC')
                            ->where('user_id', $userId)
                            ->where('is_inactive', false)
                            ->with(['bankData'])->get();

        if(!empty($creditCardData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditCardData'   =>  $creditCardData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To save credit card data...
    public function saveCreditCardData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
            'card_type'=> 'required',
            'card_number'=> 'nullable',
            'billing_date'=> 'required',
            'total_limit'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check account number is unique or not...
        $checkAccountNumber = CreditCard::where('user_id', Auth::user()->id)->where('bank_id', $request->bank_id)
                                ->where('card_number', $request->card_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            return response()->json([
                'message'   =>  'Sorry this account number is already exist.',
                'status_code'   => 500
            ], 500);
        }

        if($result = CreditCard::create($data)){
            return response()->json([
                'message' => 'CreditCard created successfully.',
                'data'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To get credit card data...
    public function editCreditCardData($id)
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single credit card data...
        $singleCreditCardData = CreditCard::where('id', $id)->with('bankData')->first();

        if(!empty($singleCreditCardData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleCreditCardData'   =>  $singleCreditCardData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To update credit card data...
    public function updateCreditCardData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_id'=> 'required',
            'card_type'=> 'required',
            'card_number'=> 'nullable',
            'billing_date'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single credit card data...
        $singleCreditCardData = CreditCard::find($id);

        if($singleCreditCardData->is_dual_currency == 0){
            $validator = Validator::make($request->all(), [
                'total_limit'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'total_bdt_limit'=> 'required',
                'total_usd_limit'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if ($singleCreditCardData->card_number == $request->card_number) {

            $checkAccountNumber = CreditCard::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
            ->where('card_number', $request->card_number)->first();

            if (isset($checkAccountNumber) && $checkAccountNumber->id != $singleCreditCardData->id) {
                return response()->json([
                    'message'   =>  'The account number allready exist.!',
                    'status_code'   => 500
                ], 500);
            }else{
                if($singleCreditCardData->update($data)){
                    return response()->json([
                        'message' => 'CreditCard updated successfully.',
                        'data'   =>  $singleCreditCardData,
                        'status_code'   => 201
                    ], 201);
                }else{
                    return response()->json([
                        'message'   =>  'Something is wrong.!',
                        'status_code'   => 500
                    ], 500);
                }
            }

        }else{

            $checkAccountNumber = CreditCard::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
            ->where('card_number', $request->card_number)->first();
                
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                return response()->json([
                    'message'   =>  'The account number allready exist.!',
                    'status_code'   => 500
                ], 500);
            }else{
                if($singleCreditCardData->update($data)){
                    return response()->json([
                        'message' => 'CreditCard updated successfully.',
                        'data'   =>  $singleCreditCardData,
                        'status_code'   => 201
                    ], 201);
                }else{
                    return response()->json([
                        'message'   =>  'Something is wrong.!',
                        'status_code'   => 500
                    ], 500);
                }
            }

        }
    }

    //To delete credit card data...
    public function deleteCreditCardData($id)
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single credit card data...
        $singleCreditCardData = CreditCard::find($id);

        if($singleCreditCardData->delete()){
            return response()->json([
                'message'   =>  'Credit card deleted successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }




    /*
        //////////////////////////////////////////////////
        For Bank, MFS, Wallet To Card Account Transfer...
        //////////////////////////////////////////////////
    */

    

    //To get account details with selected account id...
    public function getAccountDetailsWSA($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id', $id)->with(['bankData'])->first();

        //To get account transfer & payment type data...
        $transferTypeData = TransferType::getTransferTypeData();
        $paymentTypeData = PaymentType::getPaymentTypeData();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
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

    //To Wallet To Card transfer...
    public function pocketToCardTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'currency_type'=> 'required',
            'from_pocket_account_id'=> 'required',
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

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;
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
        if($request->payment_type != 'Wallet To Card'){
            return response()->json([
                'message'   =>  'Sorry payment type will be always [Wallet To Card].',
                'status_code'   => 500
			], 500);
        }

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

        //To calculate account payment with fromAccount & toAccount...
        $fromAccountId = Auth::user()->id;
        $checkStatus = $this->calculateFromAndToAccountForCardWithPocketId($fromAccountId, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
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

    //To get account details with selected account id...
    public function getAccountDetailsWSAFC($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id',$id)->with(['bankData'])->first();

        //To get all the bank list...
        $getBankIds = Account::where('user_id', $singleAccountData->user_id)->select('bank_id')->where('bank_id', '!=', null)
                        ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
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

    //To bank to card transfer...
    public function accountToCardTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'currency_type'=> 'required',
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

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;
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
        if($request->payment_type != 'Account To Card'){
            return response()->json([
                'message'   =>  'Sorry payment type will be always [Account To Card].',
                'status_code'   => 500
			], 500);
        }

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

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccountForCard($request->from_account_id, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = Account::where('id', $request->from_account_id)->with(['bankData','mobileWalletData'])->first();
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

    //To get account details with selected account id...
    public function getAccountDetailsWMFS($id)
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

    //To get account data with mobile wallet id...
    public function getAccountDataWithMFS(Request $request, $id)
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

    //To mfs to card transfer...
    public function mfsToCardTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type'=> 'required',
            'transfer_type'=> 'required',
            'currency_type'=> 'required',
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

        $data = $request->all();
        $data['user_id'] = $selectedAccountData->user_id;
        $data['to_credit_card_id'] = $selectedAccountData->id;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['is_bill_payment'] = true;
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
        if($request->payment_type != 'MFS To Card'){
            return response()->json([
                'message'   =>  'Sorry payment type will be always [MFS To Card].',
                'status_code'   => 500
			], 500);
        }

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

        //To calculate account payment with fromAccount & toAccount...
        $checkStatus = $this->calculateFromAndToAccountForCard($request->from_account_id, $selectedAccountData->id, $requestPayAmount, $totalPayAmount, $request->transfer_type, $request->currency_type, $request->pay_amount_usd);
        if($checkStatus == true){
             //To save account payment data...
            if($result = AccountPayment::create($data)){
                //To get toAccount & toBenefeciary account data...
                $fromAccountDetails = Account::where('id', $request->from_account_id)->with(['bankData','mobileWalletData'])->first();
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
            return response()->json([
                'message' => 'Card currency changed successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
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
            return response()->json([
                'message' => 'Card currency changed successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To active credit card account data....
    public function activeCreditCardData($id)
    {
        $singleCardWalletData = CreditCard::where('id',$id)->first();
        $singleCardWalletData->is_inactive = false;

        if($singleCardWalletData->save()){
            return response()->json([
                'message'   =>  'Account activated successfully.',
                'data'   =>  $singleCardWalletData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Something is wrong.!',
                'status_code'   => 500
			], 500);
        }
    }
    
    //To inactive credit card account data....
    public function inactiveCreditCardData($id)
    {
        $singleCardWalletData = CreditCard::where('id',$id)->first();
        $singleCardWalletData->is_inactive = true;

        if($singleCardWalletData->save()){
            return response()->json([
                'message'   =>  'Account inactivated successfully.',
                'data'   =>  $singleCardWalletData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Something is wrong.!',
                'status_code'   => 500
			], 500);
        }
    }
}
