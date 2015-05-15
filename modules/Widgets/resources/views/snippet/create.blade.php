{!! Form::model($file, [
	'route' => 'backend.snippet.create.post',
	'class' => 'form-horizontal panel',
	'data-api-url' => route('api.snippet.create'),
	'data-api-method' => 'post'
]) !!}

@include('widgets::snippet.form', ['snippet' => $file])

{!! Form::close() !!}