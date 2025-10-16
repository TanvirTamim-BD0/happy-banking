<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Authenticated;
use App\Models\User;
use Carbon\Carbon;

class UserActivityServiceProvider extends ServiceProvider
{
    public $increased = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['events']->listen(Authenticated::class, function ($event) {
            $userInformation = User::where('id', $event->user->id)->first();
            $todayDate = Carbon::now()->toDateString();
            
            //To check user is exist or not...
            if(isset($userInformation) && $userInformation != null){
                $getUserActivityData = UserActivity::where('user_id', $userInformation->id)->where('date', $todayDate)->first();

                //To check user activity is exist or not...
                if(isset($getUserActivityData) && $getUserActivityData != null){
                    $this->updateUserActivity($todayDate, $userInformation->id, $getUserActivityData);
                }else{
                    $this->addUserActivity($todayDate, $userInformation->id);
                }
            }
        });
    }

    //To add new user activity with date...
    private function addUserActivity($todayDate, $userId)
    {
        $data = new UserActivity();
        $data->user_id = $userId;
        $data->start_time = Carbon::now()->toTimeString();
        $data->end_time = Carbon::now()->toTimeString();
        $data->start_activity_url = Request::url();
        $data->end_activity_url = Request::url();
        $data->date = $todayDate;
        $data->total_hit = 1;
        $data->save();
    }
    
    //To update new user activity with date...
    private function updateUserActivity($todayDate, $userId, $getUserActivityData)
    {
        $getUserActivityData->end_time = Carbon::now()->toTimeString();
        $getUserActivityData->end_activity_url = Request::url();
        $getUserActivityData->save();
    }
}
