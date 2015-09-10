@extends('layout.main')

@section('footer')
	<script src="{{ url('js/handlebars.js') }}"></script>
	<script src="{{ url('js/api.js') }}"></script>
	<script src="{{ url('js/api/uploader.js') }}"></script>
	<script src="{{ url('js/post/image-uploader.js') }}"></script>
@stop

@section('content')

	@include('post.templates')

	<h2>Добавление проекта</h2>

	<form action="{{ URL::route('project.create-post') }}" method="post">

		Название проекта:<br>
		<input type="text" name="title" value="{{ (Input::old('title')) ? e(Input::old('title')) : '' }}">
		@if ( $errors->has('title') )
			{{ $errors->first('title') }}
		@endif

		<br>Описание проекта:<br>
		<textarea name="description" rows="8" cols="40">{{ (Input::old('description')) ? e(Input::old('description')) : '' }}</textarea>
		@if ( $errors->has('description') )
			{{ $errors->first('description') }}
		@endif

		<br>Постер:<br>
		<input type="file" id="poster-upload-dialog" data-url="{{ URL::route('api.upload.image') }}">
		<div id="poster"></div>

		<br><input type="checkbox" name="active" checked>
		Активен и виден всем
		@if ( $errors->has('active') )
			{{ $errors->first('active') }}
		@endif
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<br><br><input type="submit" value="Опубликовать">
	</form>

@stop