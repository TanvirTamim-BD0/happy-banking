@extends('frontend.master')
@section('content')
@section('styles')
@endsection
	
	<div class="h-100 container ">
		<form autocomplete="off" class="needs-validation card mt-3" action="{{route('webuser.change-password-update')}}" method="post">
			@csrf
			<h3 class="text-center heading_text pb-3">Change Password</h3>

			<div class="form-group custom-form-group">
			<label class="custom-form-label" for="email">Old Password <span class="custom-danger">(requierd)</span></label>
			<div class="single_input ">
				<i class="fa-solid fa-lock"></i>
				<input name="old_password" autocomplete="off" type="password" placeholder="Old Password" class="form-control">
			</div>
			</div>

			<div class="form-group custom-form-group">
			<label class="custom-form-label" for="email">New Password <span class="custom-danger">(requierd)</span></label>
			<div class="single_input ">
				<i class="fa-solid fa-lock"></i>
				<input name="new_password" autocomplete="off" type="password" placeholder="New Password" class="form-control">
			</div>
			</div>


			<div class="form-group custom-form-group">
			<label class="custom-form-label" for="email">Confirm Password <span class="custom-danger">(requierd)</span></label>
			<div class="single_input ">
				<i class="fa-solid fa-lock"></i>
				<input name="confirm_password" autocomplete="off" type="password" placeholder="Confirm Password" class="form-control">
			</div>
			</div>


			<div class="condition pt-2"><button type="submit" class="primary_btn">Change Password</button></div>
		</form>
	</div>


@endsection