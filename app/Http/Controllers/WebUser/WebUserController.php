<?php

namespace App\Http\Controllers\WebUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserActivity;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\DocumentationCategory;
use App\Models\Documentation;
use App\Models\PushNotification;
use App\Models\CreditCardReminder;
use Illuminate\Support\Facades\Crypt;

class WebUserController extends Controller
{
    //To get web user registration page...
    public function webUserRegisterPage()
    {
        return view('frontend.auth.register');
    }
    
    //To register web user...
    public function webUserRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable',
            'mobile' => 'required',
            'password' => 'required',
            'gender' => 'nullable',
            'profession_id' => 'required',
            'wallet' => 'nullable',
        ]);

        //To check mobile number..
        if(strlen($request->mobile) != 11){
            Toastr::error('Error !! Mobile number must be 11 digit.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        $data = $request->all();
        $data['verify_code'] = rand(100000, 999999);
        $data['verify_expires_at'] = Carbon::now()->addMinutes(10);

        if(!User::where('mobile', $request->mobile)->first()){
            //To check email is unique or not...
            if(!User::where('email', $request->email)->first()){
                //
            }else{
                Toastr::error('Error !! This email had already taken.', 'Error', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
            
            if($request->password == $request->password_confirmation){
                $data['password'] = Hash::make($request->password);
                if($newUser = User::create($data)){
                    $userRole = Role::where('name', 'user')->pluck('id');
                    $newUser->assignRole($userRole);

                    $userData = User::where('mobile', $request->mobile)->first();
                    $accessToken = $userData->createToken('BankSoft2023')->accessToken;

                    /*mobile send SMS*/
                    $contact = $request->mobile;
                    $text = 'Congratulations! Your Login OTP code is: '. $userData->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
                    $checkStatus = $this->sendSMS($contact,$text);
                    if($checkStatus == true){
                        $userMobile = Crypt::encrypt($userData->mobile);
                        Toastr::success('Success !! Registration completed, Please verify your account.', 'Success', ["progressbar" => true]);
                        return redirect()->route('webuser.get-verify-OTP-page', ['user_mobile' => $userMobile]);
                    }
                }else{
                    Toastr::error('Error !! Something is wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('webuser.get-login');
                }
            }
            else{
                Toastr::error('Error !! Password and confirm password not matching.', 'Error', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
        }else{
            Toastr::error('Error !! This number had already taken.', 'Error', ["progressbar" => true]);
            return redirect()->route('webuser.get-login');
        }
    }

    //To get user verify otp page...
    public function getVerifyOtpPage(Request $request, $userMobile)
    {
        $userMobile = Crypt::decrypt($userMobile);
        return view('frontend.auth.verifyOTP', compact('userMobile'));
    }

    //To verify OTP....
    public function webUserVerifyOtp(Request $request)
    {
        $singleUserData = User::where('verify_code', $request->verify_code)->first();

        if($singleUserData != null){
            if( $singleUserData->verify_expires_at < (Carbon::now())){
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
                
                Toastr::error('Error !! OTP verification time expired, Please resend OTP again.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            
            }else{
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->status = 1;
                $singleUserData->save();

                Toastr::success('Success !! You are now verified, Please login now.', 'Success', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
           
        }

        Toastr::error('Error !! Sorry, OTP not matching.', 'Error', ["progressbar" => true]);
        return redirect()->route('webuser.get-login');
    }
    
    //To get web user login page...
    public function webUserPasswordChange()
    {
        return view('frontend.auth.changePassword');
    }

    //To change password update ...
    public function webUserPasswordUpdate(Request $request)
    {   
        $request->validate([
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $singleUserData = User::where('mobile', $request->mobile)->first();

        if ($request->new_password == $request->confirm_password) {
            User::find($singleUserData->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Toastr::success('Password Updated, Please Login.', '', ["progressbar" => true]);
            return redirect()->route('webuser.get-login');

        }else{
            Toastr::error('Password and Confirm Password do not match.!', '', ["progressbar" => true]);
            return view('frontend.auth.changePassword', compact('singleUserData'));
        }

    }
    
    //To get web user login page...
    public function webUserLoginPage()
    {
        return view('frontend.auth.login');
    }

    //To check web user login form...
    public function webUserogin(Request $request)
    {
        $request->validate([
            'mobile' => 'nullable',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        //To check email or mobile...
        if($request->mobile != null){
            //To check user is avaiable or not with loginId...
            $singleUser = User::where('mobile', $request->mobile)->first();

            //to check user is available or not...
            if(isset($singleUser) && $singleUser != null){
                //To login with login_id...
                if(Auth::guard('webuser')->attempt(['mobile' => $request->mobile, 'password' => $request->password], $request->remember)) {
                    return redirect()->route('webuser.dashboard');
                }else{
                    Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('webuser.get-login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', 'Error', ["progressbar" => true]);
                return redirect()->route('webuser.get-login');
            }
        }else{
            Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get forgot password page...
    public function forgotPassword()
    {
        return view('frontend.auth.forgotPassword');
    }
    
    //To get forgot password page...
    public function forgotPasswordOTPSent(Request $request)
    {
        $request->validate([
            'mobile' => 'nullable',
        ]);

        //To check mobile number..
        if(strlen($request->mobile) != 11){
            Toastr::error('Error !! Mobile number must be 11 digit.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

        //To get single user data...
        $user = User::where('mobile',$request->mobile)->first();

        //To check user is null or not...
        if(isset($user) && $user != null){
            $user->verify_code = rand(100000, 999999);
            $user->verify_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            /*mobile send SMS*/
            $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
            $this->sendSMS($request->mobile,$text);

            $userMobile = Crypt::encrypt($request->mobile);
            Toastr::success('Check number, Please verify otp.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.get-forgot-pass-verify-OTP-page', ['user_mobile' => $userMobile]);
        }else{
            // dd('asd');
            Toastr::error('Sorry, You have entered wrong number.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get user verify otp page...
    public function getForgotVerifyOtpPage(Request $request, $userMobile)
    {
        $userMobile = Crypt::decrypt($userMobile);
        return view('frontend.auth.verifyOTPForForgotPass', compact('userMobile'));
    }

    //To verify OTP for fotgot pass....
    public function forgotPassVerifyOtp(Request $request)
    {
        $singleUserData = User::where('verify_code', $request->verify_code)->first();

        if($singleUserData != null){
            if( $singleUserData->verify_expires_at < (Carbon::now())){
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->save();
                
                Toastr::error('Error !! OTP verification time expired, Please resend OTP again.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            
            }else{
                $singleUserData->verify_code = null;
                $singleUserData->verify_expires_at = null;
                $singleUserData->status = 1;
                $singleUserData->save();

                Toastr::success('Success !! You are now verified, Update password now.', 'Success', ["progressbar" => true]);
                return view('frontend.auth.changePassword', compact('singleUserData'));
            }
        }

        Toastr::error('Error !! Sorry, OTP not matching.', 'Error', ["progressbar" => true]);
        return redirect()->route('webuser.get-login');
    }

    //To get web user home dashboard page...
    public function userDashboard()
    {
        return view('frontend.dashboard');
    }

    //To get web user logout...
    public function webUserLogout()
    {
        Auth::guard('webuser')->logout();
        return redirect(route('webuser.get-login'));
    }


    //To again resend otp for password change...
    public function resendOtpForPassChange($mobile)
    {
        $user = User::where('mobile',$mobile)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*mobile send SMS*/
        $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMS($mobile,$text);
        //To check user is null or not...
        if(isset($user) && $user != null){
            $userMobile = Crypt::encrypt($mobile);
            Toastr::success('Check number, Please verify otp.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.get-forgot-pass-verify-OTP-page', ['user_mobile' => $userMobile]);
        }else{
            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
    
    //To again resend otp...
    public function resendOtp($mobile)
    {
        $user = User::where('mobile',$mobile)->first();
        $user->verify_code = rand(100000, 999999);
        $user->verify_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        /*mobile send SMS*/
        $text = 'Congratulations! Your Login OTP code is: '. $user->verify_code.' Send By WB SOFTWARES.Please do NOT share your OTP or PIN with others.';
        $this->sendSMS($mobile,$text);
        //To check user is null or not...
        if(isset($user) && $user != null){
            $userMobile = Crypt::encrypt($mobile);
            Toastr::success('Check number, Please verify otp.', 'Success', ["progressbar" => true]);
            return redirect()->route('webuser.get-verify-OTP-page', ['user_mobile' => $userMobile]);
        }else{
            Toastr::error('Sorry, Something is wrong.!', 'Error', ["progressbar" => true]);
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

         return true;
    }


    //To get blog page .....
    public function blog()
    {   
        $blogData = Blog::orderBy('id','DESC')->where('type',  'frontend')->get();
        return view('frontend.blog.index',compact('blogData'));
    }

    public function blogCategoryWise($id)
    {
        $blogData = Blog::where('blog_category_id',$id)->where('type',  'frontend')->get();
        return view('frontend.blog.index',compact('blogData'));
    }

    public function blogDetails($id)
    {
        $blog = Blog::where('id',$id)->first();
        return view('frontend.blog.blogDetails',compact('blog'));
    }

    //To get contact page...
    public function contact()
    {
        return view('frontend.contact');
    }

    //To change push notify unseen status...
    public function changePushNotificationUnseen(Request $request)
    {
        //To update...
        PushNotification::orderBy('id','desc')->where('is_seen', false)->limit(5)->update(['is_seen' => true]);
        return view('frontend.layout.updateHeaderNotify');
    }
    
    //To change reminder unseen status...
    public function changeReminderUnseen(Request $request)
    {
        //To update...
        CreditCardReminder::orderBy('id','desc')->where('user_id', Auth::user()->id)
                            ->where('status', true)->where('is_seen', false)->limit(5)->update(['is_seen' => true]);
        return view('frontend.layout.unpaidReminderCountNumber');
    }

    //To get about page...
    public function about()
    {
        return view('frontend.about');
    }

    //To get blog/docs category page...
    public function documentationCategory()
    {
        $docsCategoryData = DocumentationCategory::orderBy('id', 'desc')->get(); 
        return view('frontend.documentationCategory', compact('docsCategoryData'));
    }
   
    //To get documentation with category wise...
    public function categoryWiseDocumentation($id)
    {
        $documentationData = Documentation::where('documentation_category_id', $id)->get(); 
        return view('frontend.documentation', compact('documentationData'));
    }

    //To update user total hit of user activity...
    public function userTotalHitIncrease()
    {
        //To get today date...
        $todayDate = Carbon::now()->toDateString();
        $singleUserActivity = UserActivity::where('user_id', Auth::user()->id)->where('date', $todayDate)->first();

        if(isset($singleUserActivity) && $singleUserActivity != null){
            $singleUserActivity->total_hit += 1;
            $singleUserActivity->save();
        }
    }


    //To Notification-Unseen all
    public function notificationUnseenAll(){
        $notifications = PushNotification::where('status', true)->get();
        return view('frontend.notification.notifications', compact('notifications'));
    }

    //To get DBR calculator 
    public function dbrCalculator(){
        return view('frontend.dbrEmiCalculator.dbr');
    }

    //To get EMI calculator 
    public function emiCalculator(){
        return view('frontend.dbrEmiCalculator.emi');
    }
    
    //To get Loan calculator 
    public function loanCalculator(){
        return view('frontend.dbrEmiCalculator.loanCalculator');
    }


}
