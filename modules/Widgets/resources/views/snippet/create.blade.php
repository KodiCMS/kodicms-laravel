{!! Form::model($file, [
	'route' => 'backend.snippet.create.post',
	'class' => 'form-horizontal panel'
]) !!}

@include('widgets::snippet.form', ['snippet' => $file])

{!! Form::close() !!}