<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'mobile_wallet_id',
        'branch',
        'bank_account_type',
        'account_number',
        'current_balance',
        'is_inactive',
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

    //To get all the account number count...
    public static function getTotalAccountNumber($accountId)
    {
        $data = Account::find($accountId);
        if(isset($data) && $data->mobile_wallet_id != null){
            $getTotalAccountCount = Account::where('user_id', $data->user_id)->where('mobile_wallet_id', $data->mobile_wallet_id)->count();
        }elseif(isset($data) && $data->bank_id != null){
            $getTotalAccountCount = Account::where('user_id', $data->user_id)->where('bank_id', $data->bank_id)->count();
        }else{
            $getTotalAccountCount = 0;
        }

        return $getTotalAccountCount;
    }
}
