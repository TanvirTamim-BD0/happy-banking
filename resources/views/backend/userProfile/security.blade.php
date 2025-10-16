@extends('backend.master')
@section('title') Bank System | Profile @endsection
@section('styles')
@endsection
@section('content')

<div class="content">
      <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Profile <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span class="breadcump-active"> Profile</span></span>
        </div>

        <div class="container-fluid">


          <div class="row">
            @include('backend.userProfile.sidebar')
            <div class="col-md-9 mb-2">

                @include('backend.userProfile.menu')

              <div class="card">
                  <div class="card-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                      <form class="row gy-1 pt-75" action="{{route('user-security-update', Auth::user()->id )}}" method="post" enctype="multipart/form-data">
                        @csrf

                      <div class="row g-2">
                          <div class="col-md-4 fv-row">
                                <div class="d-flex flex-column fv-row">
                                  <label for="course" class="col-form-label">Old Password: <span class=" text-danger">(required)</span></label>
                                  <input type="password" name="old_password" id="old_password" class=" form-control " placeholder="Enter Old Password" required="">

                            </div>
                          </div>


                          <div class="col-md-4 fv-row">
                                <div class="d-flex flex-column fv-row">
                                  <label for="course" class="col-form-label">New Password: <span class=" text-danger">(required)</span></label>
                                  <input type="password" name="new_password" id="new_password" class=" form-control " placeholder="Enter New Password" required="">

                            </div>
                          </div>

                          <div class="col-md-4 fv-row">
                                <div class="d-flex flex-column mb-2 fv-row">
                                  <label for="course" class="col-form-label">Confirm Password: <span class=" text-danger">(required)</span></label>
                                  <input type="password" name="confirm_password" id="confirm_password" class=" form-control " placeholder="Enter Confirm Password" required="">

                            </div>
                          </div>

                      </div>

                    
                      <div class="text-start">
                          <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Update</button>
                      </div>
                  </form>
                  </div>
              </div>

            </div>
          </div>
        </div>
      </main>
    </div>


@endsection