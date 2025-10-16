<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCard;
use App\Models\Account;
use App\Models\Bank;
use App\Models\MobileWallet;
use App\Models\IncomeExpense;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CurrentUser;
use App\Helpers\CardType;
use Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Models\FrontendNote;

class ExpenseController extends Controller
{
    //To get all the expense data...
    public function createExpense(Request $request)
    {
        //To decript...
        $expenseType = Crypt::decrypt($request->expense_type);

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check expense type...
        if($expenseType == 'Banking Expense'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Expense Bank')->get('description')->first();

            //To get all the bank list...
            $getBankIds = Account::where('user_id', $userId)->select('bank_id')->where('bank_id', '!=', null)
            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
                
            //To get all the bank data...
            $bankData = Bank::orderBy('bank_name', 'asc')->whereIn('id', $getBankIds)->get();
            $transactionCategoryData = TransactionCategory::where('category_type', 'Expense')->get();

            return view('frontend.expense.bankingExpense.create', compact('bankData','expenseType','transactionCategoryData', 'getNote'));
        }else if($expenseType == 'Mobile Wallet Expense'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Expense MFS')->get('description')->first();

            //To get all the bank list...
            $getMobileWalletIds = Account::where('user_id', $userId)->select('mobile_wallet_id')->where('mobile_wallet_id', '!=', null)
            ->where('is_inactive', false)->groupBy('mobile_wallet_id')->pluck('mobile_wallet_id')->toArray();
                
            //To get all the mobile wallet data...
            $mobileWalletData = MobileWallet::orderBy('mobile_wallet_name', 'asc')->whereIn('id', $getMobileWalletIds)->get();
            $transactionCategoryData = TransactionCategory::where('category_type', 'Expense')->get();

            return view('frontend.expense.mfsExpense.create', compact('mobileWalletData','expenseType','transactionCategoryData','getNote'));
        }else if($expenseType == 'Credit Card Expense'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Expense Credit Card')->get('description')->first();

            //To get all the bank list...
            $getBankIds = CreditCard::where('user_id', $userId)->select('bank_id')->where('bank_id', '!=', null)
            ->where('is_inactive', false)->groupBy('bank_id')->pluck('bank_id')->toArray();
                
            //To get all the bank data...
            $bankData = Bank::orderBy('bank_name', 'asc')->whereIn('id', $getBankIds)->get();
            
            //To get all the transaction category data...
            $transactionCategoryData = TransactionCategory::where('category_type', 'Expense')->get();

            return view('frontend.expense.creditCardExpense.create', compact('expenseType','transactionCategoryData','bankData','getNote'));
        }else if($expenseType == 'Pocket Wallet Expense'){
            // Note Fetch 
            $getNote = FrontendNote::where('description_type', 'Expense Wallet')->get('description')->first();

            $transactionCategoryData = TransactionCategory::where('category_type', 'Expense')->get();
            return view('frontend.expense.pocketExpense.create', compact('expenseType','transactionCategoryData','getNote'));
        }
    }

    //To check dual currencu with card id...
    public function checkDualCurrency(Request $request)
    {
        $creditCardData = CreditCard::where('id',$request->credit_card_id)->first();

        return view('frontend.expense.creditCardExpense.currencyTab', compact('creditCardData'));
    }



    //To add expense...
    public function saveExpense(Request $request,$expenseType)
    {
        $request->validate([
            'transaction_category_id'=> 'required',
            'notes'=> 'nullable',
        ]);

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get current month & year...
        $currentMonth = date('F');
        $currentYear = date('Y');

        $data = $request->all();
        $data['user_id'] = $userId;
        $data['month'] = $currentMonth;
        $data['year'] = $currentYear;
        $data['status'] = false;
        $expenseType = $expenseType;

        $userMobile = Auth::user()->mobile;
        $uniqueId = random_int(1000000000, 9999999999);
        $data['transaction_id'] =  $uniqueId;

        //To check account type...
        if($expenseType == 'Banking Expense' || $expenseType == 'Mobile Wallet Expense'){
            $validator = Validator::make($request->all(), [
                'from_account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }else if($expenseType == 'Credit Card Expense'){
             $validator = Validator::make($request->all(), [
                'from_credit_card_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }
        }

        //To check from account id & credit card id...
        $fromAccountId = $request->from_account_id;
        $fromCreditCardId = $request->from_credit_card_id;
        if($fromAccountId != null){
            $fromAccountData = Account::where('id', $request->from_account_id)->first();

            //To check balance...
            $previousBalance = $fromAccountData->current_balance;
            $debitBalance = $previousBalance - $request->amount;
            if($debitBalance >= 0){
                //To debit expense amount with from account balance...
                $fromAccountData->current_balance -= $request->amount;
                $data['bank_id'] = $fromAccountData->bank_id;
                $data['mobile_wallet_id'] = $fromAccountData->mobile_wallet_id;
            }else{
                 Toastr::error('Sorry, You have no sufficient balance.', 'Error', ["progressbar" => true]);
                  return redirect(route('webuser.wallet-expense',$expenseType));

            }
        }else if($fromCreditCardId != null){
            $fromAccountData = CreditCard::where('id', $request->from_credit_card_id)->first();

            //To check currency...
            if($fromAccountData->is_dual_currency == true){
                if($request->currency_type == 'BDT Currency'){
                    $request->validate([
                        'amount_bdt'=> 'required',
                    ]);

                    //To check balance...
                    $previousBalanceBDT = $fromAccountData->total_bdt_limit;
                    $debitBalanceBDT = $previousBalanceBDT - $request->amount_bdt;
                    if($debitBalanceBDT >= 0){
                        //To debit expense amount with from credit card balance...
                        $fromAccountData->total_bdt_limit -= $request->amount_bdt;
                        $data['bank_id'] = $fromAccountData->bank_id;
                        $data['amount'] = $request->amount_bdt;
                    }else{
                        Toastr::error('Sorry, You have no sufficient balance.', 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }
                }else{
                    $request->validate([
                        'amount_usd'=> 'required',
                    ]);

                    //To check balance...
                    $previousBalanceUSD = $fromAccountData->total_usd_limit;
                    $debitBalanceUSD = $previousBalanceUSD - $request->amount_usd;
                    if($debitBalanceUSD >= 0){
                        //To debit expense amount with from credit card balance...
                        $fromAccountData->total_usd_limit -= $request->amount_usd;
                        $data['bank_id'] = $fromAccountData->bank_id;
                        $data['amount'] = $request->amount_usd;
                    }else{
                        Toastr::error('Sorry, You have no sufficient balance.', 'Error', ["progressbar" => true]);
                        return redirect()->back();
                    }
                }
            }else{
                //To check balance...
                $previousBalance = $fromAccountData->total_limit;
                $debitBalance = $previousBalance - $request->amount;
                if($debitBalance >= 0){
                    //To debit expense amount with from credit card balance...
                    $fromAccountData->total_limit -= $request->amount;
                    $data['bank_id'] = $fromAccountData->bank_id;
                }else{
                    Toastr::error('Sorry, You have no sufficient balance.', 'Error', ["progressbar" => true]);
                    return redirect()->back();
                }
            }
            
        }else{
            $fromAccountData = User::where('id', Auth::user()->id)->first();

            //To check balance...
            $previousBalance = $fromAccountData->wallet;
            $debitBalance = $previousBalance - $request->amount;
            if($debitBalance >= 0){
                //To debit expense amount with from account balance...
                $fromAccountData->wallet -= $request->amount;
                $data['pocket_wallet_id'] = $fromAccountData->id;
            }else{
               Toastr::error('Sorry, You have no sufficient balance.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }

        if($result = IncomeExpense::create($data)){
            //To save fromAccountData...
            $fromAccountData->save();

            if ($result->income_expense_type == 'Banking Expense') {
                
                $resultData = Crypt::encrypt($result);
                Toastr::success('Transaction/Expense added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.expense-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id]));
            }
            elseif($result->income_expense_type == 'Mobile Wallet Expense'){

                $resultData = Crypt::encrypt($result);
                Toastr::success('Transaction/Expense added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.expense-invoice',['result'=>$resultData , 'from_account'=>$request->from_account_id]));

            }
            elseif($result->income_expense_type == 'Credit Card Expense'){
                
                $resultData = Crypt::encrypt($result);
                Toastr::success('Transaction/Expense added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.expense-card-invoice',['result'=>$resultData , 'from_account'=>$request->from_credit_card_id]));
            }
            else{
                
                $resultData = Crypt::encrypt($result);
                $from_account = Auth::user()->id;
                Toastr::success('Transaction/Income added successfully.', 'success', ["progressbar" => true]);
                return redirect(route('webuser.expense-wallet-invoice',['result'=>$resultData , 'from_account'=>$from_account]));
            }

            

        }else{
           Toastr::error('Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }




    }


}
