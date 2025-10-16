<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\Logo;
use Image;

class SettingController extends Controller
{
    //To get logo change page...
    public function logo()
    {	
    	//To fet userId & logo data..
        $userId = CurrentUser::getUserId();
        $logoData = Logo::where('user_id', $userId)->first();
        
    	return view('backend.setting.index',compact('logoData'));
    }

    //To update logo...
    public function logoUpdate(Request $request)
    {
    	$request->validate([
            'logo_image'=> 'nullable|image|mimes:jpg,jpeg,png,gif,svg',
        ]);

        $data = $request->all();

        //To fet userId & logo data..
        $userId = CurrentUser::getUserId();
        $logoData = Logo::where('user_id', $userId)->first();

        if(isset($logoData) && $logoData != null){
            if($request->hasFile('logo_image')) {

                //To remove previous file...
                $destinationPath = public_path('backend/uploads/logo/');
                if(file_exists($destinationPath.$logoData->logo_image)){
                    if($logoData->logo_image != ''){
                        unlink($destinationPath.$logoData->logo_image);

                        //For thumbnail...
                        $destinationPath = public_path('backend/uploads/logo/thumbnail/');
                        unlink($destinationPath.$logoData->logo_image);
                    }
                }
                
                $file = $request->file('logo_image');
                $imageName = time().'.'.$file->getClientOriginalExtension();

                //For large size image...
                $destinationPath = public_path('backend/uploads/logo/');
                Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
                
                //For thumbnail size image...
                $destinationPath = public_path('backend/uploads/logo/thumbnail/');
                Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
                
                $data['logo_image'] = $imageName;

            }
            
            if($logoData->update($data)){
                Toastr::success('Logo updated successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('logo'));
            }else{
                Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }else{

            if($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                $imageName = time().'.'.$file->getClientOriginalExtension();

                //For large size image...
                $destinationPath = public_path('backend/uploads/logo/');
                Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
                
                //For thumbnail size image...
                $destinationPath = public_path('backend/uploads/logo/thumbnail/');
                Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
                
                $data['logo_image'] = $imageName;

            }
        

            $data['user_id'] = $userId;
            if($result = Logo::create($data)){
                Toastr::success('Logo created successfully.', 'Success', ["progressbar" => true]);
                return redirect(route('logo'));
            }else{
                Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
                return redirect()->back();
            }
        }
    }
}
