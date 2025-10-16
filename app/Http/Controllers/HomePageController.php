<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
    	// return view('frontend.index');
        return redirect()->route('webuser.get-login');
    }

    //To get privacy policy page...
    public function privacyPolicy()
    {
        return view('frontend.privacyPolicy');
    }
    
}
