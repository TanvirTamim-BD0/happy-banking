@extends('frontend.master')
@section('title') Credit Card Wallet Accounts Details @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">
    <div class="pay_card_wrap">
        <div class="pay_card card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card_name text-capitalize">{{$singleCreditCardData->bankData->bank_name}}</h5>

                @php
                //To get card logo...
                $cardLogo = App\Models\Creditcard::getSingleCreditCardLogo($singleCreditCardData->id);
                @endphp
                
                <img src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Card Number</p>
                    <p class="text-dark">{{$singleCreditCardData->card_number}}</p>
                </div>
                <div class="odd ">
                    <p>BDT Limit</p>
                    <p class="text-dark">
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <b>৳</b> {{$singleCreditCardData->total_bdt_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="w-70 odd">
                    <p>Total Limit</p>
                    <p class="text-dark"><b>৳</b>{{$singleCreditCardData->total_limit}}</p>
                </div>
                <div class="odd">
                    <p>USD Limit</p>
                    <p class="text-dark">
                        @if ($singleCreditCardData->is_dual_currency == true)
                        <b>$</b> {{$singleCreditCardData->total_usd_limit}}
                        @else
                        <span class="custom-card-disabled-color">Disabled</span>
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-3">
        <div class="nav custom-transfer-acccount-tab gap-2 btn-no-color nav-tabs mb-3" id="nav-tab" role="tablist">
        </div>

        <div class="tab-content pb-1" id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-bdt" role="tabpanel" aria-labelledby="nav-bdt-tab">
                @if($singleCreditCardData->is_dual_currency == true)
                    <form action="{{route('webuser.card-currency-disabled', $singleCreditCardData->id)}}" method="post" autocomplete="off"
                        class="needs-validation">
                        @csrf
                @else
                    <form action="{{route('webuser.card-currency-enabled', $singleCreditCardData->id)}}" method="post" autocomplete="off"
                        class="needs-validation">
                        @csrf
                @endif

                    <h4 class="text-center pb-3">Change Dual Currency</h4>
                    <div
                        class=" border-2 pb-1 border-bottom border-top pt-1 d-flex align-items-center justify-content-between mb-2">
                        <h5>Status</h5>
                        <h5>
                            @if($singleCreditCardData->is_dual_currency == true)
                            <span href="#" class="btn btn-primary">Enabled</span>
                            @else
                            <span href="#" class="btn btn-danger">Disabled</span>
                            @endif
                        </h5>
                    </div>

                    <div class="form-group custom-form-group">
                        <label class="custom-form-label" for="email">Total BDT Limit <span
                                class="custom-danger">(requierd)</span></label>
                        <div class="single_input ">
                            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                            <input name="total_bdt_limit" autocomplete="off" type="number" placeholder="Total BDT Limit"
                                class="form-control" {{$singleCreditCardData->is_dual_currency == true ? '' : ''}} value="{{ $singleCreditCardData->total_bdt_limit }}" required>
                        </div>
                    </div>


                    <div class="form-group custom-form-group">
                        <label class="custom-form-label" for="email">Total Usd Limit <span
                                class="custom-danger">(requierd)</span></label>
                        <div class="single_input ">
                            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                            <input name="total_usd_limit" autocomplete="off" type="number" placeholder="Total Usd Limit"
                                class="form-control" {{$singleCreditCardData->is_dual_currency == true ? '' : ''}} value="{{ $singleCreditCardData->total_usd_limit }}" required>
                        </div>
                    </div>

                    <div class="form-group custom-form-group">
                        <label class="custom-form-label" for="email">Dual Currency <span class="custom-danger">(requierd)</span></label>
                        <div class="single_input custom-select2-dropdown">
                            <i class="fa-solid fa-briefcase"></i>
                            <select autocomplete="off" name="is_dual_currency" id="is_dual_currency" required class="form-control">
                                <option value="" selected disabled>Select Dual Currency</option>
                                <option value="1" {{$singleCreditCardData->is_dual_currency == true ? 'selected' : ''}}>Enable</option>
                                <option value="0" {{$singleCreditCardData->is_dual_currency == false ? 'selected' : ''}}>Disable</option>
                            </select>
                        </div>
                    </div>

                    <div class="condition mt-3">
                        <button type="submit" class="primary_btn mb-0">Update</button>
                    </div>
                    
                </form>
            </div>

            <!-- All the credit transfer data -->
            <div class="tab-pane fade" id="nav-usd" role="tabpanel" aria-labelledby="nav-usd-tab">
                <div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Total Limit</h5>
                        <h5>USD 10000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Outstanding Balance</h5>
                        <h5>USD 5000 </h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Available Limit</h5>
                        <h5>USD 5000 </h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Payment Due</h5>
                        <h5>USD 5000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Last Due Date</h5>
                        <h5>10000</h5>
                    </div>
                    <div class=" border-2 pb-1 border-bottom d-flex align-items-center justify-content-between mb-2">
                        <h5>Minimum Pay</h5>
                        <h5>USD 10000</h5>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#is_dual_currency').select2();
    });
</script>
@endsection()