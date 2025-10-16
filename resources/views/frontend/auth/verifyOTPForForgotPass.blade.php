<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/bootstrap.min.css">
    <script src="{{asset('frontend')}}/js/fontawesome.icon.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('frontend')}}/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/toastr.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/media.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/custom.css">
    <title>Bank System | Verify OTP</title>
</head>

<body>
    <div class="mobile_body no_scroll" style="background-color: #F4F8FB;">
        <div class="header">
            <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
        </div>
        <div class="d-flex container justify-content-center flex-column h-100">
            
            <form action="{{route('webuser.forgot-pass-verify-OTP')}}" method="POST" enctype="multipart/form-data"
                autocomplete="false" class="ncs-container card mt-3" novalidate>
                @csrf
                <h3 class="mb-3 text-center heading_text">Enter verification code</h3>

                <div class="single_input mb-3">
                    <i class="fa-solid fa-envelope"></i>
                    <input autocomplete="false" type="number" placeholder="OTP Code" name="verify_code" required
                        class="form-control">
                </div>

                <button class="primary_btn mb-0 w-100 mt-3 custom-common-submit-button" type="submit">Verify OTP</button>
                <label class="mt-2 text-center d-block" for="login"><a
                        href="{{route('webuser.resend-Otp-for-password-change',$userMobile)}}">Resend OTP</a></label>
            </form>
        </div>

        <div class="bottom_nav mt-4 p-2 ">
            <div class="d-flex justify-content-center text-center">
                <a href="{{route('welcome')}}" class="col">
                    <div class="menu_item {{ request()->is('webuser/login') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </div>
                </a>

                <a href="{{route('webuser.get-login')}}" class="col">
                    <div class="menu_item 
                    {{ request()->is('webuser/login') ? 'active' : '' }}
                    {{ request()->is('webuser/password-forgot-otp-sent') ? 'active' : '' }}
                    {{ request()->is('webuser/forgot-pass-verify-OTP/*') ? 'active' : '' }}
                         ">
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
                    <div class="menu_item {{ request()->is('webuser/blog*') ? 'active' : '' }} ">
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

    <script src="{{asset('frontend')}}/js/jquery-3.6.4.min.js"></script>
    <script src="{{asset('frontend')}}/js/script.js"></script>
    <script src="{{asset('frontend')}}/js/bootstrap.js"></script>
    <!--end::Custom Javascript-->
    <script src="{{asset('frontend')}}/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>

</html>