<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use App\Helpers\CurrentUser;
use Validator;
use App\Models\Bank;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\MobileWallet;

class BeneficiaryController extends Controller
{   

    // bank beneficiary list ....
    public function bankBeneficiary()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the beneficiary data...
        $accountData = Beneficiary::orderBy('id','DESC')->where('bank_id','!=',null)->where('user_id', $userId)->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.beneficiary.bankBeneficiary',compact('accountData'));
        }else{
            return view('frontend.beneficiary.emptyBankingWallet',compact('accountData'));
        }
    
    }


    // bank beneficiary create page ....
    public function bankingBeneficiaryAccountCreate()
    {
        $bankData = Bank::orderBy('bank_name', 'asc')->get();
        return view('frontend.beneficiary.bankBeneficiaryCreate',compact('bankData'));
    }


    // bank beneficiary data store....
    public function bankingBeneficiaryAccountStore(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'bank_id'=> 'required',
            'bank_account_type'=> 'required',
            'branch'=> 'nullable',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();
        
        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['account_type'] = 'Banks';

        //To check account number is unique or not...
        $checkAccountNumber = Beneficiary::where('bank_id', $request->bank_id)
                                ->where('user_id', $userId)->where('account_number', $request->account_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            Toastr::error('Sorry this account number is already exist.!', 'error', ["progressbar" => true]);
            return redirect(route('webuser.banking-beneficiary-account-create'));
        }

        if($result = Beneficiary::create($data)){
            Toastr::success('Successfully Banking Beneficiary Account Create.', 'success', ["progressbar" => true]);
            return redirect(route('webuser.bank-beneficiary'));
        }else{
            Toastr::error('Something wrong', 'error', ["progressbar" => true]);
            return redirect(route('webuser.banking-beneficiary-account-create'));
        }

    }


    // mfs beneficiary list ....
    public function mfsBeneficiary()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the beneficiary data...
        $accountData = Beneficiary::orderBy('id','DESC')->where('bank_id',null)->where('user_id', $userId)->get();
        
        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.beneficiary.mfsBeneficiary',compact('accountData'));
        }else{
            return view('frontend.beneficiary.emptyMobileWallet',compact('accountData'));
        }
       
    }


    //mfs beneficiary create page ....
    public function mfsBeneficiaryAccountCreate()
    {
        $mobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->get();
        return view('frontend.beneficiary.mfsBeneficiaryCreate',compact('mobileWalletData'));
    }


    //mfs beneficiary data store ....
    public function mfsBeneficiaryAccountStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'mobile_wallet_id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        $data['account_type'] = 'Mobile Wallets';

        //To check account number is unique or not...
        $checkAccountNumber = Beneficiary::where('mobile_wallet_id', $request->mobile_wallet_id)
                                ->where('user_id', $userId)->where('account_number', $request->account_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            Toastr::error('Sorry this account number is already exist.!', 'error', ["progressbar" => true]);
            return redirect(route('webuser.mfs-beneficiary-account-create'));
        }

        if($result = Beneficiary::create($data)){
            Toastr::success('Successfully MFS Beneficiary Account Create.', 'success', ["progressbar" => true]);
            return redirect(route('webuser.mfs-beneficiary'));
        }else{
            Toastr::error('Something wrong', 'error', ["progressbar" => true]);
            return redirect(route('webuser.mfs-beneficiary-account-create'));
        }

    }


}
