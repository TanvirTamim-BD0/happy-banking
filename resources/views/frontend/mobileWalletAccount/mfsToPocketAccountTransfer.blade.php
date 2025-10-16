@extends('frontend.master')
@section('title') Bank To Wallet Account Transfer @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container">

    <div class="pay_card mb-3 mt-3 card">
        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
    
            @if(isset($accountData->mobileWalletData->image) && $accountData->mobileWalletData->image != null)
            <img src="{{asset('backend/uploads/mobileWalletImage/thumbnail/'.$accountData->mobileWalletData->image)}}"
                alt="visa_card" />
            @else
            <img src="{{asset('frontend')}}/images/bkash.png" alt="visa_card">
            @endif
    
            <div>
                <h4 class="text-dark font-16 text-capitalize">{{$accountData->mobileWalletData->mobile_wallet_name}}
                </h4>
                <p class="text-dark font-13">Bank: {{$accountData->mobileWalletData->parent_company}}</p>
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
            <form class="card mb-3" action="{{route('webuser.mfs-to-pocket-account-transfer',$accountData->id)}}"
                method="post">
                @csrf

                <input type="hidden" id="transferType" name="transfer_type" value="Own Account">
                <input type="hidden" id="paymentType" name="payment_type" value="{{$paymentType}}">
                
                <div class="form-group custom-form-group">
                <label class="custom-form-label" for="email">Amount <span class="custom-danger">(requierd)</span></label>
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
@endsection