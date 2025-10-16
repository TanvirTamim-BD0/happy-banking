@extends('frontend.master')
@section('title') Payment Type @endsection
@section('styles')
@endsection
@section('content')

<div class="container h-100">

    <div class="card mb-3">
        <div class="d-flex gap-2">
            <div class="w-100 text-secondary custom-transfer-type profile_info">
                <h4 class="text-center">Select Payment Type</h4>
                <div class="d-flex flex-column px-1 mt-3 mb-2">
                    <a class="d-block w-100" href="{{route('webuser.banking-wallet-account-transfer', ['account_id' => $accountId,'payment_type' => Crypt::encrypt("Account To Account")])}}">
                        <button class="primary_btn text-start mb-0"> Bank To Bank <i class="fas fa-arrow-circle-right custom-transfer-type-icon"></i></button>
                    </a>
                    <a class="d-block w-100" href="{{route('webuser.banking-wallet-account-transfer', ['account_id' => $accountId,'payment_type' => Crypt::encrypt("Account To MFS")])}}">
                        <button class="primary_btn text-start mb-0"><i class="fas fa-arrow-circle-right custom-transfer-type-icon"></i> Bank To MFS</button>
                    </a>
                    <a class="d-block w-100" href="{{route('webuser.banking-wallet-account-transfer', ['account_id' => $accountId,'payment_type' => Crypt::encrypt("Account To Wallet")])}}">
                        <button class="primary_btn text-start mb-0"><i class="fas fa-arrow-circle-right custom-transfer-type-icon"></i> Bank To Wallet</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection