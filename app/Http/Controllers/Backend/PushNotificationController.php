<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\PushNotification;
use Carbon\Carbon;

class PushNotificationController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:push-notification-list|push-notification-create|push-notification-edit|push-notification-delete', ['only' => ['index','show']]);
         $this->middleware('permission:push-notification-create', ['only' => ['create','store']]);
         $this->middleware('permission:push-notification-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:push-notification-delete', ['only' => ['destroy']]);
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

        //To get all the unit data...
        $pushNotificationData = PushNotification::orderBy('id','DESC')->where('user_id', $userId)
                        ->where('sending_date', '!=', null)->get();

        return view('backend.pushNotification.index',compact('pushNotificationData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $todayDate = Carbon::now()->today()->toDateString();
        return view('backend.pushNotification.create', compact('todayDate'));
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
            'notification_title'=> 'required',
            'notification_message'=> 'required',
            'sending_date'=> 'required',
            'sending_time'=> 'required'
        ]);
        
        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;
        $sendingDate = Carbon::createFromFormat('d-m-Y', $request->sending_date)->format('Y-m-d');
        $data['sending_date'] = $sendingDate;

        if(PushNotification::create($data)){
            Toastr::success('PushNotification created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('push-notification.index');
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
        $singlePushNotification = PushNotification::find($id);
        $sendingDate = Carbon::createFromFormat('Y-m-d', $singlePushNotification->sending_date)->format('d-m-Y');

        return view('backend.pushNotification.edit' , compact('singlePushNotification','sendingDate'));
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
            'notification_title'=> 'required',
            'notification_message'=> 'required',
            'sending_date'=> 'required',
            'sending_time'=> 'required'
        ]);

        $data = $request->all();
        //To fetch single push-notification data...
        $singlePushNotification = PushNotification::where('id', $id)->first();
        $sendingDate = Carbon::createFromFormat('d-m-Y', $request->sending_date)->format('Y-m-d');
        $data['sending_date'] = $sendingDate;

        if($singlePushNotification->update($data)){
            Toastr::success('PushNotification updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('push-notification.index');
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
        $singlePushNotification = PushNotification::where('id', $id)->first();
        if($singlePushNotification->delete()){
            Toastr::success('PushNotification Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
