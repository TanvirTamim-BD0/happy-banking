<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <base href="" />
    <title>@yield('title', 'Bank System | Dashboard')</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- All the style link up file... --}}
    @include('backend.layout.partial.style')
    @yield('styles')
</head>
<!--end::Head-->

<body class="web_version_2">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">

                    <div class="card-body">
                        <h4 class="mb-0 fw-bold text-center">Admin Login</h4>
                        <hr>

                        <form action="{{route('login')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password">
                                </div>
                            </div>

                            <div class="row pb-3 pt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- All the script link up file... --}}
    @include('backend.layout.partial.script')
    @yield('scripts')
</body>

</html>