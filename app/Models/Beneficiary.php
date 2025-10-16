<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type',
        'bank_id',
        'mobile_wallet_id',
        'branch_name',
        'account_number',
        'account_holder_name',
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
}
