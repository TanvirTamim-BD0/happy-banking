<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'from_credit_card_id',
        'from_account_id',
        'from_pocket_account_id',
        'to_account_id',
        'to_credit_card_id',
        'to_beneficiary_account_id',
        'to_pocket_account_id',
        'transfer_type', //Own Account Transfer or Beneficiary Account Transfer...
        'payment_type', //'Account To Account','Account To MFS','MFS To Account','MFS To MFS','Account To Wallet','Wallet To Account','MFS To Wallet','Wallet To MFS'
        'transfer_channel',
        'pay_amount',
        'pay_fee',
        'pay_fee_amount',
        'total_pay_amount',
        'notes',
        'month',
        'year',
        'is_bill_payment',
        'transfer_currency_type',
        'usd_in_bdt_rate',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    protected function asDateTime($value)
    {
        return parent::asDateTime($value)->setTimezone('Asia/Dhaka');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function fromAccountData()
    {
        return $this->belongsTo(Account::class,'from_account_id');
    }
    
    public function creditCardData()
    {
        return $this->belongsTo(CreditCard::class,'from_credit_card_id');
    }
    
    public function toAccountData()
    {
        return $this->belongsTo(Account::class,'to_account_id');
    }
    
    public function toCreditCardData()
    {
        return $this->belongsTo(CreditCard::class,'to_credit_card_id');
    }
    
    public function toBeneficiaryAccountData()
    {
        return $this->belongsTo(Beneficiary::class,'to_beneficiary_account_id');
    }
    
    public function fromPocketAccountData()
    {
        return $this->belongsTo(User::class,'from_pocket_account_id');
    }
    
    public function toPocketAccountData()
    {
        return $this->belongsTo(User::class,'to_pocket_account_id');
    }

    //To get single account payment data...
    public static function getSingleAccountPayment($id)
    {
        $data = AccountPayment::where('id', $id)->first();
        return $data;
    }
}
