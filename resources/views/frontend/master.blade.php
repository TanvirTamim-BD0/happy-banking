<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Bank System | Dashboard')</title>
	
	{{-- All the style link up file... --}}
	@include('frontend.layout.partial.style')
	@yield('styles')
</head>

<body>
	<div class="mobile_body no_scroll" style="background-color: #F4F8FB;">

		<!--begin::Header-->
		@include('frontend.layout.header')
		<!--end::Header-->

		<!--begin::Main Content-->
		@yield('content')
		<!--end::Main Content-->

		<!--begin::Footer-->
		@include('frontend.layout.footer')
		<!--end::Footer-->

		{{-- All the script link up file... --}}
		@include('frontend.layout.partial.script')
		@yield('scripts')
	
</body>

</html>