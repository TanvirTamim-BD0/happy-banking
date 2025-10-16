<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\BalanceTransfer;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    //To get admin login page...
    public function adminLogin()
    {
        return view('backend.auth.login');
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();
        
        return view('backend.dashboard');
    }


    public function logout()
    {
         Auth::guard('web')->logout();
         return Redirect()->route('login');
    }
    
}
