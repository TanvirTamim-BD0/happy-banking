<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileWallet;
use App\Models\Bank;
use App\Models\Account;
use App\Models\User;
use App\Helpers\CurrentUser;
use Validator;
use Auth;

class AccountController extends Controller
{
    //To get account type...
    public function getAccountType()
    {
        $data = array('Mobile Wallets','Banks');

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
            'accountType'   =>  $arrayData,
            'status_code'   => 201
        ], 201);
    }
    
    //To get bank data with account type...
    public function getBankDataWithAccountType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $accountType = $request->name;

        //To check account type...
        if($accountType == 'Banks'){
            $data = Bank::orderBy('bank_name', 'asc')->get();
        }else{
            $data = MobileWallet::orderBy('mobile_wallet_name', 'asc')->get();
        }

        if(!empty($data)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'data'   =>  $data,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To get account data...
    public function getAccountData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $accountData = Account::orderBy('id','DESC')->where('user_id', $userId)
                        ->where('is_inactive', false)
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

    //To save account data...
    public function saveAccountData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'current_balance'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check bank_id is null or not...
        if($request->bank_id != null){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'required',
                'bank_account_type'=> 'required',
                'branch'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Account::where('bank_id', $request->bank_id)
                                    ->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                return response()->json([
                    'message'   =>  'Sorry this account number is already exist.',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Account::where('mobile_wallet_id', $request->mobile_wallet_id)
                                    ->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                return response()->json([
                    'message'   =>  'Sorry this account number is already exist.',
                    'status_code'   => 500
                ], 500);
            }
        }

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if($result = Account::create($data)){
            return response()->json([
                'message' => 'Your account created successfully.',
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

    //To get account data...
    public function editAccountData($id)
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single account data...
        $singleAccountData = Account::where('id',$id)->with(['bankData','mobileWalletData'])->first();

        if(!empty($singleAccountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleAccountData'   =>  $singleAccountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To update account data...
    public function updateAccountData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'current_balance'=> 'nullable',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single account data...
        $singleAccountData = Account::find($id);

        //To check bank_id is null or not...
        if($request->bank_id != null){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'required',
                'bank_account_type'=> 'required',
                'branch'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Account::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                if($checkAccountNumber->account_number != $singleAccountData->account_number){
                    return response()->json([
                        'message'   =>  'Sorry this account number is already exist.',
                        'status_code'   => 500
                    ], 500);
                }
            }

        }else{
            $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                if($checkAccountNumber->account_number != $singleAccountData->account_number){
                    return response()->json([
                        'message'   =>  'Sorry this account number is already exist.',
                        'status_code'   => 500
                    ], 500);
                }
            }
        }

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if($singleAccountData->update($data)){
            return response()->json([
                'message' => 'Account updated successfully.',
                'data'   =>  $singleAccountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To delete account data...
    public function deleteAccountData($id)
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single account data...
        $singleAccountData = Account::find($id);

        if($singleAccountData->delete()){
            return response()->json([
                'message'   =>  'Account deleted successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //Get all the bank account data...
    public function getBankAccountData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $accountData = Account::orderBy('id','DESC')
                        ->where('bank_id', '!=', null)
                        ->where('is_inactive', false)
                        ->with(['bankData'])->get();

        if(!empty($accountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'bankAccountData'   =>  $accountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }
    
    //Get all the bank account data...
    public function getMobileWalletAccountData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $accountData = Account::orderBy('id','DESC')
                        ->where('mobile_wallet_id', '!=', null)
                        ->where('is_inactive', false)
                        ->with(['bankData'])->get();

        if(!empty($accountData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'mobileWalletAccountData'   =>  $accountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To active mobile wallet account data....
    public function activeAccountData($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = false;

        if($singleMobileWalletData->save()){
            return response()->json([
                'message'   =>  'Account activated successfully.',
                'data'   =>  $singleMobileWalletData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Something is wrong.!',
                'status_code'   => 500
			], 500);
        }
    }
    
    //To inactive mobile wallet account data....
    public function inactiveAccountData($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = true;

        if($singleMobileWalletData->save()){
            return response()->json([
                'message'   =>  'Account inactivated successfully.',
                'data'   =>  $singleMobileWalletData,
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
