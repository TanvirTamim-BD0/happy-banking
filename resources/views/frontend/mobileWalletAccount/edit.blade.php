@extends('frontend.master')
@section('title') Edit Mobile Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <form action="{{route('webuser.single-mobile-wallet-account-update',$accountData->id)}}" method="post" autocomplete="off" class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h4 class="text-center heading_text pb-3">Update MFS Account</h4>

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Mobile Wallet <span class="custom-danger">(requierd)</span></label>
        <div class="single_input custom-select2-dropdown">
            <i class="fa-solid fa-piggy-bank"></i>
            <select autocomplete="off" name="mobile_wallet_id" id="mobile_wallet_id" required class="form-control">
                <option value="" selected disabled>Select Mobile Wallet</option>

                @foreach($mobileWalletData as $mobileWallet)
                @if(isset($mobileWallet) && $mobileWallet != null)
                <option value="{{$mobileWallet->id}}" {{ $mobileWallet->id == $accountData->mobile_wallet_id ? 'selected' : '' }} >{{$mobileWallet->mobile_wallet_name}}</option>
                @endif
    			@endforeach

            </select>
        </div>
        </div>
        

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Account Number <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-credit-card"></i>
            <input name="account_number" autocomplete="off" type="number" placeholder="Account Number" class="form-control" value="{{$accountData->account_number}}">
        </div>
        </div>

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Current Balance <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            <input name="current_balance" autocomplete="off" type="number" placeholder="Current Balance" class="form-control" 
            value="{{$accountData->current_balance}}">
        </div>
        </div>


        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Update Mobile Wallet</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#mobile_wallet_id').select2();
        $('#bank_account_type').select2();
    });
</script>
@endsection()