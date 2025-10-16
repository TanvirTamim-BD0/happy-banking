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
    <title>Bank System | Create Account</title>
</head>

<body>
    <div class="mobile_body no_scroll mb-2" style="background-color: #F4F8FB;">
        <div class="container h-100">
            <div class="header">
                <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
            </div>

            <form method="POST" action="{{ route('register') }}" autocomplete="off" class="needs-validation card"
                novalidate enctype="multipart/form-data" autocomplete="off" style="margin-bottom: 13px;">
                @csrf

                <h3 class="text-center heading_text">Registration</h3>

                <div class="single_input">
                    <i class="fa-solid fa-user"></i>
                    <input autocomplete="off" class="form-control" type="text" placeholder="Name" required>
                </div>

                <div class="single_input">
                    <i class="fa-solid fa-phone"></i>
                    <input autocomplete="off" type="text" placeholder="Mobile" required class="form-control">
                </div>

                <div class="single_input">
                    <i class="fa-solid fa-envelope"></i>
                    <input autocomplete="off" type="email" placeholder="Email" required class="form-control">
                </div>


                <div class="single_input d-flex align-items-center gap-2 gender">
                    <input type="text" name="gender" id="genderValue" value="male">
                    <a onclick="genderToggle('male_btn','female_btn','male_btn')" id="male_btn"
                        class="primary_btn d-flex align-items-center justify-content-center active m-0" for="male">
                        <i class="fa-solid fa-mars"></i>Male
                    </a>

                    <a onclick="genderToggle('male_btn','female_btn','female_btn')" id="female_btn"
                        class="primary_btn d-flex align-items-center justify-content-center m-0" for="female">
                        <i class="fa-solid fa-venus"></i>Female
                    </a>

                </div>

                <div class="single_input">
                    <i class="fa-solid fa-briefcase"></i>
                    <select autocomplete="off" name="profession" id="proffesion" required class="form-control">
                        <option value="banker">Banker</option>
                        <option value="gvEmploy">Gov Employ</option>
                    </select>
                </div>

                <div class="single_input">
                    <i class="fa-solid fa-lock"></i>
                    <input autocomplete="off" type="password" placeholder="Password" required class="form-control">
                </div>

                <div class="single_input">
                    <i class="fa-solid fa-lock"></i>
                    <input autocomplete="off" type="password" placeholder="Confirm Password" required
                        class="form-control">
                </div>
                <div class="condition">
                    <label class="d-flex mt-2 align-items-center gap-2" for="role"><input required
                            class="form-check-input m-0" type="checkbox" name="role" id="role">Agree with <a
                            href="{{route('privacy-policy')}}">Terms & Conditions</a></label>
                    <button type="submit" class="primary_btn">Registration</button>
                    <label class="mt-2 text-center d-block" for="login">Already have an account? <a
                            href="{{route('login')}}">login</a></label>
                </div>
            </form>

        </div>

        <div class="bottom_nav p-2 ">
            <div class="d-flex justify-content-center text-center">
                <a href="{{route('login')}}" class="col">
                    <div class="menu_item active">
                        <i class="fa-solid fa-house"></i>
                        <p>Home</p>
                    </div>
                </a>

                <a href="{{route('login')}}" class="col">
                    <div class="menu_item">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <p>Login</p>
                    </div>
                </a>

                <a href="{{route('register')}}" class="col">
                    <div class="menu_item">
                        <i class="fa-solid fa-user-plus"></i>
                        <p>Register</p>
                    </div>
                </a>

                <a href="#" class="col">
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

</body>

</html>