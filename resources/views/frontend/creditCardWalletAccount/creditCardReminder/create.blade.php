@extends('frontend.master')
@section('title') Create Credit Card Bill Reminder @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <form action="{{route('webuser.credit-card-bill-reminder-store')}}" method="post" autocomplete="off" class="needs-validation card" style="margin-bottom: 13px;">
        @csrf
        <h3 class="text-center heading_text pb-3">Card Bill Reminder</h3>
        <input type="hidden" name="credit_card_id" value="{{$creditCardId}}">

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Active Session <span class="custom-danger">(requierd)</span></label>
        <div class="single_input custom-select2-dropdown">
            <i class="fa-solid fa-piggy-bank"></i>
            <select autocomplete="off" name="active_session_id" id="active_session_id" required class="form-control">
                <option value="" selected disabled>Select active Session</option>

                @if(isset($activeSessionData) && $activeSessionData != null)
                <option value="{{$activeSessionData->id}}">{{$activeSessionData->session_name}}</option>
                @endif
            </select>
        </div>
        </div>

        <div class="form-group custom-form-group">
        <label class="custom-form-label" for="email">Last Payment Date<span class="custom-danger">(requierd)</span></label>
        <div class="single_input ">
            <i class="fa-solid fa-credit-card"></i>
            <input type="text" autocomplete="off" class="form-control form-control-solid flatpickr-input active" name="last_payment_date"
                id="last_payment_date" placeholder="Last Payment Date" required>
        </div>
        </div>  

        @php
            //To get single credit card reminder data...
            $singleCreditCardData = App\Models\CreditCardReminder::getSingleCreditCardReminderData($creditCardId);
        @endphp

        @if($singleCreditCardData->is_dual_currency == true)
        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Total BDT Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="total_bdt_due" autocomplete="off" type="text" placeholder="Total BDT Due" class="form-control" required>
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">BDT Minimum Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="bdt_minimum_due" autocomplete="off" type="text" placeholder="BDT Minimum Due" class="form-control"
                    required>
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Total USD Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="total_usd_due" autocomplete="off" type="text" placeholder="Total USD Due" class="form-control"
                    required>
            </div>
        </div>

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">USD Minimum Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="usd_minimum_due" autocomplete="off" type="text" placeholder="USD Minimum Due" class="form-control"
                    required>
            </div>
        </div>
        @else

        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Total Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="total_due" autocomplete="off" type="text" placeholder="Total Due" class="form-control" required>
            </div>
        </div>
        
        
        <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Minimum Due <span class="custom-danger">(requierd)</span></label>
            <div class="single_input ">
                <i class="fa-solid fa-credit-card"></i>
                <input name="minimum_due" autocomplete="off" type="text" placeholder="Minimum Due" class="form-control"
                    required>
            </div>
        </div>
        
        @endif

        
        <div class="condition mt-3">
            <button type="submit" class="primary_btn mb-0">Add Bill Reminder</button>
        </div>
    </form>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#active_session_id').select2();
    });
</script>
@endsection()