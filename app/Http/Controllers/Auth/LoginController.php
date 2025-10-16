<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    //To get login page...
    public function getLoginPageForAdmin()
    {
        return redirect()->route('admin.login');
    }
    
    //To get login page...
    public function getLoginPage()
    {
        return view('auth.login');
    }
    


    //User login with loginId wise...
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable',
            'mobile' => 'nullable',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        //To check email or mobile...
        if($request->email != null){
            //To check user is avaiable or not with loginId...
            $singleUser = User::where('email', $request->email)->first();

            //to check user is available or not...
            if(isset($singleUser) && $singleUser != null){
                //To login with login_id...
                if($this->guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                    return redirect()->route('home');
                }else{
                    Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', 'Error', ["progressbar" => true]);
                return redirect()->route('login');
            }
        }else if($request->mobile != null){
            //To check user is avaiable or not with loginId...
            $singleUser = User::where('mobile', $request->mobile)->first();

            //to check user is available or not...
            if(isset($singleUser) && $singleUser != null){
                //To login with login_id...
                if($this->guard()->attempt(['mobile' => $request->mobile, 'password' => $request->password], $request->remember)) {
                    return redirect()->route('user-dashboard');
                }else{
                    Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
                    return redirect()->route('login');
                }

            }else{
                Toastr::error('Error !! User Not Validate.', 'Error', ["progressbar" => true]);
                return redirect()->route('login');
            }
        }else{
            Toastr::error('Error !! Someting Is Wrong.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
