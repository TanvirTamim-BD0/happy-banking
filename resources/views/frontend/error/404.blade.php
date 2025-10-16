@extends('frontend.master')
@section('title') Invoice @endsection
@section('styles')
@endsection
@section('content')

<div class="container h-100 d-flex align-items-center flex-column justify-content-center">
    <div class="card text-center">
        <h4 class="bg-primary p-1 rounded mb-2">Sorry, Page Not Found.!</h4>
        <h5>Return To The Home Page.</h5>
        <div class="mt-2">
            <a href="{{route('webuser.dashboard')}}">
                <button class="primary_btn m-0">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </a>
        </div>
    </div>
</div>

@endsection