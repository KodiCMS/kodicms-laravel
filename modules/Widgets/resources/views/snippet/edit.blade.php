{!! Form::model($file, [
	'route' => ['backend.snippet.edit.post', $file->getName()],
	'class' => 'form-horizontal panel',
	'data-api-url' => route('api.snippet.edit'),
	'data-api-method' => 'put'
]) !!}

@include('widgets::snippet.form', ['snippet' => $file, 'roles' => $roles])

{!! Form::close() !!}