<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Helpers\CurrentUser;
use Brian2694\Toastr\Facades\Toastr;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentType=PaymentType::orderBy('id', 'DESC')->where('status','1')->get();
        return view('backend.paymentType.index', compact('paymentType'));
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
            'type_name'=> 'required|unique:payment_types',
        ]);


        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        if(PaymentType::create($data)){
            Toastr::success('PaymentType created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('payment-type.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentType $paymentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $request->validate([
            'type_name'=> 'required|unique:payment_types',
        ]);


        //To fet userId..
        $userId = CurrentUser::getUserId();

        $paymentType = PaymentType::find($paymentType->id);
        $paymentType->type_name = $request->input('type_name');
        $paymentType->user_id   = $userId;
        if($paymentType->update()){
            Toastr::success('PaymentType Updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('payment-type.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentType  $paymentType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if(PaymentType::destroy($id)){
            Toastr::success('PaymentType Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}
