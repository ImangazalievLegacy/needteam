<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@yield('title', 'Need Team')</title>
</head>
<body>
	<h1>Need Team</h1>

	@if (Session::has('global'))
		<p>{{ Session::get('global') }}</p>
	@endif

	@include('layout.navigation')
	
	@yield('content')
</body>
</html>