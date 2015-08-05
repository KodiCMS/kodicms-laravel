{!! Form::open([
	'route' => ['backend.datasource.document.create.post', $section->getId(), $document->getId()],
	'class' => 'form-horizontal panel',
	'enctype' => 'multipart/form-data'
]) !!}

@include($document->getFormTemplate(), compact('fields', 'section', 'document'))

{!! Form::close() !!}
