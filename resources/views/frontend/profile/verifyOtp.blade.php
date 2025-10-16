@extends('frontend.master')
@section('styles')
@endsection
@section('content')
	

	<div class="container h-100">
            <div class="card">
                <h3 class="text-center heading_text">Enter Verification Code</h3>
                <form action="{{route('webuser.mobile-change-verify-OTP')}}" method="POST" enctype="multipart/form-data"
                    autocomplete="false" class="ncs-container mt-3" novalidate>
                    @csrf
                    <input type="hidden" name="mobile" value="{{$mobile}}">

                    <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">OTP Code <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input">
                        <i class="fa-solid fa-envelope"></i>
                        <input autocomplete="false" type="number" placeholder="OTP Code" name="verify_code" required class="form-control">
                    </div>
                </div>
                            
                    <button class="primary_btn mb-0 w-100 mt-3" type="submit">Verify OTP</button>
                    @php
                        $newMobile = Crypt::encrypt($mobile);
                        $userPreMobile = Crypt::encrypt($userData->mobile);
                    @endphp
                    <label class="mt-2 text-center d-block" for="login"><a
                            href="{{route('webuser.resend-Otp-profile',['mobile' => $newMobile, 'user_mobile' => $userPreMobile])}}">Resend OTP</a></label>
                </form>
            </div>
        </div>

@endsection