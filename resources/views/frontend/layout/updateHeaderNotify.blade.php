
@php
//To get all the notification message count...
$notificationMSGCount = App\Models\PushNotification::getUnseenNotificationMSGCount();
@endphp

<i class="fa-solid fa-envelope-open-text pointer-event custom-notification-icon"></i>
@if(isset($notificationMSGCount) && $notificationMSGCount > 0)
    <span class="badge badge-danger position-relative dashboard_notification">{{$notificationMSGCount}}</span>
@else
<span class="badge badge-danger position-relative dashboard_notification">0</span>
@endif