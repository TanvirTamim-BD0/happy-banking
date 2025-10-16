@extends('frontend.master')
@section('title') Banking Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="container h-100 custom-main-container custom-empty-data-container">
{{-- <div class="container h-100 d-flex align-items-center flex-column justify-content-center"> --}}
    <div class="card text-center">
        <h4 class="bg-primary p-1 rounded mb-2">Sorry, Account Not Found.!</h4>
        <h5>No available account. To create an account click the link below.</h5>
        <div class="mt-2">
            <a href="{{route('webuser.banking-wallet-account-create')}}">
                <button class="primary_btn m-0">
                    <i class="fa-solid fa-plus"></i> Create An Account
                </button>
            </a>
        </div>
    </div>

    @if($accountData->count() >= 3)
    <div class="back-to-top">
        <div class="icon"><a href="{{route('webuser.banking-wallet-account-create')}}"><i
                    class="fa-solid fa-plus"></i></a></div>
    </div>
    @else
    <div class="back-to-top custom-back-to-top">
        <div class="icon"><a href="{{route('webuser.banking-wallet-account-create')}}"><i
                    class="fa-solid fa-plus"></i></a></div>
    </div>
    @endif
</div>

@endsection