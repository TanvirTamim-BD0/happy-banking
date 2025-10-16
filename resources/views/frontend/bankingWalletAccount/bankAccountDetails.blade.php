@extends('frontend.master')
@section('title') Bank Wallet Accounts Details @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <div class="pay_card_wrap">
        <div class="pay_card card">
            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">

                @if(isset($singleAccountData->bankData->image) && $singleAccountData->bankData->image != null)
                <img src="{{asset('backend/uploads/bankImage/thumbnail/'.$singleAccountData->bankData->image)}}" />
                @else
                <img src="{{asset('frontend')}}/images/visa-gold.png" alt="visa_card">
                @endif
                
                <div>
                    <h4 class="text-dark font-16 text-capitalize">{{$singleAccountData->bankData->bank_name}}</h4>
                    <p class="text-dark font-13">Branch: {{$singleAccountData->branch}}</p>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between border-bottom mb-2">
                <div class="odd">
                    <p>Balance</p>
                    <p class="text-dark">{{$singleAccountData->current_balance}} BDT</p>
                </div>
                <div class="odd">
                    <p class="text-dark">A/c Number: </p>
                    <p class="text-dark">{{$singleAccountData->account_number}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="ds_body img-40 mb-2">

        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.banking-wallet-account-transaction-all-list',$singleAccountData->id)}}">
                <i class=" fa fa-table custom-dashboard-icon"></i>
                <p class="text-dark mb-0 pb-0">Transactions</p>
            </a>
            <a class="border-end no-border-last w-50 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.payment-type-for-banking-wallet-account-transfer',$singleAccountData->id)}}">
                <i class=" fa fa-money-bill-transfer custom-dashboard-icon"></i>
                <p class="text-dark mb-0 pb-0">Transfer </p>
            </a>
            
        </div>
    
    </div>

</div>

@endsection