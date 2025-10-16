@extends('frontend.master')
@section('title') Transaction Revert @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

 
    <div class="pay_card_wrap">
        <a href="#">
            <div class="pay_card mb-3 card">

            
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-3">

                    <div class="row w-100">
                    	<div class="col-md-1">
                    		<i class="fa-solid fa-user"></i>
                    	</div>

                    	<div class="col-md-10">
                    		<h4 class="text-dark font-16 text-capitalize">{{Auth::user()->name}}</h4>
							@if(isset($data) && $data != null)
                        	<p class="text-dark font-13 mb-0">Transaction Id : {{$data->transaction_id}}</p>
							<p class="text-dark font-13">Type : {{$data->payment_type}} Transfer</p>
							@else
							Null
							@endif
                    	</div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2">
                   <table class="table table-row-bordered">

                   		<tr>
                   			<td class="border-end">
								<b class="custom-invoice-bold">From</b> <br>
								{{$fromAccountNumber}}
								<span class="custom-invoice-root-bank">{{$fromAccountWallet}}</span>
                            </td>

							<td class="border-end">
								<b class="custom-invoice-bold">To</b> <br>
								{{$toAccountNumber}}
								<span class="custom-invoice-root-bank">{{$toAccountWallet}}</span>
							</td>
                   		</tr>
                   		<tr>
							<td class="border-end">
								<b class="custom-invoice-bold">Transfer Balance</b><br>
								{{$data->pay_amount}} BDT
							</td>
							
                   			<td class="border-end">
								<b class="custom-invoice-bold">Amount: </b>
								{{$data->pay_amount}} BDT
								<br>
								<b class="custom-invoice-bold">Fee: </b>
								{{$data->pay_fee_amount}} BDT <br>
							</td>
                   		</tr>

                   		<tr class="border">
							<td class="border-end custom-notes-of-invoice">
								<b class="custom-invoice-bold">Notes</b><br>
								{{$data->notes}}
							</td>
						
							<td class="border-end">
								<b class="custom-invoice-bold">Date: </b>
								{{Carbon\Carbon::createFromFormat('Y-m-d', $data->created_at->toDateString())->format('d-m-Y')}} <br>
								<b class="custom-invoice-bold">Time: </b>
								{{Carbon\Carbon::parse($data->created_at->toTimeString())->format('h:m')}} <br>
							</td>
						</tr>

                   </table>
                </div>


                <div class="d-flex align-items-center justify-content-between gap-2 pb-2">
					@if(isset($data) && $data != null)
                    	<a class="text-white w-10 primary_btn m-0" href="{{route('webuser.transfer-revert-update',$data->id)}}">Revert Transaction <i class="fa-solid fa-arrow-right"></i> </a>
					@endif
                </div>
                
            </div>
        </a>
    </div>
</div>

@endsection