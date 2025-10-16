@extends('frontend.master')
@section('title') Create Banking Beneficiary Accounts @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <form action="{{route('webuser.banking-beneficiary-account-store')}}" method="post" autocomplete="off" class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h4 class="text-center heading_text pb-3">Add Bank Beneficiary</h4>

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
        <label class="custom-form-label" for="email">Branch <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-location-dot"></i>
            <input name="branch" autocomplete="off" type="text" placeholder="Branch" class="form-control" required>
        </div>
        </div>


        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Account Type <span class="custom-danger">(requierd)</span></label>
        <div class="single_input custom-select2-dropdown">
            <i class="fa-solid fa-coins"></i>
            <select autocomplete="off" name="bank_account_type" id="bank_account_type" required class="form-control">
                <option value="" selected disabled>Select Account Type</option>
                <option value="Savings">Savings</option>
                <option value="Current">Current</option>
            </select>
        </div>
        </div>


        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Account Number <span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-credit-card"></i>
            <input name="account_number" autocomplete="off" type="text" placeholder="Account Number" class="form-control" required>
        </div>
        </div>


        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Add Beneficiary Bank</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#bank_id').select2();
        $('#bank_account_type').select2();
    });
</script>
@endsection()