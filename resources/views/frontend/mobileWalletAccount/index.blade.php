@extends('frontend.master')
@section('title') Mobile Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container custom-main-container">

    @foreach($accountData as $account)
    @if(isset($account) && $account != null)
    <div class="pay_card_wrap">
        <a href="{{ route('webuser.mobile-account-details', $account->id) }}">
            <div class="pay_card mb-3 card">
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">

                    @if(isset($account->mobileWalletData->image) && $account->mobileWalletData->image != null)
                    <img src="{{asset('backend/uploads/mobileWalletImage/thumbnail/'.$account->mobileWalletData->image)}}" alt="visa_card"/>
                    @else
                    <img src="{{asset('frontend')}}/images/bkash.png" alt="visa_card">
                    @endif

                    {{-- <img src="{{asset('frontend')}}/images/bkash.png" alt="visa_card"> --}}

                    <div class="custom-list-right-side">
                        <h4 class="text-dark font-16 text-capitalize">{{$account->mobileWalletData->mobile_wallet_name}}</h4>
                        <p class="text-dark font-13">Bank: {{$account->mobileWalletData->parent_company}}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between border-bottom mb-2">
                    <div class="odd">
                        <p>Balance</p>
                        <p class="text-dark">{{$account->current_balance}} BDT</p>
                    </div>
                    <div class="odd">
                        <p>A/c Number: </p>
                        <p class="text-dark">{{$account->account_number}}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 custom-trans-bottom-block">
                    <a class="text-white w-50 primary_btn m-0" href="{{route('webuser.mobile-wallet-account-transaction-all-list',$account->id)}}">Transaction</a>
                    <a class="text-white w-50 primary_btn m-0" href="{{route('webuser.payment-type-for-mobile-wallet-account-transfer',$account->id)}}">Transfer</a>
                </div>
                
            </div>
        </a>
    </div>
    @endif
    @endforeach

    @if($accountData->count() >= 3)
    <div class="back-to-top">
        <div class="icon"><a href="{{route('webuser.mobile-wallet-account-create')}}"><i class="fa-solid fa-plus"></i></a></div>
    </div>
    @else
    <div class="back-to-top custom-back-to-top">
        <div class="icon"><a href="{{route('webuser.mobile-wallet-account-create')}}"><i class="fa-solid fa-plus"></i></a>
        </div>
    </div>
    @endif

</div>

@endsection