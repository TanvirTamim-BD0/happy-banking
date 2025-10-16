@extends('frontend.master')
@section('title') Edit Credit Card Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">


    @foreach($creditCardData as $creditCard)
        @if(isset($creditCard) && $creditCard != null)
        <div class="pay_card_wrap">
            <div class="pay_card card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card_name text-capitalize text-dark">{{$creditCard->bankData->bank_name}}</h4>
                        @php
                        //To get card logo...
                        $cardLogo = App\Models\Creditcard::getSingleCreditCardLogo($creditCard->id);
                        @endphp
    
                        <img src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">
    
                </div>
    
                <div class="d-flex align-items-center">
                    <div class="w-70 odd">
                        <p>Card Number</p>
                        <p class="text-dark">{{$creditCard->card_number}}</p>
                    </div>
                    <div class="odd ">
                        <p>BDT Limit</p>
                        <p class="text-dark">
                            @if ($creditCard->is_dual_currency == true)
                            <b>৳</b> {{$creditCard->total_bdt_limit}}
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
                            {{$creditCard->total_limit}}
                        </p>
                    </div>
                    <div class="odd">
                        <p>USD Limit</p>
                        <p class="text-dark">
                            @if ($creditCard->is_dual_currency == true)
                            <b>$</b> {{$creditCard->total_usd_limit}}
                            @else
                            <span class="custom-card-disabled-color">Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between edit-footer-card gap-2 mt-3">
                    <a class="text-white w-50 primary_btn m-0"
                        href="{{route('webuser.single-credit-card-wallet-account-edit',$creditCard->id)}}">Edit Account</a>
                    @if($creditCard->is_inactive == false)
                    <a class="text-white w-50 primary_btn m-0 custom-service-active-btn"
                        href="{{route('webuser.single-credit-card-wallet-account-inactive',$creditCard->id)}}">Active</a>
                    @else
                    <a class="text-white w-50 primary_btn m-0 custom-service-inactive-btn"
                        href="{{route('webuser.single-credit-card-wallet-account-active',$creditCard->id)}}">In Active</a>
                    @endif
                </div>
    
            </div>
        </div>



          
        @endif
    @endforeach


</div>

@endsection