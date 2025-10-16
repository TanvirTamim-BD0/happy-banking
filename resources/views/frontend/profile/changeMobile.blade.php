@extends('frontend.master')
@section('content')
@section('styles')
@endsection
	
	<div class="h-100 container">
		<form autocomplete="off" class="needs-validation card mt-3" action="{{route('webuser.change-mobile-update')}}" method="post">
			@csrf
			<h3 class="text-center heading_text pb-3">Update Mobiles</h3>

			<div class="form-group custom-form-group">
            <label class="custom-form-label" for="email">Mobile <span class="custom-danger">(requierd)</span></label>
			<div class="single_input ">
				<i class="fa-solid fa-phone"></i>
				<input name="mobile" id="mobile" autocomplete="off" type="number" placeholder="Mobile" class="form-control" required>
			</div>
			</div>

			<div class="condition pt-2"><button type="submit" class="primary_btn" onclick="return checkValidate()">Send OTP</button></div>
		</form>
	</div>

@endsection

@section('scripts')
<script>
	function checkValidate() {
            var mobileNumber = $("#mobile").val();
    
            if (mobileNumber != '' && mobileNumber.length != 11) { 
                event.preventDefault();
                toastr.error("Mobile number must be 11 digit."); 
            }
    
            return true;
        }
</script>
@endsection