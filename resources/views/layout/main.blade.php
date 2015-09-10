<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="api-url" content="{{ route('api.home') }}">
	<title>@yield('title', 'Need Team')</title>
</head>
<body>
	<h1>Need Team</h1>

	@if (Session::has('global'))
		<p>{{ Session::get('global') }}</p>
	@endif

	@include('layout.navigation')
	
	@yield('content')

	<script src="{{ url('js/jquery-2.1.4.min.js') }}"></script>
	@yield('footer')
</body>
</html>