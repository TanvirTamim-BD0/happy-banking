<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    public function index(){
        return view('frontend.profile.profile');
    }


    //change info page ...
    public function changeInfo(){
        return view('frontend.profile.changeInfoData');
    }


    //change info update ...
    public function changeInfoPpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'profession' => 'nullable',
            'address' => 'nullable',
        ]);

        $data = $request->all();
        $userData = User::where('id', Auth::user()->id)->first();
        
        if($userData->update($data)){
            Toastr::success('Profile Info Updated Successfully!.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.profile');
        }else{
            Toastr::error('Something is error there...!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

    }


    //change password page ...
    public function changePassword(){
        return view('frontend.profile.changePassword');
    }


    //change password update ...
    public function changePasswordPpdate(Request $request)
    {   
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $current_user = Auth()->user();

        if (Hash::check($request->old_password,$current_user->password)) {

            if ($request->new_password == $request->confirm_password) {
                User::find($current_user->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);

                Toastr::success('Password Updated Successfully!.', 'Success', ["progressbar" => true]);
                return redirect()->route('webuser.profile');

            }else{
                Toastr::error('Password and Confirm Password do not match.!', 'Error', ["progressbar" => true]);
                return Redirect()->back();
            }

        }else{
             Toastr::error('Old Password do not match.!', 'Error', ["progressbar" => true]);
             return Redirect()->back();
        }

    }


    //change mobile page ...
    public function changeMobile(){
        return view('frontend.profile.changeMobile');
    }

    //change mobile update ...
    public function changeMobileUpdate(Request $request)
    {

        //To check mobile number..
        if(strlen($request->mobile) != 11){
            Toastr::error('Error !! Mobile number must be 11 digit.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        $checkNumber = User::where('mobile', $request->mobile)->first();
        if(isset($checkNumber) && $checkNumber != null){
            Toastr::error('Error !! This number is already exist.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
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
            $getMobile = Crypt::encrypt($mobile);
            $getUserData = Crypt::encrypt($userData);
            Toastr::success('Check number, Please verify otp.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.get-mobile-change-verify-OTP-page', ['user_mobile' => $getMobile, 'user_data' => $getUserData]);
        }else{
            Toastr::error('Error !! Something is wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

    }

    //To get user verify otp page...
    public function getMobileChangeVerifyOtpPage(Request $request, $getMobile, $getUserData)
    {
        $mobile = Crypt::decrypt($getMobile);
        $userData = Crypt::decrypt($getUserData);
        return view('frontend.profile.verifyOTP', compact('mobile','userData'));
    }

    public function mobileChangeVerifyOtp(Request $request){

        $singleUserData = User::where('verify_code', $request->verify_code)->first();
        if($singleUserData != null){
            
            if( $singleUserData->verify_expires_at < (Carbon::now())){
                dd('ace');
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
                
                Toastr::error('Error !! OTP verification time expired, Please resend OTP again.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            
            }else{
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->mobile = $request->mobile;
                $singleUserData->status = 1;
                $singleUserData->save();

                Toastr::success('Success !! You are now verified, Please login.', 'Success', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
           
        }

        Toastr::error('Error !! Sorry, OTP not matching.', 'Error', ["progressbar" => true]);
        return redirect()->route('webuser.get-login');

    }


    //To again resend otp...
    public function resendOtp($mobile, $userMobile)
    {
        $inputMobile = Crypt::decrypt($mobile);
        $userPreMobile = Crypt::decrypt($userMobile);

        $user = User::where('mobile',$userPreMobile)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*mobile send SMS*/
        $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMS($inputMobile,$text);

        if($user){
            $newMobile = Crypt::encrypt($inputMobile);
            $userData = Crypt::encrypt($user);
            Toastr::success('Resend OTP check to you mobile.', 'Success', ["progressbar" => true]);

            return redirect()->route('webuser.get-mobile-change-verify-OTP-page', ['user_mobile' => $newMobile, 'user_data' => $userData]);
        }else{
            return redirect()->back();
        }

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
        
    }


}
