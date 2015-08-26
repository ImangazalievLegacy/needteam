@extends('layout.main')

@section('content')

	<h2>Вход</h2>

	<form action="{{ URL::route('account.login-post') }}" method="post">

		<br>E-mail:<br>
		<input type="text" name="email" size="50" value="{{ (Input::old('email')) ? e(Input::old('email')) : '' }}">
		@if ( $errors->has('email') )
			{{ $errors->first('email') }}
		@endif

		<br>Пароль:<br>
		<input type="text" name="password" size="30">
		@if ( $errors->has('password') )
			{{ $errors->first('password') }}
		@endif

		<br><input type="checkbox" id="remember" name="remember">
		<label for="remember">Запомнить меня</label>
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<br><br><input type="submit" value="Войти">
	</form>

@stop