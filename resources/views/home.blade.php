@extends('layout.main')

@section('title') Главная страница @stop

@section('content')

	<h2>Главная страница</h2>

	@if (Auth::check())
		<p>Привет, {{ Auth::user()->username }}</p>
	@else
		<p>Здравствуйте, гость</p>
	@endif

@stop