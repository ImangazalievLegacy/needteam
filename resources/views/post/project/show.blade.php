@extends('layout.main')

@section('content')

	<h2>{{ $project->title }}</h2>

	Дата добавления: {{ $project->published_at }}

	<br>Автор: {{ $author->username }}<br>

	@if ($project->poster !== null)
		<br><img src="{{ url($project->poster) }}" alt="poster">
	@endif

	<p>{{ $project->description }}</p>

@stop