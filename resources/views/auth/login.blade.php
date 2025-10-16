<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank Soft || Login</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/4b5d72e539.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/prototype.css" />
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/dashboard.css" />
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/style.css" />
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/media.css" />
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/select.css">
    <link rel="stylesheet" href="{{asset('backend')}}/assets/css/account.css">

    <link href="{{asset('backend')}}/custom/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend')}}/custom/css/custom.css" rel="stylesheet" type="text/css" />
</head>

<body class="web_version_2">

    <div class="content ms-0">
        <main>
            <section class="section-padding">
                <div class="container">

                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card rounded-0">
                                <div class="card-body p-4">
                                    <h4 class="mb-0 fw-bold text-center">Admin Login</h4>
                                    <hr>
                                    <form action="{{route('login')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <label for="exampleUsername" class="form-label">Email</label>
                                                <input type="email" class="form-control rounded-0" id="exampleUsername"
                                                    name="email" placeholder="Email" required>
                                            </div>
                                            <div class="col-12">
                                                <label for="examplePassword" class="form-label">Password</label>
                                                <input type="password" class="form-control rounded-0"
                                                    id="examplePassword" name="password" placeholder="Password" required>
                                                    <i class="fa-solid fa-eye-slash d-none"></i>
                                            </div>
                                            {{-- <div class="col-12">
                                                <a href="javascript:;"
                                                    class="text-content btn bg-light rounded-0 w-100"><i
                                                        class="bi bi-lock me-2"></i>Forgot Password</a>
                                            </div>
                                            <div class="col-12">
                                                <hr class="my-0">
                                            </div> --}}
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="btn btn-primary rounded-0 btn-ecomm w-100">Login</button>
                                            </div>
                                            {{-- <div class="col-12 text-center">
                                                <p class="mb-0 rounded-0 w-100">Don't have an account? <a
                                                        href="regester.html" class="text-danger">Sign Up</a></p>
                                            </div> --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </main>
    </div>
    </div>


    <script src="{{asset('backend')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('backend')}}/assets/js/script.js"></script>
    <script src="{{asset('backend')}}/assets/js/select_dynamic.js"></script>
    <script src="{{asset('backend')}}/assets/js/select.js"></script>
    <!--end::Custom Javascript-->
    <script src="{{asset('backend')}}/custom/js/toastr.min.js"></script>
    {!! Toastr::message() !!}