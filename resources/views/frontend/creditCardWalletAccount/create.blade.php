@extends('frontend.master')
@section('title') Create Credit Card Wallet Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <form action="{{route('webuser.credit-card-wallet-account-store')}}" method="post" autocomplete="off"
        class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h3 class="text-center heading_text pb-3">Add Credit Card</h3>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Bank <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-piggy-bank"></i>
                <select autocomplete="off" name="bank_id" id="bank_id" required class="form-control">
                    <option value="" selected disabled>Select Bank</option>

                    @foreach($bankData as $bank)
                    @if(isset($bank) && $bank != null)
                    <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Card Type <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-piggy-bank"></i>
                <select autocomplete="off" name="card_type" id="card_type" required class="form-control">
                    <option value="" selected disabled>Select Card Type</option>
                    @foreach($cardTypeData as $cardType)
                    @if(isset($cardType) && $cardType != null)
                    <option value="{{$cardType}}">{{$cardType}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Card Number <span
                    class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="card_number" autocomplete="off" type="number" placeholder="Card Number"
                    class="form-control" required>
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Billing Date <span
                    class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input type="text" autocomplete="off" class="form-control form-control-solid flatpickr-input active"
                    name="billing_date" id="billing_date" placeholder="Billing Date" required>
            </div>
        </div>


        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Total Limit <span
                    class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                <input name="total_limit" autocomplete="off" type="number" placeholder="Total Limit"
                    class="form-control" required>
            </div>
        </div>


        {{-- <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Total BDT Limit <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            <input name="total_bdt_limit" autocomplete="off" type="number" placeholder="Total BDT Limit" class="form-control" required>
        </div>
        </div>


        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Total Usd Limit <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            <input name="total_usd_limit" autocomplete="off" type="number" placeholder="Total Usd Limit" class="form-control" required >
        </div>
        </div> --}}


        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Add Credit Card</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#bank_id').select2();
        $('#card_type').select2();
    });
</script>
@endsection()