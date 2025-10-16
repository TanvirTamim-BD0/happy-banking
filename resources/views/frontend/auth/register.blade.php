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
    <title>Bank System | Create Account</title>

    <style>
        .red-border {
            border: 1px solid red !important;
        }
    </style>
</head>

<body>
    <div class="mobile_body no_scroll mb-2" style="background-color: #F4F8FB;">
        <div class="container h-100">
            <div class="header">
                <img src="{{asset('frontend')}}/images/logo.svg" alt="logo">
            </div>

            <form method="POST" action="{{route('webuser.post-register')}}"
                autocomplete="off"              
                class="needs-validation card" novalidate enctype="multipart/form-data"                 
                style="margin-bottom: 13px;">
                @csrf

                <h3 class="text-center heading_text">Registration</h3>

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Name <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input">
                        <i class="fa-solid fa-user"></i>
                        <input autocomplete="off" class="form-control" type="text" placeholder="Name" name="name"
                            required>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Mobile <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input">
                        <i class="fa-solid fa-phone"></i>

                        <input type="number" placeholder="Mobile" name="mobile" id="mobile" required
                            autocomplete="off" class="form-control">
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Email <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email" required
                            autocomplete="off" class="form-control">
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Gender <span
                            class="custom-danger">(requierd)</span></label>
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
                </div>

                @php
                 //To get all the profession data...
                 $userProfessionData = App\Models\User::getProfessionData();   
                @endphp

                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Profession <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input custom-select2-dropdown">
                        <i class="fa-solid fa-briefcase"></i>
                        <select name="profession_id" id="profession" required autocomplete="off" class="form-control">
                            <option value="" selected disabled>Select Profession</option>
                            @foreach ($userProfessionData as $item)
                                @if(isset($item) && $item != null)
                                    <option value="{{$item->id}}">{{$item->profession_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Wallet Balance <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-wallet"></i>
                        <input name="wallet" type="number" placeholder="Wallet Balance" autocomplete="off" class="form-control"
                            required>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Address <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-sharp fa-solid fa-location-dot"></i>
                        <input name="address" type="text" placeholder="Address" autocomplete="off" class="form-control"
                            required>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Password <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-lock"></i>
                        <input name="password" type="password" placeholder="Password" autocomplete="off"
                            class="form-control frontend-register-password-field" required>
                            <a href="javascript:void(0)">
                                <i class="fa-solid fa-eye frontend-password-eye-icon password-hide"></i>
                                <i class="fa-solid fa-eye-slash frontend-password-eye-icon password-show"></i>
                            </a>
                    </div>
                </div>


                <div class="form-group custom-form-group">
                    <label class="custom-form-label" for="email">Confirm Password <span
                            class="custom-danger">(requierd)</span></label>
                    <div class="single_input ">
                        <i class="fa-solid fa-lock"></i>
                        <input name="password_confirmation" type="password"
                            placeholder="Confirm Password" autocomplete="off" class="form-control" required>
                    </div>
                </div>

                <div class="condition">
                    <label class="d-flex mt-1 align-items-center gap-2" for="role"><input required
                            class="form-check-input m-0" type="checkbox" id="role">Agree with <a
                            href="{{route('privacy-policy')}}">Terms & Conditions</a></label>
                    <button type="submit" class="primary_btn custom-common-submit-button" onclick="return validate()">Registration</button>
                    <label class="mt-2 text-center d-block" for="login">Already have an account? <a
                            href="{{route('webuser.get-login')}}">login</a></label>
                </div>
            </form>

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
                    <div
                        class="menu_item {{ request()->is('webuser/blog*') ? 'active' : '' }} {{ request()->is('webuser/blog-details') ? 'active' : '' }} ">
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
        }else {
            // Handle custom validation for 'select' elements
            var selectElements = form.querySelectorAll('select[required]')
            Array.prototype.slice.call(selectElements).forEach(function (select) {
            if (select.value === '') {
                event.preventDefault() // Prevent form submission if any required select is not selected
                event.stopPropagation()
                select.classList.add('is-invalid') // Add Bootstrap's 'is-invalid' class to show red border
            } else {
                select.classList.remove('is-invalid') // Remove 'is-invalid' class if the select is now valid
            }
            })
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
    </script>


    <!--end::Custom Javascript-->
    <script src="{{asset('frontend')}}/js/jquery-3.6.4.min.js"></script>
    <script src="{{asset('frontend')}}/js/script.js"></script>
    <script src="{{asset('frontend')}}/js/bootstrap.js"></script>
    <script src="{{asset('frontend')}}/js/toastr.min.js"></script>
    <script src="{{asset('frontend')}}/js/select2.min.js"></script>
    {!! Toastr::message() !!}

    <script>
        $(document).ready(function() {
        $('#profession').select2();
    });
    </script>

    <script>
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

    <script type="text/javascript">
        function validate() {
            var profession = $("#profession").val();
            if(profession === null){
                $(".select2.select2-container.select2-container--default").addClass("red-border");
            }else{
                $(".select2.select2-container.select2-container--default").removeClass("red-border");
            }

            var mobileNumber = $("#mobile").val();
            if (mobileNumber != '' && mobileNumber.length != 11) {
                toastr.error("Mobile number must be 11 digit.");
            }
        };
    </script>

</body>

</html>