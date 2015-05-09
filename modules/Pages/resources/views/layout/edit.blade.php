{!! Form::model($file, [
	'route' => ['backend.layout.edit.post', $file->getName()],
	'class' => 'form-horizontal panel',
	'data-api-url' => route('api.layout.edit'),
	'data-api-method' => 'put'
]) !!}

@include('pages::layout.form', ['layout' => $file, 'roles' => $roles])

{!! Form::close() !!}