@extends('layout.main')

@section('content')

	<h2>Регистрация</h2>

	<form action="{{ URL::route('account.create-post') }}" method="post">

		Имя пользователя:<br>
		<input type="text" name="username" size="30" value="{{ (Input::old('username')) ? e(Input::old('username')) : '' }}">
		@if ( $errors->has('username') )
			{{ $errors->first('username') }}
		@endif

		<br>E-mail:<br>
		<input type="text" name="email" size="50" value="{{ (Input::old('email')) ?  e(Input::old('email')) : '' }}">
		@if ( $errors->has('email') )
			{{ $errors->first('email') }}
		@endif

		<br>Пароль:<br>
		<input type="text" name="password" size="30">
		@if ( $errors->has('password') )
			{{ $errors->first('password') }}
		@endif
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<br><br><input type="submit" value="Зарегистрироваться">
	</form>

@stop