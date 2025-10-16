<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{asset('frontend')}}/js/fontawesome.icon.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('frontend')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/media.css">
    <title>Bank System | Login</title>
</head>

<body>
    <div class="mobile_body no_scroll" style="background-color: #F4F8FB;">
        <div class="header">
            <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
        </div>
        <div class="h-100 justify-content-center flex-column d-flex container">
            <form action="{{route('login')}}" method="POST" enctype="multipart/form-data" autocomplete="off"
                class="needs-validation card mt-3" novalidate>
                @csrf

                <h3 class="text-center heading_text">Login</h3>

                <div class="single_input">
                    <i class="fa-solid fa-phone"></i>
                    <input autocomplete="off" type="tel" placeholder="Mobile" name="mobile" required
                        class="form-control">
                </div>


                <div class="single_input">
                    <i class="fa-solid fa-lock"></i>
                    <input autocomplete="off" type="password" placeholder="Password" name="password" required
                        class="form-control">
                </div>

                <div class="condition pt-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="d-flex align-items-center gap-2" for="role"><input type="checkbox" name="role"
                                id="role">Remember me</label>
                        <label class="mt-0" for="login"><a href="{{route('password-forgot')}}">Forget
                                Password</a></label>
                    </div>
                    <button type="submit" class="primary_btn">Login</button>
                    <label class="mt-2 text-center d-block" for="login">Don't have an account? <a
                            href="{{route('register')}}">Register</a></label>
                </div>
            </form>
        </div>

        <div class="bottom_nav p-2 ">
            <div class="d-flex justify-content-center text-center">
                <a href="dashboard.html" class="col">
                    <div class="menu_item active">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </div>
                </a>

                <a href="login.html" class="col">
                    <div class="menu_item">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <p>Login</p>
                    </div>
                </a>

                <a href="registration.html" class="col">
                    <div class="menu_item">
                        <i class="fa-solid fa-user-plus"></i>
                        <p>Register</p>
                    </div>
                </a>

                <a href="contact.html" class="col">
                    <div class="menu_item">
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

    <!--end::Custom Javascript-->
    <script src="{{asset('backend')}}/custom/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>

</html>