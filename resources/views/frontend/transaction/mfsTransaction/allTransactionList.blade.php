@extends('frontend.master')
@section('title') All Transaction List Of Mobile Wallet @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container ">
    <div class="custom-transfer-acccount-tab gap-2 btn-no-color">
        <a href="{{route('webuser.mobile-wallet-account-transaction-all-list', $accountId)}}" class="text-white primary_btn nav-link active">
            All
        </a>
        
        <a href="{{route('webuser.mobile-wallet-account-transaction-credit-list', $accountId)}}" class="text-white primary_btn nav-link">
            Credit
        </a>
       
        <a href="{{route('webuser.mobile-wallet-account-transaction-debit-list', $accountId)}}" class="text-white primary_btn nav-link">
            Debit
        </a>
    </div>

    

    <div class="tab-content mt-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            @foreach ($allData as $singleItemForAll)
                @if(isset($singleItemForAll) && $singleItemForAll != null)

                    @if($singleItemForAll['type'] != 'Income/Expense')
                        @php
                        $resultDataForTransfer = App\Models\AccountPayment::getSingleAccountPayment($singleItemForAll['id']);
                        @endphp
                        
                        {{-- To check from pocket account id is null or not --}}
                        @if ($resultDataForTransfer != null && $resultDataForTransfer->from_account_id != null)
                            {{-- To check to credit card & pocket id is null or not --}}
                            @if ($resultDataForTransfer != null && $resultDataForTransfer->to_credit_card_id != null)
                                <a href="{{route('webuser.card-bill-payment-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>$resultDataForTransfer->to_credit_card_id])}}">
                            @elseif ($resultDataForTransfer != null && $resultDataForTransfer->to_pocket_account_id != null)
                                <a href="{{route('webuser.tranjections-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>Auth::user()->id])}}">
                            @else
                                @if($resultDataForTransfer->transfer_type == 'Own Account')
                                    <a href="{{route('webuser.tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>$resultDataForTransfer->to_account_id])}}">
                                @else
                                    <a href="{{route('webuser.tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>$resultDataForTransfer->to_beneficiary_account_id])}}">
                                @endif
                            @endif
                        @else
                            {{-- To check to credit card & pocket id is null or not --}}
                            @if ($resultDataForTransfer != null && $resultDataForTransfer->from_credit_card_id != null)
                                <a href="{{route('webuser.card-tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_credit_card_id ,'to_account'=>$resultDataForTransfer->to_account_id])}}">
                            @elseif ($resultDataForTransfer != null && $resultDataForTransfer->from_pocket_account_id != null)
                                <a href="{{route('webuser.wallet-tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>Auth::user()->id ,'to_account'=>$resultDataForTransfer->to_account_id])}}">
                            @else
                                @if($resultDataForTransfer->transfer_type == 'Own Account')
                                    <a href="{{route('webuser.tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>$resultDataForTransfer->to_account_id])}}">
                                @else
                                    <a href="{{route('webuser.tranjection-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>$resultDataForTransfer->from_account_id ,'to_account'=>$resultDataForTransfer->to_beneficiary_account_id])}}">
                                @endif
                            @endif
                        @endif
                    @else
                        @php
                        $resultDataForInEx = App\Models\IncomeExpense::getSingleIncomeExpense($singleItemForAll['id']);
                        @endphp

                        {{-- To check income or expense --}}
                        @if($resultDataForInEx != null && $resultDataForInEx->status == 1)
                            {{-- To check income type --}}
                            @if($resultDataForInEx != null && $resultDataForInEx->income_expense_type == 'Pocket Wallet Income')
                                <a href="{{route('webuser.income-wallet-invoice',['result'=>Crypt::encrypt($resultDataForInEx) , 'from_account'=>Auth::user()->id])}}">
                            @else
                                <a href="{{route('webuser.income-invoice',['result'=>Crypt::encrypt($resultDataForInEx) , 'from_account'=>$resultDataForInEx->from_account_id])}}">
                            @endif
                        @else
                            {{-- To check expense type --}}
                            @if($resultDataForInEx != null && $resultDataForInEx->income_expense_type == 'Pocket Wallet Expense')
                                <a href="{{route('webuser.expense-wallet-invoice',['result'=>Crypt::encrypt($resultDataForInEx) , 'from_account'=>Auth::user()->id])}}">
                            @else
                                {{-- To check expense from credit card id --}}
                                @if ($resultDataForInEx != null && $resultDataForInEx->from_credit_card_id != null)
                                    <a href="{{route('webuser.expense-card-invoice',['result'=>Crypt::encrypt($resultDataForInEx) , 'from_account'=>$resultDataForInEx->from_credit_card_id])}}">
                                @else
                                    <a href="{{route('webuser.expense-invoice',['result'=>Crypt::encrypt($resultDataForInEx) , 'from_account'=>$resultDataForInEx->from_account_id])}}">
                                @endif
                            @endif
                        @endif

                    @endif

                        <div class="card transaction p-0 mb-3">
                            @if($singleItemForAll['type'] == 'Income/Expense')
                            <h6 class="p-2">{{$singleItemForAll['title']}}</h6>
                            @else
                            <h6 class="p-2">{{$singleItemForAll['title']}} Transfer</h6>
                            @endif
                            
                            <div class="d-flex align-items-center justify-content-between p-2 custom-transaction-details-block">
                                <div class="d-flex flex-column">

                                    <p class="text-secondary fw-light mb-0"><b>Tran:</b> {{$singleItemForAll['transaction_id']}}</p>
                                    <p class="text-secondary fw-light">
                                        <b>Date:</b>
                                        {{Carbon\Carbon::createFromFormat('Y-m-d', $singleItemForAll['date'])->format('d-m-Y')}} <br>
                                        <b>Time:</b>
                                        {{Carbon\Carbon::create($singleItemForAll['time'])->format('h:i')}}
                                    </p>
                                </div>
                                <div class="d-flex flex-column custom-credit-debit-date-time">

                                    <p class="text-secondary fw-light">
                                        @if($singleItemForAll['type'] == 'Income/Expense')
                                            @php
                                                $getSingleAccData = App\Models\IncomeExpense::getSingleIncomeExpense($singleItemForAll['id']);
                                            @endphp
                                            @if ($getSingleAccData != null && $getSingleAccData->income_expense_type == 'Mobile Wallet Income')
                                                <b>Transfer:</b> {{$singleItemForAll['credit_debit_amount']}} BDT
                                            @else
                                                <b>Transfer:</b> {{$singleItemForAll['credit_debit_amount']}} BDT
                                            @endif
                                        @else
                                            @php
                                                $getSingleAccPayData = App\Models\AccountPayment::getSingleAccountPayment($singleItemForAll['id']);
                                            @endphp
                                            @if ($getSingleAccPayData->to_account_id != null && $getSingleAccPayData->to_account_id == $accountId)
                                                <b>Transfer:</b> {{$singleItemForAll['credit_debit_amount']}} BDT
                                            @else
                                                <b>Transfer:</b> {{$singleItemForAll['credit_debit_amount']}} BDT
                                            @endif
                                        @endif 

                                        <br>

                                        <b>Fee:</b> {{ $singleItemForAll['processing_fee_amount'] }} BDT <br>

                                        @if($singleItemForAll['type'] == 'Income/Expense')
                                            @php
                                                $getSingleAccData = App\Models\IncomeExpense::getSingleIncomeExpense($singleItemForAll['id']);
                                            @endphp
                                            @if ($getSingleAccData != null && $getSingleAccData->income_expense_type == 'Mobile Wallet Income')
                                                <b>Credit:</b> <span class="text-primary">{{$singleItemForAll['credit_debit_amount']}} BDT</span>
                                            @else
                                                <b>Debit:</b> <span class="text-danger">{{ $singleItemForAll['processing_fee_amount'] + $singleItemForAll['credit_debit_amount'] }} BDT</span>
                                            @endif
                                        @else
                                            @php
                                                $getSingleAccPayData = App\Models\AccountPayment::getSingleAccountPayment($singleItemForAll['id']);
                                            @endphp
                                            @if ($getSingleAccPayData->to_account_id != null && $getSingleAccPayData->to_account_id == $accountId)
                                                <b>Credit:</b> <span class="text-primary">{{$singleItemForAll['credit_debit_amount']}} BDT</span>
                                            @else
                                                <b>Debit:</b> <span class="text-danger">{{ $singleItemForAll['processing_fee_amount'] + $singleItemForAll['credit_debit_amount'] }} BDT</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>


                            <div class="bg-secondary p-2">
                                @if($singleItemForAll['type'] == 'Income/Expense')
                                    @php
                                        $getSingleAccData = App\Models\IncomeExpense::getSingleIncomeExpense($singleItemForAll['id']);
                                    @endphp
                                    @if ($getSingleAccData != null && $getSingleAccData->income_expense_type == 'Mobile Wallet Income')
                                        <p class="h5 m-0 custom-invoice-bold">{{$singleItemForAll['notes']}}</p>
                                    @else
                                        <p class="h5 m-0 custom-invoice-bold">{{$singleItemForAll['notes']}}</p>
                                    @endif
                                @else
                                    @php
                                        $getSingleAccPayData = App\Models\AccountPayment::getSingleAccountPayment($singleItemForAll['id']);
                                    @endphp
                                    @if ($getSingleAccPayData->to_account_id != null && $getSingleAccPayData->to_account_id == $accountId)
                                        <p class="h5 m-0 custom-invoice-bold">{{$singleItemForAll['notes']}}</p>
                                    @else
                                        <p class="h5 m-0 custom-invoice-bold">{{$singleItemForAll['notes']}}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </a>
                @endif
            @endforeach

            @if($allData->count() > 0)
                <span class="custom-banking-pagination">
                    {{ $allData->links() }}
                </span>
            @endif
        </div>
    </div>


</div>

@endsection