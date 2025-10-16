<?php

namespace App\Http\Controllers\API;

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
use App\Models\User;
use App\Models\IncomeExpense;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionController extends Controller
{
    /*
    ////////////////////////////////////////
    For Pocket Wallet Section Start...
    ////////////////////////////////////////
    */

    //To get pocket wallet account transaction list page...
    public function transactionPocketWalletAccountAllList()
    {
        //To get single account data...
        $singleAccountData = User::where('id', Auth::user()->id)->first();

        //To get all the all data from incomeExpense...
        $incomeExpenseDataForAll = IncomeExpense::orderBy('id','desc')->where('pocket_wallet_id', $singleAccountData->id)->get();

        //To get all the all data from accountPayments...
        $accountPaymentDataForAll = AccountPayment::orderBy('id','desc')->where('to_pocket_account_id', $singleAccountData->id)
                                ->orWhere('from_pocket_account_id', $singleAccountData->id)->get();

        $allData = [];
        $indexNumberForAll = 1;
        foreach($incomeExpenseDataForAll as $key => $singleItemForAll){
            if(isset($singleItemForAll) && $singleItemForAll != null){
                //To check credit debit...
                if($singleItemForAll->income_expense_type == 'Pocket Wallet Income'){
                    $creditDebitFlagForInEx = true;
                }else{
                    $creditDebitFlagForInEx = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForAll->id,
                    'transaction_id'=>$singleItemForAll->transaction_id,
                    'title' => $singleItemForAll->income_expense_type,
                    'account_number' => $singleItemForAll->pocketWalletData->mobile,
                    'credit_debit_amount' => $singleItemForAll->amount,
                    'processing_fee_amount' => "0.00",
                    'credit_debit_flag' => $creditDebitFlagForInEx,
                    'notes' => $singleItemForAll->notes,
                    'date' => $singleItemForAll->created_at->toDateString(),
                    'time' => $singleItemForAll->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForAll as $key => $singleAccPayForAll){
            if(isset($singleAccPayForAll) && $singleAccPayForAll != null){
                //To check to and from pocket_account_id is null or not...
                if($singleAccPayForAll->to_pocket_account_id != null){
                    $accNumber = $singleAccPayForAll->toPocketAccountData->mobile;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = true;
                }else{
                    $accNumber = $singleAccPayForAll->fromPocketAccountData->mobile;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForAll->id,
                    'transaction_id' => $transactionId,
                    'title' => $singleAccPayForAll->payment_type,
                    'account_number' => $accNumber,
                    'credit_debit_amount' => $singleAccPayForAll->pay_amount,
                    'processing_fee_amount' => $singleAccPayForAll->pay_fee_amount,
                    'credit_debit_flag' => $creditDebitFlagForAccPay,
                    'notes' => $singleAccPayForAll->notes,
                    'date' => $singleAccPayForAll->created_at->toDateString(),
                    'time' => $singleAccPayForAll->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $allData = $this->paginate($allData);

        if(!empty($allData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'allData'   =>  $allData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get pocket wallet account credit transaction list page...
    public function transactionPocketWalletAccountCreditList()
    {
        //To get single account data...
        $singleAccountData = User::where('id', Auth::user()->id)->first();

        //To get all the all, credit, debit data from incomeExpense...
        $incomeExpenseDataForCredit = IncomeExpense::orderBy('id','desc')->where('status', true)->where('pocket_wallet_id', $singleAccountData->id)
                            ->where('income_expense_type','Pocket Wallet Income')->get();

        //To get all the all, credit, debit data from accountPayments...
        $accountPaymentDataForCredit = AccountPayment::orderBy('id','desc')
                                ->where('to_pocket_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Pocket Wallet Credit Data To Load...
        ////////////////////////////////////////
        */

        $creditData = [];
        $indexNumberForCredit = 1;
        foreach($incomeExpenseDataForCredit as $key => $singleItemForCredit){
            if(isset($singleItemForCredit) && $singleItemForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForCredit->id,
                    'title' => $singleItemForCredit->income_expense_type,
                    'transaction_id'=>$singleItemForCredit->transaction_id,
                    'account_number' => $singleItemForCredit->pocketWalletData->mobile,
                    'credit_amount' => $singleItemForCredit->amount,
                    'processing_fee_amount' => "0.00",
                    'notes' => $singleItemForCredit->notes,
                    'date' => $singleItemForCredit->created_at->toDateString(),
                    'time' => $singleItemForCredit->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForCredit as $key => $singleAccPayForCredit){
            if(isset($singleAccPayForCredit) && $singleAccPayForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForCredit->id,
                    'title' => $singleAccPayForCredit->payment_type,
                    'transaction_id'=>$singleAccPayForCredit->transaction_id,
                    'account_number' => $singleAccPayForCredit->toPocketAccountData->mobile,
                    'credit_amount' => $singleAccPayForCredit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForCredit->pay_fee_amount,
                    'notes' => $singleAccPayForCredit->notes,
                    'date' => $singleAccPayForCredit->created_at->toDateString(),
                    'time' => $singleAccPayForCredit->created_at->toTimeString()
                );
            }
        }
        
        //For pginatio to set...
        $creditData = $this->paginate($creditData);

        if(!empty($creditData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditData'   =>  $creditData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get pocket wallet account debit transaction list page...
    public function transactionPocketWalletAccountDebitList()
    {
        //To get single account data...
        $singleAccountData = User::where('id', Auth::user()->id)->first();

        //To get all the debit data from incomeExpense...
        $incomeExpenseDataForDebit = IncomeExpense::orderBy('id','desc')->where('status', false)->where('pocket_wallet_id', $singleAccountData->id)
                            ->where('income_expense_type','Pocket Wallet Expense')->get();

        //To get all the debit data from accountPayments...
        $accountPaymentDataForDebit = AccountPayment::orderBy('id','desc')
                                ->where('from_pocket_account_id', $singleAccountData->id)->get();


        /*
        ////////////////////////////////////////
        For Pocket Wallet Debit Data To Load...
        ////////////////////////////////////////
        */

        $debitData = [];
        $indexNumberForDebit = 1;
        foreach($incomeExpenseDataForDebit as $key => $singleItemForDebit){
            if(isset($singleItemForDebit) && $singleItemForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForDebit->id,
                    'title' => $singleItemForDebit->income_expense_type,
                    'transaction_id'=>$singleItemForDebit->transaction_id,
                    'account_number' => $singleItemForDebit->pocketWalletData->mobile,
                    'debit_amount' => $singleItemForDebit->amount,
                    'processing_fee_amount' => "0.00",
                    'notes' => $singleItemForDebit->notes,
                    'date' => $singleItemForDebit->created_at->toDateString(),
                    'time' => $singleItemForDebit->created_at->toTimeString()
                );
            }
        }

        foreach($accountPaymentDataForDebit as $key => $singleAccPayForDebit){
            if(isset($singleAccPayForDebit) && $singleAccPayForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForDebit->id,
                    'title' => $singleAccPayForDebit->payment_type,
                    'transaction_id'=>$singleAccPayForDebit->transaction_id,
                    'account_number' => $singleAccPayForDebit->fromPocketAccountData->mobile,
                    'debit_amount' => $singleAccPayForDebit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForDebit->pay_fee_amount,
                    'notes' => $singleAccPayForDebit->notes,
                    'date' => $singleAccPayForDebit->created_at->toDateString(),
                    'time' => $singleAccPayForDebit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $debitData = $this->paginate($debitData);

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
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
    ////////////////////////////////////////
    For Mobile Wallet Section Start...
    ////////////////////////////////////////
    */

    //To get mobile wallet account transaction all list page...
    public function transactionMobileWalletAccountAllList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the all data from incomeExpense...
        $incomeExpenseDataForAll = IncomeExpense::orderBy('id','desc')
                            ->where('from_account_id', $singleAccountData->id)->get();

        //To get all the all data from accountPayments...
        $accountPaymentDataForAll = AccountPayment::orderBy('id','desc')->where('to_account_id', $singleAccountData->id)
                                ->orWhere('from_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Mobile Wallet All Data To Load...
        ////////////////////////////////////////
        */

        $allData = [];
        $indexNumberForAll = 1;
        foreach($incomeExpenseDataForAll as $key => $singleItemForAll){
            if(isset($singleItemForAll) && $singleItemForAll != null){
                //To check credit debit...
                if($singleItemForAll->income_expense_type == 'Mobile Wallet Income'){
                    $creditDebitFlagForInEx = true;
                }else{
                    $creditDebitFlagForInEx = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForAll->id,
                    'transaction_id'=>$singleItemForAll->transaction_id,
                    'title' => $singleItemForAll->income_expense_type,
                    'account_number' => $singleItemForAll->fromAccountData->account_number,
                    'credit_debit_amount' => $singleItemForAll->amount,
                    'processing_fee_amount' => "0.00",
                    'credit_debit_flag' => $creditDebitFlagForInEx,
                    'notes' => $singleItemForAll->notes,
                    'date' => $singleItemForAll->created_at->toDateString(),
                    'time' => $singleItemForAll->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForAll as $key => $singleAccPayForAll){
            if(isset($singleAccPayForAll) && $singleAccPayForAll != null){
                //To check to and from account_id is null or not...
                if($singleAccPayForAll->to_account_id != null){
                    $accNumber = $singleAccPayForAll->toAccountData->account_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = true;
                }else{
                    $accNumber = $singleAccPayForAll->fromAccountData->account_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForAll->id,
                    'transaction_id'=>$transactionId,
                    'title' => $singleAccPayForAll->payment_type,
                    'account_number' => $accNumber,
                    'credit_debit_amount' => $singleAccPayForAll->pay_amount,
                    'processing_fee_amount' => $singleAccPayForAll->pay_fee_amount,
                    'credit_debit_flag' => $creditDebitFlagForAccPay,
                    'notes' => $singleAccPayForAll->notes,
                    'date' => $singleAccPayForAll->created_at->toDateString(),
                    'time' => $singleAccPayForAll->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $allData = $this->paginate($allData);
        $accountId =$id;

        if(!empty($allData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'allData'   =>  $allData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get mobile wallet account transaction credit list page...
    public function transactionMobileWalletAccountCreditList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the credit data from incomeExpense...
        $incomeExpenseDataForCredit = IncomeExpense::orderBy('id','desc')->where('status', true)->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Mobile Wallet Income')->get();

        //To get all the credit data from accountPayments...
        $accountPaymentDataForCredit = AccountPayment::orderBy('id','desc')
                                ->where('to_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Mobile Wallet Credit Data To Load...
        ////////////////////////////////////////
        */

        $creditData = [];
        $indexNumberForCredit = 1;
        foreach($incomeExpenseDataForCredit as $key => $singleItemForCredit){
            if(isset($singleItemForCredit) && $singleItemForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForCredit->id,
                    'title' => $singleItemForCredit->income_expense_type,
                    'transaction_id'=>$singleItemForCredit->transaction_id,
                    'account_number' => $singleItemForCredit->fromAccountData->account_number,
                    'credit_amount' => $singleItemForCredit->amount,
                    'processing_fee_amount' => "0.00",
                    'notes' => $singleItemForCredit->notes,
                    'date' => $singleItemForCredit->created_at->toDateString(),
                    'time' => $singleItemForCredit->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForCredit as $key => $singleAccPayForCredit){
            if(isset($singleAccPayForCredit) && $singleAccPayForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForCredit->id,
                    'title' => $singleAccPayForCredit->payment_type,
                    'transaction_id'=>$singleAccPayForCredit->transaction_id,
                    'account_number' => $singleAccPayForCredit->toAccountData->account_number,
                    'credit_amount' => $singleAccPayForCredit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForCredit->pay_fee_amount,
                    'notes' => $singleAccPayForCredit->notes,
                    'date' => $singleAccPayForCredit->created_at->toDateString(),
                    'time' => $singleAccPayForCredit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $creditData = $this->paginate($creditData);
        $accountId = $id;

        if(!empty($creditData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditData'   =>  $creditData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get mobile wallet account transaction debit list page...
    public function transactionMobileWalletAccountDebitList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the debit data from incomeExpense...
        $incomeExpenseDataForDebit = IncomeExpense::orderBy('id','desc')->where('status', false)->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Mobile Wallet Expense')->get();

        //To get all the debit data from accountPayments...
        $accountPaymentDataForDebit = AccountPayment::orderBy('id','desc')
                                ->where('from_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Mobile Wallet Debit Data To Load...
        ////////////////////////////////////////
        */

        $debitData = [];
        $indexNumberForDebit = 1;
        foreach($incomeExpenseDataForDebit as $key => $singleItemForDebit){
            if(isset($singleItemForDebit) && $singleItemForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForDebit->id,
                    'title' => $singleItemForDebit->income_expense_type,
                    'transaction_id'=>$singleItemForDebit->transaction_id,
                    'account_number' => $singleItemForDebit->fromAccountData->account_number,
                    'debit_amount' => $singleItemForDebit->amount,
                    'processing_fee_amount' => '0.00',
                    'notes' => $singleItemForDebit->notes,
                    'date' => $singleItemForDebit->created_at->toDateString(),
                    'time' => $singleItemForDebit->created_at->toTimeString()
                );
            }
        }

        foreach($accountPaymentDataForDebit as $key => $singleAccPayForDebit){
            if(isset($singleAccPayForDebit) && $singleAccPayForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForDebit->id,
                    'title' => $singleAccPayForDebit->payment_type,
                    'transaction_id'=>$singleAccPayForDebit->transaction_id,
                    'account_number' => $singleAccPayForDebit->fromAccountData->account_number,
                    'debit_amount' => $singleAccPayForDebit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForDebit->pay_fee_amount,
                    'notes' => $singleAccPayForDebit->notes,
                    'date' => $singleAccPayForDebit->created_at->toDateString(),
                    'time' => $singleAccPayForDebit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $debitData = $this->paginate($debitData);
        $accountId = $id;

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
                'accountId'   =>  $accountId,
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
    ////////////////////////////////////////
    For Banking Wallet Section Start...
    ////////////////////////////////////////
    */

    //To get banking wallet account transaction all list page...
    public function transactionBankingWalletAccountAllList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the all data from incomeExpense...
        $incomeExpenseDataForAll = IncomeExpense::orderBy('id','desc')
                            ->where('from_account_id', $singleAccountData->id)->get();

        //To get all the all data from accountPayments...
        $accountPaymentDataForAll = AccountPayment::orderBy('id','desc')->where('to_account_id', $singleAccountData->id)
                                ->orWhere('from_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Banking All Data To Load...
        ////////////////////////////////////////
        */

        $allData = [];
        $indexNumberForAll = 1;
        foreach($incomeExpenseDataForAll as $key => $singleItemForAll){
            if(isset($singleItemForAll) && $singleItemForAll != null){
                //To check credit debit...
                if($singleItemForAll->income_expense_type == 'Banking Income'){
                    $creditDebitFlagForInEx = true;
                }else{
                    $creditDebitFlagForInEx = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForAll->id,
                    'transaction_id'=>$singleItemForAll->transaction_id,
                    'title' => $singleItemForAll->income_expense_type,
                    'account_number' => $singleItemForAll->fromAccountData->account_number,
                    'credit_debit_amount' => $singleItemForAll->amount,
                    'processing_fee_amount' => "0.00",
                    'credit_debit_flag' => $creditDebitFlagForInEx,
                    'notes' => $singleItemForAll->notes,
                    'date' => $singleItemForAll->created_at->toDateString(),
                    'time' => $singleItemForAll->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForAll as $key => $singleAccPayForAll){
            if(isset($singleAccPayForAll) && $singleAccPayForAll != null){
                //To check to and from account_id is null or not...
                if($singleAccPayForAll->to_account_id != null){
                    $accNumber = $singleAccPayForAll->toAccountData->account_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = true;
                }else{
                    $accNumber = $singleAccPayForAll->fromAccountData->account_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForAll->id,
                    'transaction_id'=>$transactionId,
                    'title' => $singleAccPayForAll->payment_type,
                    'account_number' => $accNumber,
                    'credit_debit_amount' => $singleAccPayForAll->pay_amount,
                    'processing_fee_amount' => $singleAccPayForAll->pay_fee_amount,
                    'credit_debit_flag' => $creditDebitFlagForAccPay,
                    'notes' => $singleAccPayForAll->notes,
                    'date' => $singleAccPayForAll->created_at->toDateString(),
                    'time' => $singleAccPayForAll->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $allData = $this->paginate($allData);
        $accountId =$id;

        if(!empty($allData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'allData'   =>  $allData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get banking wallet account transaction credit list page...
    public function transactionBankingWalletAccountCreditList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the credit data from incomeExpense...
        $incomeExpenseDataForCredit = IncomeExpense::orderBy('id','desc')->where('status', true)->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Banking Income')->get();

        //To get all the credit data from accountPayments...
        $accountPaymentDataForCredit = AccountPayment::orderBy('id','desc')
                                ->where('to_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Banking Credit Data To Load...
        ////////////////////////////////////////
        */

        $creditData = [];
        $indexNumberForCredit = 1;
        foreach($incomeExpenseDataForCredit as $key => $singleItemForCredit){
            if(isset($singleItemForCredit) && $singleItemForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForCredit->id,
                    'title' => $singleItemForCredit->income_expense_type,
                    'transaction_id'=>$singleItemForCredit->transaction_id,
                    'account_number' => $singleItemForCredit->fromAccountData->account_number,
                    'credit_amount' => $singleItemForCredit->amount,
                    'processing_fee_amount' => "0.00",
                    'notes' => $singleItemForCredit->notes,
                    'date' => $singleItemForCredit->created_at->toDateString(),
                    'time' => $singleItemForCredit->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForCredit as $key => $singleAccPayForCredit){
            if(isset($singleAccPayForCredit) && $singleAccPayForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForCredit->id,
                    'title' => $singleAccPayForCredit->payment_type,
                    'transaction_id'=>$singleAccPayForCredit->transaction_id,
                    'account_number' => $singleAccPayForCredit->toAccountData->account_number,
                    'credit_amount' => $singleAccPayForCredit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForCredit->pay_fee_amount,
                    'notes' => $singleAccPayForCredit->notes,
                    'date' => $singleAccPayForCredit->created_at->toDateString(),
                    'time' => $singleAccPayForCredit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $creditData = $this->paginate($creditData);
        $accountId =$id;

        if(!empty($creditData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditData'   =>  $creditData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get banking wallet account transaction debit list page...
    public function transactionBankingWalletAccountDebitList($id)
    {
        //To get single account data...
        $singleAccountData = Account::where('id', $id)->first();

        //To get all the all, credit, debit data from incomeExpense...
        $incomeExpenseDataForDebit = IncomeExpense::orderBy('id','desc')->where('status', false)->where('from_account_id', $singleAccountData->id)
                            ->where('income_expense_type','Banking Expense')->get();

        //To get all the all, credit, debit data from accountPayments...
        $accountPaymentDataForDebit = AccountPayment::orderBy('id','desc')
                                ->where('from_account_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Banking Debit Data To Load...
        ////////////////////////////////////////
        */

        $debitData = [];
        $indexNumberForDebit = 1;
        foreach($incomeExpenseDataForDebit as $key => $singleItemForDebit){
            if(isset($singleItemForDebit) && $singleItemForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForDebit->id,
                    'title' => $singleItemForDebit->income_expense_type,
                    'transaction_id'=>$singleItemForDebit->transaction_id,
                    'account_number' => $singleItemForDebit->fromAccountData->account_number,
                    'debit_amount' => $singleItemForDebit->amount,
                    'processing_fee_amount' => "0.00",
                    'notes' => $singleItemForDebit->notes,
                    'date' => $singleItemForDebit->created_at->toDateString(),
                    'time' => $singleItemForDebit->created_at->toTimeString()
                );
            }
        }

        foreach($accountPaymentDataForDebit as $key => $singleAccPayForDebit){
            if(isset($singleAccPayForDebit) && $singleAccPayForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForDebit->id,
                    'title' => $singleAccPayForDebit->payment_type,
                    'transaction_id'=>$singleAccPayForDebit->transaction_id,
                    'account_number' => $singleAccPayForDebit->fromAccountData->account_number,
                    'debit_amount' => $singleAccPayForDebit->pay_amount,
                    'processing_fee_amount' => $singleAccPayForDebit->pay_fee_amount,
                    'notes' => $singleAccPayForDebit->notes,
                    'date' => $singleAccPayForDebit->created_at->toDateString(),
                    'time' => $singleAccPayForDebit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $debitData = $this->paginate($debitData);
        $accountId =$id;

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
                'accountId'   =>  $accountId,
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
    ////////////////////////////////////////
    For Credit Card Wallet Section Start...
    ////////////////////////////////////////
    */

    //To get card wallet account transaction all list page...
    public function transactionCardWalletAccountAllList($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id', $id)->first();

        //To get all the all data from incomeExpense...
        $incomeExpenseDataForAll = IncomeExpense::orderBy('id','desc')
                            ->where('from_credit_card_id', $singleAccountData->id)->get();

        //To get all the all data from accountPayments...
        $accountPaymentDataForAll = AccountPayment::orderBy('id','desc')->where('to_credit_card_id', $singleAccountData->id)
                                ->orWhere('from_credit_card_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Credit Card All Data To Load...
        ////////////////////////////////////////
        */

        $allData = [];
        $indexNumberForAll = 1;
        foreach($incomeExpenseDataForAll as $key => $singleItemForAll){
            if(isset($singleItemForAll) && $singleItemForAll != null){
                //To check credit debit...
                if($singleItemForAll->income_expense_type == 'Banking Income'){
                    $creditDebitFlagForInEx = true;
                }else{
                    $creditDebitFlagForInEx = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForAll->id,
                    'transaction_id'=>$singleItemForAll->transaction_id,
                    'title' => $singleItemForAll->income_expense_type,
                    'account_number' => $singleItemForAll->fromCreditCardData->card_number,
                    'credit_debit_amount' => $singleItemForAll->amount,
                    'processing_fee_amount' => "0.00",
                    'credit_debit_flag' => $creditDebitFlagForInEx,
                    'is_bill_payment' => 0,
                    'notes' => $singleItemForAll->notes,
                    'date' => $singleItemForAll->created_at->toDateString(),
                    'time' => $singleItemForAll->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForAll as $key => $singleAccPayForAll){
            if(isset($singleAccPayForAll) && $singleAccPayForAll != null){
                //To check to and from account_id is null or not...
                if($singleAccPayForAll->to_credit_card_id != null){
                    $accNumber = $singleAccPayForAll->toCreditCardData->card_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = true;
                }else{
                    $accNumber = $singleAccPayForAll->creditCardData->card_number;
                    $transactionId = $singleAccPayForAll->transaction_id;
                    $creditDebitFlagForAccPay = false;
                }

                $allData[] = array(
                    'serial_no' => $indexNumberForAll++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForAll->id,
                    'transaction_id'=>$transactionId,
                    'title' => $singleAccPayForAll->payment_type,
                    'account_number' => $accNumber,
                    'credit_debit_amount' => $singleAccPayForAll->pay_amount,
                    'processing_fee_amount' =>  $singleAccPayForAll->pay_fee_amount,
                    'credit_debit_flag' => $creditDebitFlagForAccPay,
                    'is_bill_payment' => $singleAccPayForAll->is_bill_payment,
                    'notes' => $singleAccPayForAll->notes,
                    'date' => $singleAccPayForAll->created_at->toDateString(),
                    'time' => $singleAccPayForAll->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $allData = $this->paginate($allData);
        $accountId =$id;

        if(!empty($allData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'allData'   =>  $allData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get card wallet account transaction credit list page...
    public function transactionCardWalletAccountCreditList($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id', $id)->first();

        //To get all the credit data from incomeExpense...
        $incomeExpenseDataForCredit = IncomeExpense::orderBy('id','desc')->where('status', true)->where('from_credit_card_id', $singleAccountData->id)
                            ->where('income_expense_type','Credit Card Income')->get();

        //To get all the credit data from accountPayments...
        $accountPaymentDataForCredit = AccountPayment::orderBy('id','desc')
                                ->where('to_credit_card_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Credit Card Credit Data To Load...
        ////////////////////////////////////////
        */

        $creditData = [];
        $indexNumberForCredit = 1;
        foreach($incomeExpenseDataForCredit as $key => $singleItemForCredit){
            if(isset($singleItemForCredit) && $singleItemForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForCredit->id,
                    'title' => $singleItemForCredit->income_expense_type,
                    'transaction_id'=>$singleItemForCredit->transaction_id,
                    'account_number' => $singleItemForCredit->fromCreditCardData->card_number,
                    'credit_amount' => $singleItemForCredit->amount,
                    'processing_fee_amount' =>  "0.00",
                    'is_bill_payment' => 0,
                    'notes' => $singleItemForCredit->notes,
                    'date' => $singleItemForCredit->created_at->toDateString(),
                    'time' => $singleItemForCredit->created_at->toTimeString()
                );
            }
        }
        
        foreach($accountPaymentDataForCredit as $key => $singleAccPayForCredit){
            if(isset($singleAccPayForCredit) && $singleAccPayForCredit != null){
                $creditData[] = array(
                    'serial_no' => $indexNumberForCredit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForCredit->id,
                    'title' => $singleAccPayForCredit->payment_type,
                    'transaction_id'=>$singleAccPayForCredit->transaction_id,
                    'account_number' => $singleAccPayForCredit->toCreditCardData->card_number,
                    'credit_amount' => $singleAccPayForCredit->pay_amount,
                    'processing_fee_amount' =>  $singleAccPayForCredit->pay_fee_amount,
                    'is_bill_payment' => $singleAccPayForCredit->is_bill_payment,
                    'notes' => $singleAccPayForCredit->notes,
                    'date' => $singleAccPayForCredit->created_at->toDateString(),
                    'time' => $singleAccPayForCredit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $creditData = $this->paginate($creditData);
        $accountId =$id;

        if(!empty($creditData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditData'   =>  $creditData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get card wallet account transaction debit list page...
    public function transactionCardWalletAccountDebitList($id)
    {
        //To get single account data...
        $singleAccountData = CreditCard::where('id', $id)->first();

        //To get all the all, credit, debit data from incomeExpense...
        $incomeExpenseDataForDebit = IncomeExpense::orderBy('id','desc')->where('status', false)->where('from_credit_card_id', $singleAccountData->id)
                            ->where('income_expense_type','Credit Card Expense')->get();

        //To get all the all, credit, debit data from accountPayments...
        $accountPaymentDataForDebit = AccountPayment::orderBy('id','desc')
                                ->where('from_credit_card_id', $singleAccountData->id)->get();

        /*
        ////////////////////////////////////////
        For Credit Card Debit Data To Load...
        ////////////////////////////////////////
        */

        $debitData = [];
        $indexNumberForDebit = 1;
        foreach($incomeExpenseDataForDebit as $key => $singleItemForDebit){
            if(isset($singleItemForDebit) && $singleItemForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Income/Expense",
                    'id' => $singleItemForDebit->id,
                    'title' => $singleItemForDebit->income_expense_type,
                    'transaction_id'=>$singleItemForDebit->transaction_id,
                    'account_number' => $singleItemForDebit->fromCreditCardData->card_number,
                    'debit_amount' => $singleItemForDebit->amount,
                    'processing_fee_amount' =>  "0.00",
                    'is_bill_payment' => 0,
                    'notes' => $singleItemForDebit->notes,
                    'date' => $singleItemForDebit->created_at->toDateString(),
                    'time' => $singleItemForDebit->created_at->toTimeString()
                );
            }
        }

        foreach($accountPaymentDataForDebit as $key => $singleAccPayForDebit){
            if(isset($singleAccPayForDebit) && $singleAccPayForDebit != null){
                $debitData[] = array(
                    'serial_no' => $indexNumberForDebit++,
                    'type' => "Account Payment",
                    'id' => $singleAccPayForDebit->id,
                    'title' => $singleAccPayForDebit->payment_type,
                    'transaction_id'=>$singleAccPayForDebit->transaction_id,
                    'account_number' => $singleAccPayForDebit->creditCardData->card_number,
                    'debit_amount' => $singleAccPayForDebit->pay_amount,
                    'processing_fee_amount' =>  $singleAccPayForDebit->pay_fee_amount,
                    'is_bill_payment' => $singleAccPayForDebit->is_bill_payment,
                    'notes' => $singleAccPayForDebit->notes,
                    'date' => $singleAccPayForDebit->created_at->toDateString(),
                    'time' => $singleAccPayForDebit->created_at->toTimeString()
                );
            }
        }

        //For pginatio to set...
        $debitData = $this->paginate($debitData);
        $accountId =$id;

        if(!empty($debitData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'debitData'   =>  $debitData,
                'accountId'   =>  $accountId,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To paginatio add...
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        // $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        // $items = $items instanceof Collection ? $items : Collection::make($items);
        // return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        $pageStart = \Request::get('page', 1);
        $offSet = ($pageStart * $perPage) - $perPage; 
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath(),'pageName' => "page"));
    }



    /*
    ************************************
    Invoice Section Start From Here.....
    ************************************
    */


    public function tranjectionInvoice(Request $request)
    {  
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_account_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = Account::where('id',$fromAccountId)->with(['bankData','mobileWalletData'])->first();
        $toAccount = Account::where('id',$toAccountId)->with(['bankData','mobileWalletData'])->first();

        if($result->transfer_type == 'Own Account'){
            $toAccount = Account::where('id',$toAccountId)->with(['bankData','mobileWalletData'])->first();
        }else{
            $toAccount = Beneficiary::where('id',$toAccountId)->with(['bankData','mobileWalletData'])->first();
        }

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function tranjectionInvoicePocketWallet(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_pocket_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_account_id;
                }
            }else{
                $fromAccountId = $result->from_account_id;
                $toAccountId = Auth::user()->id;
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id; 
                }else{
                    if ($resultDataForInEx != null && $resultDataForInEx->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id; 
                    }else{
                        $fromAccountId = $result->from_account_id; 
                    }
                }
            }
        }

        $fromAccount = Account::where('id',$fromAccountId)->with(['bankData','mobileWalletData'])->first();
        $toAccount = User::where('id',$toAccountId)->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function walletTranjectionInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_pocket_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_account_id;
                }
            }else{
                $fromAccountId = $result->from_account_id;
                $toAccountId = Auth::user()->id;
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id; 
                }else{
                    if ($resultDataForInEx != null && $resultDataForInEx->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id; 
                    }else{
                        $fromAccountId = $result->from_account_id; 
                    }
                }
            }
        }
        

        $fromAccount = User::where('id',$fromAccountId)->first();
        if($result->payment_type == 'Wallet To Card'){
            $toAccount = CreditCard::where('id',$toAccountId)->with(['bankData'])->first();
        }else{
            $toAccount = Account::where('id',$toAccountId)->with(['bankData','mobileWalletData'])->first();
        }

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }


    public function cardTranjectionInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = CreditCard::where('id',$fromAccountId)->with(['bankData'])->first();
        $toAccount = Account::where('id',$toAccountId)->with(['bankData','mobileWalletData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function cardBillPaymentInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = Account::where('id',$fromAccountId)->with(['bankData','mobileWalletData'])->first();
        $toAccount = CreditCard::where('id',$toAccountId)->with(['bankData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function cardWalletBillPaymentInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = User::where('id',$fromAccountId)->first();
        $toAccount = CreditCard::where('id',$toAccountId)->with(['bankData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'toAccount'   =>  $toAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function incomeInvoice(Request $request){

        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = Account::where('id',$fromAccountId)->with(['bankData','mobileWalletData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function incomeWalletInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_pocket_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_account_id;
                }
            }else{
                $fromAccountId = $result->from_account_id;
                $toAccountId = Auth::user()->id;
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id; 
                }else{
                    if ($resultDataForInEx != null && $resultDataForInEx->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id; 
                    }else{
                        $fromAccountId = $result->from_account_id; 
                    }
                }
            }
        }

        $fromAccount = User::where('id',$fromAccountId)->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function expenseInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id;
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                    }
                }
            }
        }

        $fromAccount = Account::where('id',$fromAccountId)->with(['bankData','mobileWalletData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function expenseCardInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = $result->to_credit_card_id;
                }else if($result != null && $result->to_pocket_account_id != null){
                    $fromAccountId = $result->from_account_id;
                    $toAccountId = Auth::user()->id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId =  $result->to_beneficiary_account_id;
                    }
                }
            }else{
                if ($result != null && $result->from_credit_card_id != null){
                    $fromAccountId = $result->from_credit_card_id;
                    $toAccountId = $result->to_account_id;
                }elseif ($result != null && $result->from_pocket_account_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    if($result->transfer_type == 'Own Account'){
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_account_id;
                    }else{
                        $fromAccountId = $result->from_account_id;
                        $toAccountId = $result->to_beneficiary_account_id;
                    }
                }
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id; 
                }else{
                    if ($result != null && $result->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id; 
                    }else{
                        $fromAccountId = $result->from_account_id; 
                    }
                }
            }
        }

        $fromAccount = CreditCard::where('id',$fromAccountId)->with(['bankData'])->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    public function expenseWalletInvoice(Request $request)
    {
        //To check type...
        if($request->type != 'Income/Expense'){
            $result = AccountPayment::where('id', $request->id)
                    ->with(['fromAccountData','creditCardData','toAccountData','toCreditCardData'
                    ,'toBeneficiaryAccountData','fromPocketAccountData','toPocketAccountData'])->first();

            //To check from and to account...
            if($result != null && $result->from_pocket_account_id != null){
                if ($result != null && $result->to_credit_card_id != null){
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_credit_card_id;
                }else{
                    $fromAccountId = Auth::user()->id;
                    $toAccountId = $result->to_account_id;
                }
            }else{
                $fromAccountId = $result->from_account_id;
                $toAccountId = Auth::user()->id;
            }
        }else{
            $result = IncomeExpense::where('id', $request->id)
                    ->with(['bankData','mobileWalletData','pocketWalletData','fromAccountData'
                    ,'fromCreditCardData','transactionCategoryData'])->first();

            //To check from and to account...
            if($result != null && $result->status == 1){
                if($result != null && $result->income_expense_type == 'Pocket Wallet Income'){
                   $fromAccountId = Auth::user()->id; 
                }else{
                    $fromAccountId = $result->from_account_id; 
                }
            }else{
                if($result != null && $result->income_expense_type == 'Pocket Wallet Expense'){
                    $fromAccountId = Auth::user()->id; 
                }else{
                    if ($resultDataForInEx != null && $resultDataForInEx->from_credit_card_id != null){
                        $fromAccountId = $result->from_credit_card_id; 
                    }else{
                        $fromAccountId = $result->from_account_id; 
                    }
                }
            }
        }

        $fromAccount = User::where('id',$fromAccountId)->first();

        if(!empty($result)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'fromAccount'   =>  $fromAccount,
                'invoiceDetails'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }
}
