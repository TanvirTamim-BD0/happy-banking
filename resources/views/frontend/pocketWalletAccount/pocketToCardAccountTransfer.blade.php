@extends('frontend.master')
@section('title') Bank To Bank Account Transfer @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <div class="pay_card mb-3 mt-3 card">
        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
    
            <i class=" fas fa-money-bill custom-dashboard-icon custom-pocket-card-icon"></i>
    
            <div>
                <h4 class="text-dark font-16 text-capitalize">Pocket Wallet</h4>
            </div>
        </div>
    
        <div class="d-flex align-items-center justify-content-between border-bottom mb-2">
            <div class="odd">
                <p>Balance</p>
                <p class="text-dark">{{Auth::user()->wallet}} BDT</p>
            </div>
            <div class="odd">
                <p>Number: </p>
                <p class="text-dark">{{Auth::user()->mobile}}</p>
            </div>
        </div>
    
    </div>
 

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <form class="card mb-3" action="{{route('webuser.pocket-to-card-account-transfer')}}"
                method="post">
                @csrf

                <input type="hidden" id="paymentType" name="payment_type" value="{{$paymentType}}">

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
                <label class="custom-form-label" for="email">Card <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-coins"></i>
                    <select autocomplete="off" name="to_credit_card_id" id="to_account_id" required class="form-control">
                        <option value="" selected disabled>Select Card</option>

                    </select>
                </div>
                </div>
                
                <span id="dualCurrency">
                    <div class="form-group custom-form-group">
                        <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
                        <div class="single_input ">
                            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                            <input name="amount" type="number" id="pay_amount" placeholder="Enter Amount" step="0.01" required>
                            <input type="hidden" id="currencyType" name="currency_type" value="BDT Currency">
                        </div>
                    </div>
                </span>

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Note <span class="custom-danger">(requierd)</span></label>
                <div class="single_input">
                    <textarea name="notes" type="text" id="notes" placeholder="Write Note" required></textarea>
                </div>
                </div>

                <div class="d-flex gap-2 mb-3 bg-light-green p-2"><i class="fa-solid fa-triangle-exclamation text-warning"></i>
                    <ul class="p-0 m-0">
                        @if (isset($getNote) && $getNote->description !=null)
                                <li>{!! $getNote->description !!}</li>
                        @else               
                                <li>* No Transaction Fee</li>
                                <li>* Each Transaction 30,000.00 BDT (Max) 100.00 BDT (Min)</li>
                                <li>* Each Total Amount 500,000.00 BD (Max) Single Day Transaction</li>
                        @endif
                    </ul>
                </div>

                <button class="primary_btn mb-1">Transfer Money</button>
            </form>
        </div>

            
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#bank_id').select2();
        $('#to_account_id').select2();
    });


    //To fetch all the section & subject with classId...
    $("#bank_id").change(function () {
        var bankId = $(this).val();
        var url = "{{ route('webuser.get-bank-wise-credit-card-data') }}";
        if (bankId != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    bank_id: bankId,
                },
                success: function (data) {
                    //For Section...
                    $("#to_account_id").empty();
                    $("#to_account_id").append('<option value="" selected disabled>Select Card</option>');

                    $.each(data, function(key,value){
                        $("#to_account_id").append('<option value="'+value.id+'">'+value.card_number+'</option>');
                    });

                }

            });
        }
    });


    //To fetch all the section & subject with classId...
    $("#to_account_id").change(function () {
        var creditCardId = $(this).val();
        var url = "{{ route('webuser.check-dual-currency-with-card-id') }}";
        if (creditCardId != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    credit_card_id: creditCardId,
                },
                success: function (data) {
                    //For Section...
                    $("#dualCurrency").html(data);

                }

            });
        }
    });


</script>
@endsection()