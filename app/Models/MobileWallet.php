<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobile_wallet_name',
        'parent_company',
        'image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
