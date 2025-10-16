@extends('frontend.master')
@section('title') MFS Expense Create @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <form class="card mb-3" action="{{route('webuser.wallet-expense-save',['expense_type' => Crypt::encrypt("Mobile Wallet Expense")])}}"
                method="post">
                @csrf
    
                <input type="hidden" id="expenseType" name="income_expense_type" value="{{$expenseType}}">

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
                </div>
                </div>
                

                 <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Account <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-coins"></i>
                    <select autocomplete="off" name="from_account_id" id="from_account_id" required class="form-control">
                        <option value="" selected disabled>Select Account</option>

                
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
                

                 <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
                <div class="single_input ">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    <input name="amount" type="number" id="amount" placeholder="Enter Amount" step="0.01" required>
                </div>
                </div>

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

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#mobile_wallet_id').select2();
        $('#from_account_id').select2();
        $('#transaction_category_id').select2();
    });


     //To fetch all the section & subject with classId...
    $("#mobile_wallet_id").change(function () {
        var mobileWalletId = $(this).val();
        var url = "{{ route('webuser.get-account-mobile-wallet-wise') }}";
        if (mobileWalletId != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    mobile_wallet_id: mobileWalletId,
                },
                success: function (data) {
                    //For Section...
                    $("#from_account_id").empty();
                    $("#from_account_id").append('<option value="" selected disabled>Select Account</option>');

                    $.each(data, function(key,value){
                        $("#from_account_id").append('<option value="'+value.id+'">'+value.account_number+'</option>');
                    });

                }

            });
        }
    });


</script>
@endsection()