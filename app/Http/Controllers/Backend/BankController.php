<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\Bank;
use Carbon\Carbon;
use Image;

class BankController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:bank-list|bank-create|bank-edit|bank-delete', ['only' => ['index','show']]);
         $this->middleware('permission:bank-create', ['only' => ['create','store']]);
         $this->middleware('permission:bank-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:bank-delete', ['only' => ['destroy']]);
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
        
        //To fetch all the bank data with user id...
        $bankData = Bank::where('user_id', $userId)->get();

        return view('backend.bank.index',compact('bankData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.bank.create');
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
            'bank_name'=> 'required',
            'bank_type'=> 'required',
            'image'=> 'required|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check bank image...
        if($request->image){
            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/bankImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/bankImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if(Bank::create($data)){
            Toastr::success('Bank created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('banks.index');
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
        $singleBankData = Bank::find($id);
        return view('backend.bank.edit' , compact('singleBankData'));
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
            'bank_name'=> 'required',
            'bank_type'=> 'required',
            'image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single bank data...
        $singleBankData = Bank::findOrFail($id);

        //To check bank image...
        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/bankImage/');
            if(file_exists($destinationPath.$singleBankData->image)){
                if($singleBankData->image != ''){
                    unlink($destinationPath.$singleBankData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/bankImage/thumbnail/');
                    unlink($destinationPath.$singleBankData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/bankImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/bankImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($singleBankData->update($data)){
            Toastr::success('Bank updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('banks.index');
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
        $singleBankData = Bank::findOrFail($id);

        //To remove previous file...
        $destinationPath = public_path('backend/uploads/bankImage/');
        if(file_exists($destinationPath.$singleBankData->image)){
            if($singleBankData->image != ''){
                unlink($destinationPath.$singleBankData->image);

                //For thumbnail...
                $destinationPath = public_path('backend/uploads/bankImage/thumbnail/');
                unlink($destinationPath.$singleBankData->image);
            }
        }

        if($singleBankData->delete()){
            Toastr::success('Bank Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
