{!! Form::open([
	'route' => ['backend.datasource.document.create.post', $section->getId(), $document->getId()],
	'class' => 'form-horizontal panel',
	'enctype' => 'multipart/form-data'
]) !!}

@include('datasource::document.partials.form', compact('fields', 'section', 'document'))

{!! Form::close() !!}
