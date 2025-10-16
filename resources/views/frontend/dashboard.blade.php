@extends('frontend.master')
@section('title') Dashboard @endsection
@section('styles')
@endsection
@section('content')

<div class="container h-100">
    <div class="card mb-1">
        <div class="d-flex align-items-start gap-2">
            <div class="pe-2 w-48 text-secondary">
                <h4 class="text-center mb-2">Wallet</h4>
                <p class="d-flex align-items-center mb-2"> <i class="fa-solid fa-images h5 me-1"></i> <span>Current
                        Balance</span></p>
                <h4 class="text-center text-success mb-2"> {{Auth::user()->wallet}}Tk</h4>
                <a href="{{route('webuser.pocket-wallet-account-transaction-all-list')}}" class="">
                    <button class="primary_btn"> Transaction</button>
                </a>
            </div>
            <div class="w-48 text-secondary profile_info border-start border-secondary custom-transfer-to-block">
                <h4 class="text-center">Transfer To</h4>
                <div class="d-flex align-items-center flex-column px-1 custom-transfer-type">
                    <a class="d-block w-100 border-bottom pb-1"
                        href="{{route('webuser.pocket-wallet-account-transfer', ['payment_type' => Crypt::encrypt("Wallet To MFS")])}}">
                        <button class="primary_btn mb-0">MFS</button>
                    </a>

                    <a class="d-block w-100 border-bottom pb-1" href="{{route('webuser.pocket-wallet-account-transfer', ['payment_type' => Crypt::encrypt("Wallet To Account")])}}">
                        <button class="primary_btn mb-0">Account</button>
                    </a>
                    
                    
                    <a class="d-block w-100" href="{{route('webuser.pocket-wallet-account-transfer', ['payment_type' => Crypt::encrypt("Wallet To Card")])}}">
                        <button class="primary_btn mb-0">Card</button>
                    </a>

                </div>
            </div>
        </div>
    </div>
    <div class="ds_body img-40 mb-1">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> My Accounts</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.mobile-wallet-account')}}">
                <i class=" fas fa-mobile-android-alt custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">MFS </p>
            </a>

            <a class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.banking-wallet-account')}}">

                {{-- <img src="{{asset('frontend')}}/images/bd-flag.png" class="custom-home-image" alt="Rule"> --}}
                <i class=" fa fa-university custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bank</p>
            </a>
            
            <a class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.credit-card-wallet-account')}}">
                <i class=" fa fa-credit-card custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Credit Card</p>
            </a>
        </div>
    </div>
    <div class="ds_body img-40 mb-1">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> Income</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-income-create', ['income_type' => Crypt::encrypt("Pocket Wallet Income")])}}">
                <i class=" fas fa-money-bill custom-dashboard-icon"></i>
                {{-- <i class="fas fa-suitcase"></i> --}}
                <p class="text-dark pb-0 mb-0">Wallet</p>
            </a>
            <a class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-income-create', ['income_type' => Crypt::encrypt("Mobile Wallet Income")])}}">
                <i class=" fas fa-mobile-android-alt custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">MFS </p>
            </a>
            <a
                class="border-end no-border-last w-30 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-income-create', ['income_type' => Crypt::encrypt("Banking Income")])}}">
                <i class=" fa fa-university custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bank</p>
            </a>
        </div>
    </div>
    <div class="ds_body img-40 mb-1">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> Expense</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="w-25 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-expense-create', ['expense_type' => Crypt::encrypt("Pocket Wallet Expense")])}}">
                <i class=" fas fa-money-bill custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Wallet</p>
            </a>
            <a class="w-25 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-expense-create', ['expense_type' => Crypt::encrypt("Mobile Wallet Expense")])}}">
                <i class=" fas fa-mobile-android-alt custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">MFS </p>
            </a>
            <a class="w-25 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-expense-create', ['expense_type' => Crypt::encrypt("Banking Expense")])}}">
                <i class=" fa fa-university custom-dashboard-icon"></i>
                <p class="text-dark text-center">Bank</p>
            </a>
            
            <a class="w-25 gap-2 p-0 flex-column d-flex align-items-center"
                href="{{route('webuser.wallet-expense-create', ['expense_type' => Crypt::encrypt("Credit Card Expense")])}}">
                <i class=" fa fa-credit-card custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Credit Card</p>
            </a>
            
        </div>

    </div>

    <div class="ds_body img-40 mb-1">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> Beneficiary </h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.mfs-beneficiary')}}">
                <i class=" fas fa-mobile-android-alt custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">MFS Beneficiary</p>
            </a>
            <a class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.bank-beneficiary')}}">
                <i class=" fa fa-university custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Bank Beneficiary</p>
            </a>
        </div>
    </div>

    <div class="ds_body img-40 mb-1">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> Calculator </h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between">
            <a class="w-30 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.dbr-caculator')}}">
                <i class=" fa-solid fa-money-check-dollar custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">DBR</p>
            </a>
            <a class="w-30 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.emi-caculator')}}">
                <i class="fa-solid fa-cash-register custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Loan EMI</p>
            </a>
            <a class="w-30 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.loan-caculator')}}">
                <i class="fa-solid fa-calculator custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Card EMI</p>
            </a>
        </div>
    </div>


    <div class="ds_body img-40 mb-3">
        <h4 class="mb-1 pl-4 text-left custom-home-title"> Others</h5>
        <div class="d-flex flex-wrap flex-row card justify-content-between"><a
                class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.about')}}">
                <i class=" fas fa-address-card custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">About</p>
            </a><a class="w-50 gap-2 p-0 flex-column d-flex align-items-center border-end no-border-last"
                href="{{route('webuser.documentation-category')}}">
                <i class=" fas fa-book-reader custom-dashboard-icon"></i>
                <p class="text-dark pb-0 mb-0">Documentation</p>
            </a></div>
    </div>

</div>

@endsection