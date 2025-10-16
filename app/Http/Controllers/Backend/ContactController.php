<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\Contact;

class ContactController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:contact-list|contact-create|contact-edit|contact-delete', ['only' => ['index','show']]);
         $this->middleware('permission:contact-create', ['only' => ['create','store']]);
         $this->middleware('permission:contact-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:contact-delete', ['only' => ['destroy']]);
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

        //To fetch all the contact data...
        $contactData = Contact::where('user_id', $userId)->get();

        return view('backend.contact.index',compact('contactData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'=> 'required',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'phone' => 'required|min:11|max:11|unique:users,phone,'.Auth::user()->id,
            'subject'=> 'required',
            'description'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(Contact::create($data)){
            Toastr::success('Contact created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('contact.index');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleContactData = Contact::findOrFail($id);

        if($singleContactData->delete()){
            Toastr::success('Contact Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
