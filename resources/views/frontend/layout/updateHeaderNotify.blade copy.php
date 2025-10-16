<div class="d-flex align-items-center gap-3 dropdown notification_first_div">
    <a class="dropdown-toggle" type="button" id="notificationCountNumber" data-bs-toggle="dropdown"
        aria-expanded="false">
        {{-- <i class="fa-solid fa-bell pointer-event custom-notification-icon"></i> --}}
        <i class="fa-solid fa-envelope-open-text pointer-event custom-notification-icon"></i>
        <span class="badge badge-danger position-relative dashboard_notification">4</span>
        @if(isset($unpaidReminderData) && $unpaidReminderData->count() > 0)
            <span class="custom-notification-number text-white">{{$unpaidReminderData->count()}}</span>
        @endif
    </a>

    <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="notificationCountNumber">
        @if(isset($unpaidReminderData) && $unpaidReminderData->count() > 0)
            @foreach ($unpaidReminderData as $singleUnpaidReminderData)
                @if(isset($singleUnpaidReminderData) && $singleUnpaidReminderData != null)
                    <li class="border-bottom">
                        <a class="dropdown-item" 
                            href="{{route('webuser.credit-card-bill-reminder-edit', ['credit_card_id'=>$singleUnpaidReminderData->credit_card_id, 'card_reminder_id'=>$singleUnpaidReminderData->id])}}">
                            Acc: {{$singleUnpaidReminderData->creditCardData->card_number}} <br>
                            @if($singleUnpaidReminderData->creditCardData->is_dual_currency == 1)
                                Bal: {{$singleUnpaidReminderData->total_bdt_due}}tk
                            @else
                                Bal: {{$singleUnpaidReminderData->total_due}}tk
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach 
            <li>
                <a class="dropdown-item text-primary pt-2" href="{{route('webuser.credit-card-wallet-account')}}">View All Upaid Reminder</a>
            </li>
        @else
        <li><a class="dropdown-item text-center" href="#">Unpaid Reminder Unavailable !</a></li>
        @endif
    </ul>
</div>



<div class="d-flex align-items-center gap-3 dropdown">
    <a class="dropdown-toggle" type="button" id="notificationCountNumber" data-bs-toggle="dropdown"
        aria-expanded="false" onclick="changeNotificationUnseenStatus()">
        <i class="fa-solid fa-bell pointer-event custom-notification-icon"></i>
        @if(isset($unpaidReminderData) && $unpaidReminderData->count() > 0)
            <span class="custom-notification-number text-white">{{$unpaidReminderData->count()}}</span>
        @endif
    </a>

    <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="notificationCountNumber">
        @if(isset($unpaidReminderData) && $unpaidReminderData->count() > 0)
            @foreach ($unpaidReminderData as $singleUnpaidReminderData)
                @if(isset($singleUnpaidReminderData) && $singleUnpaidReminderData != null)
                    <li class="border-bottom">
                        <a class="dropdown-item" 
                            href="{{route('webuser.credit-card-bill-reminder-edit', ['credit_card_id'=>$singleUnpaidReminderData->credit_card_id, 'card_reminder_id'=>$singleUnpaidReminderData->id])}}">
                            Acc: {{$singleUnpaidReminderData->creditCardData->card_number}} <br>
                            @if($singleUnpaidReminderData->creditCardData->is_dual_currency == 1)
                                Bal: {{$singleUnpaidReminderData->total_bdt_due}}tk
                            @else
                                Bal: {{$singleUnpaidReminderData->total_due}}tk
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach 
            <li>
                <a class="dropdown-item text-primary pt-2" href="{{route('webuser.credit-card-wallet-account')}}">View All Upaid Reminder</a>
            </li>
        @else
        <li><a class="dropdown-item text-center" href="#">Unpaid Reminder Unavailable !</a></li>
        @endif
    </ul>
</div>