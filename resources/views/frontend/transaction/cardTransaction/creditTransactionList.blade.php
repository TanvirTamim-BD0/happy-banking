@extends('frontend.master')
@section('title') Credit Transaction List Of Credit Card Wallet @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container ">
    <div class="custom-transfer-acccount-tab gap-2 btn-no-color">
        <a href="{{route('webuser.card-wallet-account-transaction-all-list', $accountId)}}"
            class="text-white primary_btn nav-link">
            All
        </a>
    
        <a href="{{route('webuser.card-wallet-account-transaction-credit-list', $accountId)}}"
            class="text-white primary_btn nav-link active">
            Credit
        </a>
    
        <a href="{{route('webuser.card-wallet-account-transaction-debit-list', $accountId)}}" class="text-white primary_btn nav-link">
            Debit
        </a>
    </div>

    

    <div class="mt-2">
    
        <!-- All the credit transfer data -->
        <div class="active">
            @foreach ($creditData as $singleItemForCredit)
                @if(isset($singleItemForCredit) && $singleItemForCredit != null)

                    @if($singleItemForCredit['type'] != 'Income/Expense')
                        @php
                        $resultDataForTransfer = App\Models\AccountPayment::getSingleAccountPayment($singleItemForCredit['id']);
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
                                <a href="{{route('webuser.card-wallet-bill-payment-invoice',['result'=>Crypt::encrypt($resultDataForTransfer) , 'from_account'=>Auth::user()->id ,'to_account'=>$resultDataForTransfer->to_credit_card_id])}}">
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
                        $resultDataForInEx = App\Models\IncomeExpense::getSingleIncomeExpense($singleItemForCredit['id']);
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
                            @if($singleItemForCredit['type'] == 'Income/Expense')
                            <h6 class="p-2">{{$singleItemForCredit['title']}}</h6>
                            @else
                            <h6 class="p-2">{{$singleItemForCredit['title']}} Transfer</h6>
                            @endif

                            <div class="d-flex align-items-center justify-content-between p-2 custom-transaction-details-block">
                                <div class="d-flex flex-column">

                                    <p class="text-secondary fw-light mb-0"><b>Tran:</b> {{$singleItemForCredit['transaction_id']}}</p>
                                    <p class="text-secondary fw-light">
                                        <b>Date:</b>
                                        {{Carbon\Carbon::createFromFormat('Y-m-d', $singleItemForCredit['date'])->format('d-m-Y')}} <br>
                                        <b>Time:</b>
                                        {{Carbon\Carbon::create($singleItemForCredit['time'])->format('h:i')}}
                                    </p>
                                </div>
                                <div class="d-flex flex-column custom-credit-debit-date-time">
                                    <p class="text-secondary fw-light">
                                        <b>Transfer:</b> {{$singleItemForCredit['credit_amount']}} BDT <br>
                                        <b>Fee:</b> {{ $singleItemForCredit['processing_fee_amount'] }} BDT <br>
                                        <b>Credit:</b> <span class="text-primary">{{$singleItemForCredit['credit_amount']}} BDT</span>
                                    </p>
                                </div>
                            </div>
                            <div class="bg-secondary p-2">
                                <p class="h5 m-0 custom-invoice-bold">{{$singleItemForCredit['notes']}}</p>
                            </div>
                        </div>

                    </a>
                @endif
            @endforeach

            @if($creditData->count() > 0)
                <span class="custom-banking-pagination">
                    {{ $creditData->links() }}
                </span>
            @endif
        </div>

    </div>


</div>

@endsection