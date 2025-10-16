<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_title',
        'notification_message',
        'sending_date',
        'sending_time',
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

    //To get latest unseen push notification msg...
    public static function getUnseenNotificationMSG()
    {
        $data = PushNotification::orderBy('id','desc')->where('status', true)->where('is_seen', false)->limit(5)->get();
        return $data;
    } 
    
    //To get latest unseen push notification msg...
    public static function getUnseenNotificationMSGCount()
    {
        $data = PushNotification::orderBy('id','desc')->where('status', true)->where('is_seen', false)->count();
        return $data;
    } 

    //To send push notification...
    public static function pushNotificationSend($title, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
            // dd($FcmToken);

        $serverKey = 'AAAAcbRxdOs:APA91bGjVUnfD9AtXHzdUT4aJJE-hnEogDPGbVYrnVk1nvci8aFwzrxzHpeq_EUxqs9VBVt4MaSrFLcsqmNZucO8qUAroy5ynmJg2x9WylJ34jhAmPtB4mjxZP_60pKrJK48sR8xB5KQ'; // ADD SERVER KEY HERE PROVIDED BY FCM
        // $serverKey = 'AAAA0y2UURQ:APA91bFHcNSFs2iUUntO_MUqC__4A1lrMqM99akhy1Lx7bY3NMoLkUUJaknp2Z66mTxruqxkMQzm-7RoI0OCJtjSo5ea-1s34OpQ1D4fhi7vJ1RCOKWrTqkiazBOVdqftq5YiPf2xzBd'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            // "to" => "eo45lBCmTxet-j-35laXHV:APA91bEBLQYBA08Ks_A3H0GuANAgV6bHob2JCuhlexRz88187D3iYAFvRrIk2bwRAC77s7Js9MeqNdH5_A2j7XDX1L2uHhai6Ahd304ysxf3w7kPBYk_IBFp8X3qOUytpSZ7pYgM8Kem",
            "notification" => [
                "title" => $title,
                "body" => $message,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            return response()->json([
                'error' => 'Somethig is wrong.!'
            ]);
        }else{
            curl_close($ch);
            return response()->json([
                'message'   =>  'Successfully loaded message.',
                'data'   =>  $result
            ], 201);
        } 
    }
}
