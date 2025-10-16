<div class="header d-flex align-items-center justify-content-between bg-primary text-white mb-3 mr-3 ml-3">
    <a href="{{route('webuser.dashboard')}}"><i class="fa-solid fa-house custom-home-icon"></i></a>
    <div>
        <img class="logo-image" src="{{asset('frontend')}}/images/Untitled-4.png" alt="visa_card">
    </div>
    @php
        //To get all the notification message...
        $notificationMSG = App\Models\PushNotification::getUnseenNotificationMSG();
        $notificationMSGCount = App\Models\PushNotification::getUnseenNotificationMSGCount();
        $unpaidReminderData = App\Models\CreditCardReminder::getUnPaidCreditCardReminder();
        $unpaidReminderCount = App\Models\CreditCardReminder::getUnPaidCreditCardReminderCount();
    @endphp

    <div class="dashboard_notification d-flex">
        <div class="d-flex align-items-center gap-3 dropdown notification_first_div">
            <a class="dropdown-toggle custom-headernotification-icon-left" type="button" id="notificationCountNumber" data-bs-toggle="dropdown"
                aria-expanded="false" onclick="changeNotificationUnseenStatus()">
                <i class="fa-solid fa-envelope-open-text pointer-event custom-notification-icon"></i>
                @if(isset($notificationMSGCount) && $notificationMSGCount > 0)
                    <span class="badge badge-danger position-relative dashboard_notification">{{$notificationMSGCount}}</span>
                @else
                 <span class="badge badge-danger position-relative dashboard_notification">0</span>
                @endif
            </a>
    
            <ul class="dropdown-menu custom-dropdown-menu shadow" id="notification-unorder-list" aria-labelledby="notificationCountNumber">
                @if(isset($notificationMSG) && $notificationMSG->count() > 0)
                    @foreach ($notificationMSG as $singlenotificationMSG)
                        @if(isset($singlenotificationMSG) && $singlenotificationMSG != null)
                            <li class="px-3 py-1 border-bottom">
                                <a href="javascript:void(0);" class="text-dark">
                                    <strong class="text-sm"> {{ Str::limit($singlenotificationMSG->notification_title, 18) }}</strong>
                                    <p>
                                        <small>  {{ Str::limit($singlenotificationMSG->notification_message, 25) }} </small> 
                                    </p>
                                </a>
                            </li>
                        @endif
                    @endforeach 
                    <li>
                        <a class="dropdown-item text-primary pt-2" id="view_all_notification" href="{{ route('webuser.notification-all-data') }}">View All Notification</a>
                    </li>
                @else
                <li><a class="dropdown-item text-center" href="#">Notification Unavailable !</a></li>
                @endif
            </ul>
        </div>


        <div class="d-flex align-items-center gap-3 dropdown">
            <a class="dropdown-toggle" type="button" id="unpaidReminderCountNumber" data-bs-toggle="dropdown"
                aria-expanded="false" onclick="changeReminderUnseenStatus()">
                <i class="fa-solid fa-bell pointer-event custom-notification-icon"></i>
                @if(isset($unpaidReminderCount) && $unpaidReminderCount > 0)
                    <span class="custom-notification-number text-white">{{$unpaidReminderCount}}</span>
                @else
                    <span class="custom-notification-number text-white">0</span>
                @endif
            </a>
    
            <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="unpaidReminderCountNumber">
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
    </div>
</div>

<script>
    //  To change notification status...
    function changeNotificationUnseenStatus() {
        var url = "{{ route('webuser.change-push-notification-is-seen') }}";

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: url,
            data: {
            'is_seen' : true
            },
            success: function (data) {
                document.getElementById("notificationCountNumber").removeAttribute("onclick");
                $("#notificationCountNumber").html(data);
            }

        });
    }
    
    //  To change notification status...
    function changeReminderUnseenStatus() {
        var url = "{{ route('webuser.change-unpaid-reminder-is-seen') }}";

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: url,
            data: {
            'is_seen' : true
            },
            success: function (data) {
                document.getElementById("unpaidReminderCountNumber").removeAttribute("onclick");
                $("#unpaidReminderCountNumber").html(data);
            }

        });
    }
</script>
