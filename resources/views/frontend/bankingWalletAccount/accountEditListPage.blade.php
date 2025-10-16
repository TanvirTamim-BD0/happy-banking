@extends('frontend.master')
@section('title') Banking Wallet Account Edit @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    @foreach($accountData as $account)
    @if(isset($account) && $account != null)
    <div class="pay_card_wrap">
        <div class="pay_card mb-3 card">
            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                

                @if(isset($account->bankData->image) && $account->bankData->image != null)
                    <img src="{{asset('backend/uploads/bankImage/thumbnail/'.$account->bankData->image)}}" />
                @else
                    <img src="{{asset('frontend')}}/images/visa-gold.png" alt="visa_card">
                @endif

                <div>
                    <h4 class="text-dark font-16 text-capitalize">{{$account->bankData->bank_name}}</h4>
                    <p class="text-dark font-13">Branch: {{$account->branch}}</p>
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

            <div class="d-flex align-items-center justify-content-between edit-footer-card gap-2">
                <a class="text-white w-50 primary_btn m-0"
                    href="{{route('webuser.single-banking-wallet-account-edit',$account->id)}}">Edit Account</a>
                @if($account->is_inactive == false)
                <a class="text-white w-50 primary_btn custom-service-active-btn m-0"
                    href="{{route('webuser.single-banking-wallet-account-inactive',$account->id)}}">Active</a>
                @else
                <a class="text-white w-50 primary_btn custom-service-inactive-btn m-0"
                    href="{{route('webuser.single-banking-wallet-account-active',$account->id)}}">In Active</a>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endforeach


</div>

@endsection