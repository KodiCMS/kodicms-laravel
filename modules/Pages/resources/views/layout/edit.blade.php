{!! Form::model($file, [
	'route' => ['backend.layout.edit.post', [$file->getName()]],
	'class' => 'form-horizontal panel'
]) !!}

@include('pages::layout.form', ['layout' => $file, 'roles' => $roles])

{!! Form::close() !!}