<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use App\Models\CreditCard;
use Carbon\Carbon;
use Validator;
use Session;
use Hash;
use Auth;
use Image;

class ProfileController extends Controller
{
    //To get single user data...
    public function getUserData(Request $request)
    {
        $userData = User::where('id', Auth::user()->id)->with(['userProfessionData'])->first();
        if(!empty($userData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'userData'   =>  $userData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To update user basic profile data...
    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'profession_id' => 'required',
            'wallet' => 'nullable',
            'address' => 'nullable',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        $data = $request->all();
        $userData = User::where('id', Auth::user()->id)->first();

        //To check profile image...
        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/userProfile/');
            if(file_exists($destinationPath.$userData->image)){
                if($userData->image != ''){
                    unlink($destinationPath.$userData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/userProfile/thumbnail/');
                    unlink($destinationPath.$userData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/userProfile/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/userProfile/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }
        
        if($userData->update($data)){
            return response()->json([
                'message' => 'Profile updated successfully.',
                'userData'   =>  $userData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To update user mobile...
    public function profileMobileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:11|max:11',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }


        $userData = User::where('id', Auth::user()->id)->first();
        $userData->verify_code = rand(100000, 999999);
        $userData->verify_expires_at = Carbon::now()->addMinutes(10);
        $contact = $request->mobile;
        $text = 'Congratulations! Your Login OTP code is: '. $userData->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMS($contact,$text);
        $mobile = $request->mobile;
        Session::put('selectedMobile',$mobile);

        if($userData->save()){
            return response()->json([
                'message'   =>  'OTP has sent to your mobile, Please verify your mobile.',
                'verifyOtp'   =>  $userData->verify_code,
                'mobile'   =>  $request->mobile,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
        }
    }


    //To verify mobile otp....
    public function profileMobileVerifyOtp(Request $request)
    {
        $verify_code_mas = User::where('verify_code', $request->verify_code)->first();
        $mobileNum = $request->mobile;
        if(isset($verify_code_mas) && $verify_code_mas != null){
            if( $verify_code_mas->verify_expires_at < (Carbon::now())){
                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->save();
                
                    return response()->json([
                        'message'   =>  'Your Verify Opt has expired. Please Resend Code.',
                        'status_code'   => 201
                    ], 500);
                
                }else{
                    $verify_code_mas->verify_code = null;
                    $verify_code_mas->verify_expires_at = null;
                    $verify_code_mas->mobile = $mobileNum;
                    $verify_code_mas->status = 1;
                    $verify_code_mas->save();

                    Session::forget('selectedMobile');
                    return response()->json([
                        'message'   =>  'You are verified now, Please login..',
                        'status_code'   => 201
                    ], 201);
                }
        }else{
             return response()->json([
                'message'   =>  'Your Opt you have entered does not match',
                'status_code'   => 500
            ], 500);
        }
    }

    //To update user password...
    public function securityUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);

        $currentUser = User::where('id', Auth::user()->id)->first();
        if (Hash::check($request->old_password,$currentUser->password)) {
            if ($request->password == $request->password_confirmation) {
                User::find($currentUser->id)->update([
                    'password' => Hash::make($request->password)
                ]);

                // $this->userLogout($currentUser->id);
                return response()->json([
                    'message'   =>  'Your password has changed successfully',
                    'status_code'   => 201
                ], 201);
            }else{
                return response()->json([
                    'message'   =>  'Password and Confirm Password do not match.',
                    'status_code'   => 500
                ], 500);
            }
        }else{
            return response()->json([
                'message'   =>  'Old Password do not match.',
                'status_code'   => 500
            ], 500);
        }
    }


    //To logout...
    public function userLogout($userId)
    {
        $user = User::where('id', $userId)->first();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully',
            'status_code'   => 201
        ]);
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
        $response = curl_exec($ch);
        curl_close($ch);

		// Instead of returning the raw response, you can just return a success message or any other desired output
        if ($response === 'SUCCESS') {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['message' => 'Failed to send SMS'], 500);
        }
        
    }

    //mobile wallet edit list ....
    public function mobileWalletAccountEditList()
    {
        $accountData = Account::orderBy('id','DESC')->where('mobile_wallet_id', '!=', null)->where('user_id',Auth::user()->id)
                        ->with(['bankData','mobileWalletData'])->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountData'   =>  $accountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //banking wallet edit list ....
    public function bankingWalletAccountEditList()
    {
        $accountData = Account::orderBy('id','desc')->where('bank_id', '!=', null)->where('user_id',Auth::user()->id)
                        ->with(['bankData','mobileWalletData'])->get();

        //To check data empty or not...
        if($accountData->count() != 0){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountData'   =>  $accountData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //credit card edit list ....
    public function creditCardWalletAccountEditList()
    {
        $creditCardData = CreditCard::orderBy('id','desc')->where('user_id',Auth::user()->id)
                            ->with(['bankData'])->get();

        //To check data empty or not...
        if($creditCardData->count() != 0){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'accountData'   =>  $creditCardData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }
}
