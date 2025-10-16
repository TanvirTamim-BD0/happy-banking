@extends('frontend.master')
@section('title') Credit Card Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container custom-main-container">


    @foreach($creditCardData as $creditCard)
        @if(isset($creditCard) && $creditCard != null)
            <div class="pay_card_wrap">
                <a href="{{ route('webuser.credit-card-details', $creditCard->id) }}">
                    <div class="pay_card card mb-3" >
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card_name text-capitalize text-dark">{{$creditCard->bankData->bank_name}}</h4>
                            @php
                             //To get card logo...
                             $cardLogo = App\Models\Creditcard::getSingleCreditCardLogo($creditCard->id);   
                            @endphp

                            <img  src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">

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

                    </div>
                </a>
            </div>
        @endif
    @endforeach


    @if($creditCardData->count() >= 3)
        <div class="back-to-top">
            <div class="icon"><a href="{{route('webuser.credit-card-wallet-account-create')}}"><i
                        class="fa-solid fa-plus"></i></a></div>
        </div>
    @else
    <div class="back-to-top custom-back-to-top">
        <div class="icon"><a href="{{route('webuser.credit-card-wallet-account-create')}}"><i
                    class="fa-solid fa-plus"></i></a></div>
    </div>
    @endif
    
    

</div>

@endsection