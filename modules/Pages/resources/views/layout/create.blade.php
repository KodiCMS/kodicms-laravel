{!! Form::model($file, [
	'route' => 'backend.layout.create.post',
	'class' => 'form-horizontal panel',
	'data-api-url' => route('api.layout.create'),
	'data-api-method' => 'post'
]) !!}

@include('pages::layout.form', ['layout' => $file, 'roles' => $roles])

{!! Form::close() !!}