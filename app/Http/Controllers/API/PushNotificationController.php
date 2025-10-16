<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\PushNotification;
use App\Models\User;
use Validator;

class PushNotificationController extends Controller
{
    //To update device token...
    public function updateDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get Auth info for update device token...
        Auth::user()->device_token =  $request->device_token;

        if(Auth::user()->save()){
            return response()->json([
                'message'   =>  'Device token successfully added to server.'
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Something is wrong.!'
			], 500);
        }

    }

    //To send notification...
    public function pushNotificationSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_title' => 'required',
            'notification_message' => 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }


        $title = $request->notification_title;
        $message = $request->notification_message; 

        //To send push notification...
        if(PushNotification::pushNotificationSend($title, $message)){
            return response()->json([
                'message' => 'Push notification sent successfully.'
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Something is wrong.!'
			], 500);
        } 
    }

    //To get push notification data...
    public function getPushNotificationData(Request $request)
    {
        $getData = PushNotification::orderBy('id', 'desc')->where('status', true)->get();

        $arrayData = [];
        foreach($getData as $key=>$item){
            if(isset($item) && $item != null){
                $singleNotification = PushNotification::where('id', $item->id)->first();
                // For Notification message...
                $tagRemovalNotificationMessage = strip_tags($item->notification_message);
                $originalNotificationMessage = preg_replace("/\s|&nbsp;/"," ",$tagRemovalNotificationMessage);
                
                $arrayData[] = array(
                    'notificationData' => $singleNotification,
                    'notificationMessage' => $originalNotificationMessage
                );
            }
        }
        if(!empty($arrayData)){
            return response()->json([
                'message'   =>  'Successfully loaded ata.',
                'data'   =>  $arrayData
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.'
			], 500);
        }
    }
}
