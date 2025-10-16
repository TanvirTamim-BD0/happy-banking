<?php
namespace App\Helpers;
use Auth;

class CurrentUser{

    public static function getUserId()
    {
        if(Auth::user()->role == 'superadmin'){
            $userId = Auth::user()->id;
        }elseif(Auth::user()->role == 'admin'){
            $userId = Auth::user()->id;
        }elseif(Auth::user()->role == 'manager'){
            $userId = Auth::user()->admin_id;
        }elseif(Auth::user()->role == 'user'){
            $userId = Auth::user()->id;
        }

        return $userId;
    }
}