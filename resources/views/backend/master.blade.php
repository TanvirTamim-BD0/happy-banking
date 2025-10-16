<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head><base href=""/>
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
		<div id="left_side" class="leftSideBar hide_scrollbar">
			<!--begin::Sidebar-->
			@include('backend.layout.sidebar')
			<!--end::Sidebar-->
		</div>
		<header class="top_bar px-2">
			<!--begin::Header-->
			@include('backend.layout.header')
			<!--end::Header-->
		</header>

		<!--begin::Main Content-->
			@yield('content')
		<!--end::Main Content-->

		<div class="footer_text">
			<!--begin::Footer-->
			@include('backend.layout.footer')
			<!--end::Footer-->
		</div>

		{{-- All the script link up file... --}}
		@include('backend.layout.partial.script')
		@yield('scripts')
	</body>
</html>