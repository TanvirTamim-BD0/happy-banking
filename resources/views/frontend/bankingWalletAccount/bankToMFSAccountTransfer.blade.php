@extends('frontend.master')
@section('title') Bank To MFS Account Transfer @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <div class="nav custom-transfer-acccount-tab gap-2 btn-no-color nav-tabs" id="nav-tab" role="tablist">
        <button class="primary_btn nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
            type="button" role="tab" aria-controls="nav-home" aria-selected="true">Own Account</button>
        <button class="primary_btn nav-link " id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
            type="button" role="tab" aria-controls="nav-profile" aria-selected="false"
            onclick="getBeneficiaryBankData()">Beneficiary Account</button>
    </div>



    <div class="pay_card card mb-3 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
    
            @if(isset($accountData->bankData->image) && $accountData->bankData->image != null)
            <img src="{{asset('backend/uploads/bankImage/thumbnail/'.$accountData->bankData->image)}}" />
            @else
            <img src="{{asset('frontend')}}/images/visa-gold.png" alt="visa_card">
            @endif
    
            <div>
                <h4 class="text-dark font-16 text-capitalize">{{$accountData->bankData->bank_name}}</h4>
                <p class="text-dark font-13">Branch: {{$accountData->branch}}</p>
            </div>
    
        </div>
    
        <div class="d-flex align-items-center justify-content-between border-bottom mb-2">
            <div class="odd">
                <p>Balance</p>
                <p class="text-dark">{{$accountData->current_balance}} BDT</p>
            </div>
            <div class="odd">
                <p>A/c Number: </p>
                <p class="text-dark">{{$accountData->account_number}}</p>
            </div>
        </div>
    
    </div>



    <input type="hidden" id="accountId" value="{{$accountData->id}}">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <form class="card mb-3" action="{{route('webuser.bank-to-mfs-account-transfer',$accountData->id)}}"
                method="post">
                @csrf

                <input type="hidden" id="transferType" name="transfer_type" value="Own Account">
                <input type="hidden" id="paymentType" name="payment_type" value="{{$paymentType}}">

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
                <label class="custom-form-label" for="email">Account  <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-coins"></i>
                    <select autocomplete="off" name="to_account_id" id="to_account_id" required class="form-control">
                        <option value="" selected disabled>Select Account</option>

                    </select>
                </div>
                </div>


                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Amount  <span class="custom-danger">(requierd)</span></label>
                <div class="single_input ">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    <input name="pay_amount" type="number" id="pay_amount" placeholder="Enter Amount" step="0.01" required>
                </div>
                </div>

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Processing Fee(Max 5%) <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                        <input name="pay_fee" type="number" id="pay_fee" placeholder="Processing Fee(Max 5%)"
                            onblur="checkProcessingFee()" step="0.01" required>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Note  <span class="custom-danger">(requierd)</span></label>
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

                <button class="primary_btn mb-1">Transfer Money</button>
            </form>
        </div>

        <!-- Beneficiary account transfer -->
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <form class="card mb-3" action="{{route('webuser.bank-to-mfs-account-transfer',$accountData->id)}}"
                method="post">
                @csrf

                <input type="hidden" id="transferType" name="transfer_type" value="Beneficiary Account">

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Mobile Wallet <span class="custom-danger">(requierd)</span></label>
                <input type="hidden" id="paymentType" name="payment_type" value="{{$paymentType}}">
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-piggy-bank"></i>
                    <select autocomplete="off" name="beneficiary_mobile_wallet_id" id="beneficiary_mobile_wallet_id" required class="form-control">
                        <option value="" selected disabled>Select Mobile Wallet</option>


                    </select>
                </div>
                </div>


                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Account <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-coins"></i>
                    <select autocomplete="off" name="to_beneficiary_account_id" id="to_beneficiary_account_id" required
                        class="form-control">
                        <option value="" selected disabled>Select Account</option>

                    </select>
                </div>
                </div>


                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
                <div class="single_input ">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    <input name="pay_amount" type="number" id="pay_amount" placeholder="Enter Amount" required>
                </div>
                </div>

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Processing Fee(Max 5%) <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                        <input name="pay_fee" type="number" id="pay_fee" placeholder="Processing Fee(Max 5%)"
                            onblur="checkProcessingFee()" step="0.01" required>
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

                <button class="primary_btn mb-1">Transfer Money</button>
            </form>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#mobile_wallet_id').select2();
        $('#beneficiary_mobile_wallet_id').select2();
        $('#to_account_id').select2();
        $('#to_beneficiary_account_id').select2();
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
                    $("#to_account_id").empty();
                    $("#to_account_id").append('<option value="" selected disabled>Select Account</option>');

                    $.each(data, function(key,value){
                        $("#to_account_id").append('<option value="'+value.id+'">'+value.account_number+'</option>');
                    });

                }

            });
        }
    });


    //To get beneficiary bank data...
    function getBeneficiaryBankData()
    {
        var url = "{{ route('webuser.get-beneficiary-mobile-wallet-data') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                //For Section...
                $("#beneficiary_mobile_wallet_id").empty();
                $("#beneficiary_mobile_wallet_id").append('<option value="" selected disabled>Select Mobile Wallet</option>');

                $.each(data, function(key,value){
                    $("#beneficiary_mobile_wallet_id").append('<option value="'+value.id+'">'+value.mobile_wallet_name+'</option>');
                });

            }

        });
    
    }


    //To fetch all the account data with bankId...
    $("#beneficiary_mobile_wallet_id").change(function () {
        var mobileWalletId = $(this).val();
        var accountId = $("#accountId").val();
        var url = "{{ route('webuser.get-mobile-wallet-wise-account-data') }}";
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
                    transfer_type: 'Beneficiary Account',
                    account_id: accountId
                },
                success: function (data) {
                    //For Section...
                    $("#to_beneficiary_account_id").empty();
                    $("#to_beneficiary_account_id").append('<option value="" selected disabled>Select Account</option>');

                    $.each(data, function(key,value){
                        $("#to_beneficiary_account_id").append('<option value="'+value.id+'">'+value.account_number+'</option>');
                    });

                }

            });
        }
    });


</script>
@endsection()