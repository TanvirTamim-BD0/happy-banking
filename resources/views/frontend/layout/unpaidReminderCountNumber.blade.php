@php
//To get all the reminder count...
$unpaidReminderCount = App\Models\CreditCardReminder::getUnPaidCreditCardReminderCount();
@endphp

<i class="fa-solid fa-bell pointer-event custom-notification-icon"></i>
@if(isset($unpaidReminderCount) && $unpaidReminderCount > 0)
<span class="custom-notification-number text-white">{{$unpaidReminderCount}}</span>
@else
<span class="custom-notification-number text-white">0</span>
@endif