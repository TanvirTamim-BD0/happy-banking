<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreditCard;
use Auth;

class CreditCardReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credit_card_id',
        'active_session_id',
        'billing_date',
        'last_payment_date',
        'total_due',
        'total_bdt_due',
        'total_usd_due',
        'minimum_due',
        'bdt_minimum_due',
        'usd_minimum_due',
        'status',
        'is_seen',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function activeSessionData()
    {
        return $this->belongsTo(ActiveSession::class,'active_session_id');
    }

    public function creditCardData()
    {
        return $this->belongsTo(CreditCard::class,'credit_card_id');
    }

    //To get single credit card reminder data...
    public static function getSingleCreditCardReminderData($creditCardId)
    {
        $creditCardId =  CreditCard::where('id',$creditCardId)->first();
        return $creditCardId;
    }
    
    //To get credit card reminder data...
    public static function getUnPaidCreditCardReminder()
    {
        if(isset(Auth::user()->id) && Auth::user()->id != null){
            $creditCardReminderData =  CreditCardReminder::where('user_id', Auth::user()->id)
                        ->where('status', false)->where('is_seen', false)->limit(5)->get();
        }else{
            $creditCardReminderData = null;
        }
        
        return $creditCardReminderData;
    }
    
    //To get credit card reminder count data...
    public static function getUnPaidCreditCardReminderCount()
    {
        if(isset(Auth::user()->id) && Auth::user()->id != null){
            $creditCardReminderData =  CreditCardReminder::where('user_id', Auth::user()->id)
                        ->where('status', false)->where('is_seen', false)->count();
        }else{
            $creditCardReminderData = null;
        }
        
        return $creditCardReminderData;
    }

}
