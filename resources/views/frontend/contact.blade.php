<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/bootstrap.min.css">
    <script src="{{asset('frontend')}}/js/fontawesome.icon.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('frontend')}}/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/toastr.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/media.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/custom.css">
    <title>Bank System | Blog</title>
</head>

<body>
    <div class="mobile_body no_scroll mb-2" style="background-color: #F4F8FB;">

    	<div class="header">
            <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
        </div>

        <div class="container h-100 mt-3">


	    <div class="pay_card_wrap">
	        <a href="#">
	            <div class="pay_card mb-3 card">
	                 <div class="d-flex align-items-center justify-content-between  mb-2">
	                    <div class="odd">
	                    	<h4 class="text-center">About Us</h4>
	                        <p class="text-dark mt-2">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. Lorem ipsum may be used as a placeholder before final copy is available.  </p>
	                    </div>
	                </div>
	            </div>
	        </a>
	    </div>


	    <div class="pay_card_wrap">
	        <a href="#">
	            <div class="pay_card mb-3 card">
	                 <div class="d-flex justify-content-between align-items-center mb-2 pb-3">

                    <div class="row">
                        <div class="col-md-2">
                             <i class="fa-solid fa-map"></i>
                        </div>

                        <div class="col-md-10">
                            <h4 class="text-dark font-16 text-capitalize">Wb Softwares</h4>
                        </div>

                        <div class="col-md-2">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div class="col-md-10">
                            <h4 class="text-dark font-16 text-capitalize">House# 433, Flat# 3B, Road# 30, New DOHS Mohakhali, Dhaka</h4>
                        </div>

                    </div>


                </div>
	            </div>
	        </a>
	    </div>




        </div>

        <div class="bottom_nav p-2 ">
            <div class="d-flex justify-content-center text-center">
                <a href="{{route('welcome')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/login') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </div>
                </a>

                <a href="{{route('webuser.get-login')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/login') ? 'active' : '' }} ">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <p>Login</p>
                    </div>
                </a>

                <a href="{{route('webuser.get-register')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/register') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-plus"></i>
                        <p>Register</p>
                    </div>
                </a>

                 <a href="{{route('webuser.blog')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/blog*') ? 'active' : '' }} {{ request()->is('webuser/blog-details') ? 'active' : '' }} ">
                        <i class="fa-solid fa-book"></i>
                        <p>Blog</p>
                    </div>
                </a>

                <a href="{{route('webuser.contact')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/contact') ? 'active' : '' }} ">
                        <i class="fa-solid fa-phone"></i>
                        <p>Contact</p>
                    </div>
                </a>
            </div>
        </div>

        
    </div>

    <!-- javascript -->
    <script src="{{asset('frontend')}}/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('frontend')}}/js/jquery-3.6.4.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
    </script>


<!--end::Custom Javascript-->
<script src="{{asset('frontend')}}/js/select2.min.js"></script>
<script src="{{asset('frontend')}}/js/toastr.min.js"></script>
{!! Toastr::message() !!}

<script>
    $(document).ready(function() {
        $('#profession').select2();
    });
</script>

</body>

</html>