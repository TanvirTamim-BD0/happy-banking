@extends('frontend.master')
@section('title') MFS To Credit Card Account Transfer @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <div class="pay_card_wrap">
        <div class="pay_card card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card_name text-capitalize text-dark">{{$accountData->bankData->bank_name}}</h4>
                @php
                //To get card logo...
                $cardLogo = App\Models\Creditcard::getSingleCreditCardLogo($accountData->id);
                @endphp
            
                <img src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Card Number</p>
                    <p class="text-dark">{{$accountData->card_number}}</p>
                </div>
                <div class="odd ">
                    <p>BDT Limit</p>
                    <p class="text-dark">
                        @if ($accountData->is_dual_currency == true)
                        <b>৳</b> {{$accountData->total_bdt_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Total Limit</p>
                    <p class="text-dark"><b>৳</b>
                        {{$accountData->total_limit}}
                    </p>
                </div>
                <div class="odd">
                    <p>USD Limit</p>
                    <p class="text-dark">
                        @if ($accountData->is_dual_currency == true)
                        <b>$</b> {{$accountData->total_usd_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" id="accountId" value="{{$accountData->id}}">

    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

            <form class="card mb-3" action="{{route('webuser.mfs-to-credit-card-account-transfer',$accountData->id)}}"
                method="post">
                @csrf

                <input type="hidden" id="transferType" name="transfer_type" value="Own Account">
                <input type="hidden" id="paymentType" name="payment_type" value="{{$paymentType}}">
                <input type="hidden" id="currencyType" name="currency_type" value="BDT Currency">

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Mobile Wallet <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-piggy-bank"></i>
                    <select autocomplete="off" name="mobile_wallet_id" id="mobile_wallet_id" required
                        class="form-control">
                        <option value="" selected disabled>Select From Mobile Wallet</option>

                        @foreach($getMobileWalletData as $mobileWalletData)
                        @if(isset($mobileWalletData) && $mobileWalletData != null)
                        <option value="{{$mobileWalletData->id}}">{{$mobileWalletData->mobile_wallet_name}}</option>
                        @endif
                        @endforeach

                    </select>
                </div>
                </div>

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">From Account <span class="custom-danger">(requierd)</span></label>
                <div class="single_input custom-select2-dropdown">
                    <i class="fa-solid fa-coins"></i>
                    <select autocomplete="off" name="from_account_id" id="from_account_id" required class="form-control">
                        <option value="" selected disabled>Select From Account</option>


                    </select>
                </div>
                </div>
                
                @if($accountData->is_dual_currency == true)
                <nav class="mb-3">
                    <div class="nav nav-tabs custom-transfer-acccount-tab gap-2 btn-no-color pb-2" id="nav-tab" role="tablist">
                        <button class="text-white primary_btn m-0 nav-link active" id="nav-bdt-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-bdt" type="button" role="tab" aria-controls="nav-bdt" aria-selected="true"
                            onclick="changeCurrency('BDT Currency')">BDT
                            Currency </button>
                        <button class="text-white primary_btn m-0 nav-link" id="nav-usd-tab" data-bs-toggle="tab" data-bs-target="#nav-usd"
                            type="button" role="tab" aria-controls="nav-usd" aria-selected="false"
                            onclick="changeCurrency('USD Currency')">USD Currency </button>
                
                    </div>
                </nav>
                
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-bdt" role="tabpanel" aria-labelledby="nav-bdt-tab">

                        <div class="form-group custom-form-group">
                            <label class="custom-form-label" for="email">BDT Amounts <span class="custom-danger">(requierd)</span></label>
                            <div class="single_input ">
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                <input name="pay_amount_bdt" type="number" id="pay_amount_bdt" placeholder="BDT Amounts" step="0.01">
                    
                                @error('pay_amount_bdt')
                                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                
                    <div class="tab-pane" id="nav-usd" role="tabpanel" aria-labelledby="nav-usd-tab">

                        <div class="form-group custom-form-group">
                            <label class="custom-form-label" for="email">USD Amount <span class="custom-danger">(requierd)</span></label>
                            <div class="single_input ">
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                <input name="pay_amount_usd" type="number" id="pay_amount_usd" placeholder="USD Amount"
                                onblur="converUSDToBDTCurrency()" step="0.01">
                    
                                @error('pay_amount_usd')
                                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group custom-form-group">
                            <label class="custom-form-label" for="email">1 USD In BDT <span class="custom-danger">(requierd)</span></label>
                            <div class="single_input ">
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                <input name="usd_in_bdt_rate" type="number" id="usd_in_bdt_rate" step="0.01" placeholder="1 USD In BDT"
                                    onblur="converUSDToBDTCurrency()">
                        
                                @error('usd_in_bdt_rate')
                                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group custom-form-group">
                            <label class="custom-form-label" for="email">Converted BDT Amount <span
                                    class="custom-danger">(requierd)</span></label>
                            <div class="single_input ">
                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                <input class="custom-readonly-color" name="pay_amount_convert_bdt" type="number" id="pay_amount_convert_bdt"
                                    placeholder="Converted BDT Amount" value="0.00" step="0.01" readonly>
                        
                                @error('pay_amount_convert_bdt')
                                <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                @else
                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">BDT Amount <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                        <input name="pay_amount" type="number" id="pay_amount" placeholder="BDT Amount" step="0.01" required>
                    
                        @error('pay_amount')
                        <span class="custom-text-danger custom-text-danger-position">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                @endif


                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Processing Fee(Max 5%) <span class="custom-danger">(requierd)</span></label>
                <div class="single_input ">
                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                    <input name="pay_fee" type="number" id="pay_fee" placeholder="Processing Fee(Max 5%)"
                        onblur="checkProcessingFee()" required>
                </div>
                </div>

                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Note <span class="custom-danger">(requierd)</span></label>
                <div class="single_input"><textarea name="notes" type="text" id="notes" placeholder="Write Note" required></textarea></div>
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
        $('#mobile_wallet_id').select2();
        $('#from_account_id').select2();
    });

    //To fetch all the section & subject with classId...
    $("#mobile_wallet_id").change(function () {
        var mobileWalletId = $(this).val();
        var accountId = $("#accountId").val();
        var url = "{{ route('webuser.get-account-data-with-mobile-wallet-wise-for-credit-card') }}";
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
                    transfer_type: 'Own Account',
                    accountId: accountId
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

    //To check processing fee...
    function checkProcessingFee() {
        var payFee = $('#pay_fee').val();
        
        if(payFee > 5){
            toastr.error('Processign fee must be less than 5%.');
            $('#pay_fee').val(5);
        }
        if(payFee < 0){
            toastr.error('Processign fee can not be less than 0%.');
            $('#pay_fee').val(0);
        }
    }

    //To change currency...
    function changeCurrency(currencyType){
        $("#currencyType").val(currencyType);
    }
    
    //To convert usd amount to bdt amount...
    function converUSDToBDTCurrency() {
        var usdAmount = $('#pay_amount_usd').val();
        if(usdAmount != ''){
            var BDTRate = $('#usd_in_bdt_rate').val();
            var totalBdtAmount = (usdAmount * BDTRate).toFixed(2);
            $('#pay_amount_convert_bdt').val(totalBdtAmount);
        }else{
            toastr.error('First fillup USD amount.!');
            $('#usd_in_bdt_rate').val('');
        }
    }

</script>
@endsection()