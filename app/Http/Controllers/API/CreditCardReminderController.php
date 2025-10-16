<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditCardReminder;
use App\Models\ActiveSession;
use App\Models\User;
use App\Models\CreditCard;
use App\Helpers\CurrentUser;
use Carbon\Carbon;
use Validator;

class CreditCardReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $creditCardReminderData = CreditCardReminder::orderBy('id','DESC')->where('user_id', $userId)->get();
        $getArrayData = [];
        foreach($creditCardReminderData as $key=>$item){
            $singleCardReminderData = CreditCardReminder::where('id', $item->id)
                                    ->with(['activeSessionData','creditCardData'])->first();
            $getArrayData[] = array(
                'singleReminderData' => $singleCardReminderData,
                'bankData' => $singleCardReminderData->creditCardData->bankData
            );
        }

        if(!empty($getArrayData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditCardReminderData'   =>  $getArrayData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credit_card_id'=> 'required',
            'active_session_id'=> 'required',
            'last_payment_date'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        //To get selected account & single card reminder data...
        $selectedAccountData = CreditCard::where('id',$request->credit_card_id)->first();
 
        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            $validator = Validator::make($request->all(), [
                'total_bdt_due'=> 'required',
                'total_usd_due'=> 'required',
                'bdt_minimum_due'=> 'required',
                'usd_minimum_due'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            if($request->total_bdt_due > $request->bdt_minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total bdt due always will be greater than minimum bdt due.!',
                    'status_code'   => 500
                ], 500);
            }
            
            if($request->total_usd_due > $request->usd_minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total usd due always will be greater than minimum usd due.!',
                    'status_code'   => 500
                ], 500);
            }
            
        }else{
            $validator = Validator::make($request->all(), [
                'total_due'=> 'required',
                'minimum_due'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            if($request->total_due > $request->minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total due always will be greater than minimum due.!',
                    'status_code'   => 500
                ], 500);
            }
        }
      
        if($result = CreditCardReminder::create($data)){
            return response()->json([
                'message' => 'CreditCardReminder created successfully.',
                'data'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
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
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single credit card reminder data...
        $singleCreditCardReminderData = CreditCardReminder::where('id',$id)->with(['activeSessionData','creditCardData'])->first();

        if(!empty($singleCreditCardReminderData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleCreditCardReminderData'   =>  $singleCreditCardReminderData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
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
        $validator = Validator::make($request->all(), [
            'active_session_id'=> 'required',
            'last_payment_date'=> 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        $lastPaymentDate = Carbon::createFromFormat('d-m-Y', $request->last_payment_date)->format('Y-m-d');
        $data['last_payment_date'] = $lastPaymentDate;

        //To get selected account & single card reminder data...
        $singleCardReminderData = CreditCardReminder::where('id', $id)->first();
        $selectedAccountData = CreditCard::where('id',$singleCardReminderData->credit_card_id)->first();
        
        //To check currency...
        if($selectedAccountData->is_dual_currency == true){
            $validator = Validator::make($request->all(), [
                'total_bdt_due'=> 'required',
                'total_usd_due'=> 'required',
                'bdt_minimum_due'=> 'required',
                'usd_minimum_due'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            if($request->total_bdt_due > $request->bdt_minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total bdt due always will be greater than minimum bdt due.!',
                    'status_code'   => 500
                ], 500);
            }
            
            if($request->total_usd_due > $request->usd_minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total usd due always will be greater than minimum usd due.!',
                    'status_code'   => 500
                ], 500);
            }
            
        }else{
            $validator = Validator::make($request->all(), [
                'total_due'=> 'required',
                'minimum_due'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            if($request->total_due > $request->minimum_due){
                //
            }else{
                return response()->json([
                    'message'   =>  'Total due always will be greater than minimum due.!',
                    'status_code'   => 500
                ], 500);
            }
        }

        if($singleCardReminderData->update($data)){
            return response()->json([
                'message' => 'CreditCardReminder updated successfully.',
                'data'   =>  $singleCardReminderData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.',
                'status_code'   => 500
            ], 500);
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
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single credit card reminder data...
        $singleCreditCardReminderData = CreditCardReminder::find($id);

        if($singleCreditCardReminderData->delete()){
            return response()->json([
                'message'   =>  'CreditCardReminder deleted successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get active reminder session data...
    public function getActiveReminderSession()
    {
        //To fetch all the active session with user id...
        $activeSessionData = ActiveSession::where('status', true)->first();

        if(!empty($activeSessionData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'activeSessionData'   =>  $activeSessionData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get single credit card reminder data...
    public function getUnPaidCreditCardReminder()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();
        $creditCardReminderData =  CreditCardReminder::where('user_id', $userId)
                        ->where('status', false)->get();

        $getArrayData = [];
        foreach($creditCardReminderData as $key=>$item){
            $singleCardReminderData = CreditCardReminder::where('id', $item->id)
                                    ->with(['activeSessionData','creditCardData'])->first();
            $getArrayData[] = array(
                'singleReminderData' => $singleCardReminderData,
                'bankData' => $singleCardReminderData->creditCardData->bankData
            );
        }

        if(!empty($getArrayData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditCardReminderData'   =>  $getArrayData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To change card reminder status...
    public function changeStatusCreditCardBillReminder(Request $request)
    {
        //To get single credit card reminder & active session data...
        $singleCardReminderData = CreditCardReminder::where('id', $request->credit_card_reminder_id)->first();

        if($singleCardReminderData->status == 1){
            $singleCardReminderData->status = 0;
        }else{
            $singleCardReminderData->status = 1;
        }

        if($singleCardReminderData->save()){
            return response()->json([
                'message'   =>  'CreditCardReminder status changed successfully',
                'singleCardReminderData'   =>  $singleCardReminderData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }

    //To get bill reminder data with card id...
    public function getBillReminderDataWithCardId($creditCardId)
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the credit card data...
        $creditCardReminderData = CreditCardReminder::orderBy('id','DESC')->where('user_id', $userId)
                                    ->where('credit_card_id', $creditCardId)->get();
        $getArrayData = [];
        foreach($creditCardReminderData as $key=>$item){
            $singleCardReminderData = CreditCardReminder::where('id', $item->id)
                                    ->with(['activeSessionData','creditCardData'])->first();
            $getArrayData[] = array(
                'singleReminderData' => $singleCardReminderData,
                'bankData' => $singleCardReminderData->creditCardData->bankData
            );
        }

        if(!empty($getArrayData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'creditCardReminderData'   =>  $getArrayData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }
}
