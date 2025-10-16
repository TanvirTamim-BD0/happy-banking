@extends('frontend.master')
@section('title') Credit Card Wallet Accounts Details @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <div class="pay_card_wrap">
        <div class="pay_card card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card_name text-capitalize text-dark">{{$singleCreditCardData->bankData->bank_name}}</h4>
                @php
                    //To get card logo...
                    $cardLogo = App\Models\Creditcard::getSingleCreditCardLogo($singleCreditCardData->id);
                @endphp

                <img src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Card Number</p>
                    <p class="text-dark">{{$singleCreditCardData->card_number}}</p>
                </div>
                <div class="odd ">
                    <p>BDT Limit</p>
                    <p class="text-dark">
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <b>৳</b> {{$singleCreditCardData->total_bdt_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Total Limit</p>
                    <p class="text-dark"><b>৳</b>
                        {{$singleCreditCardData->total_limit}}
                    </p>
                </div>
                <div class="odd">
                    <p>USD Limit</p>
                    <p class="text-dark">
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <b>$</b> {{$singleCreditCardData->total_usd_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-3">
        <div class="nav custom-transfer-acccount-tab gap-2 btn-no-color nav-tabs mb-3" id="nav-tab" role="tablist">
        </div>

        <div class="tab-content mt-2" id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-bdt" role="tabpanel" aria-labelledby="nav-bdt-tab">
                <div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Total Limit</h5>
                        <h5><b>৳</b>{{$singleCreditCardData->total_limit}}</h5>
                    </div>
                    
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>BDT Limit</h5>
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <h5><b>৳</b>{{$singleCreditCardData->total_bdt_limit}}</h5>
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </div>
                   
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>USD Limit</h5>
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <h5><b>$</b>{{$singleCreditCardData->total_usd_limit}}</h5>
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </div>

                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Billing Date</h5>
                        <h5>{{Carbon\Carbon::createFromFormat('Y-m-d', $singleCreditCardData->billing_date)->format('d-m-Y')}}</h5>
                    </div>
                </div>
            </div>
        
            <!-- All the credit transfer data -->
            <div class="tab-pane fade" id="nav-usd" role="tabpanel" aria-labelledby="nav-usd-tab">
                {{-- <div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Total Limit</h5>
                        <h5>USD 10000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Outstanding Balance</h5>
                        <h5>USD 5000 </h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Available Limit</h5>
                        <h5>USD 5000 </h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Payment Due</h5>
                        <h5>USD 5000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Last Due Date</h5>
                        <h5>10000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Minimum Pay</h5>
                        <h5>USD 10000</h5>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>

    <div class="ds_body img-40 mb-3">
        <h5 class="mb-1">Transaction & Currency</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.card-wallet-account-transaction-all-list', $singleCreditCardData->id)}}">
                <i class=" fa fa-table custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Transaction</p>
            </a>
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.card-currency', $singleCreditCardData->id)}}">
                <i class=" fa fa-coins custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Currency</p>
            </a>
        </div>
    
    </div>
    
    <div class="ds_body img-40 mb-3">
        <h5 class="mb-1">Bill Payment & Reminder</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.payment-type-for-bill-payment', $singleCreditCardData->id)}}">
                <i class=" fa fa-money-check custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bill Payment </p>
            </a>
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.credit-card-bill-reminder',$singleCreditCardData->id)}}">
                <i class=" fa fa-bell custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bill Reminder</p>
            </a>
        </div>
    
    </div>

    <div class="ds_body img-40 mb-3">
        <h5 class="mb-1">Transfer</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">

            <a class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.credit-card-wallet-account-transfer', ['account_id' => $singleCreditCardData->id,'payment_type' => Crypt::encrypt("Card To Account")])}}">
                    <i class=" fa fa-university custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bank Transfer</p>
            </a>

            <a class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.credit-card-wallet-account-transfer', ['account_id' => $singleCreditCardData->id,'payment_type' => Crypt::encrypt("Card To MFS")])}}">
                <i class=" fas fa-mobile-android-alt custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">MFS Transfer</p>
            </a>

        </div>
    </div>

</div>

@endsection