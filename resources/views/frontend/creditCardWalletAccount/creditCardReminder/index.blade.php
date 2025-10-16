@extends('frontend.master')
@section('title') Credit Card Reminder @endsection
@section('styles')
@endsection
@section('content')

<div class="h-100 container custom-main-container">

    @foreach($cardReminderData as $cardReminder)
    @if(isset($cardReminder) && $cardReminder != null)
    <div class="pay_card_wrap">

        <a href="{{route('webuser.credit-card-bill-reminder-edit', ['credit_card_id'=>$creditCardId, 'card_reminder_id'=>$cardReminder->id])}}">
            <div class="pay_card mb-3 card">
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">

                    @php
                    //To get card logo...
                    $cardLogo = App\Models\CreditCard::getSingleCreditCardLogo($cardReminder->credit_card_id);
                    @endphp

                    <img src="{{asset('frontend/images/'.$cardLogo)}}" alt="visa_card">
                    
                    <div>
                        <h5 class="card_name text-capitalize text-dark">{{$cardReminder->creditCardData->bankData->bank_name}}</h4>
                        <p class="text-dark font-13 text-capitalize">Acc: {{$cardReminder->creditCardData->card_number}}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="w-70 odd">
                        <p>BDT Due</p>
                        <p class="text-dark">
                            @if ($cardReminder->creditCardData->is_dual_currency == true)
                            <b>৳</b> {{$cardReminder->total_bdt_due}}
                            @else
                            <b>৳</b> {{$cardReminder->total_due}}
                            @endif
                        </p>
                    </div>
                    
                    <div class="odd ">
                        <p>USD Due</p>
                        <p class="text-dark">
                            @if ($cardReminder->creditCardData->is_dual_currency == true)
                            <b>$</b> {{$cardReminder->total_usd_due}}
                            @else
                            <span class="custom-card-disabled-color">Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="w-70 odd">
                        <p>Minimum Pay</p>
                        <p class="text-dark">
                            @if ($cardReminder->creditCardData->is_dual_currency == true)
                            <b>৳</b> {{$cardReminder->bdt_minimum_due}}
                            @else
                            <b>৳</b> {{$cardReminder->minimum_due}}
                            @endif
                        </p>
                    </div>
                    <div class="odd">
                        <p>Minimum Pay</p>
                        <p class="text-dark">
                            @if ($cardReminder->creditCardData->is_dual_currency == true)
                            <b>$</b> {{$cardReminder->usd_minimum_due}}
                            @else
                            <span class="custom-card-disabled-color">Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        </a>
    </div>
    @endif
    @endforeach


    @if($cardReminderData->count() > 4)
    <div class="back-to-top">
        <div class="icon">
            <a href="{{route('webuser.credit-card-bill-reminder-create',$creditCardId)}}"><i
                    class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>
    @else
    <div class="back-to-top custom-back-to-top">
        <div class="icon">
            <a href="{{route('webuser.credit-card-bill-reminder-create',$creditCardId)}}"><i
                    class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>
    @endif

</div>

@endsection