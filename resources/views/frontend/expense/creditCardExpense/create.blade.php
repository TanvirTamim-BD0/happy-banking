@extends('frontend.master')
@section('title') Credit Card Expense Create @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    
    <div class="tab-content mt-3" id="nav-tabContent">
        <form class="card mb-3" action="{{route('webuser.wallet-expense-save',['expense_type' => Crypt::encrypt("Credit Card Expense")])}}"
            method="post">
            @csrf

            <input type="hidden" id="expenseType" name="income_expense_type" value="{{$expenseType}}">

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
            <label class="custom-form-label" for="email">Credit Card <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-piggy-bank"></i>
                <select autocomplete="off" name="from_credit_card_id" id="from_credit_card_id" required class="form-control">
                    <option value="" selected disabled>Select Credit Card</option>
            
                  
                </select>
            </div>
            </div>
        
            <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Category <span class="custom-danger">(requierd)</span></label>
            <div class="single_input custom-select2-dropdown">
                <i class="fa-solid fa-list"></i>
                <select autocomplete="off" name="transaction_category_id" id="transaction_category_id" required class="form-control">
                    <option value="" selected disabled>Select Category</option>

                    @foreach($transactionCategoryData as $transactionCategory)
                    @if(isset($transactionCategory) && $transactionCategory != null)
                    <option value="{{$transactionCategory->id}}">{{$transactionCategory->category_name}}</option>
                    @endif
                    @endforeach
            
                </select>
            </div>
            </div>

            <span id="dualCurrency">
                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
                <div class="single_input ">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    <input name="amount" type="number" id="amount" placeholder="BDT Amount" required>
                    <input type="hidden" id="currencyType" name="currency_type" value="BDT Currency" step="0.01">
                </div>
                </div>
            </span>

            <div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Note <span class="custom-danger">(requierd)</span></label>
            <div class="single_input">
                <textarea name="notes" type="text" id="notes" placeholder="Write Note" required></textarea>
            </div>
            </div>

            <div class="d-flex gap-2 mb-3 bg-light-green p-2"><i
                    class="fa-solid fa-triangle-exclamation text-warning"></i>
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

            <button class="primary_btn mb-1">Add Expense</button>
        </form>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#from_credit_card_id').select2();
        $('#transaction_category_id').select2();
        $('#from_credit_card_id_usd').select2();
        $('#transaction_category_id_usd').select2();
        $('#bank_id').select2();
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
                    $("#from_credit_card_id").empty();
                    $("#from_credit_card_id").append('<option value="" selected disabled>Select Card</option>');

                    $.each(data, function(key,value){
                        $("#from_credit_card_id").append('<option value="'+value.id+'">'+value.card_number+'</option>');
                    });

                }

            });
        }
    });
    

    //To fetch all the section & subject with classId...
    $("#from_credit_card_id").change(function () {
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