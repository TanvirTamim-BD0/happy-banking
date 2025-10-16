@extends('frontend.master')
@section('title') Income Expesnse Revert @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

 
    <div class="pay_card_wrap">
        <a href="#">
            <div class="pay_card mb-3 card">
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-3">

                    <div class="row">
                    	<div class="col-md-1">
                    		<i class="fa-solid fa-user"></i>
                    	</div>

                    	<div class="col-md-10">
							<h4 class="text-dark font-16 text-capitalize"><b class="custom-invoice-bold">{{Auth::user()->name}}</b></h4>
							<p class="text-dark font-13 mb-0">Transaction Id : {{$data->transaction_id}}</p>
							<p class="text-dark font-13">Type : {{$data->income_expense_type}}</p>
                    	</div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2 pb-5">
                   <table class="table table-row-bordered">

                   		<tr>
                   			<td class="border-end">
								<b class="custom-invoice-bold">Expense Account</b> <br>
								{{$fromAccountNumber}}
								<span class="custom-invoice-root-bank">{{$fromAccountWallet}}</span>
                            </td>

							<td class="border-end">
								<b class="custom-invoice-bold">Expense Type </b><br>
								{{$data->transactionCategoryData->category_name}}
							</td>
                   		</tr>
                   		<tr>

							<td class="border-end">
								<b class="custom-invoice-bold">Expense Balance</b><br>
								{{$data->amount}} BDT
							</td>
							
							<td class="border-end">
								<b class="custom-invoice-bold">Amount: </b>
								{{$data->amount}} BDT <br>
								<b class="custom-invoice-bold">Fee: </b>
								0.00 BDT <br>
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
								{{Carbon\Carbon::create($data->created_at->toTimeString())->format('h:i')}} <br>
							</td>
						</tr>

                   </table>
                </div>


                <div class="d-flex align-items-center justify-content-between gap-2 pb-2">
                    <a class="text-white w-10 primary_btn m-0" href="{{route('webuser.income-expense-revert-update',$data->id)}}">Revert <i class="fa-solid fa-arrow-right"></i> </a>
                </div>
                
            </div>
        </a>
    </div>
</div>

@endsection