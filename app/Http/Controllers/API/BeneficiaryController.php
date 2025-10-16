<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use App\Helpers\CurrentUser;
use Validator;

class BeneficiaryController extends Controller
{
    //To get beneficiary type...
    public function getBeneficiaryType()
    {
        $data = array('Current Account','Savings Account');

        $arrayData = [];
        foreach($data as $key => $item){
            if($item != null){
                $arrayData[] = array(
                    'id' => $key+1,
                    'name' => $item
                );
            }
        }

        return response()->json([
            'message'   =>  'Successfully loaded data.',
            'beneficiaryType'   =>  $arrayData,
            'status_code'   => 201
        ], 201);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the beneficiary data...
        $beneficiaryData = Beneficiary::orderBy('id','DESC')->where('user_id', $userId)->with(['bankData','mobileWalletData'])->get();

        if(!empty($beneficiaryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'beneficiaryData'   =>  $beneficiaryData,
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
            'account_type'=> 'required',
            'account_number'=> 'required',
            'account_holder_name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To check bank_id is null or not...
        if($request->bank_id != null){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'required',
                'branch_name'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Beneficiary::where('bank_id', $request->bank_id)
                                ->where('user_id', $userId)->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                return response()->json([
                    'message'   =>  'Sorry this account number is already exist.',
                    'status_code'   => 500
                ], 500);
            }

        }else{
            $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
        $checkAccountNumber = Beneficiary::where('mobile_wallet_id', $request->mobile_wallet_id)
                                ->where('user_id', $userId)->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                return response()->json([
                    'message'   =>  'Sorry this account number is already exist.',
                    'status_code'   => 500
                ], 500);
            }
        }

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;

        if($result = Beneficiary::create($data)){
            return response()->json([
                'message' => 'Beneficiary created successfully.',
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

        //To get single beneficiary data...
        $singleBeneficiaryData = Beneficiary::where('id', $id)->with(['bankData','mobileWalletData'])->first();

        if(!empty($singleBeneficiaryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleBeneficiaryData'   =>  $singleBeneficiaryData,
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
            'account_type'=> 'required',
            'account_number'=> 'required',
            'account_holder_name'=> 'required',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get single beneficiary data...
        $singleBeneficiaryData = Beneficiary::find($id);

        //To check bank_id is null or not...
        if($request->bank_id != null){
            $validator = Validator::make($request->all(), [
                'bank_id'=> 'required',
                'branch_name'=> 'nullable',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Beneficiary::where('bank_id', $request->bank_id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                if($checkAccountNumber->account_number != $singleBeneficiaryData->account_number){
                    return response()->json([
                        'message'   =>  'Sorry this account number is already exist.',
                        'status_code'   => 500
                    ], 500);
                }
            }

        }else{
            $validator = Validator::make($request->all(), [
                'mobile_wallet_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response(['errors'=>$validator->errors()->all()], 422);
            }

            //To check account number is unique or not...
            $checkAccountNumber = Beneficiary::where('mobile_wallet_id', $request->mobile_wallet_id)
                                    ->where('account_number', $request->account_number)->first();
            if(isset($checkAccountNumber) && $checkAccountNumber != null){
                if($checkAccountNumber->account_number != $singleBeneficiaryData->account_number){
                    return response()->json([
                        'message'   =>  'Sorry this account number is already exist.',
                        'status_code'   => 500
                    ], 500);
                }
            }
        }

        //To get all the form data...
        $data = $request->all();
        $data['user_id'] = $userId;
        
        if($singleBeneficiaryData->update($data)){
            return response()->json([
                'message' => 'Beneficiary updated successfully.',
                'data'   =>  $singleBeneficiaryData,
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

        //To get single beneficiary data...
        $singleBeneficiaryData = Beneficiary::find($id);

        if($singleBeneficiaryData->delete()){
            return response()->json([
                'message'   =>  'Beneficiary deleted successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
				'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
			], 500);
        }
    }


    //get bank Beneficiary data ...........
    public function getBankBeneficiaryData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the beneficiary data...
        $beneficiaryData = Beneficiary::orderBy('id','DESC')->where('bank_id','!=',null)
                            ->where('user_id', $userId)->with(['bankData','mobileWalletData'])->get();

        if(!empty($beneficiaryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'beneficiaryData'   =>  $beneficiaryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }


    //get mfs Beneficiary data ...........
    public function getMfsBaneficiaryData()
    {
        //To get current user...
        $userId = CurrentUser::getUserId();

        //To get all the beneficiary data...
        $beneficiaryData = Beneficiary::orderBy('id','DESC')->where('mobile_wallet_id','!=',null)
                            ->where('user_id', $userId)->with(['bankData','mobileWalletData'])->get();

        if(!empty($beneficiaryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'beneficiaryData'   =>  $beneficiaryData,
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
