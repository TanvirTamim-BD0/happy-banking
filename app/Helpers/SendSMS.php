<?php
namespace App\Helpers;

use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\SendSMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SendSMS{

	//For send sms to email..
    public static function sendSMS($contact,$text)
	{
    	$url = "https://esms.mimsms.com/smsapi";
		$data = [
		    "api_key" => "C20090626197dd85101bd7.34935998",
		    "type" => "text",
		    "contacts" => $contact,
		    "senderid" => "8809612436737",
		    "msg" => $text,
		 ];
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 $response = curl_exec($ch);
		 curl_close($ch);
		 return $response;
    }

	//For send sms to email..
	public static function sendSMSToEmail($email,$text)
	{
		$mail_details = [
			'subject' => 'WB SOFTWARES SMS',
			'body' => $text
		];

		\Mail::to($email)->send(new sendEmail($mail_details));
	}
 
}