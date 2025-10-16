<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\User;
use Image;
use Auth;
use Hash;

class ProfileController extends Controller
{
    //To get user profile page...
    public function userProfile()
    {
    	return view('backend.userProfile.index');
    }

    //To update user profile...
    public function updateUserProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required',
            'mobile' => 'nullable',
        ]);

        $data = $request->all();

        //To get user data..
        $singleUser = User::where('id', Auth::user()->id)->first();

        if($request->hasFile('image')) {

            //To remove previous file...
            $destinationPath = public_path('backend/uploads/userProfile/');
            if(file_exists($destinationPath.$singleUser->image)){
                if($singleUser->image != ''){
                    unlink($destinationPath.$singleUser->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/userProfile/thumbnail/');
                    unlink($destinationPath.$singleUser->image);
                }
            }
            
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/userProfile/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/userProfile/thumbnail/');
            Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
            
            $data['image'] = $imageName;

        }
        
        if($singleUser->update($data)){
            Toastr::success('User basic information updated successfully.', 'Success', ["progressbar" => true]);
            return redirect(route('user-profile'));
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    //To get user profile security page...
    public function userSecurity()
    {
    	return view('backend.userProfile.security');
    }

    //To update user login password...
    public function userSecurityUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        $data = $request->all();
        //To get user data..
        $singleUser = User::where('id', Auth::user()->id)->first();

        //To checkold password with current password...
        if (Hash::check($request->old_password, $singleUser->password)) {
            //To check new & confirm password...
            if ($request->new_password == $request->confirm_password) {

                $data['password'] = Hash::make($request->new_password);
                if($singleUser->update($data)){
                    Toastr::success('User login password updated successfully.', 'Success', ["progressbar" => true]);
                    return redirect()->route('user-security');
                }else{
                    Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                    return redirect()->back();
                }
            }else{
                Toastr::error('Password and confirm password do not match.!', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
            
        }else{
            Toastr::error('Old password do not match.!', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
