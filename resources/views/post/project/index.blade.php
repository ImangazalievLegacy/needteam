@extends('layout.main')

@section('content')

	<h2>Проекты</h2>

	<a href="{{ route('project.add') }}">Добавить проект</a>
	<br><br>

	@if (isset($projects) && count($projects)>0)
			@foreach ($projects as $project)
				<li>
					<a href="{{ route('project.show', $project->id) }}">{{ $project->title }}</a>
				</li>
			@endforeach
	@endif

@stop