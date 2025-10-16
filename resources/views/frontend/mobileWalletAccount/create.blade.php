@extends('frontend.master')
@section('title') Create Mobile Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container ">

    <form action="{{route('webuser.mobile-wallet-account-store')}}" method="post" autocomplete="off" class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h4 class="text-center heading_text pb-3">Add MFS Account</h4>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Mobile Wallet <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-piggy-bank"></i>
                <select autocomplete="off" name="mobile_wallet_id" id="mobile_wallet_id" required class="form-control">
                    <option value="" selected disabled>Select Mobile Wallet</option>

                    @foreach($mobileWalletData as $mobileWallet)
                    @if(isset($mobileWallet) && $mobileWallet != null)
                    <option value="{{$mobileWallet->id}}">{{$mobileWallet->mobile_wallet_name}}</option>
                    @endif
        			@endforeach

                </select>

                 @error('mobile_wallet_id')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Account Number <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="account_number" autocomplete="off" type="number" placeholder="Account Number" class="form-control" required >

                @error('account_number')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Current Balance <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input name="current_balance" autocomplete="off" type="number" placeholder="Current Balance" class="form-control" required >

                @error('current_balance')
                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                @enderror
            </div>
        </div>            


        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Add Mobile Wallet Account</button>
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