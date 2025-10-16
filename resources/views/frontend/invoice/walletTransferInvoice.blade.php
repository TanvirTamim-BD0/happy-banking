@extends('frontend.master')
@section('title') Invoice @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

 
    <div class="pay_card_wrap">
        <a href="#">
            <div class="pay_card mb-3 card">

            	<h5 class="mb-4 mt-1">Your Transaction Is <span class="text-success">Successfull <i class="fa-sharp fa-regular fa-circle-check"></i></span></h5>

                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-3">

                    <div class="row w-100">
                    	<div class="col-md-1">
                    		<i class="fa-solid fa-user"></i>
                    	</div>

                    	<div class="col-md-10">
                    		<h4 class="text-dark font-16 text-capitalize"><b class="custom-invoice-bold">{{Auth::user()->name}}</b></h4>
                        	<p class="text-dark font-13 mb-0">Transaction Id : {{$result->transaction_id}}</p>
							<p class="text-dark font-13">Type : {{$result->payment_type}} Transfer</p>
                    	</div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2 pb-3">
                   <table class="table table-row-bordered">

                   		<tr class="border">
                   			<td class="border-end">
                                <b class="custom-invoice-bold">From</b><br>
                                {{$fromAccount->mobile}}
								<span class="custom-invoice-root-bank">Wallet</span>
                            </td>

                   			<td class="border-end">
                   				<b class="custom-invoice-bold">To</b> <br>
								@if($result->payment_type == 'Wallet To Card')
									{{$result->toCreditCardData->card_number}}
									<span class="custom-invoice-root-bank">{{$result->toCreditCardData->bankData->bank_name}}</span>
								@else
									{{$toAccount->account_number}}
									@if($toAccount->bank_id != null)
									<span class="custom-invoice-root-bank">{{$toAccount->bankData->bank_name}}</span>
									@else
									<span class="custom-invoice-root-bank">{{$toAccount->mobileWalletData->mobile_wallet_name}}</span>
									@endif
								@endif
                   			</td>
                   		</tr>
                   		<tr class="border">
							<td class="border-end">
								<b class="custom-invoice-bold">Transfer Balance</b><br>
								{{$result->pay_amount}} BDT
							</td>

                   			<td class="border-end">
								<b class="custom-invoice-bold">Amount: </b>
								{{$result->pay_amount}} BDT <br>
								<b class="custom-invoice-bold">Fee: </b>
								@if($result->pay_fee_amount != null)
								{{$result->pay_fee_amount}} BDT <br>
								@else
								0.00 BDT <br>
								@endif
                   			</td>
                   		</tr>

                   		<tr class="border">
							<td class="border-end custom-notes-of-invoice">
								<b class="custom-invoice-bold">Notes</b><br>
								{{$result->notes}}
							</td>

                            <td class="border-end">
								<b class="custom-invoice-bold">Date: </b>
								{{Carbon\Carbon::createFromFormat('Y-m-d', $result->created_at->toDateString())->format('d-m-Y')}} <br>
								<b class="custom-invoice-bold">Time: </b>
								{{Carbon\Carbon::create($result->created_at->toTimeString())->format('h:i')}} <br>
							</td>
                   		</tr>

                   </table>
                </div>


                <div class="d-flex align-items-center justify-content-between gap-2 pb-2">
                    <a class="text-white w-10 primary_btn m-0" href="{{route('webuser.dashboard')}}">Back To Home <i class="fa-solid fa-arrow-right"></i> </a>
                </div>
                
            </div>
        </a>
    </div>
</div>

@endsection