<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\TransactionCategory;
use Carbon\Carbon;
use Image;

class TransactionCategoryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:transaction-category-list|transaction-category-create|transaction-category-edit|transaction-category-delete', ['only' => ['index','show']]);
         $this->middleware('permission:transaction-category-create', ['only' => ['create','store']]);
         $this->middleware('permission:transaction-category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:transaction-category-delete', ['only' => ['destroy']]);
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
        
        //To fetch all the transactionCategory data with user id...
        $transactionCategoryData = TransactionCategory::where('user_id', $userId)->get();

        return view('backend.transactionCategory.index',compact('transactionCategoryData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.transactionCategory.create');
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
            'category_type'=> 'required',
            'category_name'=> 'required',
            'image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
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
            $destinationPath = public_path('backend/uploads/transactionCategory/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/transactionCategory/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if(TransactionCategory::create($data)){
            Toastr::success('TransactionCategory created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('transaction-category.index');
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
        $singleTransactionCategoryData = TransactionCategory::find($id);
        return view('backend.transactionCategory.edit' , compact('singleTransactionCategoryData'));
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
            'category_type'=> 'required',
            'category_name'=> 'required',
            'image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single transactionCategory data...
        $singleTransactionCategoryData = TransactionCategory::findOrFail($id);

        //To check transactionCategory image...
        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/transactionCategory/');
            if(file_exists($destinationPath.$singleTransactionCategoryData->image)){
                if($singleTransactionCategoryData->image != ''){
                    unlink($destinationPath.$singleTransactionCategoryData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/transactionCategory/thumbnail/');
                    unlink($destinationPath.$singleTransactionCategoryData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/transactionCategory/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/transactionCategory/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($singleTransactionCategoryData->update($data)){
            Toastr::success('TransactionCategory updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('transaction-category.index');
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
        $singleTransactionCategoryData = TransactionCategory::findOrFail($id);

        //To remove previous file...
        $destinationPath = public_path('backend/uploads/transactionCategory/');
        if(file_exists($destinationPath.$singleTransactionCategoryData->image)){
            if($singleTransactionCategoryData->image != ''){
                unlink($destinationPath.$singleTransactionCategoryData->image);

                //For thumbnail...
                $destinationPath = public_path('backend/uploads/transactionCategory/thumbnail/');
                unlink($destinationPath.$singleTransactionCategoryData->image);
            }
        }

        if($singleTransactionCategoryData->delete()){
            Toastr::success('TransactionCategory Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
