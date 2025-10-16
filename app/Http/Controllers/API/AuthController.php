<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Helpers\SendSMS;
use App\Models\User;
use App\Models\UserProfession;
use Carbon\Carbon;
use Session;

class AuthController extends Controller
{
    //To get all the profession data...
    public function getProfessionData()
    {
        //To get all the profession data...
        $userProfessionData = UserProfession::orderBy('profession_name', 'asc')->get();
        if(!empty($userProfessionData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'userProfessionData'   =>  $userProfessionData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To user register...
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable',
            'mobile' => 'required|min:11|max:11',
            'password' => 'required|same:password_confirmation',
            'gender' => 'nullable',
            'profession_id' => 'required',
            'wallet' => 'nullable',
        ]);

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        if(!User::where('mobile', $request->mobile)->first()){
            //To check email is unique or not...
            if(!User::where('email', $request->email)->first()){
                //
            }else{
                return response()->json([
                    'message'   =>  'This email had already taken.!',
                    'status_code'   => 500
                ], 500);
            }
            
            if($request->password == $request->password_confirmation){
                $data['password'] = Hash::make($request->password);
                if($newUser = User::create($data)){
                    $userRole = Role::where('name', 'user')->pluck('id');
                    $newUser->assignRole($userRole);

                    $user = User::where('mobile', $request->mobile)->first();
                    $accessToken = $user->createToken('BankSoft2023')->accessToken;

                    /*mobile send SMS*/
                    $contact = $request->mobile;
                    $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
                    $this->sendSMS($contact,$text);

                    /*user id Session*/
                    $id=$user->id;
                    Session::put('user_id',$id);
                
                    $data = [
                        'message' => 'Registration completed, Please verify your account.',
                        'otpVerifyCode' => $user->verify_code,
                        'status_code'   => 201
                    ];
            
                    return response()->json($data);
                }else{
                    return response()->json([
                        'message'   =>  'Something is wrong.!',
                        'status_code'   => 500
                    ], 500);
                }
            }
            else{
                return response()->json([
                    'message'   =>  'Password and confirm password not matching.!',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'This number had already taken.!',
                'status_code'   => 500
            ], 500);
        }
    }

    //To user login...
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        if (isset($user) && $user != null) {
            if (Hash::check($request->password, $user->password)) {
                if($user->status == 1){
                    $tokenData = $user->createToken('Testpaper2022');
                    $token = $tokenData->token;

                    if($token->save()){
                        $data = [
                            'message' => 'Login successfully done.',
                            'access_token' => $tokenData->accessToken,
                            'userData' => $user,
                            'status_code'   => 201
                        ];

                        return response()->json($data);
                    }
                }else{
                    return response()->json([
                        'message'   =>  'Sorry, Your are not verified user.!',
                        'status_code'   => 500
                    ], 500);
                }
                
            }else {
                return response()->json([
                    'message'   =>  'Sorry, Password not matching.!',
                    'status_code'   => 500
                ], 500);
            }
        }
        else {
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!',
                'status_code'   => 500
            ], 500);
        }
    }

    //To reset password...
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required'],
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        $mobile = $request->mobile;
        $text = 'Congratulations! Your Login OTP code is: '.$data['verify_code'].' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        
        $userData = User::where('mobile', $request->mobile)->first();
        if(isset($userData)){
            $this->sendSMS($mobile,$text);
            if($userData->update($data)){
                return response()->json([
                    'message'   =>  'OTP has sent to your email, Please verify your email.',
                    'verifyOtp'   =>  $userData->verify_code,
                    'mobile'   =>  $userData->mobile,
                    'status_code'   => 201
                ], 201);
            }else{
                return response()->json([
                    'message'   =>  'The opt has been sent not again!.',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!',
                'status_code'   => 500
            ], 500);
        }
        
    }

    //To password update...
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required'],
            'password' => ['required', 'confirmed']
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        if($request->password == $request->password_confirmation){
            $data['password'] = Hash::make($request->password);

            $user = User::where('mobile', $request->mobile)->first();
            if($user->update($data)){
                $data = [
                    'message' => 'Password changed successfully, Please login.',
                    'userData' => $user,
                    'status_code'   => 201
                ];
        
                return response()->json($data);
            }else{
                return response()->json([
                    'message'   =>  'Sorry, Password not matching.!',
                    'status_code'   => 500
                ], 500);
            }
        }
        else{
            return response()->json([
				'message'   =>  'Sorry, Something is wrond.!',
                'status_code'   => 500
			], 500);
        }
    }


    //To logout...
    public function logOut(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully done.',
            'status_code'   => 201
        ]);
    }


    //To verify otp....
    public function verifyOtp(Request $request)
    {
        $singleUserData = User::where('verify_code', $request->verify_code)->first();

        if($singleUserData ){
            if( $singleUserData->verify_expires_at < (Carbon::now())){

                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
              
                return response()->json([
                    'message'   =>  'OTP verification time expired, Please resend OTP again.!',
                    'status_code'   => 500
                ], 500);
            
            }else{

                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->status = 1;
                $singleUserData->save();
                return response()->json([
                    'message'   =>  'You are now verified, Please login.',
                    'status_code'   => 201
                ], 201);
            }
           
        }
        return response()->json([
            'message'   =>  'Sorry, OTP not matching.!',
            'status_code'   => 500
        ], 500);
       
    }

    //To again resend otp...
    public function resendOtp(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*mobile send SMS*/
        $text = 'Congratulations! Your Verify Opt. '. $user->verify_code;
        $this->sendSMS($user->mobile,$text);

        if($user){
            return response()->json([
                'message'   =>  'OTP send to your number, Please verify your OTP.!',
                'verifyOtp'   =>  $user->verify_code,
                'mobile'   =>  $user->mobile,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry, You are not registered.!',
                'status_code'   => 500
            ], 500);
        }

    }

    //To get test message...
    public function testMessage(Request $request)
    {
        $url = "https://mimsms.com.bd/smsAPI";
		$data = [
            "sendsms"=>"",
		    "apikey" => "XQn6pUiIdnzhNdcTviqiqdlCflEFaORu",
		    "apitoken" => "vNd71691398017",
		    "type" => "sms",
		    "from" => "8809601004746",
		    "to" => $contact,
		    "text" => $text,
		    "route" => 0,
		 ];

		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 $response = curl_exec($ch);
		 curl_close($ch);
    }

    //To Send Verify SMS...
    private function sendSMS($contact, $text)
    {
    	$url = "https://mimsms.com.bd/smsAPI";
		$data = [
            "sendsms"=>"",
		    "apikey" => "XQn6pUiIdnzhNdcTviqiqdlCflEFaORu",
		    "apitoken" => "vNd71691398017",
		    "type" => "sms",
		    "from" => "8809601004746",
		    "to" => $contact,
		    "text" => $text,
		    "route" => 0,
		 ];

		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 $response = curl_exec($ch);
		 curl_close($ch);

		// Instead of returning the raw response, you can just return a success message or any other desired output
        if ($response === 'SUCCESS') {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['message' => 'Failed to send SMS'], 500);
        }
        
    }


    protected function guard()
    {
        return Auth::guard();
    }
}
