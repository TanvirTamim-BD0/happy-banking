<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\ActiveSession;

class ActiveSessionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:active-session-list|active-session-create|active-session-edit|active-session-delete', ['only' => ['index','show']]);
         $this->middleware('permission:active-session-create', ['only' => ['create','store']]);
         $this->middleware('permission:active-session-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:active-session-delete', ['only' => ['destroy']]);
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
        
        //To fetch all the active session with user id...
        $activeSessionData = ActiveSession::where('user_id', $userId)->get();

        return view('backend.activeSession.index',compact('activeSessionData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.activeSession.create');
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
            'session_name'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(ActiveSession::create($data)){
            Toastr::success('ActiveSession created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('active-session.index');
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
        $singleActiveSessionData = ActiveSession::find($id);
        return view('backend.activeSession.edit' , compact('singleActiveSessionData'));
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
            'session_name'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single active session data...
        $singleActiveSessionData = ActiveSession::findOrFail($id);

        if($singleActiveSessionData->update($data)){
            Toastr::success('ActiveSession updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('active-session.index');
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
        $singleActiveSessionData = ActiveSession::findOrFail($id);

        if($singleActiveSessionData->delete()){
            Toastr::success('ActiveSession Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
   
    //To activate the activeSession...
    public function activeSession($id)
    {
        $singleActiveSessionData = ActiveSession::findOrFail($id);
        $singleActiveSessionData->status = true;

        if($singleActiveSessionData->save()){
            //To inactive all the session...
            ActiveSession::whereNotIn('id', [$singleActiveSessionData->id])->update(['status'=>false]);

            Toastr::success('ActiveSession Activated Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
   
    //To activate the inActiveSession...
    public function inActiveSession($id)
    {
        $singleActiveSessionData = ActiveSession::findOrFail($id);
        $singleActiveSessionData->status = false;

        if($singleActiveSessionData->save()){
            Toastr::success('ActiveSession Inactivated Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
