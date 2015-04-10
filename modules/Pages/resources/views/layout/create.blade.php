{!! Form::model($file, [
	'route' => 'backend.layout.create.post',
	'class' => 'form-horizontal panel'
]) !!}

@include('pages::layout.form', ['layout' => $file, 'roles' => $roles])

{!! Form::close() !!}