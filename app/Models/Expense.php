<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'mobile_wallet_id',
        'pocket_wallet_id',
        'from_account_id',
        'from_credit_card_id',
        'source_of_expense_id',
        'expense_type',
        'amount',
        'notes',
        'month',
        'year',
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
    
    public function sourceOfExpenseData()
    {
        return $this->belongsTo(TransactionCategory::class,'source_of_expense_id');
    }
}
