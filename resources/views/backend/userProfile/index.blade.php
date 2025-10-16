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
                      <form class="row gy-1 pt-75" action="{{route('user-profile.update', Auth::user()->id )}}" method="post" enctype="multipart/form-data">
                        @csrf

                      <div class="row g-2">
                          <div class="col-md-6 fv-row">
                                <div class="d-flex flex-column fv-row">
                                  <label for="course" class="col-form-label">Name: <span class=" text-danger">(required)</span></label>
                                  <input type="text" name="name" id="name" class=" form-control " placeholder="Enter Name" required="" value="{{ Auth::guard()->user()->name}}">

                            </div>
                          </div>


                          <div class="col-md-6 fv-row">
                                <div class="d-flex flex-column fv-row">
                                  <label for="course" class="col-form-label">Email: <span class=" text-danger">(required)</span></label>
                                  <input type="email" name="email" id="email" class=" form-control " placeholder="Enter Email" required="" value="{{ Auth::guard()->user()->email}}">

                            </div>
                          </div>

                          <div class="col-md-6 fv-row">
                                <div class="d-flex flex-column mb-2 fv-row">
                                  <label for="course" class="col-form-label">Mobile: <span class=" text-danger">(required)</span></label>
                                  <input type="text" name="mobile" id="mobile" class=" form-control " placeholder="Enter Mobile" required="" value="{{ Auth::guard()->user()->mobile}}">

                            </div>
                          </div>

                          <div class="col-md-6">
                              <div id="card_body_5" class="card-body my-2 mb-3 position-relative">
                                  <div style="width:100px; height:100px" class="select_imgWith_preview">

                                    @if(isset(Auth::guard()->user()->image))
                                    <img id="uploadPreview" src="{{ asset('backend/uploads/userProfile/'. Auth::guard()->user()->image) }}"/>
                                    @else
                                    <img id="uploadPreview" src="{{asset('backend')}}/uploads/userProfile/default.png"/>
                                    @endif

                                    <input id="uploadImage" type="file" name="image" onchange="PreviewImage('uploadImage','uploadPreview');" />
                                    <i onclick="cancelPreview('uploadPreview')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                                  </div>
                                </div>
                          </div>

                      </div>

                    
                      <div class="text-start">
                          <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Update</button>
                          <button type="reset" id="kt_modal_new_target_cancel" data-bs-dismiss="modal" class="btn btn-light me-3">Cancel</button>
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