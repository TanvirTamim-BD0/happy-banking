<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'bank_id',
        'mobile_wallet_id',
        'pocket_wallet_id',
        'from_account_id',
        'from_credit_card_id',
        'transaction_category_id',
        'income_expense_type',
        'amount',
        'notes',
        'month',
        'year',
        'status',
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function bankData()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }
    
    public function mobileWalletData()
    {
        return $this->belongsTo(MobileWallet::class,'mobile_wallet_id');
    }
    
    public function pocketWalletData()
    {
        return $this->belongsTo(User::class,'pocket_wallet_id');
    }
    
    public function fromAccountData()
    {
        return $this->belongsTo(Account::class,'from_account_id');
    }
    
    public function fromCreditCardData()
    {
        return $this->belongsTo(CreditCard::class,'from_credit_card_id');
    }
    
    public function transactionCategoryData()
    {
        return $this->belongsTo(TransactionCategory::class,'transaction_category_id');
    }

    //To get single income expense data...
    public static function getSingleIncomeExpense($id)
    {
        $data = IncomeExpense::where('id', $id)->with(['transactionCategoryData'])->first();
        return $data;
    }
}
