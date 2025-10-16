<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\MobileWallet;
use Carbon\Carbon;
use Image;

class MobileWalletController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:mobile-wallet-list|mobile-wallet-create|mobile-wallet-edit|mobile-wallet-delete', ['only' => ['index','show']]);
         $this->middleware('permission:mobile-wallet-create', ['only' => ['create','store']]);
         $this->middleware('permission:mobile-wallet-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:mobile-wallet-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();
        
        //To fetch all the mobile wallet data with user id...
        $mobileWalletData = MobileWallet::where('user_id', $userId)->get();

        return view('backend.mobileWallet.index',compact('mobileWalletData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mobileWallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'mobile_wallet_name'=> 'required',
            'parent_company'=> 'required',
            'image'=> 'required|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check mobileWallet image...
        if($request->image){
            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/mobileWalletImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/mobileWalletImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if(MobileWallet::create($data)){
            Toastr::success('MobileWallet created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('mobile-wallet.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $singleMobileWalletData = MobileWallet::find($id);
        return view('backend.mobileWallet.edit' , compact('singleMobileWalletData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_wallet_name'=> 'required',
            'parent_company'=> 'required',
            'image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single mobileWallet data...
        $singleMobileWalletData = MobileWallet::findOrFail($id);

        //To check bank image...
        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/mobileWalletImage/');
            if(file_exists($destinationPath.$singleMobileWalletData->image)){
                if($singleMobileWalletData->image != ''){
                    unlink($destinationPath.$singleMobileWalletData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/mobileWalletImage/thumbnail/');
                    unlink($destinationPath.$singleMobileWalletData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/mobileWalletImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/mobileWalletImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($singleMobileWalletData->update($data)){
            Toastr::success('MobileWallet updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('mobile-wallet.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleMobileWalletData = MobileWallet::findOrFail($id);

        //To remove previous file...
        $destinationPath = public_path('backend/uploads/mobileWalletImage/');
        if(file_exists($destinationPath.$singleMobileWalletData->image)){
            if($singleMobileWalletData->image != ''){
                unlink($destinationPath.$singleMobileWalletData->image);

                //For thumbnail...
                $destinationPath = public_path('backend/uploads/mobileWalletImage/thumbnail/');
                unlink($destinationPath.$singleMobileWalletData->image);
            }
        }

        if($singleMobileWalletData->delete()){
            Toastr::success('MobileWallet Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
