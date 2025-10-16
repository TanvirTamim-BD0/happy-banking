<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\UserProfession;

class UserProfessionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:profession-list|profession-create|profession-edit|profession-delete', ['only' => ['index','show']]);
         $this->middleware('permission:profession-create', ['only' => ['create','store']]);
         $this->middleware('permission:profession-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:profession-delete', ['only' => ['destroy']]);
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
        
        //To fetch all the user profession data with user id...
        $userProfessionData = UserProfession::where('user_id', $userId)->get();

        return view('backend.userProfession.index',compact('userProfessionData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.userProfession.create');
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
            'profession_name'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(UserProfession::create($data)){
            Toastr::success('UserProfession created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('profession.index');
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
        $singleUserProfessionData = UserProfession::find($id);
        return view('backend.userProfession.edit' , compact('singleUserProfessionData'));
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
            'profession_name'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single user profession data...
        $singleUserProfessionData = UserProfession::findOrFail($id);

        if($singleUserProfessionData->update($data)){
            Toastr::success('UserProfession updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('profession.index');
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
        $singleUserProfessionData = UserProfession::findOrFail($id);

        if($singleUserProfessionData->delete()){
            Toastr::success('UserProfession Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
