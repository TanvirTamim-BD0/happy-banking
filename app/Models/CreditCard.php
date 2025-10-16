<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'card_type',
        'card_number',
        'billing_date',
        'total_limit',
        'total_bdt_limit',
        'total_usd_limit',
        'is_dual_currency',
        'is_inactive',
        'current_bdt_outstanding',
        'current_usd_outstanding',
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

    //To get single credit card logo name...
    public static function getSingleCreditCardLogo($creditCardId)
    {
        //To get single credit card data...
        $data = CreditCard::where('id', $creditCardId)->first();

        //To check credit card type..
        if($data->card_type == 'Amex'){
            return 'american.jpg';
        }else if($data->card_type == 'Discover'){
            return 'discover.jpg';
        }else if($data->card_type == 'JCB'){
            return 'jcb.jpg';
        }else if($data->card_type == 'Master Card'){
            return 'master.jpg';
        }else if($data->card_type == 'NEXUS'){
            return 'nexus.jpg';
        }else if($data->card_type == 'Visa Card'){
            return 'visa.jpg';
        }else if($data->card_type == 'Union Pay'){
            return 'union.jpg';
        }
    }
}
