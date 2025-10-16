




@extends('backend.master')
@section('title') Bank System | Push Notification Update @endsection
@section('styles')
<style>
</style>
@endsection
@section('content')


<div class="content tools">
    <main>
        <section>
            <div class="container-fluid mt-3">

                <div class="align-items-center pb-2 pt-2">
                    <h3 class="breadcump-header">Push Notification <span class="divider"></span></h3>
                    <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span
                            class="breadcump-active"> Update</span></span>
                </div>


                <div class="card_wrap">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card-body card p-3">
                                <div class="card-content custom-card-content-for-datatable pb-5 pt-2">
                                    <h2 class="card-title">Push Notification Update</h2>
                                    <div class="float-right justify-content-end">
                                        <a class="custom-datatable-add-btn" href="{{route('push-notification.index')}}">
                                            <i class="fa fa-list"></i> Notification List
                                        </a>
                                    </div>
                                </div>

                                <form class="gy-1 pt-75" action="{{route('push-notification.update', $singlePushNotification->id)}}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf
                                    @method('put')
                                    <div class="row mt-4">
                                        <div class="col-md-6 mb-3 custom-select2-dropdown">
                                            <div class="single_input">
                                                <label for="sending_date" class="fw-bolder mb-1">Sending Date <span
                                                        class="text-danger custom-required-font">(Required)</span></label>

                                                <input type="text" placeholder="Sending Date" name="sending_date"
                                                    id="sending_date" class="form-control"
                                                    onblur="inputValidator(this.value,'warnTextDate','text')" 
                                                    value="{{$sendingDate}}" required>

                                                <span id="warnTextDate" class="text-danger warn">Sending date is
                                                    required</span>

                                                @error('sending_date')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="single_input">
                                                <label for="sending_time" class="fw-bolder mb-1">Sending Time <span
                                                        class="text-danger custom-required-font">(Required)</span></label>

                                                <input type="text" placeholder="Sending Time" name="sending_time"
                                                    id="sending_time" class="form-control flatpickr-input active mb-2"
                                                    onblur="inputValidator(this.value,'warnTextTime','text')" 
                                                    value="{{$singlePushNotification->sending_time}}"required>

                                                <span id="warnTextTime" class="text-danger warn">Sending time is
                                                    required</span>

                                                @error('sending_time')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3 custom-select2-dropdown">
                                            <div class="single_input">
                                                <label for="notification_title" class="fw-bolder mb-1">Notification
                                                    Title <span
                                                        class="text-danger custom-required-font">(Required)</span></label>
                                                <input onblur="inputValidator(this.value,'warnTextTitle','text')"
                                                    placeholder="Notification Title" class="form-control" type="text"
                                                    name="notification_title" id="notification_title" 
                                                    value="{{$singlePushNotification->notification_title}}"required>
                                                <span id="warnTextTitle" class="text-danger warn">Title is
                                                    required</span>

                                                @error('notification_title')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <div class="single_input">
                                                <label for="notification_message" class="fw-bolder mb-1">Notification
                                                    Message <span
                                                        class="text-danger custom-required-font">(Required)</span></label>

                                                <textarea name="notification_message" class="form-control"
                                                    id="notification_message" cols="30" rows="5"
                                                    onblur="inputValidator(this.value,'warnTextMessage','text')"
                                                    placeholder="Enter Message">{{$singlePushNotification->notification_message}}</textarea>

                                                <span id="warnTextMessage" class="text-danger warn">Title is
                                                    required</span>

                                                @error('notification_message')
                                                <span class=text-danger>{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" id="kt_modal_new_target_submit"
                                            class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

@endsection

@section('scripts')
<script>
    flatpickr("#sending_date", {
        dateFormat: "d-m-Y",
        allowInput: true
      });

      flatpickr("#sending_time", {
        enableTime: true,
        noCalendar: true,
        allowInput: true
      });
</script>
@endsection()