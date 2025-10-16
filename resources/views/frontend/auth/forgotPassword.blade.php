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
    <title>Bank System | Forgot Password</title>
</head>

<body>
    <div class="mobile_body no_scroll" style="background-color: #F4F8FB;">
        <div class="header">
            <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
        </div>
        <div class="h-100 justify-content-center flex-column d-flex container">
            <form action="{{route('webuser.password-forgot-otp-sent')}}" method="POST" enctype="multipart/form-data" autocomplete="off"
                class="needs-validation card mt-3" novalidate>
                @csrf

                <h3 class="text-center heading_text pb-3">Forgot Password</h3>

                <div class="single_input">
                    <i class="fa-solid fa-phone"></i>
                    <input autocomplete="off" type="number" placeholder="Mobile" name="mobile" id="mobile" required
                        class="form-control">
                </div>

                <div class="condition pt-3">
                    <button type="submit" class="primary_btn custom-common-submit-button" onclick="return checkValidate()">Verification Code Sent</button>
                    <label class="mt-2 text-center d-block" for="login">Don't have an account? <a
                            href="{{route('webuser.get-register')}}">Register</a></label>
                </div>
            </form>
        </div>

        <div class="bottom_nav mt-4 p-2">
            <div class="d-flex justify-content-center text-center">
                <a href="{{route('welcome')}}" class="col">
                    <div class="menu_item 
                    {{ request()->is('webuser/login') ? 'active' : '' }}
                    ">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </div>
                </a>

                <a href="{{route('webuser.get-login')}}" class="col">
                    <div class="menu_item 
                    {{ request()->is('webuser/login') ? 'active' : '' }}
                    {{ request()->is('webuser/password-forgot') ? 'active' : '' }}
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

    </body>

</html>