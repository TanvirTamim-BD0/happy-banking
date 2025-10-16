<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PushNotification;
use Illuminate\Support\Carbon;
use App\Models\User;

class PushNotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notification send.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dt = Carbon::now('Asia/Dhaka');
        $currentDate = $dt->toDateString();
        // $currentTime = $dt->hour.':'.$dt->minute;
        $currentTime = $dt->toTimeString();

        //To get single notification message with date and time...
        $data = PushNotification::where('sending_date', $currentDate)->where('sending_time', '<=', $currentTime)->where('status', false)->first();

        if($data != null){
            $title = $data->notification_title;
            $message = $data->notification_message;
            PushNotification::pushNotificationSend($title, $message);

            //To update status...
            $data->status = true;
            $data->save();
        }
    }
}
