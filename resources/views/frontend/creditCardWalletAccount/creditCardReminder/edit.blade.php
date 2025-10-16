@extends('frontend.master')
@section('title') Update Credit Card Bill Reminder @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <nav class="mb-2">
        <div class="nav nav-tabs custom-transfer-acccount-tab gap-2 btn-no-color pb-2" id="nav-tab" role="tablist">
            <button class="text-white primary_btn m-0 nav-link active" id="nav-pay-tab" data-bs-toggle="tab"
                data-bs-target="#nav-bdt" type="button" role="tab" aria-controls="nav-bdt" aria-selected="true"
                onclick="changeCurrency('BDT Currency')">Bill Payment</button>
            <button class="text-white primary_btn m-0 nav-link" id="nav-reminder-tab" data-bs-toggle="tab"
                data-bs-target="#nav-usd" type="button" role="tab" aria-controls="nav-usd" aria-selected="false"
                onclick="changeCurrency('USD Currency')">Update Reminder</button>
    
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-bdt" role="tabpanel" aria-labelledby="nav-pay-tab">
    
            <form action="{{route('webuser.credit-card-bill-reminder-status-change')}}" method="post" autocomplete="off"
                class="needs-validation card" style="margin-bottom: 13px;">
                @csrf
                <h3 class="text-center heading_text pb-3">Card Bill Reminder</h3>
                <input type="hidden" name="credit_card_id" value="{{$creditCardId}}">
                <input type="hidden" name="credit_card_reminder_id" value="{{$singleCardReminderData->id}}">
            
                @php
                //To get single credit card reminder data...
                $singleCreditCardData = App\Models\CreditCardReminder::getSingleCreditCardReminderData($creditCardId);
                @endphp

                <div class=" border-2 pb-1 border-bottom border-top pt-1 d-flex align-items-center justify-content-between mb-2">
                    <h5>Status</h5>
                    <h5>
                        @if($singleCardReminderData->status == true)
                            <span href="#" class="btn btn-primary">Paid</span>
                        @else
                            <span href="#" class="btn btn-danger">Unpaid</span>
                        @endif
                    </h5>
                </div>
            
                <div class="condition mt-3">
                    @if($singleCardReminderData->status == true)
                        <button type="submit" class="btn btn-danger w-100 mb-0">Change To Bill UnPaid</button>
                    @else
                        <button type="submit" class="btn btn-primary w-100 mb-0">Change To Bill Paid</button>
                    @endif
                    
                </div>
            </form>

        </div>
    
        <div class="tab-pane" id="nav-usd" role="tabpanel" aria-labelledby="nav-reminder-tab">
    
            <form action="{{route('webuser.credit-card-bill-reminder-update')}}" method="post" autocomplete="off"
                class="needs-validation card" style="margin-bottom: 13px;">
                @csrf
                <h3 class="text-center heading_text pb-3">Card Bill Reminder</h3>
                <input type="hidden" name="credit_card_id" value="{{$creditCardId}}">
                <input type="hidden" name="credit_card_reminder_id" value="{{$singleCardReminderData->id}}">
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Active Session <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input custom-select2-dropdown">
                        <i class="fa-solid fa-piggy-bank"></i>
                        <select autocomplete="off" name="active_session_id" id="active_session_id" required class="form-control">
                            <option value="" selected disabled>Select Active Session</option>
            
                            @if(isset($activeSessionData) && $activeSessionData != null)
                            <option value="{{$activeSessionData->id}}"
                                {{$singleCardReminderData->active_session_id == $activeSessionData->id ? 'selected' : ''}}>
                                {{$activeSessionData->session_name}}</option>
                            @endif
            
                        </select>
                    </div>
                </div>
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Last Payment Date<span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input type="text" autocomplete="off" class="form-control form-control-solid flatpickr-input active"
                            name="last_payment_date" id="last_payment_date" placeholder="Last Payment Date"
                            value="{{$lastPaymentDate}}" required>
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
                        <input name="total_bdt_due" autocomplete="off" type="text" placeholder="Total BDT Due" class="form-control"
                            value="{{$singleCardReminderData->total_bdt_due}}" required>
                    </div>
                </div>
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">BDT Minimum Due <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input name="bdt_minimum_due" autocomplete="off" type="text" placeholder="BDT Minimum Due"
                            class="form-control" value="{{$singleCardReminderData->bdt_minimum_due}}" required>
                    </div>
                </div>
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Total USD Due <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input name="total_usd_due" autocomplete="off" type="text" placeholder="Total USD Due" class="form-control"
                            value="{{$singleCardReminderData->total_usd_due}}" required>
                    </div>
                </div>
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">USD Minimum Due <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input name="usd_minimum_due" autocomplete="off" type="text" placeholder="USD Minimum Due"
                            class="form-control" value="{{$singleCardReminderData->usd_minimum_due}}" required>
                    </div>
                </div>
                @else
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Total Due <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input name="total_due" autocomplete="off" type="text" placeholder="Total Due" class="form-control"
                            value="{{$singleCardReminderData->total_due}}" required>
                    </div>
                </div>
            
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Minimum Due <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-credit-card"></i>
                        <input name="minimum_due" autocomplete="off" type="text" placeholder="Minimum Due" class="form-control"
                            value="{{$singleCardReminderData->minimum_due}}" required>
                    </div>
                </div>
            
                @endif
            
            
                <div class="condition mt-3">
                    <button type="submit" class="primary_btn mb-0">Update Bill Reminder</button>
                </div>
            </form>
    
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#active_session_id').select2();
    });
</script>
@endsection()