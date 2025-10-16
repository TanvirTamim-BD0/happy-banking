@extends('frontend.master')
@section('styles')
<style>
    .custom-profile-block-area {
        margin-top: -25px;
        margin-left: -10px;
    }
</style>
@endsection

@section('content')
<div class="container h-100">

<div class="profile_details card mb-3">
    <div class="d-flex align-items-center gap-4 ">
        <!-- <div class="p-2 w-50"> -->
        <img class="w-30 mb-2 border border-primary rounded " src="{{asset('frontend')}}/images/user.jpg" alt="user">
        <!-- </div> -->
        <div class="custom-profile-block-area">
            <h5>{{Auth::user()->name}}</h5>
            <p class="mb-0">{{Auth::user()->mobile}}</p>
            <p class="mb-0">{{Str::title(Auth::user()->gender)}}</p>
            <p class="mb-0">{{Str::title(Auth::user()->userProfessionData->profession_name)}}</p>
        </div>
    </div>
</div>


<div class="card mb-3">

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.mobile-wallet-account-edit-list')}}"
            class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">MFS Service</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>
    
    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.banking-wallet-account-edit-list')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Account Service</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.credit-card-wallet-account-edit-list')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Credit Card Service</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

  </div>

<div class="card mb-3">

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.transfer-revert')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Transfer Revert</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.income-expense-revert')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Income Expense Revert</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

</div>


<div class="card mb-3">

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.change-info')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Change your info</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.change-mobile')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Change your Mobile</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

    <div class="toggler mb-2 border-bottom pb-2">
        <a href="{{route('webuser.change-password')}}" class="d-flex align-items-center justify-content-between pointer-event text-dark">
            <h4 class="h5">Change your Password</h4>
            <i class="fa-solid fa-angle-right h5"></i>
        </a>
    </div>

    <div class="toggler mb-2 border-bottom pb-2">
        <a class="d-flex align-items-center justify-content-between pointer-event text-danger" href="{{route('webuser.logout')}}">
            <h4 class="h5">Logout</h4><i class="fa-solid fa-angle-right font-h4"></i>
        </a>
    </div>

  </div>

</div>

@endsection