{!! Form::model($file, [
	'route' => ['backend.snippet.edit.post', $file->getName()],
	'class' => 'form-horizontal panel'
]) !!}

@include('widgets::snippet.form', ['snippet' => $file, 'roles' => $roles])

{!! Form::close() !!}