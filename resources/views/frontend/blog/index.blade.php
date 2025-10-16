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

        <div class="container h-100">

        <div class="pay_card_wrap">
            <a href="#">
                <div class="pay_card mb-3 card">
                     <div class="d-flex align-items-center justify-content-between  mb-2">
                        <div class="odd">
                            <h4 class="text-center">Blog</h4>
                            <p class="text-dark mt-2">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual.  </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        @foreach($blogData as $blog)
   		@if(isset($blog) && $blog != null)
	    <div class="pay_card_wrap">
	        <a href="{{route('webuser.blog-details',$blog->id)}}">
	            <div class="pay_card mb-3 card pay_card_blog">
	                <div class="d-flex justify-content-between align-items-center mb-2  pb-1">

	                    @if(isset($blog->image) && $blog->image != null)
	                    <img src="{{asset('backend/uploads/blog/'.$blog->image)}}" />
	                    @else
	                    <img src="{{asset('frontend')}}/images/visa-gold.png" alt="visa_card">
	                    @endif

	                </div>

	                <div class="d-flex align-items-center justify-content-between mb-2">
	                    <div class="odd">
	                        <h6 class="text-dark">{{$blog->title}}</h6>
	                    </div>
	                </div>

	                <div class="d-flex align-items-center justify-content-between border-bottom mb-2">
	                    <div class="odd mb-2">
	                        <p>{{Carbon\Carbon::createFromFormat('Y-m-d', $blog->created_at->toDateString())->format('d-m-Y')}} </p>
	                    </div>

	                    <div class="odd mb-2">
	                        <a href="{{route('webuser.blog-category-wise',$blog->blogCategoryData->id)}}"><h6>{{$blog->blogCategoryData->blog_category_name}}</h6></a>
	                    </div>
	                </div>

	                
	                <div class="d-flex align-items-center justify-content-between gap-2">
	                    <a class="text-white w-100 primary_btn m-0" href="{{route('webuser.blog-details',$blog->id)}}">Read More ...</a>
	                </div>
	            </div>
	        </a>
	    </div>
	    @endif
    	@endforeach


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