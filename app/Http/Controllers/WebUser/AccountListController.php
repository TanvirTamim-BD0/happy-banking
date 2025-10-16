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
use Carbon\Carbon;
use App\Models\User;
use App\Models\IncomeExpense;

class AccountListController extends Controller
{   


    /*
    /////////////////////////////////////
    Mobile Banking Account Start From Here...
    /////////////////////////////////////
    */

    //To get mobile wallet account list page...
    public function getMobileWalletAccount()
    {     
        //To get all the credit card data...
        $accountData = Account::orderBy('id','DESC')
                        ->where('mobile_wallet_id', '!=', null)
                        ->where('user_id',Auth::user()->id)
                        ->where('is_inactive', false)->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.mobileWalletAccount.index',compact('accountData'));
        }else{
            return view('frontend.mobileWalletAccount.emptyMobileWallet',compact('accountData'));
        }
    }


    //create mobile wallet account ...
    public function createMobileWalletAccount()
    {      
        $mobileWalletData = MobileWallet::orderBy('id','desc')->get();
        return view('frontend.mobileWalletAccount.create',compact('mobileWalletData'));
    }


    //To save mobile wallet account...
    public function storeMobileWalletAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'current_balance'=> 'nullable',
            'mobile_wallet_id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check account number is unique or not...
        $checkAccountNumber = Account::where('mobile_wallet_id', $request->mobile_wallet_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('account_number', $request->account_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            if($checkAccountNumber->account_number == $request->account_number){
                Toastr::error('Sorry this account number is already exist.', 'Error', ["progressbar" => true]);
                return redirect(route('webuser.mobile-wallet-account-create'));
            }
        }
        
        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if($result = Account::create($data)){
            Toastr::success('Successfully Mobile Wallet Account Create.', 'Success', ["progressbar" => true]);
            return redirect(route('webuser.mobile-wallet-account'));
        }else{
            Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.mobile-wallet-account-create'));
        }

    }


    //mobile wallet edit list ....
    public function mobileWalletAccountEditList()
    {
        $accountData = Account::orderBy('id','DESC')->where('mobile_wallet_id', '!=', null)->where('user_id',Auth::user()->id)->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.mobileWalletAccount.mobileWalletEditListPage',compact('accountData'));
        }else{
            return view('frontend.mobileWalletAccount.emptyMobileWallet',compact('accountData'));
        }
    }

    public function singleMobileWalletAccountEdit($id)
    {
        $accountData = Account::where('id',$id)->first();
        $mobileWalletData = MobileWallet::orderBy('id','desc')->get();
        return view('frontend.mobileWalletAccount.edit',compact('accountData','mobileWalletData'));
    }

    public function singleMobileWalletAccountUpdate(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'mobile_wallet_id'=> 'required',
        ]);


        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();

         
        /*$check = Account::where('account_number',$request->account_number)->first();   */

        //To fetch single bank data...
        $singleAccountData = Account::findOrFail($id);

            if ($singleAccountData->account_number == $request->account_number) {
                
                $checkAccountNumber = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
                if (isset($checkAccountNumber) && $checkAccountNumber->id != $singleAccountData->id) {
                    Toastr::error('The account number allready exist'.' '.$checkAccountNumber->mobileWalletData->mobile_wallet_name, 'Error', ["progressbar" => true]);
                    return redirect()->back();
                }else{

                    if($singleAccountData->update($data)){
                        Toastr::success('Account updateed successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('webuser.mobile-wallet-account-edit-list');
                    }else{
                        Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }
                }
                


            }else{
                 
                 $checkAccountNumber = Account::where('mobile_wallet_id', $request->mobile_wallet_id)->where('user_id', Auth::user()->id)
                                    ->where('account_number', $request->account_number)->first();
                   
                   if(isset($checkAccountNumber) && $checkAccountNumber != null){
                        Toastr::error('The account number allready exist'.' '.$checkAccountNumber->mobileWalletData->mobile_wallet_name, 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }else{

                        if($singleAccountData->update($data)){
                        Toastr::success('Account updateed successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('webuser.profile');
                        }else{
                            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                            return redirect()->back();
                        }
                    }
                

            }


            
        

    }

    //To active mobile wallet account data....
    public function singleMobileWalletAccountActive($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = false;

        if($singleMobileWalletData->save()){
            Toastr::success('Account activated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To inactive mobile wallet account data....
    public function singleMobileWalletAccountInActive($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = true;

        if($singleMobileWalletData->save()){
            Toastr::success('Account inactivated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }


    /*
    /////////////////////////////////////
    Banking Account Start From Here...
    /////////////////////////////////////
    */

    //To get banking wallet account list page...
    public function getBankingWalletAccount()
    {   
        //To get all the bank account data...
        $accountData = Account::orderBy('id','desc')->where('bank_id', '!=', null)
                        ->where('user_id',Auth::user()->id)
                        ->where('is_inactive', false)->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.bankingWalletAccount.index',compact('accountData'));
        }else{
            return view('frontend.bankingWalletAccount.emptyBankingWallet',compact('accountData'));
        }
    }
    
    //To get banking wallet account list page...
    public function createBankingWalletAccount()
    {   
        $bankData = Bank::orderBy('id','desc')->get();
        return view('frontend.bankingWalletAccount.create',compact('bankData'));
    }


    //To save banking wallet account...
    public function storeBankingWalletAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number'=> 'required',
            'current_balance'=> 'nullable',
            'bank_id'=> 'required',
            'bank_account_type'=> 'required',
            'branch'=> 'nullable',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check account number is unique or not...
        $checkAccountNumber = Account::where('bank_id', $request->bank_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('account_number', $request->account_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            Toastr::error('Sorry this account number is already exist.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account-create'));
        }
        
        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if($result = Account::create($data)){
            Toastr::success('Successfully Banking Wallet Account Create.', 'Success', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account'));
        }else{
            Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.banking-wallet-account-create'));
        }

    }


    //banking wallet edit list ....
    public function bankingWalletAccountEditList()
    {
        $accountData = Account::orderBy('id','desc')->where('bank_id', '!=', null)->where('user_id',Auth::user()->id)->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return view('frontend.bankingWalletAccount.accountEditListPage',compact('accountData'));
        }else{
            return view('frontend.bankingWalletAccount.emptyBankingWallet',compact('accountData'));
        }
    }


    //get single banking account ....
    public function singleBankingWalletAccountEdit($id)
    {
        $accountData = Account::where('id',$id)->first();
        $bankData = Bank::orderBy('id','desc')->get();
        return view('frontend.bankingWalletAccount.edit',compact('accountData','bankData'));
    }


    //update account....
    public function singleBankingWalletAccountUpdate(Request $request,$id)
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

        $data = $request->all();

        //To fetch single bank data...
        $singleAccountData = Account::findOrFail($id);

             if ($singleAccountData->account_number == $request->account_number) {

                $checkAccountNumber = Account::where('bank_id', $request->bank_id)
                ->where('account_number', $request->account_number)->first();

                if (isset($checkAccountNumber) && $checkAccountNumber->id != $singleAccountData->id) {
                    Toastr::error('The account number allready exist'.' '.$checkAccountNumber->bankData->bank_name, 'Error', ["progressbar" => true]);
                    return redirect()->back();
                }else{

                     if($singleAccountData->update($data)){
                        Toastr::success('Account updateed successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('webuser.banking-wallet-account-edit-list');
                    }else{
                        Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }
                }

             }else{

                $checkAccountNumber = Account::where('bank_id', $request->bank_id)
                ->where('account_number', $request->account_number)->first();
                   
                   if(isset($checkAccountNumber) && $checkAccountNumber != null){
                        Toastr::error('The account number allready exist'.' '.$checkAccountNumber->bankData->bank_name, 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }else{

                        if($singleAccountData->update($data)){
                            Toastr::success('Account updateed successfully.', 'Success', ["progressbar" => true]);
                            return redirect()->route('webuser.profile');
                        }else{
                            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                            return redirect()->back();
                        }
                    }

             }
        

    }

    //To active banking wallet account data....
    public function singleBankingWalletAccountActive($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = false;

        if($singleMobileWalletData->save()){
            Toastr::success('Account activated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To inactive banking wallet account data....
    public function singleBankingWalletAccountInActive($id)
    {
        $singleMobileWalletData = Account::where('id',$id)->first();
        $singleMobileWalletData->is_inactive = true;

        if($singleMobileWalletData->save()){
            Toastr::success('Account inactivated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }


    /*
    /////////////////////////////////////
    Credit Card Start From Here...
    /////////////////////////////////////
    */

    //To get credit card wallet account list page...
    public function getCreditCardWalletAccount()
    {   
        //To get all the card account data...
        $creditCardData = CreditCard::orderBy('id','desc')
                            ->where('user_id',Auth::user()->id)
                            ->where('is_inactive', false)->get();

        //To check data empty or not...
        if($creditCardData->count() != 0){
            return view('frontend.creditCardWalletAccount.index',compact('creditCardData'));
        }else{
            return view('frontend.creditCardWalletAccount.emptyCreditCardWallet',compact('creditCardData'));
        }
    }

    //create credit card wallet account ...
    public function createCreditCardWalletAccount()
    {   
        $cardTypeData = CardType::getCardTypeData();
        $bankData = Bank::orderBy('id','desc')->get();
        return view('frontend.creditCardWalletAccount.create',compact('cardTypeData','bankData'));
    }

    public function storeCreditCardWalletAccount(Request $request)
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

        $billingDate = Carbon::createFromFormat('d-m-Y', $request->billing_date)->format('Y-m-d');
        $data['billing_date'] = $billingDate;

        //To check account number is unique or not...
        $checkAccountNumber = CreditCard::where('bank_id', $request->bank_id)
                                ->where('user_id', Auth::user()->id)
                                ->where('card_number', $request->card_number)->first();
        if(isset($checkAccountNumber) && $checkAccountNumber != null){
            Toastr::error('Sorry this account number is already exist.', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.credit-card-wallet-account-create'));
        }

        if($result = CreditCard::create($data)){
            Toastr::success('CreditCard created successfully.', 'Success', ["progressbar" => true]);
            return redirect(route('webuser.credit-card-wallet-account'));
        }else{
           Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
            return redirect(route('webuser.credit-card-wallet-account-create'));
        }
    }


    //credit card edit list ....
    public function creditCardWalletAccountEditList()
    {
        $creditCardData = CreditCard::orderBy('id','desc')->where('user_id',Auth::user()->id)->get();

        //To check data empty or not...
        if($creditCardData->count() != 0){
            return view('frontend.creditCardWalletAccount.creditCardAccountEditListPage',compact('creditCardData'));
        }else{
            return view('frontend.creditCardWalletAccount.emptyCreditCardWallet',compact('creditCardData'));
        }
    }


    //get single credit card ....
    public function singleCreditCardWalletAccountEdit($id)
    {
        $creditCardData = CreditCard::where('id',$id)->first();
        $bankData = Bank::orderBy('id','desc')->get();
        $cardTypeData = CardType::getCardTypeData();
        $singleBillingDate = Carbon::createFromFormat('Y-m-d', $creditCardData->billing_date)->format('d-m-Y');
        return view('frontend.creditCardWalletAccount.edit',compact('creditCardData','bankData','cardTypeData','singleBillingDate'));
    }


    public function singleCreditCardWalletAccountUpdate(Request $request,$id)
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

        $data = $request->all();

        $billingDate = Carbon::createFromFormat('d-m-Y', $request->billing_date)->format('Y-m-d');
        $data['billing_date'] = $billingDate;

         //To fetch single bank data...
        $singleCreditCardData = CreditCard::findOrFail($id);

        if ($singleCreditCardData->card_number == $request->card_number) {

            $checkAccountNumber = CreditCard::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
            ->where('card_number', $request->card_number)->first();

            if (isset($checkAccountNumber) && $checkAccountNumber->id != $singleCreditCardData->id) {
                Toastr::error('The account number allready exist'.' '.$checkAccountNumber->bankData->bank_name, 'Error', ["progressbar" => true]);
                return redirect()->back();
            }else{
                if($singleCreditCardData->update($data)){
                    Toastr::success('CreditCard Updated successfully.', 'Success', ["progressbar" => true]);
                    return redirect()->route('webuser.credit-card-wallet-account-edit-list');
                }else{
                    Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                    return redirect()->back();
                }
            }

        }else{

            $checkAccountNumber = CreditCard::where('bank_id', $request->bank_id)->where('user_id', Auth::user()->id)
            ->where('card_number', $request->card_number)->first();
                
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                Toastr::error('The account number allready exist'.' '.$checkAccountNumber->bankData->bank_name, 'Error', ["progressbar" => true]);
                return redirect()->back();
            }else{

                    if($singleCreditCardData->update($data)){
                        Toastr::success('CreditCard Updated successfully.', 'Success', ["progressbar" => true]);
                        return redirect()->route('webuser.profile');
                    }else{
                        Toastr::error('Something wrong', 'Error', ["progressbar" => true]);
                        return redirect()->route('webuser.profile');
                    }
            }

        }


    }

    //To active credit card account data....
    public function singleCreditCardWalletAccountActive($id)
    {
        $singleCardWalletData = CreditCard::where('id',$id)->first();
        $singleCardWalletData->is_inactive = false;

        if($singleCardWalletData->save()){
            Toastr::success('Account activated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To inactive credit card account data....
    public function singleCreditCardWalletAccountInActive($id)
    {
        $singleCardWalletData = CreditCard::where('id',$id)->first();
        $singleCardWalletData->is_inactive = true;

        if($singleCardWalletData->save()){
            Toastr::success('Account inactivated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }


    /*............. invoice .............*/
    public function tranjectionInvoice(Request $request)
    {   
        $result = Crypt::decrypt($request->result);
        $fromAccount = Account::where('id',$request->from_account)->first();
        $toAccount = Account::where('id',$request->to_account)->first();

        if($result->transfer_type == 'Own Account'){
            $toAccount = Account::where('id',$request->to_account)->first();
        }else{
            $toAccount = Beneficiary::where('id',$request->to_account)->first();
        }

        return view('frontend.invoice.invoice',compact('result','fromAccount','toAccount'));
    }

    public function tranjectionInvoicePocketWallet(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = Account::where('id',$request->from_account)->first();
        $toAccount = User::where('id',$request->to_account)->first();

        return view('frontend.invoice.pocketWalletInvoice',compact('result','fromAccount','toAccount'));
    }

    public function walletTranjectionInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = User::where('id',$request->from_account)->first();

        if($result->payment_type == 'Wallet To Card'){
            $toAccount = CreditCard::where('id',$request->to_account)->first();
        }else{
            $toAccount = Account::where('id',$request->to_account)->first();
        }

        return view('frontend.invoice.walletTransferInvoice',compact('result','fromAccount','toAccount'));
    }


    public function cardTranjectionInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = CreditCard::where('id',$request->from_account)->first();
        $toAccount = Account::where('id',$request->to_account)->first();

        return view('frontend.invoice.cardTransferInvoice',compact('result','fromAccount','toAccount'));
    }

    public function cardBillPaymentInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = Account::where('id',$request->from_account)->first();
        $toAccount = CreditCard::where('id',$request->to_account)->first();

        return view('frontend.invoice.cardBillPaymentInvoice',compact('result','fromAccount','toAccount'));
    }

    public function cardWalletBillPaymentInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = User::where('id',$request->from_account)->first();
        $toAccount = CreditCard::where('id',$request->to_account)->first();

        return view('frontend.invoice.cardWalletBillPaymentInvoice',compact('result','fromAccount','toAccount'));
    }

    public function incomeInvoice(Request $request){

        $result = Crypt::decrypt($request->result);
        $fromAccount = Account::where('id',$request->from_account)->first();

        return view('frontend.invoice.incomeInvoice',compact('result','fromAccount'));
    }

    public function incomeWalletInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = User::where('id',$request->from_account)->first();

        return view('frontend.invoice.incomeWalletInvoice',compact('result','fromAccount'));
    }

    public function expenseInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = Account::where('id',$request->from_account)->first();

        return view('frontend.invoice.expenseInvoice',compact('result','fromAccount'));
    }

    public function expenseCardInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = CreditCard::where('id',$request->from_account)->first();

        return view('frontend.invoice.expenseCardInvoice',compact('result','fromAccount'));
    }

    public function expenseWalletInvoice(Request $request)
    {
        $result = Crypt::decrypt($request->result);
        $fromAccount = User::where('id',$request->from_account)->first();

        return view('frontend.invoice.expenseWalletInvoice',compact('result','fromAccount'));
    }




    /* ........ transfer revert .............*/

    public function transferRevert()
    {
        return view('frontend.transactionRevert.transferRevert.transferRevert');
    }

    public function transferRevertFilter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id'=> 'required',
        ]);

        $data = AccountPayment::where('transaction_id',$request->transaction_id)->first();

        if (isset($data) && $data->from_pocket_account_id != null) {
            $fromAccountNumber = $data->fromPocketAccountData->mobile;
            $accountBalance = $data->fromPocketAccountData->wallet;
            $fromAccountWallet = 'Wallet';
        }elseif(isset($data) && $data->from_account_id != null){
            $fromAccountNumber = $data->fromAccountData->account_number;
            $accountBalance = $data->fromAccountData->current_balance;
            if($data->fromAccountData->bank_id != null){
                $fromAccountWallet = $data->fromAccountData->bankData->bank_name;
            }else{
                $fromAccountWallet = $data->fromAccountData->mobileWalletData->mobile_wallet_name;
            }
        }elseif(isset($data) && $data->from_credit_card_id != null){
            $fromAccountNumber = $data->creditCardData->card_number;
            $accountBalance = $data->creditCardData->total_limit;
            $fromAccountWallet = $data->creditCardData->bankData->bank_name;
        }else{
            Toastr::error('Sorry you have no data with this transaction id.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        if (isset($data) && $data->to_pocket_account_id != null) {
            $toAccountNumber = $data->toPocketAccountData->mobile;
            $toAccountWallet = 'Wallet';
        }elseif(isset($data) && $data->to_account_id != null){
            $toAccountNumber = $data->toAccountData->account_number;
            if($data->toAccountData->bank_id != null){
                $toAccountWallet = $data->toAccountData->bankData->bank_name;
            }else{
                $toAccountWallet = $data->toAccountData->mobileWalletData->mobile_wallet_name;
            }
        }elseif(isset($data) && $data->to_credit_card_id != null){
            $toAccountNumber = $data->toCreditCardData->card_number;
            $toAccountWallet = $data->toCreditCardData->bankData->bank_name;
        }elseif(isset($data) && $data->to_beneficiary_account_id != null){
            $toAccountNumber = $data->toBeneficiaryAccountData->account_number;
            if($data->toBeneficiaryAccountData->bank_id != null){
                $toAccountWallet = $data->toBeneficiaryAccountData->bankData->bank_name;
            }else{
                $toAccountWallet = $data->toBeneficiaryAccountData->mobileWalletData->mobile_wallet_name;
            }
        }else{
            Toastr::error('Sorry you have no data with this transaction id.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        return view('frontend.transactionRevert.transferRevert.transferRevertFilter',compact('data','fromAccountNumber','accountBalance','fromAccountWallet','toAccountWallet','toAccountNumber'));

    }


    // transacetion update........
    public function transferRevertUpdate($id)
    {
        $data = AccountPayment::where('id',$id)->first();


        //from acount balance update.........
        if ($data->from_pocket_account_id != null) {
            $fromAccountData = User::where('id', $data->from_pocket_account_id)->first();
            $balance = $data->total_pay_amount;
            $fromAccountData->wallet += $balance;
            $fromAccountData->save();
               
        }elseif($data->from_account_id != null){
            
            $fromAccountData = Account::where('id', $data->from_account_id)->first();
            $balance = $data->total_pay_amount;
            $fromAccountData->current_balance += $balance;
            $fromAccountData->save();

        }elseif($data->from_credit_card_id != null){
           
            $fromAccountData = CreditCard::where('id', $data->from_credit_card_id)->first();
            
            if($fromAccountData->is_dual_currency == true && $data->transfer_currency_type != null){
                if($data->transfer_currency_type == 'USD Currency'){
                    $balance = $data->total_pay_amount;
                    $payAmountWithPayFee= $balance / $data->usd_in_bdt_rate;
                    $fromAccountData->total_usd_limit += $payAmountWithPayFee;
                    $fromAccountData->save();
                }else if($data->transfer_currency_type == 'BDT Currency'){
                    $balance = $data->total_pay_amount;
                    $fromAccountData->total_bdt_limit += $balance;
                    $fromAccountData->save();
                }
            }else if( ($fromAccountData->is_dual_currency == true && $data->transfer_currency_type == null) ||  ($fromAccountData->is_dual_currency == false && $data->transfer_currency_type == null) ){
                $balance = $data->total_pay_amount;
                $fromAccountData->total_limit += $balance;
                $fromAccountData->save();

            }else if($fromAccountData->is_dual_currency == false && $data->transfer_currency_type != null){
                Toastr::error('Dual Currency Disable', 'Error', ["progressbar" => true]);
                return redirect()->route('webuser.transfer-revert');
            }
        }


        //to Account balance update.....
        if ($data->to_pocket_account_id != null) {
            $toAccountData = User::where('id', $data->to_pocket_account_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->wallet -= $balance;
            $toAccountData->save();

        }elseif($data->to_account_id != null){
            
            $toAccountData = Account::where('id', $data->to_account_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->current_balance -= $balance;
            $toAccountData->save();

        }elseif($data->to_credit_card_id != null){
            
            $toAccountData = CreditCard::where('id', $data->to_credit_card_id)->first();
            $balance = $data->pay_amount;
            $toAccountData->total_limit -= $balance;
            $toAccountData->save();
        }


        //delete account data .......
        $deleteAccountPayment = AccountPayment::where('id',$id)->delete();
        Toastr::success('Successfully Transaction Revert.', 'Success', ["progressbar" => true]);
        return redirect()->route('webuser.profile');


    }


    /* ........ income expense revert .............*/

    public function incomeExpenseRevert()
    {
        return view('frontend.transactionRevert.incomeExpenseRevert.incomeExpenseRevert');
    }


    public function incomeExpenseRevertFilter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id'=> 'required',
        ]);

        $data = IncomeExpense::where('transaction_id',$request->transaction_id)->first();

        if (isset($data) && $data->pocket_wallet_id != null) {
            $fromAccountNumber = $data->pocketWalletData->mobile;
            $accountBalance = $data->pocketWalletData->wallet;
            $fromAccountWallet = 'Wallet';
        }elseif(isset($data) && $data->from_account_id != null){
            $fromAccountNumber = $data->fromAccountData->account_number;
            $accountBalance = $data->fromAccountData->current_balance;
            if($data->fromAccountData->bank_id != null){
                $fromAccountWallet = $data->fromAccountData->bankData->bank_name;
            }else{
                $fromAccountWallet = $data->fromAccountData->mobileWalletData->mobile_wallet_name;
            }
        }elseif(isset($data) && $data->from_credit_card_id != null){
            $fromAccountNumber = $data->fromCreditCardData->card_number;
            $accountBalance = $data->fromCreditCardData->total_limit;
            $fromAccountWallet = $data->creditCardData->bankData->bank_name;
        }else{
            Toastr::error('Sorry you have no data with this transaction id.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        return view('frontend.transactionRevert.incomeExpenseRevert.incomeExpenseRevertFilter',compact('data','fromAccountNumber','fromAccountWallet','accountBalance'));
    }


    //update amount ....
    public function incomeExpenseRevertUpdate($id){

        $data = IncomeExpense::where('id',$id)->first();

        //status true income and status false expesne.....
        if ($data->status == true) {
            
            if ($data->pocket_wallet_id != null) {
                $account = User::where('id', $data->pocket_wallet_id)->first();
                $balance = $data->amount;
                $account->wallet -= $balance;
                $account->save();

            }elseif($data->from_account_id != null){
                $account = Account::where('id', $data->from_account_id)->first();
                $balance = $data->amount;
                $account->current_balance -= $balance;
                $account->save();

            }elseif($data->from_credit_card_id != null){
                $account = CreditCard::where('id', $data->from_credit_card_id)->first();
                $balance = $data->amount;
                $account->total_limit -= $balance;
                $account->save();
            }

        }else{

          if ($data->pocket_wallet_id != null) {
                $account = User::where('id', $data->pocket_wallet_id)->first();
                $balance = $data->amount;
                $account->wallet += $balance;
                $account->save();

            }elseif($data->from_account_id != null){
                $account = Account::where('id', $data->from_account_id)->first();
                $balance = $data->amount;
                $account->current_balance += $balance;
                $account->save();
                
            }elseif($data->from_credit_card_id != null){
                $account = CreditCard::where('id', $data->from_credit_card_id)->first();
                $balance = $data->amount;
                $account->total_limit += $balance;
                $account->save();

            }
        }

        //delete data .......
        $deleteAccountPayment = IncomeExpense::where('id',$id)->delete();
        Toastr::success('Successfully Income Expesne Revert.', 'Success', ["progressbar" => true]);
        return redirect()->route('webuser.profile');
    }   



}
