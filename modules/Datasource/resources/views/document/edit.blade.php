{!! Form::open([
	'route' => ['backend.datasource.document.edit.post', $section->getId(), $document->getId()],
	'class' => 'form-horizontal panel',
	'enctype' => 'multipart/form-data'
]) !!}

@include('datasource::document.partials.form', compact('fields', 'section', 'document'))

{!! Form::close() !!}
